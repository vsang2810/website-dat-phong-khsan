<?php
declare(strict_types=1);

final class Room extends Model
{
    private const BOOKING_BLOCK_STATUSES = "('pending_payment','pending_hotel_payment','confirmed','checked_in')";

    public function all(array $filters): array
    {
        $where = [];
        $params = [];
        if ($filters['q'] !== '') {
            $where[] = '(r.room_number LIKE :q_number OR rt.name LIKE :q_type OR h.name LIKE :q_hotel OR r.note LIKE :q_note)';
            $like = '%' . $filters['q'] . '%';
            $params += ['q_number' => $like, 'q_type' => $like, 'q_hotel' => $like, 'q_note' => $like];
        }
        if ($filters['hotel_id'] !== '' && ctype_digit($filters['hotel_id'])) {
            $where[] = 'h.id = :hotel_id';
            $params['hotel_id'] = (int)$filters['hotel_id'];
        }
        if ($filters['room_type_id'] !== '' && ctype_digit($filters['room_type_id'])) {
            $where[] = 'rt.id = :room_type_id';
            $params['room_type_id'] = (int)$filters['room_type_id'];
        }
        if ($filters['base_status'] !== '' && in_array($filters['base_status'], ['available','maintenance','inactive'], true)) {
            $where[] = 'r.status = :base_status';
            $params['base_status'] = $filters['base_status'];
        }

        $sql = "SELECT r.*, rt.name AS room_type_name, rt.status AS room_type_status,
                       h.id AS hotel_id, h.name AS hotel_name,
                       CASE
                         WHEN EXISTS (
                           SELECT 1 FROM booking_rooms br
                           INNER JOIN bookings b ON b.id = br.booking_id
                           WHERE br.room_id = r.id AND b.booking_status = 'checked_in'
                             AND CURDATE() >= b.check_in AND CURDATE() < b.check_out
                         ) THEN 'occupied'
                         WHEN EXISTS (
                           SELECT 1 FROM booking_rooms br
                           INNER JOIN bookings b ON b.id = br.booking_id
                           WHERE br.room_id = r.id AND b.booking_status = 'confirmed'
                             AND CURDATE() >= b.check_in AND CURDATE() < b.check_out
                         ) THEN 'reserved'
                         WHEN EXISTS (
                           SELECT 1 FROM booking_rooms br
                           INNER JOIN bookings b ON b.id = br.booking_id
                           WHERE br.room_id = r.id AND b.booking_status IN " . self::BOOKING_BLOCK_STATUSES . "
                             AND b.check_in <= CURDATE() AND b.check_out > CURDATE()
                         ) THEN 'held'
                         ELSE r.status
                       END AS display_status,
                       (SELECT b.booking_code FROM booking_rooms br
                         INNER JOIN bookings b ON b.id = br.booking_id
                         WHERE br.room_id = r.id AND b.booking_status IN " . self::BOOKING_BLOCK_STATUSES . "
                           AND b.check_in <= CURDATE() AND b.check_out > CURDATE()
                         ORDER BY FIELD(b.booking_status,'checked_in','confirmed','pending_hotel_payment','pending_payment'), b.id DESC
                         LIMIT 1) AS current_booking_code,
                       (SELECT b.guest_name FROM booking_rooms br
                         INNER JOIN bookings b ON b.id = br.booking_id
                         WHERE br.room_id = r.id AND b.booking_status IN " . self::BOOKING_BLOCK_STATUSES . "
                           AND b.check_in <= CURDATE() AND b.check_out > CURDATE()
                         ORDER BY FIELD(b.booking_status,'checked_in','confirmed','pending_hotel_payment','pending_payment'), b.id DESC
                         LIMIT 1) AS current_guest
                FROM rooms r
                INNER JOIN room_types rt ON rt.id = r.room_type_id
                INNER JOIN hotels h ON h.id = rt.hotel_id";
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= " ORDER BY h.name, r.floor_number, r.room_number";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, rt.hotel_id FROM rooms r
             INNER JOIN room_types rt ON rt.id = r.room_type_id WHERE r.id=:id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function roomTypes(): array
    {
        $stmt = $this->db->query(
            "SELECT rt.id, rt.name, rt.hotel_id, h.name AS hotel_name
             FROM room_types rt INNER JOIN hotels h ON h.id=rt.hotel_id
             ORDER BY h.name, rt.name"
        );
        return $stmt->fetchAll();
    }

    public function save(array $data, ?int $id = null): int
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('SELECT id FROM room_types WHERE id=:id FOR UPDATE');
            $stmt->execute(['id' => $data['room_type_id']]);
            if (!$stmt->fetchColumn()) throw new RuntimeException('Loại phòng không tồn tại.');

            $sql = 'SELECT id FROM rooms WHERE room_number=:number';
            $params = ['number' => $data['room_number']];
            if ($id) { $sql .= ' AND id<>:id'; $params['id'] = $id; }
            $sql .= ' LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            if ($stmt->fetchColumn()) throw new RuntimeException('Mã phòng đã được sử dụng. Mỗi phòng phải có mã riêng.');

            if ($id) {
                $stmt = $this->db->prepare('SELECT COUNT(*) FROM booking_rooms WHERE room_id=:id');
                $stmt->execute(['id' => $id]);
                $hasHistory = (int)$stmt->fetchColumn() > 0;
                $old = $this->find($id);
                if (!$old) throw new RuntimeException('Không tìm thấy phòng cần cập nhật.');
                if ($hasHistory && (int)$old['room_type_id'] !== (int)$data['room_type_id']) {
                    throw new RuntimeException('Không thể đổi loại phòng vì phòng đã được gắn với lịch sử đặt phòng.');
                }
                $stmt = $this->db->prepare(
                    'UPDATE rooms SET room_type_id=:room_type_id, room_number=:room_number,
                     floor_number=:floor_number, note=:note WHERE id=:id'
                );
                $stmt->execute($data + ['id' => $id]);
                $savedId = $id;
            } else {
                $stmt = $this->db->prepare(
                    "INSERT INTO rooms(room_type_id,room_number,floor_number,status,note)
                     VALUES(:room_type_id,:room_number,:floor_number,'available',:note)"
                );
                $stmt->execute($data);
                $savedId = (int)$this->db->lastInsertId();
            }
            $this->db->commit();
            return $savedId;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }

    public function setStatus(int $id, string $status): void
    {
        if (!in_array($status, ['available','maintenance','inactive'], true)) {
            throw new RuntimeException('Trạng thái phòng không hợp lệ.');
        }
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('SELECT id FROM rooms WHERE id=:id FOR UPDATE');
            $stmt->execute(['id' => $id]);
            if (!$stmt->fetchColumn()) throw new RuntimeException('Không tìm thấy phòng.');

            if ($status !== 'available') {
                $stmt = $this->db->prepare(
                    "SELECT b.booking_code, b.check_in, b.check_out
                     FROM booking_rooms br INNER JOIN bookings b ON b.id=br.booking_id
                     WHERE br.room_id=:id
                       AND b.booking_status IN " . self::BOOKING_BLOCK_STATUSES . "
                       AND b.check_out > CURDATE()
                     ORDER BY b.check_in LIMIT 1"
                );
                $stmt->execute(['id' => $id]);
                $booking = $stmt->fetch();
                if ($booking) {
                    throw new RuntimeException(
                        'Không thể chuyển trạng thái: phòng đang được giữ hoặc sử dụng trong đơn '
                        . $booking['booking_code'] . ' (' . $booking['check_in'] . ' đến ' . $booking['check_out'] . ').'
                    );
                }
            }
            $stmt = $this->db->prepare('UPDATE rooms SET status=:status WHERE id=:id');
            $stmt->execute(['status' => $status, 'id' => $id]);
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }
}

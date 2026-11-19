<?php
declare(strict_types=1);

final class RoomType extends Model
{
    private const BOOKING_BLOCK_STATUSES = "('pending_payment','pending_hotel_payment','confirmed','checked_in')";

    public function all(string $keyword = '', string $hotelId = ''): array
    {
        $where = [];
        $params = [];
        if ($keyword !== '') {
            $where[] = '(rt.name LIKE :q_name OR rt.bed_type LIKE :q_bed OR h.name LIKE :q_hotel)';
            $like = '%' . $keyword . '%';
            $params['q_name'] = $like;
            $params['q_bed'] = $like;
            $params['q_hotel'] = $like;
        }
        if ($hotelId !== '' && ctype_digit($hotelId)) {
            $where[] = 'rt.hotel_id = :hotel_id';
            $params['hotel_id'] = (int) $hotelId;
        }
        $sql = "SELECT rt.*, h.name AS hotel_name,
                       (SELECT COUNT(*) FROM rooms r WHERE r.room_type_id = rt.id) AS room_count
                FROM room_types rt INNER JOIN hotels h ON h.id = rt.hotel_id";
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY h.name, rt.name';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM room_types WHERE id=:id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function save(array $data, ?int $id = null): int
    {
        if ($id) {
            $stmt = $this->db->prepare(
                "UPDATE room_types SET hotel_id=:hotel_id,name=:name,description=:description,
                 area=:area,max_guests=:max_guests,bed_type=:bed_type,base_price=:base_price,
                 thumbnail=:thumbnail,status=:status WHERE id=:id"
            );
            $stmt->execute($data + ['id' => $id]);
            return $id;
        }
        $stmt = $this->db->prepare(
            "INSERT INTO room_types (hotel_id,name,description,area,max_guests,bed_type,base_price,thumbnail,status)
             VALUES (:hotel_id,:name,:description,:area,:max_guests,:bed_type,:base_price,:thumbnail,:status)"
        );
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function toggleStatus(int $id): void
    {
        $stmt = $this->db->prepare(
            "UPDATE room_types SET status = IF(status='active','inactive','active') WHERE id=:id"
        );
        $stmt->execute(['id' => $id]);
    }

    // Client-facing methods
    public function getFeatured(int $limit = 6): array
    {
        $sql = "SELECT rt.*, h.name AS hotel_name, h.address AS hotel_address,
                       (SELECT COUNT(*) FROM rooms r WHERE r.room_type_id = rt.id AND r.status = 'available') AS total_rooms,
                       (SELECT AVG(rating) FROM reviews rev WHERE rev.room_type_id = rt.id AND rev.status = 'approved') AS avg_rating,
                       (SELECT COUNT(*) FROM reviews rev WHERE rev.room_type_id = rt.id AND rev.status = 'approved') AS review_count
                FROM room_types rt
                INNER JOIN hotels h ON h.id = rt.hotel_id
                WHERE rt.status = 'active' AND h.status = 'active'
                ORDER BY rt.base_price ASC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $roomTypes = $stmt->fetchAll();

        foreach ($roomTypes as &$rt) {
            $rt['amenities'] = $this->getAmenities((int)$rt['id']);
        }
        return $roomTypes;
    }

    public function getActiveList(array $filters = []): array
    {
        $where = ["rt.status = 'active'", "h.status = 'active'"];
        $params = [];

        if (!empty($filters['hotel_id']) && ctype_digit((string)$filters['hotel_id'])) {
            $where[] = 'rt.hotel_id = :hotel_id';
            $params['hotel_id'] = (int)$filters['hotel_id'];
        }

        if (!empty($filters['guests']) && ctype_digit((string)$filters['guests'])) {
            $where[] = 'rt.max_guests >= :guests';
            $params['guests'] = (int)$filters['guests'];
        }

        if (!empty($filters['keyword'])) {
            $where[] = '(rt.name LIKE :kw_name OR rt.bed_type LIKE :kw_bed OR rt.description LIKE :kw_desc)';
            $term = '%' . $filters['keyword'] . '%';
            $params['kw_name'] = $term;
            $params['kw_bed'] = $term;
            $params['kw_desc'] = $term;
        }

        if (!empty($filters['min_price']) && is_numeric($filters['min_price'])) {
            $where[] = 'rt.base_price >= :min_price';
            $params['min_price'] = (float)$filters['min_price'];
        }

        if (!empty($filters['max_price']) && is_numeric($filters['max_price'])) {
            $where[] = 'rt.base_price <= :max_price';
            $params['max_price'] = (float)$filters['max_price'];
        }

        if (!empty($filters['bed_type'])) {
            $where[] = 'rt.bed_type LIKE :bed_type';
            $params['bed_type'] = '%' . $filters['bed_type'] . '%';
        }

        $sql = "SELECT rt.*, h.name AS hotel_name, h.address AS hotel_address,
                       (SELECT COUNT(*) FROM rooms r WHERE r.room_type_id = rt.id AND r.status = 'available') AS total_rooms,
                       (SELECT AVG(rating) FROM reviews rev WHERE rev.room_type_id = rt.id AND rev.status = 'approved') AS avg_rating,
                       (SELECT COUNT(*) FROM reviews rev WHERE rev.room_type_id = rt.id AND rev.status = 'approved') AS review_count
                FROM room_types rt
                INNER JOIN hotels h ON h.id = rt.hotel_id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY rt.base_price ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();

        $checkIn = $filters['check_in'] ?? null;
        $checkOut = $filters['check_out'] ?? null;
        $hasDateFilter = !empty($checkIn) && !empty($checkOut);

        foreach ($results as &$item) {
            $item['amenities'] = $this->getAmenities((int)$item['id']);
            if ($hasDateFilter) {
                $item['available_count'] = $this->checkAvailableRoomsCount((int)$item['id'], $checkIn, $checkOut);
            } else {
                $item['available_count'] = (int)$item['total_rooms'];
            }
        }

        return $results;
    }

    public function findWithDetails(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT rt.*, h.name AS hotel_name, h.address AS hotel_address, h.phone AS hotel_phone,
                    h.email AS hotel_email, h.star_rating AS hotel_stars, h.check_in_time, h.check_out_time,
                    (SELECT COUNT(*) FROM rooms r WHERE r.room_type_id = rt.id AND r.status = 'available') AS total_rooms
             FROM room_types rt
             INNER JOIN hotels h ON h.id = rt.hotel_id
             WHERE rt.id = :id AND rt.status = 'active'
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $room = $stmt->fetch();
        if (!$room) {
            return null;
        }

        $room['amenities'] = $this->getAmenities($id);
        $room['images'] = $this->getImages($id);
        return $room;
    }

    public function getAmenities(int $roomTypeId): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.* FROM amenities a
             INNER JOIN room_type_amenities rta ON rta.amenity_id = a.id
             WHERE rta.room_type_id = :room_type_id
             ORDER BY a.id ASC"
        );
        $stmt->execute(['room_type_id' => $roomTypeId]);
        return $stmt->fetchAll();
    }

    public function getImages(int $roomTypeId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM room_images WHERE room_type_id = :room_type_id ORDER BY is_thumbnail DESC, id ASC"
        );
        $stmt->execute(['room_type_id' => $roomTypeId]);
        return $stmt->fetchAll();
    }

    public function checkAvailableRoomsCount(int $roomTypeId, string $checkIn, string $checkOut): int
    {
        $blockStatuses = self::BOOKING_BLOCK_STATUSES;
        $sql = "SELECT COUNT(*) FROM rooms r
                WHERE r.room_type_id = :room_type_id
                  AND r.status = 'available'
                  AND NOT EXISTS (
                      SELECT 1 FROM booking_rooms br
                      INNER JOIN bookings b ON b.id = br.booking_id
                      WHERE br.room_id = r.id
                        AND b.booking_status IN $blockStatuses
                        AND (b.check_in < :check_out AND b.check_out > :check_in)
                  )";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'room_type_id' => $roomTypeId,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function findAvailableRoom(int $roomTypeId, string $checkIn, string $checkOut): ?array
    {
        $blockStatuses = self::BOOKING_BLOCK_STATUSES;
        $sql = "SELECT r.* FROM rooms r
                WHERE r.room_type_id = :room_type_id
                  AND r.status = 'available'
                  AND NOT EXISTS (
                      SELECT 1 FROM booking_rooms br
                      INNER JOIN bookings b ON b.id = br.booking_id
                      WHERE br.room_id = r.id
                        AND b.booking_status IN $blockStatuses
                        AND (b.check_in < :check_out AND b.check_out > :check_in)
                  )
                ORDER BY r.floor_number ASC, r.room_number ASC
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'room_type_id' => $roomTypeId,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
        ]);
        return $stmt->fetch() ?: null;
    }
}

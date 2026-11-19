<?php
declare(strict_types=1);

final class RoomType extends Model
{
    public function all(string $keyword = '', string $hotelId = ''): array
    {
        $where = [];
        $params = [];
        if ($keyword !== '') {
            $where[] = '(rt.name LIKE :q OR rt.bed_type LIKE :q OR h.name LIKE :q)';
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
}

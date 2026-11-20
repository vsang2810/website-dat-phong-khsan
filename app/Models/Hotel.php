<?php
declare(strict_types=1);

final class Hotel extends Model
{
    public function all(string $keyword = ''): array
    {
        $sql = "SELECT h.*,
                       (SELECT COUNT(*) FROM room_types rt WHERE rt.hotel_id = h.id) AS room_type_count,
                       (SELECT COUNT(*) FROM rooms r INNER JOIN room_types rt2 ON rt2.id = r.room_type_id WHERE rt2.hotel_id = h.id) AS room_count
                FROM hotels h";
        $params = [];
        if ($keyword !== '') {
            $sql .= " WHERE h.name LIKE :q_name OR h.city LIKE :q_city OR h.address LIKE :q_address OR h.phone LIKE :q_phone";
            $like = '%' . $keyword . '%';
            $params['q_name'] = $like;
            $params['q_city'] = $like;
            $params['q_address'] = $like;
            $params['q_phone'] = $like;
        }
        $sql .= " ORDER BY h.created_at DESC, h.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM hotels WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function save(array $data, ?int $id = null): int
    {
        if ($id) {
            $stmt = $this->db->prepare(
                "UPDATE hotels SET name=:name,address=:address,city=:city,country=:country,
                 description=:description,phone=:phone,email=:email,star_rating=:stars,
                 thumbnail=:thumbnail,check_in_time=:check_in,check_out_time=:check_out,status=:status
                 WHERE id=:id"
            );
            $stmt->execute($data + ['id' => $id]);
            return $id;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO hotels (name,address,city,country,description,phone,email,star_rating,
             thumbnail,check_in_time,check_out_time,status)
             VALUES (:name,:address,:city,:country,:description,:phone,:email,:stars,
             :thumbnail,:check_in,:check_out,:status)"
        );
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function toggleStatus(int $id): void
    {
        $stmt = $this->db->prepare(
            "UPDATE hotels SET status = IF(status='active','inactive','active') WHERE id=:id"
        );
        $stmt->execute(['id' => $id]);
    }

    public function getDefault(): ?array
    {
        $stmt = $this->db->query("SELECT * FROM hotels ORDER BY id ASC LIMIT 1");
        return $stmt->fetch() ?: null;
    }
}

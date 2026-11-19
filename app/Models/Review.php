<?php
declare(strict_types=1);

final class Review extends Model
{
    public function getByRoomType(int $roomTypeId, int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT rev.*, u.full_name AS guest_name, u.avatar
             FROM reviews rev
             INNER JOIN users u ON u.id = rev.user_id
             WHERE rev.room_type_id = :room_type_id AND rev.status = 'approved'
             ORDER BY rev.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':room_type_id', $roomTypeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFeaturedReviews(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT rev.*, u.full_name AS guest_name, u.avatar, rt.name AS room_name
             FROM reviews rev
             INNER JOIN users u ON u.id = rev.user_id
             INNER JOIN room_types rt ON rt.id = rev.room_type_id
             WHERE rev.status = 'approved' AND rev.rating >= 4
             ORDER BY rev.rating DESC, rev.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

<?php
declare(strict_types=1);

final class Wishlist extends Model
{
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT w.id AS wishlist_id, w.created_at AS added_at, rt.*, h.name AS hotel_name
             FROM wishlists w
             INNER JOIN room_types rt ON rt.id = w.room_type_id
             INNER JOIN hotels h ON h.id = rt.hotel_id
             WHERE w.user_id = :user_id AND rt.status = 'active'
             ORDER BY w.created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function toggle(int $userId, int $roomTypeId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM wishlists WHERE user_id = :user_id AND room_type_id = :room_type_id");
        $stmt->execute(['user_id' => $userId, 'room_type_id' => $roomTypeId]);
        $existing = $stmt->fetchColumn();

        if ($existing) {
            $del = $this->db->prepare("DELETE FROM wishlists WHERE id = :id");
            $del->execute(['id' => $existing]);
            return false; // Removed
        }

        $ins = $this->db->prepare("INSERT INTO wishlists (user_id, room_type_id) VALUES (:user_id, :room_type_id)");
        $ins->execute(['user_id' => $userId, 'room_type_id' => $roomTypeId]);
        return true; // Added
    }

    public function isWishlisted(int $userId, int $roomTypeId): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM wishlists WHERE user_id = :user_id AND room_type_id = :room_type_id LIMIT 1");
        $stmt->execute(['user_id' => $userId, 'room_type_id' => $roomTypeId]);
        return (bool)$stmt->fetchColumn();
    }
}

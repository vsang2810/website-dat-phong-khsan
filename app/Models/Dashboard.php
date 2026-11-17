<?php
declare(strict_types=1);

class Dashboard extends Model
{
    public function statistics(): array
    {
        return [
            'bookings' => (int)$this->db->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
            'customers' => (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn(),
            'rooms' => (int)$this->db->query("SELECT COUNT(*) FROM rooms WHERE status <> 'inactive'")->fetchColumn(),
            'revenue' => (float)$this->db->query(
                "SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payment_status = 'success'"
            )->fetchColumn(),
        ];
    }

    public function recentBookings(int $limit = 8): array
    {
        $limit = max(1, min($limit, 30));
        return $this->db->query(
            "SELECT booking_code, guest_name, check_in, check_out, total_amount,
                    booking_status, payment_status, payment_method
             FROM bookings ORDER BY created_at DESC LIMIT {$limit}"
        )->fetchAll();
    }

    public function bookingStatuses(): array
    {
        return $this->db->query(
            "SELECT booking_status, COUNT(*) AS total
             FROM bookings GROUP BY booking_status ORDER BY total DESC"
        )->fetchAll();
    }
}

<?php
declare(strict_types=1);

final class Payment extends Model
{
    public function paginate(array $f, int $page = 1, int $perPage = 12): array
    {
        $where = [];
        $params = [];
        if ($f['q'] !== '') {
            $where[] = '(p.transaction_code LIKE :q1 OR b.booking_code LIKE :q2 OR b.guest_name LIKE :q3 OR b.guest_email LIKE :q4 OR b.guest_phone LIKE :q5)';
            $term = '%' . $f['q'] . '%';
            foreach (['q1','q2','q3','q4','q5'] as $key) $params[$key] = $term;
        }
        if ($f['status'] !== '') { $where[] = 'p.payment_status = :status'; $params['status'] = $f['status']; }
        if ($f['method'] !== '') { $where[] = 'p.payment_method = :method'; $params['method'] = $f['method']; }
        if ($f['from'] !== '') { $where[] = 'DATE(COALESCE(p.paid_at,p.created_at)) >= :date_from'; $params['date_from'] = $f['from']; }
        if ($f['to'] !== '') { $where[] = 'DATE(COALESCE(p.paid_at,p.created_at)) <= :date_to'; $params['date_to'] = $f['to']; }
        $clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $st = $this->db->prepare("SELECT COUNT(*) FROM payments p JOIN bookings b ON b.id=p.booking_id $clause");
        $st->execute($params);
        $total = (int)$st->fetchColumn();
        $pages = max(1, (int)ceil($total / $perPage));
        $page = min(max(1, $page), $pages);
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT p.*,b.booking_code,b.guest_name,b.guest_email,b.payment_status booking_payment_status,b.booking_status,b.total_amount booking_total
                FROM payments p JOIN bookings b ON b.id=p.booking_id $clause
                ORDER BY p.created_at DESC,p.id DESC LIMIT $perPage OFFSET $offset";
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return ['items'=>$st->fetchAll(),'total'=>$total,'page'=>$page,'pages'=>$pages];
    }

    public function find(int $id): ?array
    {
        $st = $this->db->prepare("SELECT p.*,b.booking_code,b.guest_name,b.guest_email,b.guest_phone,b.total_amount booking_total,b.payment_status booking_payment_status,b.payment_method booking_method,b.booking_status,b.check_in,b.check_out,b.id booking_id,u.full_name confirmer_name
            FROM payments p JOIN bookings b ON b.id=p.booking_id LEFT JOIN users u ON u.id=p.confirmed_by WHERE p.id=:id LIMIT 1");
        $st->execute(['id'=>$id]);
        $row = $st->fetch();
        return $row ?: null;
    }
}

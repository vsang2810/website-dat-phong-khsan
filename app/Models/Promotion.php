<?php
declare(strict_types=1);

final class Promotion extends Model
{
    public function allActive(): array
    {
        $sql = "SELECT * FROM promotions
                WHERE status = 'active'
                  AND NOW() BETWEEN start_date AND end_date
                  AND (usage_limit IS NULL OR used_count < usage_limit)
                ORDER BY discount_value DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findByCode(string $code): ?array
    {
        $sql = "SELECT * FROM promotions
                WHERE UPPER(code) = UPPER(:code)
                  AND status = 'active'
                  AND NOW() BETWEEN start_date AND end_date
                  AND (usage_limit IS NULL OR used_count < usage_limit)
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['code' => trim($code)]);
        return $stmt->fetch() ?: null;
    }

    public function calculateDiscount(array $promo, float $orderSubtotal): array
    {
        if ($orderSubtotal < (float)$promo['min_order_value']) {
            return [
                'valid' => false,
                'discount' => 0.0,
                'message' => 'Đơn phòng chưa đạt giá trị tối thiểu ' . number_format((float)$promo['min_order_value'], 0, ',', '.') . ' VNĐ để áp dụng mã này.',
            ];
        }

        $discount = 0.0;
        if ($promo['discount_type'] === 'percentage') {
            $discount = ($orderSubtotal * (float)$promo['discount_value']) / 100;
            if ($promo['max_discount'] !== null && (float)$promo['max_discount'] > 0) {
                $discount = min($discount, (float)$promo['max_discount']);
            }
        } else {
            $discount = min((float)$promo['discount_value'], $orderSubtotal);
        }

        return [
            'valid' => true,
            'discount' => round($discount, 2),
            'promotion' => $promo,
            'message' => 'Áp dụng mã ưu đãi thành công!',
        ];
    }

    public function incrementUsage(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE promotions SET used_count = used_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

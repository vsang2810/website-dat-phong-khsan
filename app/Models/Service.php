<?php
declare(strict_types=1);

final class Service extends Model
{
    public function allActive(): array
    {
        $stmt = $this->db->query("SELECT * FROM services WHERE status = 'active' ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE id = :id AND status = 'active' LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        $validIds = array_filter(array_map('intval', $ids), fn($id) => $id > 0);
        if (empty($validIds)) {
            return [];
        }
        $in = implode(',', $validIds);
        $stmt = $this->db->query("SELECT * FROM services WHERE id IN ($in) AND status = 'active'");
        $items = $stmt->fetchAll();
        $indexed = [];
        foreach ($items as $item) {
            $indexed[(int)$item['id']] = $item;
        }
        return $indexed;
    }
}

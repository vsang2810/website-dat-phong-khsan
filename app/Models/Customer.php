<?php
declare(strict_types=1);

final class Customer extends Model
{
    public function paginate(array $filters, int $page = 1, int $perPage = 15): array
    {
        $where = ["u.role = 'customer'"];
        $params = [];
        if ($filters['q'] !== '') {
            $where[] = "(u.full_name LIKE :q_name OR u.email LIKE :q_email OR u.phone LIKE :q_phone)";
            $like = '%' . $filters['q'] . '%';
            $params += ['q_name' => $like, 'q_email' => $like, 'q_phone' => $like];
        }
        if ($filters['status'] !== '') {
            $where[] = 'u.status = :status';
            $params['status'] = $filters['status'];
        }
        $clause = 'WHERE ' . implode(' AND ', $where);

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users u $clause");
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();
        $pages = max(1, (int)ceil($total / $perPage));
        $page = min(max(1, $page), $pages);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT u.id,u.full_name,u.email,u.phone,u.gender,u.status,u.created_at,
                       COUNT(DISTINCT b.id) booking_count,
                       COALESCE(SUM(CASE WHEN b.booking_status <> 'cancelled' THEN b.total_amount ELSE 0 END),0) booking_value,
                       MAX(b.created_at) last_booking_at
                FROM users u
                LEFT JOIN bookings b ON b.user_id=u.id
                $clause
                GROUP BY u.id
                ORDER BY u.created_at DESC,u.id DESC
                LIMIT $perPage OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return ['items' => $stmt->fetchAll(), 'total' => $total, 'page' => $page, 'pages' => $pages];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT u.id,u.full_name,u.email,u.phone,u.gender,u.status,u.created_at,u.updated_at,
                    COUNT(DISTINCT b.id) booking_count,
                    COALESCE(SUM(CASE WHEN b.booking_status <> 'cancelled' THEN b.total_amount ELSE 0 END),0) booking_value
             FROM users u LEFT JOIN bookings b ON b.user_id=u.id
             WHERE u.id=:id AND u.role='customer'
             GROUP BY u.id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $customer = $stmt->fetch();
        if (!$customer) return null;

        $stmt = $this->db->prepare(
            "SELECT b.id,b.booking_code,b.hotel_id,b.check_in,b.check_out,b.total_amount,
                    b.payment_method,b.payment_status,b.booking_status,b.created_at,h.name hotel_name
             FROM bookings b LEFT JOIN hotels h ON h.id=b.hotel_id
             WHERE b.user_id=:id ORDER BY b.created_at DESC,b.id DESC"
        );
        $stmt->execute(['id' => $id]);
        $customer['bookings'] = $stmt->fetchAll();
        return $customer;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => trim($email)]);
        return $stmt->fetch() ?: null;
    }

    public function register(string $fullName, string $email, string $phone, string $password, string $gender = 'other'): int
    {
        $email = trim($email);
        $phone = trim($phone);

        $check = $this->db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $check->execute(['email' => $email]);
        if ($check->fetchColumn()) {
            throw new RuntimeException('Địa chỉ email này đã được đăng ký.');
        }

        if ($phone !== '') {
            $checkPhone = $this->db->prepare("SELECT id FROM users WHERE phone = :phone LIMIT 1");
            $checkPhone->execute(['phone' => $phone]);
            if ($checkPhone->fetchColumn()) {
                throw new RuntimeException('Số điện thoại này đã được sử dụng.');
            }
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (full_name, email, phone, password_hash, gender, role, status)
             VALUES (:name, :email, :phone, :hash, :gender, 'customer', 'active')"
        );
        $stmt->execute([
            'name' => trim($fullName),
            'email' => $email,
            'phone' => $phone !== '' ? $phone : null,
            'hash' => $hash,
            'gender' => in_array($gender, ['male', 'female', 'other'], true) ? $gender : 'other',
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function updateCustomerProfile(int $id, string $fullName, string $phone, string $gender): void
    {
        if ($phone !== '') {
            $stmt = $this->db->prepare('SELECT id FROM users WHERE phone = :phone AND id <> :id LIMIT 1');
            $stmt->execute(['phone' => $phone, 'id' => $id]);
            if ($stmt->fetchColumn()) {
                throw new RuntimeException('Số điện thoại này đã được tài khoản khác sử dụng.');
            }
        }

        $stmt = $this->db->prepare(
            "UPDATE users SET full_name = :full_name, phone = :phone, gender = :gender WHERE id = :id"
        );
        $stmt->execute([
            'id' => $id,
            'full_name' => trim($fullName),
            'phone' => $phone !== '' ? $phone : null,
            'gender' => in_array($gender, ['male', 'female', 'other'], true) ? $gender : 'other',
        ]);
    }

    public function updatePassword(int $userId, string $oldPassword, string $newPassword): void
    {
        $stmt = $this->db->prepare("SELECT password_hash FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $userId]);
        $currentHash = $stmt->fetchColumn();

        if ($currentHash && !password_verify($oldPassword, $currentHash)) {
            throw new RuntimeException('Mật khẩu hiện tại không chính xác.');
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $upd = $this->db->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
        $upd->execute(['hash' => $newHash, 'id' => $userId]);
    }

    public function updateProfile(int $id, array $data): void
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE id=:id AND role='customer'");
        $stmt->execute(['id' => $id]);
        if (!$stmt->fetchColumn()) throw new RuntimeException('Không tìm thấy khách hàng.');

        $stmt = $this->db->prepare('SELECT id FROM users WHERE email=:email AND id<>:id LIMIT 1');
        $stmt->execute(['email' => $data['email'], 'id' => $id]);
        if ($stmt->fetchColumn()) throw new RuntimeException('Email này đã được tài khoản khác sử dụng.');

        if ($data['phone'] !== '') {
            $stmt = $this->db->prepare('SELECT id FROM users WHERE phone=:phone AND id<>:id LIMIT 1');
            $stmt->execute(['phone' => $data['phone'], 'id' => $id]);
            if ($stmt->fetchColumn()) throw new RuntimeException('Số điện thoại này đã được tài khoản khác sử dụng.');
        }

        $stmt = $this->db->prepare(
            "UPDATE users SET full_name=:full_name,email=:email,phone=:phone,gender=:gender WHERE id=:id AND role='customer'"
        );
        $stmt->execute($data + ['id' => $id]);
    }

    public function setStatus(int $id, string $status): void
    {
        if (!in_array($status, ['active','inactive','blocked'], true)) {
            throw new RuntimeException('Trạng thái tài khoản không hợp lệ.');
        }
        $stmt = $this->db->prepare("UPDATE users SET status=:status WHERE id=:id AND role='customer'");
        $stmt->execute(['status' => $status, 'id' => $id]);
        if ($stmt->rowCount() === 0) {
            $check = $this->db->prepare("SELECT id FROM users WHERE id=:id AND role='customer'");
            $check->execute(['id' => $id]);
            if (!$check->fetchColumn()) throw new RuntimeException('Không tìm thấy khách hàng.');
        }
    }
}

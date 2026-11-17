<?php
declare(strict_types=1);

// Chạy bằng CLI: php scripts/create_admin.php
// Tạo/cập nhật mật khẩu cho admin@lottehotel.vn.
require dirname(__DIR__) . '/app/Core/Database.php';
define('BASE_PATH', dirname(__DIR__));

$password = $argv[1] ?? null;
if (!$password || strlen($password) < 10) {
    fwrite(STDERR, "Cách dùng: php scripts/create_admin.php \"MatKhauDaiHon10KyTu\"\n");
    exit(1);
}

try {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = Database::connection()->prepare(
        "UPDATE users SET password_hash = :hash, status = 'active', role = 'admin'
         WHERE email = 'admin@lottehotel.vn'"
    );
    $stmt->execute(['hash' => $hash]);

    if ($stmt->rowCount() === 0) {
        echo "Không cập nhật được. Hãy import SQL để tạo admin@lottehotel.vn trước.\n";
        exit(1);
    }

    echo "Đã thiết lập mật khẩu cho admin@lottehotel.vn\n";
} catch (Throwable $e) {
    fwrite(STDERR, "Lỗi: " . $e->getMessage() . "\n");
    exit(1);
}

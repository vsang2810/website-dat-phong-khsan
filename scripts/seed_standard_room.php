<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';
$config = require dirname(__DIR__) . '/config/database.php';

try {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // 1. Check if Standard Room already exists
    $stmt = $pdo->prepare("SELECT id FROM room_types WHERE name = 'Standard Room' LIMIT 1");
    $stmt->execute();
    $existingId = $stmt->fetchColumn();

    if ($existingId) {
        $roomTypeId = (int)$existingId;
        echo "Standard Room đã tồn tại (ID: $roomTypeId). Đang cập nhật dữ liệu...\n";
        $upd = $pdo->prepare("UPDATE room_types SET area = 35, max_guests = 2, bed_type = '1 giường đôi', base_price = 1800000, thumbnail = 'assets/images/rooms/standard.jpg', status = 'active' WHERE id = :id");
        $upd->execute(['id' => $roomTypeId]);
    } else {
        $ins = $pdo->prepare("INSERT INTO room_types (hotel_id, name, description, area, max_guests, bed_type, base_price, thumbnail, status)
            VALUES (1, 'Standard Room', 'Hạng phòng tiêu chuẩn ấm cúng, thiết kế tinh tế với đầy đủ tiện nghi hiện đại, phù hợp cho 2 khách.', 35, 2, '1 giường đôi', 1800000, 'assets/images/rooms/standard.jpg', 'active')");
        $ins->execute();
        $roomTypeId = (int)$pdo->lastInsertId();
        echo "Đã tạo mới Standard Room thành công (ID: $roomTypeId).\n";
    }

    // 2. Add physical rooms if not exist
    $rooms = [
        ['1001', 10],
        ['1002', 10],
        ['1003', 10],
    ];
    $checkRoom = $pdo->prepare("SELECT id FROM rooms WHERE room_number = :num AND room_type_id = :type_id");
    $insRoom = $pdo->prepare("INSERT INTO rooms (room_type_id, room_number, floor_number, status, note) VALUES (:type_id, :num, :floor, 'available', 'Phòng tiêu chuẩn Standard Room')");

    foreach ($rooms as $r) {
        $checkRoom->execute(['num' => $r[0], 'type_id' => $roomTypeId]);
        if (!$checkRoom->fetchColumn()) {
            $insRoom->execute(['type_id' => $roomTypeId, 'num' => $r[0], 'floor' => $r[1]]);
            echo "Đã thêm phòng thực tế {$r[0]} (Tầng {$r[1]}).\n";
        }
    }

    // 3. Add room images
    $delImages = $pdo->prepare("DELETE FROM room_images WHERE room_type_id = :type_id");
    $delImages->execute(['type_id' => $roomTypeId]);

    $insImg = $pdo->prepare("INSERT INTO room_images (room_type_id, image_url, is_thumbnail) VALUES (:type_id, :url, :thumb)");
    $insImg->execute(['type_id' => $roomTypeId, 'url' => 'assets/images/rooms/standard.jpg', 'thumb' => 1]);
    $insImg->execute(['type_id' => $roomTypeId, 'url' => 'assets/images/rooms/standard-2.jpg', 'thumb' => 0]);
    echo "Đã cấu hình hình ảnh cho Standard Room.\n";

    // 4. Add amenities
    $delAmen = $pdo->prepare("DELETE FROM room_type_amenities WHERE room_type_id = :type_id");
    $delAmen->execute(['type_id' => $roomTypeId]);

    $insAmen = $pdo->prepare("INSERT IGNORE INTO room_type_amenities (room_type_id, amenity_id) VALUES (:type_id, :amen_id)");
    foreach ([1, 2, 3, 4, 6] as $amenId) { // WiFi, Điều hòa, Smart TV, Minibar, Két an toàn
        $insAmen->execute(['type_id' => $roomTypeId, 'amen_id' => $amenId]);
    }
    echo "Đã liên kết tiện ích (WiFi, Điều hòa, Smart TV, Minibar, Két an toàn) cho Standard Room.\n";

    echo "Hoàn thành khởi tạo hạng phòng Standard Room!\n";
} catch (Throwable $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}

-- LOTTE HOTEL BOOKING DATABASE
-- MySQL 8.0+ / MariaDB 10.5+
-- Import file này trong phpMyAdmin. Các bảng đã tồn tại sẽ được giữ nguyên.
CREATE DATABASE IF NOT EXISTS lotte_hotel_booking
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lotte_hotel_booking;

CREATE TABLE IF NOT EXISTS users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(150) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 phone VARCHAR(20) UNIQUE,
 password_hash VARCHAR(255) DEFAULT NULL,
 avatar VARCHAR(500),
 gender ENUM('male','female','other') DEFAULT 'other',
 role ENUM('customer','staff','admin') DEFAULT 'customer',
 status ENUM('active','inactive','blocked') DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS hotels (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(200) NOT NULL,
 address VARCHAR(255) NOT NULL,
 city VARCHAR(100),
 country VARCHAR(100) DEFAULT 'Vietnam',
 description TEXT,
 phone VARCHAR(20),
 email VARCHAR(150),
 star_rating TINYINT DEFAULT 5,
 thumbnail VARCHAR(500),
 check_in_time TIME DEFAULT '14:00:00',
 check_out_time TIME DEFAULT '12:00:00',
 status ENUM('active','inactive') DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS room_types (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 hotel_id BIGINT UNSIGNED NOT NULL,
 name VARCHAR(150) NOT NULL,
 description TEXT,
 area DECIMAL(8,2),
 max_guests TINYINT UNSIGNED NOT NULL DEFAULT 2,
 bed_type VARCHAR(100),
 base_price DECIMAL(15,2) NOT NULL,
 thumbnail VARCHAR(500),
 status ENUM('active','inactive') DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS rooms (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 room_type_id BIGINT UNSIGNED NOT NULL,
 room_number VARCHAR(20) NOT NULL,
 floor_number INT,
 status ENUM('available','maintenance','inactive') DEFAULT 'available',
 note TEXT,
 UNIQUE KEY unique_room_number (room_type_id, room_number),
 FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS room_images (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 room_type_id BIGINT UNSIGNED NOT NULL,
 image_url VARCHAR(500) NOT NULL,
 is_thumbnail TINYINT(1) DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS amenities (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL UNIQUE,
 icon VARCHAR(100),
 description TEXT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS room_type_amenities (
 room_type_id BIGINT UNSIGNED NOT NULL,
 amenity_id BIGINT UNSIGNED NOT NULL,
 PRIMARY KEY (room_type_id, amenity_id),
 FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE CASCADE,
 FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS services (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL,
 description TEXT,
 price DECIMAL(15,2) NOT NULL DEFAULT 0,
 image VARCHAR(500),
 status ENUM('active','inactive') DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS promotions (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 code VARCHAR(50) NOT NULL UNIQUE,
 name VARCHAR(150) NOT NULL,
 description TEXT,
 discount_type ENUM('percentage','fixed') NOT NULL,
 discount_value DECIMAL(15,2) NOT NULL,
 min_order_value DECIMAL(15,2) DEFAULT 0,
 max_discount DECIMAL(15,2) DEFAULT NULL,
 usage_limit INT DEFAULT NULL,
 used_count INT DEFAULT 0,
 start_date DATETIME NOT NULL,
 end_date DATETIME NOT NULL,
 status ENUM('active','inactive') DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 booking_code VARCHAR(30) NOT NULL UNIQUE,
 user_id BIGINT UNSIGNED NOT NULL,
 hotel_id BIGINT UNSIGNED NOT NULL,
 promotion_id BIGINT UNSIGNED DEFAULT NULL,
 guest_name VARCHAR(150) NOT NULL,
 guest_email VARCHAR(150) NOT NULL,
 guest_phone VARCHAR(20) NOT NULL,
 check_in DATE NOT NULL,
 check_out DATE NOT NULL,
 total_guests TINYINT UNSIGNED NOT NULL DEFAULT 1,
 number_of_nights INT UNSIGNED NOT NULL,
 subtotal DECIMAL(15,2) NOT NULL DEFAULT 0,
 discount_amount DECIMAL(15,2) DEFAULT 0,
 service_amount DECIMAL(15,2) DEFAULT 0,
 total_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
 payment_method ENUM('online','pay_at_hotel') NOT NULL,
 booking_status ENUM('pending_payment','pending_hotel_payment','confirmed','checked_in','completed','cancelled') DEFAULT 'pending_payment',
 payment_status ENUM('unpaid','paid','failed','refunded') DEFAULT 'unpaid',
 special_request TEXT,
 cancellation_reason TEXT,
 confirmed_by BIGINT UNSIGNED DEFAULT NULL,
 confirmed_at DATETIME DEFAULT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_booking_user (user_id),
 INDEX idx_booking_dates (check_in, check_out),
 INDEX idx_booking_status (booking_status),
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
 FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE RESTRICT,
 FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE SET NULL,
 FOREIGN KEY (confirmed_by) REFERENCES users(id) ON DELETE SET NULL,
 CHECK (check_out > check_in)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS booking_rooms (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 booking_id BIGINT UNSIGNED NOT NULL,
 room_id BIGINT UNSIGNED NOT NULL,
 room_name_snapshot VARCHAR(150),
 room_number_snapshot VARCHAR(20),
 price_per_night DECIMAL(15,2) NOT NULL,
 quantity INT UNSIGNED DEFAULT 1,
 guests INT UNSIGNED DEFAULT 1,
 subtotal DECIMAL(15,2) NOT NULL,
 FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
 FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 booking_id BIGINT UNSIGNED NOT NULL,
 transaction_code VARCHAR(100) UNIQUE,
 payment_method ENUM('online','pay_at_hotel') NOT NULL,
 amount DECIMAL(15,2) NOT NULL,
 payment_status ENUM('pending','success','failed','refunded') DEFAULT 'pending',
 payment_gateway VARCHAR(100),
 paid_at DATETIME DEFAULT NULL,
 confirmed_by BIGINT UNSIGNED DEFAULT NULL,
 note TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_payment_booking (booking_id),
 FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE RESTRICT,
 FOREIGN KEY (confirmed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS booking_services (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 booking_id BIGINT UNSIGNED NOT NULL,
 service_id BIGINT UNSIGNED NOT NULL,
 quantity INT UNSIGNED NOT NULL DEFAULT 1,
 unit_price DECIMAL(15,2) NOT NULL,
 subtotal DECIMAL(15,2) NOT NULL,
 FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
 FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reviews (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 booking_id BIGINT UNSIGNED NOT NULL,
 user_id BIGINT UNSIGNED NOT NULL,
 room_type_id BIGINT UNSIGNED NOT NULL,
 rating TINYINT UNSIGNED NOT NULL,
 comment TEXT,
 status ENUM('pending','approved','hidden') DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY unique_booking_review (booking_id),
 CHECK (rating BETWEEN 1 AND 5),
 FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
 FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS wishlists (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 room_type_id BIGINT UNSIGNED NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY unique_wishlist (user_id, room_type_id),
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS chat_conversations (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED DEFAULT NULL,
 assigned_admin_id BIGINT UNSIGNED DEFAULT NULL,
 guest_name VARCHAR(150),
 guest_email VARCHAR(150),
 status ENUM('open','closed') DEFAULT 'open',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
 FOREIGN KEY (assigned_admin_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS chat_messages (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 conversation_id BIGINT UNSIGNED NOT NULL,
 sender_id BIGINT UNSIGNED DEFAULT NULL,
 sender_type ENUM('customer','admin','system') NOT NULL,
 message TEXT NOT NULL,
 is_read TINYINT(1) DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_chat_conversation (conversation_id),
 FOREIGN KEY (conversation_id) REFERENCES chat_conversations(id) ON DELETE CASCADE,
 FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- SAMPLE DATA
INSERT IGNORE INTO users (id,full_name,email,phone,password_hash,role,status) VALUES
(1,'Quản trị viên Lotte','admin@lottehotel.vn','0901000001','$2y$12$z8szAanva0QPbmlimLUdNu.xIbKPZ7kKhrK4qQF/GoGOynqy5C0Ra','admin','active'),
(2,'Nguyễn Văn An','an@example.com','0901000002',NULL,'customer','active'),
(3,'Trần Minh Anh','minhanh@example.com','0901000003',NULL,'customer','active');

-- Đảm bảo tài khoản quản trị mặc định hoạt động cả khi import lại vào database cũ.
UPDATE users
SET full_name = 'Quản trị viên Lotte',
    password_hash = '$2y$12$z8szAanva0QPbmlimLUdNu.xIbKPZ7kKhrK4qQF/GoGOynqy5C0Ra',
    role = 'admin',
    status = 'active'
WHERE email = 'admin@lottehotel.vn';


INSERT IGNORE INTO hotels (id,name,address,city,country,description,phone,email,star_rating,thumbnail) VALUES
(1,'LOTTE HOTEL HANOI','54 Liễu Giai, Ba Đình','Hà Nội','Vietnam','Khách sạn cao cấp với hệ thống phòng nghỉ hiện đại và dịch vụ chuyên nghiệp.','02433331000','info@lottehotel.vn',5,'assets/images/hotel/lotte-hotel.jpg');

INSERT IGNORE INTO room_types (id,hotel_id,name,description,area,max_guests,bed_type,base_price,thumbnail) VALUES
(1,1,'Deluxe Room','Phòng nghỉ hiện đại, tiện nghi, phù hợp cho 2 khách.',42,2,'1 giường đôi',2500000,'assets/images/rooms/deluxe.jpg'),
(2,1,'Executive Room','Phòng cao cấp với không gian rộng và tầm nhìn đẹp.',50,2,'1 giường đôi',3500000,'assets/images/rooms/executive.jpg'),
(3,1,'Presidential Suite','Phòng tổng thống sang trọng với khu vực tiếp khách riêng.',120,4,'2 giường đôi',12000000,'assets/images/rooms/presidential.jpg');

INSERT IGNORE INTO rooms (id,room_type_id,room_number,floor_number,status) VALUES
(1,1,'1201',12,'available'),(2,1,'1202',12,'available'),
(3,2,'1501',15,'available'),(4,2,'1502',15,'available'),
(5,3,'2001',20,'available');

INSERT IGNORE INTO room_images (id,room_type_id,image_url,is_thumbnail) VALUES
(1,1,'assets/images/rooms/deluxe.jpg',1),(2,1,'assets/images/rooms/deluxe-2.jpg',0),
(3,2,'assets/images/rooms/executive.jpg',1),(4,2,'assets/images/rooms/executive-2.jpg',0),
(5,3,'assets/images/rooms/presidential.jpg',1);

INSERT IGNORE INTO amenities (id,name,icon,description) VALUES
(1,'WiFi miễn phí','wifi','Internet tốc độ cao'),
(2,'Điều hòa','air-conditioning','Điều hòa nhiệt độ'),
(3,'TV thông minh','tv','Smart TV màn hình lớn'),
(4,'Minibar','coffee','Minibar trong phòng'),
(5,'Bồn tắm','bath','Bồn tắm cao cấp'),
(6,'Két an toàn','lock','Két an toàn cá nhân');

INSERT IGNORE INTO room_type_amenities (room_type_id,amenity_id) VALUES
(1,1),(1,2),(1,3),(1,4),(2,1),(2,2),(2,3),(2,4),(2,5),
(3,1),(3,2),(3,3),(3,4),(3,5),(3,6);

INSERT IGNORE INTO services (id,name,description,price,image) VALUES
(1,'Ăn sáng buffet','Buffet sáng tại nhà hàng khách sạn.',350000,'assets/images/services/buffet.jpg'),
(2,'Đưa đón sân bay','Dịch vụ đưa đón sân bay bằng xe riêng.',500000,'assets/images/services/airport.jpg'),
(3,'Spa thư giãn','Dịch vụ chăm sóc và thư giãn.',800000,'assets/images/services/spa.jpg'),
(4,'Giặt là','Dịch vụ giặt và là quần áo.',150000,'assets/images/services/laundry.jpg');

INSERT IGNORE INTO promotions (id,code,name,description,discount_type,discount_value,min_order_value,max_discount,usage_limit,used_count,start_date,end_date) VALUES
(1,'WELCOME10','Ưu đãi khách hàng mới','Giảm 10% cho đơn đặt phòng đủ điều kiện.','percentage',10,1000000,1000000,100,0,'2026-01-01 00:00:00','2027-12-31 23:59:59');

INSERT IGNORE INTO bookings (id,booking_code,user_id,hotel_id,promotion_id,guest_name,guest_email,guest_phone,check_in,check_out,total_guests,number_of_nights,subtotal,discount_amount,service_amount,total_amount,payment_method,booking_status,payment_status,confirmed_by,confirmed_at) VALUES
(1,'LOTTE202611001',2,1,1,'Nguyễn Văn An','an@example.com','0901000002','2026-12-01','2026-12-03',2,2,5000000,500000,700000,5200000,'online','confirmed','paid',NULL,'2026-11-17 09:00:00'),
(2,'LOTTE202611002',3,1,NULL,'Trần Minh Anh','minhanh@example.com','0901000003','2026-12-05','2026-12-07',2,2,7000000,0,500000,7500000,'pay_at_hotel','pending_hotel_payment','unpaid',NULL,NULL);

INSERT IGNORE INTO booking_rooms (id,booking_id,room_id,room_name_snapshot,room_number_snapshot,price_per_night,quantity,guests,subtotal) VALUES
(1,1,1,'Deluxe Room','1201',2500000,1,2,5000000),
(2,2,3,'Executive Room','1501',3500000,1,2,7000000);

INSERT IGNORE INTO payments (id,booking_id,transaction_code,payment_method,amount,payment_status,payment_gateway,paid_at,confirmed_by,note) VALUES
(1,1,'TXN-LOTTE-001','online',5200000,'success','Online Payment','2026-11-17 09:00:00',NULL,'Thanh toán online thành công.'),
(2,2,'TXN-LOTTE-002','pay_at_hotel',7500000,'pending',NULL,NULL,NULL,'Chờ khách đến quầy và thanh toán.');

INSERT IGNORE INTO booking_services (id,booking_id,service_id,quantity,unit_price,subtotal) VALUES
(1,1,1,2,350000,700000),(2,2,2,1,500000,500000);

INSERT IGNORE INTO wishlists (id,user_id,room_type_id) VALUES (1,2,2),(2,2,3),(3,3,1);
INSERT IGNORE INTO chat_conversations (id,user_id,assigned_admin_id,guest_name,guest_email,status) VALUES
(1,2,1,'Nguyễn Văn An','an@example.com','open');
INSERT IGNORE INTO chat_messages (id,conversation_id,sender_id,sender_type,message,is_read) VALUES
(1,1,2,'customer','Xin chào, tôi muốn hỏi về dịch vụ đặt phòng.',1),
(2,1,1,'admin','Xin chào quý khách! Khách sạn có thể hỗ trợ quý khách.',1),
(3,1,2,'customer','Khách sạn có phục vụ ăn sáng không?',0);

-- Password cho admin chưa được gán. Xem README để tạo hash an toàn bằng PHP.

-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20261013.0ff4ebe5a2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2026 at 12:49 PM
-- Server version: 9.6.0
-- PHP Version: 8.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ks_nhom6`
--

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--
CREATE TABLE `amenities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon`, `description`) VALUES
(1, 'WiFi miễn phí', 'wifi', 'Internet tốc độ cao'),
(2, 'Điều hòa', 'air-conditioning', 'Điều hòa nhiệt độ'),
(3, 'TV thông minh', 'tv', 'Smart TV màn hình lớn'),
(4, 'Minibar', 'coffee', 'Minibar trong phòng'),
(5, 'Bồn tắm', 'bath', 'Bồn tắm cao cấp'),
(6, 'Két an toàn', 'lock', 'Két an toàn cá nhân');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--
CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_code` varchar(30) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `hotel_id` bigint UNSIGNED NOT NULL,
  `promotion_id` bigint UNSIGNED DEFAULT NULL,
  `guest_name` varchar(150) NOT NULL,
  `guest_email` varchar(150) NOT NULL,
  `guest_phone` varchar(20) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_guests` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `total_adults` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `total_children` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `children_ages` varchar(100) DEFAULT NULL,
  `number_of_nights` int UNSIGNED NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `service_amount` decimal(15,2) DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `deposit_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remaining_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('online','pay_at_hotel') NOT NULL,
  `booking_status` enum('pending_payment','pending_hotel_payment','confirmed','checked_in','completed','cancelled') DEFAULT 'pending_payment',
  `payment_status` enum('unpaid','partially_paid','paid','failed','refunded') DEFAULT 'unpaid',
  `special_request` text,
  `cancellation_reason` text,
  `confirmed_by` bigint UNSIGNED DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `actual_check_in` datetime DEFAULT NULL,
  `actual_check_out` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_code`, `user_id`, `hotel_id`, `promotion_id`, `guest_name`, `guest_email`, `guest_phone`, `check_in`, `check_out`, `total_guests`, `total_adults`, `total_children`, `children_ages`, `number_of_nights`, `subtotal`, `discount_amount`, `service_amount`, `total_amount`, `deposit_amount`, `remaining_amount`, `payment_method`, `booking_status`, `payment_status`, `special_request`, `cancellation_reason`, `confirmed_by`, `confirmed_at`, `actual_check_in`, `actual_check_out`, `created_at`, `updated_at`) VALUES
(1, 'LOTTE202611001', 2, 1, 1, 'Nguyễn Văn An', 'an@example.com', '0901000002', '2026-12-01', '2026-12-03', 2, 2, 0, NULL, 2, 5000000.00, 500000.00, 700000.00, 5200000.00, 5200000.00, 0.00, 'online', 'confirmed', 'paid', NULL, NULL, NULL, '2026-11-17 09:00:00', NULL, NULL, '2026-11-27 11:45:48', '2026-11-27 12:28:57'),
(2, 'LOTTE202611002', 3, 1, NULL, 'Trần Minh Anh', 'minhanh@example.com', '0901000003', '2026-12-05', '2026-12-07', 2, 2, 0, NULL, 2, 7000000.00, 0.00, 500000.00, 7500000.00, 0.00, 7500000.00, 'pay_at_hotel', 'pending_hotel_payment', 'unpaid', NULL, NULL, NULL, NULL, NULL, NULL, '2026-11-27 11:45:48', '2026-11-27 12:28:57'),
(3, 'LOTTE2026118416', 5, 1, NULL, 'Nguyễn Đại Khang', 'nguyendaikhang2000@gmail.com', '0349869157', '2026-11-28', '2026-11-29', 2, 2, 0, NULL, 1, 3500000.00, 0.00, 1800000.00, 5300000.00, 0.00, 5300000.00, 'pay_at_hotel', 'pending_hotel_payment', 'unpaid', 'ertert', NULL, NULL, NULL, NULL, NULL, '2026-11-27 11:49:08', '2026-11-27 12:28:57'),
(4, 'LOTTE2026115878', 5, 1, 1, 'Nguyễn Đại Khang', 'nguyendaikhang2000@gmail.com', '0349869157', '2026-11-28', '2026-11-29', 2, 2, 0, NULL, 1, 1800000.00, 180000.00, 0.00, 1620000.00, 1620000.00, 0.00, 'online', 'confirmed', 'paid', '', NULL, NULL, '2026-11-27 12:01:20', NULL, NULL, '2026-11-27 12:01:20', '2026-11-27 12:28:57'),
(5, 'LOTTE2026118208', 1, 1, 1, 'Quản trị viên Lotte', 'admin@lottehotel.vn', '0901000001', '2026-11-28', '2026-11-29', 4, 2, 2, '9,10', 1, 12000000.00, 1000000.00, 1800000.00, 12800000.00, 0.00, 12800000.00, 'pay_at_hotel', 'confirmed', 'paid', '', NULL, 1, '2026-11-27 19:34:03', NULL, NULL, '2026-11-27 12:33:31', '2026-11-27 12:34:03');

-- --------------------------------------------------------

--
-- Table structure for table `booking_rooms`
--
CREATE TABLE `booking_rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `room_name_snapshot` varchar(150) DEFAULT NULL,
  `room_number_snapshot` varchar(20) DEFAULT NULL,
  `price_per_night` decimal(15,2) NOT NULL,
  `quantity` int UNSIGNED DEFAULT '1',
  `guests` int UNSIGNED DEFAULT '1',
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_rooms`
--

INSERT INTO `booking_rooms` (`id`, `booking_id`, `room_id`, `room_name_snapshot`, `room_number_snapshot`, `price_per_night`, `quantity`, `guests`, `subtotal`) VALUES
(1, 1, 1, 'Deluxe Room', '1201', 2500000.00, 1, 2, 5000000.00),
(2, 2, 3, 'Executive Room', '1501', 3500000.00, 1, 2, 7000000.00),
(3, 3, 3, 'Executive Room', '1501', 3500000.00, 1, 2, 3500000.00),
(4, 4, 6, 'Standard Room', '1001', 1800000.00, 1, 2, 1800000.00),
(5, 5, 5, 'Presidential Suite', '2001', 12000000.00, 1, 4, 12000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `booking_services`
--
CREATE TABLE `booking_services` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `unit_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_services`
--

INSERT INTO `booking_services` (`id`, `booking_id`, `service_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 1, 2, 350000.00, 700000.00),
(2, 2, 2, 1, 500000.00, 500000.00),
(3, 3, 1, 1, 350000.00, 350000.00),
(4, 3, 2, 1, 500000.00, 500000.00),
(5, 3, 3, 1, 800000.00, 800000.00),
(6, 3, 4, 1, 150000.00, 150000.00),
(7, 5, 1, 1, 350000.00, 350000.00),
(8, 5, 2, 1, 500000.00, 500000.00),
(9, 5, 3, 1, 800000.00, 800000.00),
(10, 5, 4, 1, 150000.00, 150000.00);

-- --------------------------------------------------------

--
-- Table structure for table `chat_conversations`
--
CREATE TABLE `chat_conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_admin_id` bigint UNSIGNED DEFAULT NULL,
  `guest_name` varchar(150) DEFAULT NULL,
  `guest_email` varchar(150) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `user_id`, `assigned_admin_id`, `guest_name`, `guest_email`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Nguyễn Văn An', 'an@example.com', 'open', '2026-11-27 11:45:48', '2026-11-27 11:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--
CREATE TABLE `chat_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED DEFAULT NULL,
  `sender_type` enum('customer','admin','system') NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_id`, `sender_type`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 2, 'customer', 'Xin chào, tôi muốn hỏi về dịch vụ đặt phòng.', 1, '2026-11-27 11:45:48'),
(2, 1, 1, 'admin', 'Xin chào quý khách! Khách sạn có thể hỗ trợ quý khách.', 1, '2026-11-27 11:45:48'),
(3, 1, 2, 'customer', 'Khách sạn có phục vụ ăn sáng không?', 0, '2026-11-27 11:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--
CREATE TABLE `hotels` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'Vietnam',
  `description` text,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `star_rating` tinyint DEFAULT '5',
  `thumbnail` varchar(500) DEFAULT NULL,
  `check_in_time` time DEFAULT '14:00:00',
  `check_out_time` time DEFAULT '12:00:00',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `address`, `city`, `country`, `description`, `phone`, `email`, `star_rating`, `thumbnail`, `check_in_time`, `check_out_time`, `status`, `created_at`) VALUES
(1, 'LOTTE HOTEL HANOI', '54 Liễu Giai, Ba Đình', 'Hà Nội', 'Vietnam', 'Khách sạn cao cấp với hệ thống phòng nghỉ hiện đại và dịch vụ chuyên nghiệp.', '02433331000', 'info@lottehotel.vn', 5, 'assets/images/hotel/lotte-hotel.jpg', '14:00:00', '12:00:00', 'active', '2026-11-27 11:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--
CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `transaction_code` varchar(100) DEFAULT NULL,
  `payment_method` enum('online','pay_at_hotel') NOT NULL,
  `payment_type` enum('deposit','balance','full') NOT NULL DEFAULT 'full',
  `amount` decimal(15,2) NOT NULL,
  `payment_status` enum('pending','success','failed','refunded') DEFAULT 'pending',
  `payment_gateway` varchar(100) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `confirmed_by` bigint UNSIGNED DEFAULT NULL,
  `note` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `transaction_code`, `payment_method`, `payment_type`, `amount`, `payment_status`, `payment_gateway`, `paid_at`, `confirmed_by`, `note`, `created_at`) VALUES
(1, 1, 'TXN-LOTTE-001', 'online', 'full', 5200000.00, 'success', 'Online Payment', '2026-11-17 09:00:00', NULL, 'Thanh toán online thành công.', '2026-11-27 11:45:48'),
(2, 2, 'TXN-LOTTE-002', 'pay_at_hotel', 'full', 7500000.00, 'pending', NULL, NULL, NULL, 'Chờ khách đến quầy và thanh toán.', '2026-11-27 11:45:48'),
(3, 3, 'TXN-20261127-463326', 'pay_at_hotel', 'full', 5300000.00, 'pending', 'Thanh toán tại quầy', NULL, NULL, 'Đơn thanh toán tại quầy khi nhận phòng. Chờ nhân viên thu tiền và xác nhận.', '2026-11-27 11:49:08'),
(4, 4, 'TXN-20261127-429046', 'online', 'full', 1620000.00, 'success', 'Cổng thanh toán trực tuyến Lotte Pay / QR Banking', '2026-11-27 12:01:20', NULL, 'Khách hàng thanh toán trực tuyến thành công.', '2026-11-27 12:01:20'),
(5, 5, 'TXN-20261127-905365', 'pay_at_hotel', 'full', 12800000.00, 'success', 'Thanh toán tại quầy', '2026-11-27 19:34:03', 1, 'Đơn thanh toán tại quầy khi nhận phòng. Chờ nhân viên thu tiền và xác nhận. | Admin xác nhận đã thu tại quầy', '2026-11-27 12:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--
CREATE TABLE `promotions` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(15,2) NOT NULL,
  `min_order_value` decimal(15,2) DEFAULT '0.00',
  `max_discount` decimal(15,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `code`, `name`, `description`, `discount_type`, `discount_value`, `min_order_value`, `max_discount`, `usage_limit`, `used_count`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 'WELCOME10', 'Ưu đãi khách hàng mới', 'Giảm 10% cho đơn đặt phòng đủ điều kiện.', 'percentage', 10.00, 1000000.00, 1000000.00, 100, 2, '2026-01-01 00:00:00', '2027-12-31 23:59:59', 'active', '2026-11-27 11:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--
CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `room_type_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` text,
  `status` enum('pending','approved','hidden') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--
CREATE TABLE `rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `room_type_id` bigint UNSIGNED NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `floor_number` int DEFAULT NULL,
  `status` enum('available','maintenance','inactive') DEFAULT 'available',
  `note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_type_id`, `room_number`, `floor_number`, `status`, `note`) VALUES
(1, 1, '1201', 12, 'available', NULL),
(2, 1, '1202', 12, 'available', NULL),
(3, 2, '1501', 15, 'available', NULL),
(4, 2, '1502', 15, 'available', NULL),
(5, 3, '2001', 20, 'available', NULL),
(6, 4, '1001', 10, 'available', NULL),
(7, 4, '1002', 10, 'available', NULL),
(8, 4, '1003', 10, 'available', NULL),
(9, 5, '2101', 21, 'available', NULL),
(10, 5, '2102', 21, 'available', NULL),
(11, 6, '2201', 22, 'available', NULL),
(12, 7, '2301', 23, 'available', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_images`
--
CREATE TABLE `room_images` (
  `id` bigint UNSIGNED NOT NULL,
  `room_type_id` bigint UNSIGNED NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `is_thumbnail` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room_images`
--

INSERT INTO `room_images` (`id`, `room_type_id`, `image_url`, `is_thumbnail`, `created_at`) VALUES
(1, 1, 'assets/images/rooms/deluxe.jpg', 1, '2026-11-27 11:45:48'),
(2, 1, 'assets/images/rooms/deluxe-2.jpg', 0, '2026-11-27 11:45:48'),
(3, 2, 'assets/images/rooms/executive.jpg', 1, '2026-11-27 11:45:48'),
(4, 2, 'assets/images/rooms/executive-2.jpg', 0, '2026-11-27 11:45:48'),
(5, 3, 'assets/images/rooms/presidential.jpg', 1, '2026-11-27 11:45:48'),
(6, 4, 'assets/images/rooms/standard.jpg', 1, '2026-11-27 11:45:48'),
(7, 4, 'assets/images/rooms/standard-2.jpg', 0, '2026-11-27 11:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--
CREATE TABLE `room_types` (
  `id` bigint UNSIGNED NOT NULL,
  `hotel_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `area` decimal(8,2) DEFAULT NULL,
  `max_guests` tinyint UNSIGNED NOT NULL DEFAULT '2',
  `bed_type` varchar(100) DEFAULT NULL,
  `base_price` decimal(15,2) NOT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `hotel_id`, `name`, `description`, `area`, `max_guests`, `bed_type`, `base_price`, `thumbnail`, `status`, `created_at`) VALUES
(1, 1, 'Deluxe Room', 'Phòng nghỉ hiện đại, tiện nghi, phù hợp cho 2 khách.', 42.00, 2, '1 giường đôi', 2500000.00, 'assets/images/rooms/deluxe.jpg', 'active', '2026-11-27 11:45:48'),
(2, 1, 'Executive Room', 'Phòng cao cấp với không gian rộng và tầm nhìn đẹp.', 50.00, 2, '1 giường đôi', 3500000.00, 'assets/images/rooms/executive.jpg', 'active', '2026-11-27 11:45:48'),
(3, 1, 'Presidential Suite', 'Phòng tổng thống sang trọng với khu vực tiếp khách riêng.', 120.00, 4, '2 giường đôi', 12000000.00, 'assets/images/rooms/presidential.jpg', 'active', '2026-11-27 11:45:48'),
(4, 1, 'Standard Room', 'Hạng phòng tiêu chuẩn ấm cúng, thiết kế tinh tế với đầy đủ tiện nghi hiện đại, phù hợp cho 2 khách.', 35.00, 2, '1 giường đôi', 1800000.00, 'assets/images/rooms/standard.jpg', 'active', '2026-11-27 11:45:48'),
(5, 1, 'Family Suite', 'Phòng gia đình rộng rãi, có khu vực sinh hoạt chung, phù hợp cho gia đình tối đa 5 khách.', 85.00, 5, '2 giường đôi + sofa bed', 6500000.00, 'assets/images/rooms/presidential.jpg', 'active', '2026-11-27 12:36:03'),
(6, 1, 'Family Connecting Room', 'Hai không gian phòng kết nối riêng tư, phù hợp nhóm gia đình hoặc bạn bè tối đa 6 khách.', 95.00, 6, '2 giường đôi + 2 giường đơn', 7800000.00, 'assets/images/rooms/presidential.jpg', 'active', '2026-11-27 12:36:03'),
(7, 1, 'Grand Family Suite', 'Suite gia đình cao cấp với phòng khách riêng, phù hợp đoàn đông tối đa 8 khách.', 150.00, 8, '3 giường đôi + sofa bed', 15000000.00, 'assets/images/rooms/presidential.jpg', 'active', '2026-11-27 12:36:03');

-- --------------------------------------------------------

--
-- Table structure for table `room_type_amenities`
--
CREATE TABLE `room_type_amenities` (
  `room_type_id` bigint UNSIGNED NOT NULL,
  `amenity_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room_type_amenities`
--

INSERT INTO `room_type_amenities` (`room_type_id`, `amenity_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(1, 4),
(2, 4),
(3, 4),
(4, 4),
(5, 4),
(6, 4),
(7, 4),
(2, 5),
(3, 5),
(5, 5),
(7, 5),
(3, 6),
(4, 6),
(5, 6),
(6, 6),
(7, 6);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--
CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `image` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `image`, `status`, `created_at`) VALUES
(1, 'Ăn sáng buffet', 'Buffet sáng tại nhà hàng khách sạn.', 350000.00, 'assets/images/services/buffet.jpg', 'active', '2026-11-27 11:45:48'),
(2, 'Đưa đón sân bay', 'Dịch vụ đưa đón sân bay bằng xe riêng.', 500000.00, 'assets/images/services/airport.jpg', 'active', '2026-11-27 11:45:48'),
(3, 'Spa thư giãn', 'Dịch vụ chăm sóc và thư giãn.', 800000.00, 'assets/images/services/spa.jpg', 'active', '2026-11-27 11:45:48'),
(4, 'Giặt là', 'Dịch vụ giặt và là quần áo.', 150000.00, 'assets/images/services/laundry.jpg', 'active', '2026-11-27 11:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT 'other',
  `role` enum('customer','staff','admin') DEFAULT 'customer',
  `status` enum('active','inactive','blocked') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password_hash`, `avatar`, `gender`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Quản trị viên Lotte', 'admin@lottehotel.vn', '0901000001', '$2y$12$yrvQy32Sg4xLBkgxskFOsuR2RE1hzx7QcFILbeA.oZ6cZsH8cxc3y', NULL, 'other', 'admin', 'active', '2026-11-27 11:45:48', '2026-11-27 11:55:49'),
(2, 'Nguyễn Văn An', 'an@example.com', '0901000002', '$2y$12$z8szAanva0QPbmlimLUdNu.xIbKPZ7kKhrK4qQF/GoGOynqy5C0Ra', NULL, 'other', 'customer', 'active', '2026-11-27 11:45:48', '2026-11-27 11:45:48'),
(3, 'Trần Minh Anh', 'minhanh@example.com', '0901000003', '$2y$12$z8szAanva0QPbmlimLUdNu.xIbKPZ7kKhrK4qQF/GoGOynqy5C0Ra', NULL, 'other', 'customer', 'active', '2026-11-27 11:45:48', '2026-11-27 11:45:48'),
(4, 'Khách Hàng Lotte', 'customer@lottehotel.vn', '0901000004', '$2y$12$z8szAanva0QPbmlimLUdNu.xIbKPZ7kKhrK4qQF/GoGOynqy5C0Ra', NULL, 'other', 'customer', 'active', '2026-11-27 11:45:48', '2026-11-27 11:45:48'),
(5, 'Nguyễn Đại Khang', 'nguyendaikhang2000@gmail.com', '0349869157', '$2y$12$yrvQy32Sg4xLBkgxskFOsuR2RE1hzx7QcFILbeA.oZ6cZsH8cxc3y', NULL, 'other', 'admin', 'active', '2026-11-27 11:48:29', '2026-11-27 11:55:28');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--
CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `room_type_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `room_type_id`, `created_at`) VALUES
(1, 2, 2, '2026-11-27 11:45:48'),
(2, 2, 3, '2026-11-27 11:45:48'),
(3, 3, 1, '2026-11-27 11:45:48'),
(4, 1, 2, '2026-11-27 12:19:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `idx_booking_user` (`user_id`),
  ADD KEY `idx_booking_dates` (`check_in`,`check_out`),
  ADD KEY `idx_booking_status` (`booking_status`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `promotion_id` (`promotion_id`),
  ADD KEY `confirmed_by` (`confirmed_by`);

--
-- Indexes for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `assigned_admin_id` (`assigned_admin_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chat_conversation` (`conversation_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_code` (`transaction_code`),
  ADD KEY `idx_payment_booking` (`booking_id`),
  ADD KEY `confirmed_by` (`confirmed_by`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking_review` (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_room_number` (`room_type_id`,`room_number`);

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `room_type_amenities`
--
ALTER TABLE `room_type_amenities`
  ADD PRIMARY KEY (`room_type_id`,`amenity_id`),
  ADD KEY `amenity_id` (`amenity_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`room_type_id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking_services`
--
ALTER TABLE `booking_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD CONSTRAINT `booking_rooms_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD CONSTRAINT `chat_conversations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_conversations_ibfk_2` FOREIGN KEY (`assigned_admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `room_images`
--
ALTER TABLE `room_images`
  ADD CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_types`
--
ALTER TABLE `room_types`
  ADD CONSTRAINT `room_types_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `room_type_amenities`
--
ALTER TABLE `room_type_amenities`
  ADD CONSTRAINT `room_type_amenities_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_type_amenities_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

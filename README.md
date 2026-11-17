# LOTTE HOTEL HANOI — Admin MVC

Phiên bản khởi đầu chuyển dự án đặt phòng sang **PHP thuần MVC + PDO + MySQL**. Giao diện Admin theo phong cách Modern Minimal: nền sáng, sidebar than chì, điểm nhấn trung tính.

## 1. Yêu cầu

- PHP 8.1 trở lên (khuyến nghị PHP 8.2+)
- MySQL 8.0+ hoặc MariaDB 10.5+
- Extension PHP: `pdo_mysql`, `mbstring`
- Apache (XAMPP) hoặc PHP built-in server để chạy thử

## 2. Import cơ sở dữ liệu

1. Mở XAMPP, bật Apache và MySQL.
2. Truy cập `http://localhost/phpmyadmin`.
3. Chọn tab **Import**.
4. Chọn file `database/lotte_hotel_booking.sql`.
5. Nhấn **Import/Go**.

> Lưu ý: File SQL sử dụng `CREATE TABLE IF NOT EXISTS` và `INSERT IGNORE`, không xóa bảng/dữ liệu hiện có. Nếu bạn đã tạo database theo SQL ở bước trước thì có thể bỏ qua bước import; chỉ import khi cần tạo database mẫu ban đầu.

## 3. Cấu hình kết nối

Mở `config/database.php` và chỉnh host, port, database, username, password theo MySQL trên máy.

Mặc định XAMPP thường là:
- host: `127.0.0.1`
- port: `3306`
- database: `lotte_hotel_booking`
- username: `root`
- password: rỗng (tùy cấu hình XAMPP)

## 4. Tài khoản Admin mặc định

Sau khi import database, có thể đăng nhập bằng:
- Email: `admin@lottehotel.vn`
- Mật khẩu mặc định: `123456`

Mật khẩu đã được lưu dưới dạng hash bằng `password_hash`, không lưu plaintext.
Vì đây là mật khẩu mặc định đơn giản, hãy đổi mật khẩu sau khi đăng nhập và
không sử dụng cấu hình này trên website đang hoạt động công khai.

Nếu muốn tự đặt lại mật khẩu bằng PHP CLI, chạy:

```bash
php scripts/create_admin.php "MatKhauMoiCuaBan"
```

## 5. Chạy dự án

### Cách A — XAMPP Apache

1. Chép cả thư mục `lotte-hotel-mvc` vào `C:\xampp\htdocs\`.
2. Trong Apache, đặt DocumentRoot hoặc VirtualHost trỏ tới thư mục `lotte-hotel-mvc/public` (khuyến nghị).
3. Nếu dùng URL thư mục con mặc định, truy cập `http://localhost/lotte-hotel-mvc/public/`.
4. Nếu rewrite không hoạt động, bật `mod_rewrite` và `AllowOverride All` trong Apache.

### Cách B — PHP built-in server

Từ thư mục gốc dự án:

```bash
php -S localhost:8000 -t public public/index.php
```

Mở `http://localhost:8000`.

## 6. Các route đã có

- `GET /login`: trang đăng nhập Admin.
- `POST /login`: xác thực tài khoản.
- `GET /admin`: Dashboard.
- `POST /logout`: đăng xuất.

## 7. Dashboard hiện lấy dữ liệu thật từ MySQL

- Tổng số booking.
- Tổng khách hàng.
- Số phòng đang hoạt động.
- Tổng doanh thu từ payment có `payment_status = success`.
- 8 booking gần đây.
- Tổng hợp trạng thái booking.

## 8. Cấu trúc

```text
app/
  Controllers/
  Core/
  Models/
  Views/
config/
database/
public/
  assets/css/
  assets/js/
routes/
scripts/
```

## 9. Phạm vi hiện tại

Đã có MVC core, router, PDO, session authentication, CSRF token cho form đăng nhập/đăng xuất và giao diện dashboard. Các mục đặt phòng, phòng, khách hàng, thanh toán, khuyến mãi trong sidebar mới là nhãn điều hướng chờ phát triển, chưa có CRUD.

## 10. Lưu ý nghiệp vụ

Dashboard chỉ đọc dữ liệu. Chưa triển khai luồng xác nhận booking, chống đặt trùng phòng, check-in/check-out, hoàn tiền hoặc xử lý webhook thanh toán. Quy tắc đã thống nhất cho giai đoạn sau:
- Online: chỉ xác nhận khi cổng thanh toán trả về thành công và được xác thực.
- Pay at hotel: Admin/nhân viên xác nhận sau khi khách đến quầy và thanh toán.

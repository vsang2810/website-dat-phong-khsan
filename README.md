# LOTTE HOTEL HANOI — Hệ thống Đặt Phòng (PHP MVC)

Dự án website đặt phòng khách sạn 5 sao **LOTTE HOTEL HANOI** xây dựng trên nền tảng **PHP thuần MVC + PDO + MySQL**. Giao diện Client sang trọng chuẩn 5 sao và Admin theo phong cách Modern Minimal.

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

Nếu đã import SQL trước đó, hãy mở phpMyAdmin → chọn database `lotte_hotel_booking` → tab SQL,
chạy lệnh cập nhật sau để áp dụng mật khẩu mặc định cho bản ghi Admin hiện có:

```sql
UPDATE users
SET password_hash = '$2y$12$z8szAanva0QPbmlimLUdNu.xIbKPZ7kKhrK4qQF/GoGOynqy5C0Ra',
    role = 'admin',
    status = 'active'
WHERE email = 'admin@lottehotel.vn';
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


## Quản lý đặt phòng (V2)
- Danh sách đơn, tìm kiếm mã đơn/tên/email/số điện thoại; lọc trạng thái, phương thức và ngày nhận phòng; phân trang.
- Xem chi tiết khách, thời gian lưu trú, phòng, tổng tiền và lịch sử thanh toán.
- Đơn online chỉ xác nhận khi `payment_status = paid` hoặc có giao dịch `payments.payment_status = success`.
- Đơn thanh toán tại khách sạn chỉ xác nhận sau khi khách đến quầy và Admin/Staff xác nhận đã thu tiền; hệ thống ghi nhận giao dịch thành công rồi mới chuyển sang `confirmed`.
- Luồng tiếp theo: `confirmed` → `checked_in` → `completed`; có thể hủy đơn trước khi nhận phòng.
- Thao tác cập nhật dùng POST, CSRF và transaction. Không xóa cứng đơn đặt phòng.


## Quản lý hạng phòng (V3)

- Hệ thống được tối ưu hóa cho mô hình quản lý **1 khách sạn duy nhất (LOTTE HOTEL HANOI)** với các hạng phòng nghỉ cao cấp:
  - **Standard Room**: 35 m², tối đa 2 khách, 1 giường đôi, giá niêm yết 1.800.000 ₫ / đêm.
  - **Deluxe Room**: 42 m², tối đa 2 khách, 1 giường đôi, giá niêm yết 2.500.000 ₫ / đêm.
  - **Executive Room**: 50 m², tối đa 2 khách, 1 giường đôi, giá niêm yết 3.500.000 ₫ / đêm.
  - **Presidential Suite**: 120 m², tối đa 4 khách, 2 giường đôi, giá niêm yết 12.000.000 ₫ / đêm.
- Quản lý danh sách hạng phòng: tên hạng phòng, mô tả, diện tích, sức chứa tối đa, loại giường, giá niêm yết và số phòng thực tế tương ứng.
- Thêm mới, cập nhật hạng phòng không cần chọn khách sạn thủ công (hệ thống tự động liên kết với khách sạn duy nhất).
- Chuyển trạng thái kinh doanh: Đang bán (`active`) hoặc Tạm ngừng (`inactive`).
- Bảng thống kê nhanh: tổng số hạng phòng, số hạng đang kinh doanh, số hạng tạm ngừng, tổng số phòng thực tế.
- Đường dẫn quản trị: Menu **Hạng phòng** (`/admin/room-types`).
- Cấu hình thông tin khách sạn: Menu **Thông tin khách sạn** (`/admin/hotel-info`). Các yêu cầu truy cập vào `/admin/hotels` được tự động chuyển hướng sang Quản lý hạng phòng.


## Quản lý phòng thực tế (V4)

Truy cập **Admin → Phòng thực tế** để:
- Thêm/sửa phòng với mã phòng duy nhất toàn hệ thống, tầng, loại phòng và ghi chú.
- Tìm kiếm theo mã phòng, loại phòng, khách sạn; lọc theo khách sạn, loại phòng và trạng thái quản lý.
- Chuyển trạng thái quản lý: Sẵn sàng (`available`), Bảo trì (`maintenance`), Ngừng hoạt động (`inactive`).
- Xem tình trạng được suy ra từ đơn đặt phòng trong ngày: Đang sử dụng, Đã đặt hôm nay hoặc Đang giữ chỗ.

**Bảo vệ dữ liệu đặt phòng:** Không cho chuyển phòng sang bảo trì/ngừng hoạt động khi phòng còn liên quan đến đơn chưa hủy/chưa hoàn tất có ngày trả phòng trong tương lai. Mã phòng không được trùng trong toàn hệ thống. Không cho đổi loại phòng nếu phòng đã có lịch sử trong `booking_rooms`.

Không cần thay đổi cấu trúc database hiện tại; chức năng sử dụng các bảng `rooms`, `room_types`, `hotels`, `booking_rooms` và `bookings` đã có.


## Quản lý khách hàng (V5)

- Truy cập **Khách hàng** trên menu quản trị hoặc `/admin/customers`.
- Danh sách chỉ hiển thị tài khoản có `users.role = 'customer'`; Admin/Staff không bị đưa vào danh sách.
- Hỗ trợ tìm kiếm theo họ tên, email, số điện thoại; lọc trạng thái tài khoản; phân trang.
- Xem hồ sơ khách hàng, tổng số đơn, giá trị đơn không bị hủy và lịch sử đặt phòng.
- Chỉnh sửa họ tên, email, số điện thoại, giới tính; kiểm tra định dạng và trùng email/số điện thoại.
- Thay đổi trạng thái tài khoản: active / inactive / blocked.
- Không xóa khách hàng để bảo toàn khóa ngoại và lịch sử đặt phòng. Hồ sơ dùng `bookings.user_id`; dữ liệu khách chụp tại thời điểm đặt (`guest_name`, `guest_email`, `guest_phone`) không bị sửa khi cập nhật hồ sơ.
- Không cần chạy migration hoặc nhập lại SQL: V5 sử dụng bảng `users` và `bookings` hiện có.

Lưu ý: việc khóa/ngừng hoạt động tài khoản được lưu trong `users.status`. Việc chặn đăng nhập thực tế phụ thuộc vào luồng đăng nhập phía khách hàng; module quản trị này không thay đổi quy trình đăng nhập/đặt phòng hiện có.


## Quản lý thanh toán (V6)

- Truy cập **Admin → Thanh toán** để xem danh sách giao dịch, tìm theo mã giao dịch/mã đơn/thông tin khách, lọc trạng thái, phương thức và khoảng ngày; có phân trang.
- Xem chi tiết giao dịch, số tiền, cổng thanh toán, thời gian, người xác nhận và liên kết tới đơn đặt phòng.
- Giao dịch online không có thao tác sửa trạng thái thủ công trong Admin; chỉ được ghi nhận thành công khi có kết quả xác thực từ cổng thanh toán. Module này chưa tích hợp webhook/cổng thanh toán thực tế.
- Giao dịch tại quầy chỉ được xác nhận khi đơn đang chờ thanh toán tại khách sạn và nhân viên xác nhận đã thực thu đủ tiền. Thao tác dùng lại workflow đặt phòng hiện tại, transaction và khóa bản ghi; đồng bộ payment thành success, booking payment_status thành paid và booking_status thành confirmed.
- Không xóa giao dịch, không sửa số tiền giao dịch đã phát sinh và không tự đánh dấu hoàn tiền. Trạng thái refunded chỉ hiển thị dữ liệu đã được hệ thống/cổng thanh toán ghi nhận; chưa có tích hợp hoàn tiền thực tế.
- Không cần migration: sử dụng bảng `payments`, `bookings`, `users` hiện có.

---

## Giao diện & Chức năng Khách hàng (Client Portal)

Hệ thống giao diện và chức năng dành cho khách hàng được xây dựng hoàn chỉnh, kết nối trực tiếp và đồng bộ 100% với các chức năng đã xây dựng trên trang quản trị:

### 1. Các trang và chức năng chính:
- **Trang chủ (`/`)**:
  - Banner sang trọng chuẩn khách sạn 5 sao LOTTE HOTEL HANOI.
  - Thanh tìm kiếm & đặt phòng nhanh (chọn chi nhánh, ngày nhận/trả phòng, số khách).
  - Giới thiệu tổng quan khách sạn, các hạng phòng nổi bật, dịch vụ & tiện ích, ưu đãi hot và đánh giá từ khách hàng.
- **Danh sách phòng & Tìm kiếm (`/rooms`)**:
  - Lọc theo khách sạn, khoảng ngày, số khách, khoảng giá, loại giường.
  - Kiểm tra số phòng trống thực tế theo thời gian thực (real-time availability) dựa trên bảng `rooms` và `bookings`.
  - Hiển thị thông số diện tích, tiện nghi, loại giường, trạng thái phòng, nút xem chi tiết và đặt phòng ngay.
- **Chi tiết phòng (`/room-detail?id=...`)**:
  - Thư viện hình ảnh chất lượng cao.
  - Thông số chi tiết: diện tích, hướng nhìn, tiện nghi phòng (`amenities`), quy định nhận/trả phòng và đánh giá của khách lưu trú.
  - Widget tính giá động linh hoạt theo số đêm và kiểm tra phòng trống tức thì.
  - Nút thêm vào danh sách yêu thích (Wishlist).
- **Quy trình Đặt phòng & Thanh toán (`/booking` & `/booking/checkout`)**:
  - **Bước 1**: Chọn phòng, ngày nhận/trả phòng, số lượng khách (tự động tính số đêm và tiền phòng).
  - **Bước 2**: Chọn dịch vụ gia tăng (`services`: Buffet sáng, Đưa đón sân bay, Spa thư giãn, Giặt là) với số lượng tùy chọn.
  - **Bước 3**: Áp dụng mã ưu đãi (`promotions`, VD: `WELCOME10`), hệ thống kiểm tra điều kiện và chiết khấu trực tiếp vào tổng tiền.
  - **Bước 4**: Nhập thông tin người đặt và ghi chú đặc biệt (tự động điền nếu đã đăng nhập).
  - **Bước 5**: Lựa chọn phương thức thanh toán:
    - *Thanh toán trực tuyến (`online`)*: Mô phỏng chuyển khoản QR/thẻ, tự động xác nhận đơn và thanh toán thành công.
    - *Thanh toán tại quầy (`pay_at_hotel`)*: Chờ khách đến khách sạn nhận phòng và thanh toán tại quầy lễ tân (Admin duyệt xác nhận thu tiền).
  - Tự động gán phòng trống thực tế từ bảng `rooms`, chống đặt trùng phòng.
- **Xác nhận đặt phòng thành công (`/booking/success?code=...`)**:
  - Biên lai / Vé xác nhận đặt phòng đầy đủ thông tin chi tiết.
  - Hỗ trợ in vé / lưu phiếu xác nhận, tra cứu đơn hoặc quay về trang chủ.
- **Tra cứu đơn đặt phòng (`/booking/lookup`)**:
  - Tra cứu bằng Mã đặt phòng (`booking_code`) và Email hoặc SĐT.
  - Xem chi tiết trạng thái đơn, dịch vụ và lịch sử thanh toán.
  - Hỗ trợ khách hàng gửi yêu cầu hủy đơn nếu còn trước ngày nhận phòng.
- **Tài khoản khách hàng (`/account`, `/register`, `/login`)**:
  - Đăng ký tài khoản khách hàng mới với mật khẩu mã hóa an toàn (`password_hash`).
  - Đăng nhập phân quyền thông minh: Khách hàng chuyển hướng đến `/account` hoặc trang trước đó; Admin/Staff tự động vào `/admin`.
  - Quản lý thông tin cá nhân, đổi mật khẩu.
  - Quản lý lịch sử đặt phòng (danh sách đơn, trạng thái, hủy đơn).
  - Danh sách phòng yêu thích (`wishlist`).
- **Dịch vụ (`/services`) & Ưu đãi (`/promotions`)**:
  - Danh sách chi tiết các dịch vụ cao cấp và mã voucher khuyến mãi kèm nút sử dụng ngay.
- **Giới thiệu (`/about`) & Liên hệ (`/contact`)**:
  - Thông tin khách sạn, kiến trúc, chính sách lưu trú.
  - Biểu mẫu liên hệ gửi tin nhắn lưu trực tiếp vào hệ thống chat/hỗ trợ.


---

## Hoàn thiện STT 33–40 (v1.1 — 26/11/2026)

### STT 33 — Xử lý & kiểm tra dữ liệu Form liên hệ ✅
- **Server-side**: validate họ tên ≥2 ký tự, email hợp lệ, nội dung ≥10 ký tự, số điện thoại tùy chọn; lưu vào `chat_conversations` + `chat_messages` trong DB.
- **Client-side**: JS real-time validation blur-event, disable nút submit khi đang gửi.
- Flash message riêng: `contact_success` / `contact_error` không xung đột hệ thống flash chung.

### STT 34 — Khu vực thông tin hỗ trợ ✅ (đã có từ trước)
- Email hỗ trợ, hotline 24/7, link tra cứu đơn ngay trên trang Liên hệ.

### STT 35 — Địa chỉ & thông tin liên hệ + Google Maps ✅
- Nhúng **Google Maps iframe** (54 Liễu Giai, Ba Đình, Hà Nội).
- 3 card info: Địa chỉ · Phương tiện · Giờ Check-in/out.
- Thêm mạng xã hội Facebook/Instagram.

### STT 36 — Responsive toàn website ✅
- Thêm **Mobile Nav Drawer** (`#mobileNavDrawer`) với overlay + nút ✕ + nav links đầy đủ + nút đăng nhập/đặt phòng.
- CSS bổ sung: contact layout, map info grid, name/phone grid stack 1 cột trên mobile.
- Ẩn user dropdown và btn-dark trên mobile, hiện hamburger menu.

### STT 37 — Kiểm thử & hoàn thiện JavaScript ✅
- Mobile nav: open/close qua overlay + nút ✕ + click link.
- User dropdown desktop: toggle + đóng khi click ngoài.
- Wishlist AJAX: toast thay `alert()`, redirect có delay.
- Gallery thumbnails: active border khi chọn ảnh.
- Contact form: char counter textarea, real-time blur validation.
- Toast: hỗ trợ type `success`/`error`, clear timer khi gọi liên tiếp.
- Auto-dismiss flash alerts sau 5 giây, smooth scroll anchor links.

### STT 38 — Kiểm thử quy trình tìm kiếm và đặt phòng ✅
- Booking calculator: tự động sửa ngày check-out khi check-in thay đổi.
- Voucher AJAX: uppercase input, thông báo lỗi kết nối riêng.
- Flow đầy đủ: Tìm phòng → Chi tiết → Đặt phòng → Xác nhận → Tra cứu.

### STT 39 — Tích hợp ✅
- Tất cả Views, Controllers, Models, CSS, JS đồng bộ nhất quán.
- Không có xung đột key session giữa contact form và hệ thống flash chung.

### STT 40 — QA & hoàn thiện phiên bản cuối ✅
- Kiểm tra logic các file đã sửa (HomeController, contact.php, client.js, client.css, layouts/client.php).
- README cập nhật đầy đủ.
- Commit lên `main` với commit message rõ ràng.

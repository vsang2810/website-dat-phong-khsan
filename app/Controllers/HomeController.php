<?php
declare(strict_types=1);

final class HomeController extends Controller
{
    private RoomType $roomTypeModel;
    private Hotel $hotelModel;
    private Service $serviceModel;
    private Promotion $promotionModel;
    private Review $reviewModel;

    public function __construct()
    {
        $this->roomTypeModel = new RoomType();
        $this->hotelModel = new Hotel();
        $this->serviceModel = new Service();
        $this->promotionModel = new Promotion();
        $this->reviewModel = new Review();
    }

    public function index(): void
    {
        $featuredRooms = $this->roomTypeModel->getFeatured(4);
        $hotels = $this->hotelModel->all();
        $mainHotel = !empty($hotels) ? $hotels[0] : null;
        $services = $this->serviceModel->allActive();
        $promotions = $this->promotionModel->allActive();
        $reviews = $this->reviewModel->getFeaturedReviews(4);

        $this->view('client/home', [
            'title' => 'Khách sạn 5 sao sang trọng tại Hà Nội',
            'hotel' => $mainHotel,
            'featuredRooms' => $featuredRooms,
            'hotels' => $hotels,
            'services' => $services,
            'promotions' => $promotions,
            'reviews' => $reviews,
            'activeNav' => 'home',
        ], 'client');
    }

    public function about(): void
    {
        $hotels = $this->hotelModel->all();
        $mainHotel = !empty($hotels) ? $hotels[0] : null;

        $this->view('client/about', [
            'title' => 'Giới thiệu về LOTTE HOTEL HANOI',
            'hotel' => $mainHotel,
            'activeNav' => 'about',
        ], 'client');
    }

    public function services(): void
    {
        $services = $this->serviceModel->allActive();

        $this->view('client/services', [
            'title' => 'Dịch vụ & Tiện ích đặc quyền',
            'services' => $services,
            'activeNav' => 'services',
        ], 'client');
    }

    public function promotions(): void
    {
        $promotions = $this->promotionModel->allActive();

        $this->view('client/promotions', [
            'title' => 'Ưu đãi & Khuyến mãi đặc biệt',
            'promotions' => $promotions,
            'activeNav' => 'promotions',
        ], 'client');
    }

    public function contact(): void
    {
        $hotels = $this->hotelModel->all();
        $mainHotel = !empty($hotels) ? $hotels[0] : null;

        // Lấy flash message từ session để hiển thị trong view (STT 33)
        $contactSuccess = $_SESSION['contact_success'] ?? null;
        $contactError   = $_SESSION['contact_error'] ?? null;
        unset($_SESSION['contact_success'], $_SESSION['contact_error']);

        $this->view('client/contact', [
            'title'          => 'Liên hệ & Hỗ trợ khách hàng',
            'hotel'          => $mainHotel,
            'activeNav'      => 'contact',
            'contactSuccess' => $contactSuccess,
            'contactError'   => $contactError,
        ], 'client');
    }

    public function submitContact(): void
    {
        // Xác minh CSRF (STT 33)
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['contact_error'] = 'Phiên làm việc không hợp lệ. Vui lòng thử lại.';
            $this->redirect('/contact');
            return;
        }

        $name    = trim((string)($_POST['name']    ?? ''));
        $email   = trim((string)($_POST['email']   ?? ''));
        $phone   = trim((string)($_POST['phone']   ?? ''));
        $subject = trim((string)($_POST['subject'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));

        // Validate server-side đầy đủ (STT 33)
        $errors = [];
        if (mb_strlen($name) < 2) {
            $errors[] = 'Họ và tên phải có ít nhất 2 ký tự.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Địa chỉ email không hợp lệ.';
        }
        if (mb_strlen($message) < 10) {
            $errors[] = 'Nội dung tin nhắn phải có ít nhất 10 ký tự.';
        }
        if (mb_strlen($message) > 2000) {
            $errors[] = 'Nội dung tin nhắn không được vượt quá 2000 ký tự.';
        }
        if ($phone !== '' && !preg_match('/^[0-9+\-\s\(\)]{7,20}$/', $phone)) {
            $errors[] = 'Số điện thoại không hợp lệ.';
        }

        if (!empty($errors)) {
            $_SESSION['contact_error'] = implode(' ', $errors);
            $this->redirect('/contact');
            return;
        }

        try {
            $chatModel = new Chat();
            $userId    = Auth::id();
            // Gộp subject vào message nếu có (STT 33 – lưu DB)
            $fullMessage = $subject !== '' ? "[{$subject}] {$message}" : $message;
            $chatModel->createInquiry($name, $email, $fullMessage, $userId);

            $_SESSION['contact_success'] = 'Cảm ơn quý khách đã liên hệ! Đội ngũ Chăm sóc Khách hàng Lotte Hotel sẽ phản hồi qua email ' . htmlspecialchars($email) . ' trong vòng 24 giờ.';
        } catch (Throwable $e) {
            error_log('Contact submit error: ' . $e->getMessage());
            $_SESSION['contact_error'] = 'Không thể gửi tin nhắn lúc này. Vui lòng thử lại hoặc gọi hotline 024 3333 1000.';
        }

        $this->redirect('/contact');
    }
}

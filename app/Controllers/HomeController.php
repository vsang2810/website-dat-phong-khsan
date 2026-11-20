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

        $this->view('client/contact', [
            'title' => 'Liên hệ & Hỗ trợ khách hàng',
            'hotel' => $mainHotel,
            'activeNav' => 'contact',
        ], 'client');
    }

    public function submitContact(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ. Vui lòng thử lại.';
            $this->redirect('/contact');
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
            $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ họ tên, email hợp lệ và nội dung tin nhắn.';
            $this->redirect('/contact');
        }

        try {
            $chatModel = new Chat();
            $userId = Auth::id();
            $chatModel->createInquiry($name, $email, $message, $userId);

            $_SESSION['flash_success'] = 'Cảm ơn quý khách đã liên hệ! Đội ngũ Chăm sóc Khách hàng Lotte Hotel sẽ phản hồi trong thời gian sớm nhất.';
        } catch (Throwable $e) {
            error_log('Contact error: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Không thể gửi tin nhắn lúc này. Vui lòng thử lại hoặc gọi hotline 024 3333 1000.';
        }

        $this->redirect('/contact');
    }
}

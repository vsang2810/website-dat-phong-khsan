<?php
declare(strict_types=1);

final class ClientBookingController extends Controller
{
    private Booking $bookingModel;
    private RoomType $roomTypeModel;
    private Service $serviceModel;
    private Promotion $promotionModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
        $this->roomTypeModel = new RoomType();
        $this->serviceModel = new Service();
        $this->promotionModel = new Promotion();
    }

    public function index(): void
    {
        $roomTypeId = (int)($_GET['room_type_id'] ?? 0);
        if ($roomTypeId <= 0) {
            $this->redirect('/rooms');
        }

        $room = $this->roomTypeModel->findWithDetails($roomTypeId);
        if (!$room) {
            $this->redirect('/rooms');
        }

        $checkIn = trim((string)($_GET['check_in'] ?? date('Y-m-d', strtotime('+1 day'))));
        $checkOut = trim((string)($_GET['check_out'] ?? date('Y-m-d', strtotime('+2 days'))));
        $guests = max(1, (int)($_GET['guests'] ?? 2));

        if (strtotime($checkOut) <= strtotime($checkIn)) {
            $checkOut = date('Y-m-d', strtotime($checkIn . ' +1 day'));
        }

        $inDate = new DateTimeImmutable($checkIn);
        $outDate = new DateTimeImmutable($checkOut);
        $diff = $inDate->diff($outDate);
        $nights = max(1, (int)$diff->days);

        $availableCount = $this->roomTypeModel->checkAvailableRoomsCount($roomTypeId, $checkIn, $checkOut);
        $services = $this->serviceModel->allActive();
        $promotions = $this->promotionModel->allActive();

        $currentUser = Auth::user();

        $this->view('client/booking/index', [
            'title' => 'Đặt phòng - ' . $room['name'],
            'room' => $room,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guests' => $guests,
            'nights' => $nights,
            'availableCount' => $availableCount,
            'services' => $services,
            'promotions' => $promotions,
            'currentUser' => $currentUser,
            'activeNav' => 'rooms',
        ], 'client');
    }

    public function store(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc đã hết hạn. Vui lòng thử lại.';
            $this->redirect('/rooms');
        }

        $roomTypeId = (int)($_POST['room_type_id'] ?? 0);
        $checkIn = trim((string)($_POST['check_in'] ?? ''));
        $checkOut = trim((string)($_POST['check_out'] ?? ''));
        $totalGuests = max(1, (int)($_POST['total_guests'] ?? 1));
        $guestName = trim((string)($_POST['guest_name'] ?? ''));
        $guestEmail = trim((string)($_POST['guest_email'] ?? ''));
        $guestPhone = trim((string)($_POST['guest_phone'] ?? ''));
        $specialRequest = trim((string)($_POST['special_request'] ?? ''));
        $paymentMethod = trim((string)($_POST['payment_method'] ?? 'pay_at_hotel'));
        $promotionCode = trim((string)($_POST['promotion_code'] ?? ''));

        // Validate
        if ($roomTypeId <= 0 || empty($checkIn) || empty($checkOut)) {
            $_SESSION['flash_error'] = 'Thông tin thời gian hoặc loại phòng không hợp lệ.';
            $this->redirect('/rooms');
        }

        if (strtotime($checkOut) <= strtotime($checkIn)) {
            $_SESSION['flash_error'] = 'Ngày trả phòng phải sau ngày nhận phòng ít nhất 1 đêm.';
            $this->redirect('/booking?room_type_id=' . $roomTypeId . '&check_in=' . urlencode($checkIn) . '&check_out=' . urlencode($checkOut));
        }

        if ($guestName === '' || !filter_var($guestEmail, FILTER_VALIDATE_EMAIL) || $guestPhone === '') {
            $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ họ tên, email hợp lệ và số điện thoại liên hệ.';
            $this->redirect('/booking?room_type_id=' . $roomTypeId . '&check_in=' . urlencode($checkIn) . '&check_out=' . urlencode($checkOut));
        }

        // Parse services selected
        $services = [];
        if (!empty($_POST['services']) && is_array($_POST['services'])) {
            foreach ($_POST['services'] as $srvId => $val) {
                if ($val) {
                    $qty = max(1, (int)($_POST['service_qty'][$srvId] ?? 1));
                    $services[(int)$srvId] = $qty;
                }
            }
        }

        $userId = Auth::id();

        try {
            $result = $this->bookingModel->createCustomerBooking([
                'room_type_id' => $roomTypeId,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_guests' => $totalGuests,
                'guest_name' => $guestName,
                'guest_email' => $guestEmail,
                'guest_phone' => $guestPhone,
                'special_request' => $specialRequest,
                'payment_method' => $paymentMethod,
                'promotion_code' => $promotionCode,
                'services' => $services,
                'user_id' => $userId,
            ]);

            $_SESSION['flash_success'] = 'Đặt phòng thành công! Mã đơn của bạn là: ' . $result['booking_code'];
            $this->redirect('/booking/success?code=' . urlencode($result['booking_code']));
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            $this->redirect('/booking?room_type_id=' . $roomTypeId . '&check_in=' . urlencode($checkIn) . '&check_out=' . urlencode($checkOut));
        }
    }

    public function success(): void
    {
        $code = trim((string)($_GET['code'] ?? ''));
        if ($code === '') {
            $this->redirect('/');
        }

        $booking = $this->bookingModel->findByCode($code);
        if (!$booking) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Không tìm thấy đơn đặt phòng'], 'client');
            return;
        }

        $this->view('client/booking/success', [
            'title' => 'Xác nhận đặt phòng thành công - ' . $booking['booking_code'],
            'booking' => $booking,
            'activeNav' => 'lookup',
        ], 'client');
    }

    public function lookup(): void
    {
        $code = trim((string)($_GET['code'] ?? ''));
        $contact = trim((string)($_GET['contact'] ?? ''));
        $booking = null;
        $searched = false;

        if ($code !== '' && $contact !== '') {
            $searched = true;
            $booking = $this->bookingModel->findByCodeAndContact($code, $contact);
            if (!$booking) {
                $_SESSION['flash_error'] = 'Không tìm thấy đơn đặt phòng với thông tin đã cung cấp. Vui lòng kiểm tra lại mã đặt phòng và Email/SĐT.';
            }
        }

        $this->view('client/booking/lookup', [
            'title' => 'Tra cứu đơn đặt phòng',
            'code' => $code,
            'contact' => $contact,
            'booking' => $booking,
            'searched' => $searched,
            'activeNav' => 'lookup',
        ], 'client');
    }

    public function cancel(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/account');
        }

        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $reason = trim((string)($_POST['reason'] ?? ''));
        $redirectUrl = trim((string)($_POST['redirect'] ?? '/account'));

        if (!Auth::check()) {
            // Cancel via lookup with code and contact verification
            $code = trim((string)($_POST['code'] ?? ''));
            $contact = trim((string)($_POST['contact'] ?? ''));
            $booking = $this->bookingModel->findByCodeAndContact($code, $contact);
            if (!$booking || (int)$booking['id'] !== $bookingId) {
                $_SESSION['flash_error'] = 'Xác thực đơn đặt phòng không hợp lệ.';
                $this->redirect('/booking/lookup');
            }

            try {
                $this->bookingModel->cancelByCustomer($bookingId, (int)$booking['user_id'], $reason);
                $_SESSION['flash_success'] = 'Hủy đơn đặt phòng thành công.';
            } catch (Throwable $e) {
                $_SESSION['flash_error'] = $e->getMessage();
            }
            $this->redirect('/booking/lookup?code=' . urlencode($code) . '&contact=' . urlencode($contact));
        }

        $userId = (int)Auth::id();
        try {
            $this->bookingModel->cancelByCustomer($bookingId, $userId, $reason);
            $_SESSION['flash_success'] = 'Hủy đơn đặt phòng thành công.';
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        $this->redirect($redirectUrl);
    }

    public function checkPromotion(): void
    {
        $code = trim((string)($_POST['code'] ?? ''));
        $subtotal = (float)($_POST['subtotal'] ?? 0);

        if ($code === '' || $subtotal <= 0) {
            $this->json(['valid' => false, 'discount' => 0, 'message' => 'Vui lòng cung cấp mã hợp lệ và đơn giá.']);
        }

        $promo = $this->promotionModel->findByCode($code);
        if (!$promo) {
            $this->json(['valid' => false, 'discount' => 0, 'message' => 'Mã ưu đãi không tồn tại hoặc đã hết hạn.']);
        }

        $calc = $this->promotionModel->calculateDiscount($promo, $subtotal);
        $this->json($calc);
    }
}

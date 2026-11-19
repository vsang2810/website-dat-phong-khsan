<?php
declare(strict_types=1);

final class ClientAuthController extends Controller
{
    private Customer $customerModel;
    private Booking $bookingModel;
    private Wishlist $wishlistModel;

    public function __construct()
    {
        $this->customerModel = new Customer();
        $this->bookingModel = new Booking();
        $this->wishlistModel = new Wishlist();
    }

    public function showLogin(): void
    {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                $this->redirect('/admin');
            } else {
                $this->redirect('/account');
            }
        }

        $this->view('client/auth/login', [
            'title' => 'Đăng nhập tài khoản - LOTTE HOTEL HANOI',
            'activeNav' => 'auth',
        ], 'client');
    }

    public function login(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ. Vui lòng thử lại.';
            $this->redirect('/login');
        }

        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            $_SESSION['flash_error'] = 'Vui lòng nhập đầy đủ email và mật khẩu.';
            $this->redirect('/login');
        }

        try {
            if (Auth::attempt($email, $password)) {
                $user = Auth::user();
                if ($user['role'] === 'admin' || $user['role'] === 'staff') {
                    $this->redirect('/admin');
                }

                $intended = $_SESSION['intended_url'] ?? '/account';
                unset($_SESSION['intended_url']);
                $_SESSION['flash_success'] = 'Chào mừng ' . htmlspecialchars($user['full_name']) . ' đã quay trở lại!';
                $this->redirect($intended);
            }
        } catch (Throwable $e) {
            error_log('Auth error: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Lỗi kết nối hệ thống. Vui lòng thử lại.';
            $this->redirect('/login');
        }

        $_SESSION['flash_error'] = 'Địa chỉ email hoặc mật khẩu không chính xác.';
        $this->redirect('/login');
    }

    public function showRegister(): void
    {
        if (Auth::check()) {
            $this->redirect('/account');
        }

        $this->view('client/auth/register', [
            'title' => 'Đăng ký tài khoản - LOTTE HOTEL HANOI',
            'activeNav' => 'auth',
        ], 'client');
    }

    public function register(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ. Vui lòng thử lại.';
            $this->redirect('/register');
        }

        $fullName = trim((string)($_POST['full_name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $passwordConfirm = (string)($_POST['password_confirmation'] ?? '');
        $gender = trim((string)($_POST['gender'] ?? 'other'));

        if ($fullName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            $_SESSION['flash_error'] = 'Vui lòng điền họ tên, email hợp lệ và mật khẩu tối thiểu 6 ký tự.';
            $this->redirect('/register');
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['flash_error'] = 'Xác nhận mật khẩu không khớp.';
            $this->redirect('/register');
        }

        try {
            $userId = $this->customerModel->register($fullName, $email, $phone, $password, $gender);
            $user = $this->customerModel->find($userId);

            Auth::loginUser($user);
            $_SESSION['flash_success'] = 'Đăng ký tài khoản thành công! Chào mừng quý khách đến với LOTTE HOTEL.';
            $this->redirect('/account');
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            $this->redirect('/register');
        }
    }

    public function account(): void
    {
        Auth::requireCustomer();
        $user = Auth::user();
        $customer = $this->customerModel->find((int)$user['id']);
        $bookings = $this->bookingModel->getByUser((int)$user['id']);
        $wishlist = $this->wishlistModel->getByUser((int)$user['id']);

        $this->view('client/account/index', [
            'title' => 'Tài khoản của tôi - ' . $user['full_name'],
            'customer' => $customer,
            'bookings' => $bookings,
            'wishlist' => $wishlist,
            'activeTab' => $_GET['tab'] ?? 'bookings',
            'activeNav' => 'account',
        ], 'client');
    }

    public function updateProfile(): void
    {
        Auth::requireCustomer();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/account?tab=profile');
        }

        $userId = (int)Auth::id();
        $fullName = trim((string)($_POST['full_name'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $gender = trim((string)($_POST['gender'] ?? 'other'));

        if ($fullName === '') {
            $_SESSION['flash_error'] = 'Vui lòng nhập họ và tên.';
            $this->redirect('/account?tab=profile');
        }

        try {
            $this->customerModel->updateCustomerProfile($userId, $fullName, $phone, $gender);
            $_SESSION['user']['full_name'] = $fullName;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['gender'] = $gender;
            $_SESSION['flash_success'] = 'Cập nhật thông tin cá nhân thành công.';
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        $this->redirect('/account?tab=profile');
    }

    public function updatePassword(): void
    {
        Auth::requireCustomer();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/account?tab=password');
        }

        $userId = (int)Auth::id();
        $currentPassword = (string)($_POST['current_password'] ?? '');
        $newPassword = (string)($_POST['new_password'] ?? '');
        $newPasswordConfirm = (string)($_POST['new_password_confirmation'] ?? '');

        if (strlen($newPassword) < 6) {
            $_SESSION['flash_error'] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
            $this->redirect('/account?tab=password');
        }

        if ($newPassword !== $newPasswordConfirm) {
            $_SESSION['flash_error'] = 'Xác nhận mật khẩu mới không khớp.';
            $this->redirect('/account?tab=password');
        }

        try {
            $this->customerModel->updatePassword($userId, $currentPassword, $newPassword);
            $_SESSION['flash_success'] = 'Đổi mật khẩu thành công.';
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        $this->redirect('/account?tab=password');
    }

    public function myBookings(): void
    {
        Auth::requireCustomer();
        $this->redirect('/account?tab=bookings');
    }

    public function wishlist(): void
    {
        Auth::requireCustomer();
        $this->redirect('/account?tab=wishlist');
    }

    public function logout(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('CSRF token không hợp lệ.');
        }

        Auth::logout();
        $_SESSION['flash_success'] = 'Đã đăng xuất tài khoản thành công.';
        $this->redirect('/');
    }
}

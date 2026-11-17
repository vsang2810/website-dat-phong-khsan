<?php
declare(strict_types=1);

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/admin');
        }

        $this->view('auth/login', ['title' => 'Đăng nhập quản trị'], 'auth');
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
            $_SESSION['flash_error'] = 'Vui lòng nhập email và mật khẩu hợp lệ.';
            $this->redirect('/login');
        }

        try {
            if (Auth::attempt($email, $password)) {
                $this->redirect('/admin');
            }
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $_SESSION['flash_error'] = 'Không thể kết nối cơ sở dữ liệu. Hãy kiểm tra cấu hình và import SQL.';
            $this->redirect('/login');
        }

        $_SESSION['flash_error'] = 'Thông tin đăng nhập không chính xác hoặc tài khoản không có quyền quản trị.';
        $this->redirect('/login');
    }

    public function logout(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('CSRF token không hợp lệ.');
        }

        Auth::logout();
        $this->redirect('/login');
    }
}

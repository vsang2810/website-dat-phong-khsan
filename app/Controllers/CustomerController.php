<?php
declare(strict_types=1);

final class CustomerController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();
        $filters = [
            'q' => trim((string)($_GET['q'] ?? '')),
            'status' => (string)($_GET['status'] ?? ''),
        ];
        if (!in_array($filters['status'], ['', 'active', 'inactive', 'blocked'], true)) $filters['status'] = '';
        $this->view('admin/customers/index', [
            'title' => 'Quản lý khách hàng',
            'activeMenu' => 'customers',
            'filters' => $filters,
            'result' => (new Customer())->paginate($filters, max(1, (int)($_GET['page'] ?? 1))),
        ]);
    }

    public function show(): void
    {
        Auth::requireAdmin();
        $customer = (new Customer())->find(max(0, (int)($_GET['id'] ?? 0)));
        if (!$customer) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Không tìm thấy khách hàng']);
            return;
        }
        $this->view('admin/customers/show', [
            'title' => 'Chi tiết khách hàng',
            'activeMenu' => 'customers',
            'customer' => $customer,
        ]);
    }

    public function edit(): void
    {
        Auth::requireAdmin();
        $customer = (new Customer())->find(max(0, (int)($_GET['id'] ?? 0)));
        if (!$customer) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Không tìm thấy khách hàng']);
            return;
        }
        $this->view('admin/customers/form', [
            'title' => 'Cập nhật khách hàng',
            'activeMenu' => 'customers',
            'customer' => $customer,
        ]);
    }

    public function update(): void
    {
        Auth::requireAdmin();
        $id = max(0, (int)($_POST['id'] ?? 0));
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/admin/customers');
        }

        $data = [
            'full_name' => trim((string)($_POST['full_name'] ?? '')),
            'email' => strtolower(trim((string)($_POST['email'] ?? ''))),
            'phone' => trim((string)($_POST['phone'] ?? '')),
            'gender' => (string)($_POST['gender'] ?? 'other'),
        ];
        if ($data['full_name'] === '' || mb_strlen($data['full_name']) > 150
            || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
            || mb_strlen($data['email']) > 150
            || ($data['phone'] !== '' && !preg_match('/^[0-9+\s().-]{8,20}$/', $data['phone']))
            || !in_array($data['gender'], ['male','female','other'], true)) {
            $_SESSION['flash_error'] = 'Vui lòng kiểm tra họ tên, email, số điện thoại và giới tính.';
            $this->redirect('/admin/customers/edit?id=' . $id);
        }

        try {
            (new Customer())->updateProfile($id, $data);
            $_SESSION['flash_success'] = 'Đã cập nhật thông tin khách hàng.';
            $this->redirect('/admin/customers/show?id=' . $id);
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = $e instanceof RuntimeException ? $e->getMessage() : 'Không thể cập nhật khách hàng.';
            if (!$e instanceof RuntimeException) error_log($e->getMessage());
            $this->redirect('/admin/customers/edit?id=' . $id);
        }
    }

    public function status(): void
    {
        Auth::requireAdmin();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/admin/customers');
        }
        $id = max(0, (int)($_POST['id'] ?? 0));
        try {
            (new Customer())->setStatus($id, (string)($_POST['status'] ?? ''));
            $_SESSION['flash_success'] = 'Đã cập nhật trạng thái tài khoản khách hàng.';
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = $e instanceof RuntimeException ? $e->getMessage() : 'Không thể cập nhật trạng thái khách hàng.';
            if (!$e instanceof RuntimeException) error_log($e->getMessage());
        }
        $this->redirect('/admin/customers');
    }
}

<?php
declare(strict_types=1);

final class HotelController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();
        $q = trim((string)($_GET['q'] ?? ''));
        $this->view('admin/hotels/index', [
            'title' => 'Quản lý khách sạn',
            'activeMenu' => 'hotels',
            'hotels' => (new Hotel())->all($q),
            'q' => $q,
        ]);
    }

    public function create(): void
    {
        Auth::requireAdmin();
        $this->view('admin/hotels/form', [
            'title' => 'Thêm khách sạn',
            'activeMenu' => 'hotels',
            'hotel' => null,
            'formAction' => BASE_URL . '/admin/hotels/store',
        ]);
    }

    public function edit(): void
    {
        Auth::requireAdmin();
        $hotel = (new Hotel())->find(max(0, (int)($_GET['id'] ?? 0)));
        if (!$hotel) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Không tìm thấy khách sạn']);
            return;
        }
        $this->view('admin/hotels/form', [
            'title' => 'Cập nhật khách sạn',
            'activeMenu' => 'hotels',
            'hotel' => $hotel,
            'formAction' => BASE_URL . '/admin/hotels/update',
        ]);
    }

    public function store(): void { $this->persist(null); }
    public function update(): void { $this->persist(max(0, (int)($_POST['id'] ?? 0))); }

    private function persist(?int $id): void
    {
        Auth::requireAdmin();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ. Vui lòng thử lại.';
            $this->redirect('/admin/hotels');
        }

        $data = [
            'name' => trim((string)($_POST['name'] ?? '')),
            'address' => trim((string)($_POST['address'] ?? '')),
            'city' => trim((string)($_POST['city'] ?? '')),
            'country' => trim((string)($_POST['country'] ?? 'Vietnam')),
            'description' => trim((string)($_POST['description'] ?? '')),
            'phone' => trim((string)($_POST['phone'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'stars' => (int)($_POST['star_rating'] ?? 5),
            'thumbnail' => trim((string)($_POST['thumbnail'] ?? '')),
            'check_in' => (string)($_POST['check_in_time'] ?? '14:00'),
            'check_out' => (string)($_POST['check_out_time'] ?? '12:00'),
            'status' => in_array($_POST['status'] ?? '', ['active', 'inactive'], true) ? $_POST['status'] : 'active',
        ];

        if ($data['name'] === '' || $data['address'] === '') {
            $_SESSION['flash_error'] = 'Tên khách sạn và địa chỉ là bắt buộc.';
            $this->redirect($id ? '/admin/hotels/edit?id=' . $id : '/admin/hotels/create');
        }
        if ($data['stars'] < 1 || $data['stars'] > 5) {
            $_SESSION['flash_error'] = 'Xếp hạng sao phải từ 1 đến 5.';
            $this->redirect($id ? '/admin/hotels/edit?id=' . $id : '/admin/hotels/create');
        }
        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Email khách sạn không hợp lệ.';
            $this->redirect($id ? '/admin/hotels/edit?id=' . $id : '/admin/hotels/create');
        }
        try {
            (new Hotel())->save($data, $id);
            $_SESSION['flash_success'] = $id ? 'Đã cập nhật khách sạn.' : 'Đã thêm khách sạn.';
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash_error'] = 'Không thể lưu khách sạn. Vui lòng kiểm tra dữ liệu.';
        }
        $this->redirect('/admin/hotels');
    }

    public function toggle(): void
    {
        Auth::requireAdmin();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/admin/hotels');
        }
        (new Hotel())->toggleStatus(max(0, (int)($_POST['id'] ?? 0)));
        $_SESSION['flash_success'] = 'Đã cập nhật trạng thái khách sạn.';
        $this->redirect('/admin/hotels');
    }
}

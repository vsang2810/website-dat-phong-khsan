<?php
declare(strict_types=1);

final class RoomTypeController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();
        $q = trim((string)($_GET['q'] ?? ''));
        $hotelId = trim((string)($_GET['hotel_id'] ?? ''));
        $this->view('admin/room-types/index', [
            'title' => 'Quản lý loại phòng',
            'activeMenu' => 'room-types',
            'roomTypes' => (new RoomType())->all($q, $hotelId),
            'hotels' => (new Hotel())->all(),
            'q' => $q,
            'hotelId' => $hotelId,
        ]);
    }

    public function create(): void
    {
        Auth::requireAdmin();
        $hotels = (new Hotel())->all();
        $this->view('admin/room-types/form', [
            'title' => 'Thêm loại phòng',
            'activeMenu' => 'room-types',
            'roomType' => null,
            'hotels' => $hotels,
            'formAction' => BASE_URL . '/admin/room-types/store',
        ]);
    }

    public function edit(): void
    {
        Auth::requireAdmin();
        $roomType = (new RoomType())->find(max(0, (int)($_GET['id'] ?? 0)));
        if (!$roomType) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Không tìm thấy loại phòng']);
            return;
        }
        $this->view('admin/room-types/form', [
            'title' => 'Cập nhật loại phòng',
            'activeMenu' => 'room-types',
            'roomType' => $roomType,
            'hotels' => (new Hotel())->all(),
            'formAction' => BASE_URL . '/admin/room-types/update',
        ]);
    }

    public function store(): void { $this->persist(null); }
    public function update(): void { $this->persist(max(0, (int)($_POST['id'] ?? 0))); }

    private function persist(?int $id): void
    {
        Auth::requireAdmin();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/admin/room-types');
        }
        $data = [
            'hotel_id' => (int)($_POST['hotel_id'] ?? 0),
            'name' => trim((string)($_POST['name'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
            'area' => trim((string)($_POST['area'] ?? '')) === '' ? null : (float)$_POST['area'],
            'max_guests' => (int)($_POST['max_guests'] ?? 2),
            'bed_type' => trim((string)($_POST['bed_type'] ?? '')),
            'base_price' => (float)($_POST['base_price'] ?? 0),
            'thumbnail' => trim((string)($_POST['thumbnail'] ?? '')),
            'status' => in_array($_POST['status'] ?? '', ['active', 'inactive'], true) ? $_POST['status'] : 'active',
        ];

        $hotel = (new Hotel())->find($data['hotel_id']);
        if (!$hotel || $data['name'] === '' || $data['base_price'] <= 0
            || $data['max_guests'] < 1 || $data['max_guests'] > 20
            || ($data['area'] !== null && $data['area'] <= 0)) {
            $_SESSION['flash_error'] = 'Vui lòng chọn khách sạn và nhập tên, diện tích, sức chứa, giá hợp lệ.';
            $this->redirect($id ? '/admin/room-types/edit?id=' . $id : '/admin/room-types/create');
        }

        try {
            (new RoomType())->save($data, $id);
            $_SESSION['flash_success'] = $id ? 'Đã cập nhật loại phòng.' : 'Đã thêm loại phòng.';
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash_error'] = 'Không thể lưu loại phòng. Vui lòng kiểm tra dữ liệu.';
        }
        $this->redirect('/admin/room-types');
    }

    public function toggle(): void
    {
        Auth::requireAdmin();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/admin/room-types');
        }
        (new RoomType())->toggleStatus(max(0, (int)($_POST['id'] ?? 0)));
        $_SESSION['flash_success'] = 'Đã cập nhật trạng thái loại phòng.';
        $this->redirect('/admin/room-types');
    }
}

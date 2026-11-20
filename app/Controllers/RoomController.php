<?php
declare(strict_types=1);

final class RoomController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();
        $filters = [
            'q' => trim((string)($_GET['q'] ?? '')),
            'hotel_id' => trim((string)($_GET['hotel_id'] ?? '')),
            'room_type_id' => trim((string)($_GET['room_type_id'] ?? '')),
            'base_status' => (string)($_GET['status'] ?? ''),
        ];
        if (!in_array($filters['base_status'], ['', 'available', 'maintenance', 'inactive'], true)) {
            $filters['base_status'] = '';
        }
        $this->view('admin/rooms/index', [
            'title' => 'Quản lý phòng thực tế',
            'activeMenu' => 'rooms',
            'rooms' => (new Room())->all($filters),
            'roomTypes' => (new Room())->roomTypes(),
            'hotels' => (new Hotel())->all(),
            'filters' => $filters,
        ]);
    }

    public function create(): void
    {
        Auth::requireAdmin();
        $types = (new Room())->roomTypes();
        if (!$types) {
            $_SESSION['flash_error'] = 'Bạn cần tạo hạng phòng trước khi thêm phòng thực tế.';
            $this->redirect('/admin/room-types');
        }
        $this->view('admin/rooms/form', [
            'title' => 'Thêm phòng thực tế', 'activeMenu' => 'rooms',
            'room' => null, 'roomTypes' => $types,
            'formAction' => BASE_URL . '/admin/rooms/store',
        ]);
    }

    public function edit(): void
    {
        Auth::requireAdmin();
        $room = (new Room())->find(max(0, (int)($_GET['id'] ?? 0)));
        if (!$room) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Không tìm thấy phòng']);
            return;
        }
        $this->view('admin/rooms/form', [
            'title' => 'Cập nhật phòng', 'activeMenu' => 'rooms',
            'room' => $room, 'roomTypes' => (new Room())->roomTypes(),
            'formAction' => BASE_URL . '/admin/rooms/update',
        ]);
    }

    public function store(): void { $this->persist(null); }
    public function update(): void { $this->persist(max(0, (int)($_POST['id'] ?? 0))); }

    private function persist(?int $id): void
    {
        Auth::requireAdmin();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/admin/rooms');
        }
        $number = strtoupper(trim((string)($_POST['room_number'] ?? '')));
        $floorRaw = trim((string)($_POST['floor_number'] ?? ''));
        $data = [
            'room_type_id' => (int)($_POST['room_type_id'] ?? 0),
            'room_number' => $number,
            'floor_number' => $floorRaw === '' ? null : (int)$floorRaw,
            'note' => trim((string)($_POST['note'] ?? '')),
        ];
        if ($data['room_type_id'] < 1 || $number === '' || !preg_match('/^[A-Z0-9-]{1,20}$/', $number)
            || ($floorRaw !== '' && (!preg_match('/^-?\d+$/', $floorRaw) || (int)$floorRaw < 0 || (int)$floorRaw > 200))) {
            $_SESSION['flash_error'] = 'Vui lòng nhập mã phòng (chữ in hoa, số hoặc dấu gạch ngang), tầng và loại phòng hợp lệ.';
            $this->redirect($id ? '/admin/rooms/edit?id=' . $id : '/admin/rooms/create');
        }
        try {
            (new Room())->save($data, $id);
            $_SESSION['flash_success'] = $id ? 'Đã cập nhật thông tin phòng.' : 'Đã thêm phòng mới.';
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = $e instanceof RuntimeException
                ? $e->getMessage() : 'Không thể lưu phòng. Vui lòng kiểm tra dữ liệu.';
            if (!$e instanceof RuntimeException) error_log($e->getMessage());
            $this->redirect($id ? '/admin/rooms/edit?id=' . $id : '/admin/rooms/create');
        }
        $this->redirect('/admin/rooms');
    }

    public function status(): void
    {
        Auth::requireAdmin();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/admin/rooms');
        }
        $id = max(0, (int)($_POST['id'] ?? 0));
        $status = (string)($_POST['status'] ?? '');
        try {
            (new Room())->setStatus($id, $status);
            $_SESSION['flash_success'] = 'Đã cập nhật trạng thái phòng.';
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = $e instanceof RuntimeException ? $e->getMessage() : 'Không thể cập nhật trạng thái phòng.';
            if (!$e instanceof RuntimeException) error_log($e->getMessage());
        }
        $this->redirect('/admin/rooms');
    }
}

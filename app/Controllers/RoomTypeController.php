<?php
declare(strict_types=1);

final class RoomTypeController extends Controller
{
    private Hotel $hotelModel;
    private RoomType $roomTypeModel;

    public function __construct()
    {
        $this->hotelModel = new Hotel();
        $this->roomTypeModel = new RoomType();
    }

    public function index(): void
    {
        Auth::requireAdmin();
        $q = trim((string)($_GET['q'] ?? ''));
        $status = trim((string)($_GET['status'] ?? ''));

        $hotel = $this->hotelModel->getDefault();
        $roomTypes = $this->roomTypeModel->all($q, '', $status);

        $this->view('admin/room-types/index', [
            'title' => 'Quản lý hạng phòng',
            'activeMenu' => 'room-types',
            'roomTypes' => $roomTypes,
            'hotel' => $hotel,
            'q' => $q,
            'status' => $status,
        ]);
    }

    public function create(): void
    {
        Auth::requireAdmin();
        $hotel = $this->hotelModel->getDefault();

        $this->view('admin/room-types/form', [
            'title' => 'Thêm hạng phòng mới',
            'activeMenu' => 'room-types',
            'roomType' => null,
            'hotel' => $hotel,
            'formAction' => BASE_URL . '/admin/room-types/store',
        ]);
    }

    public function edit(): void
    {
        Auth::requireAdmin();
        $id = max(0, (int)($_GET['id'] ?? 0));
        $roomType = $this->roomTypeModel->find($id);

        if (!$roomType) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Không tìm thấy hạng phòng']);
            return;
        }

        $hotel = $this->hotelModel->find((int)$roomType['hotel_id']) ?: $this->hotelModel->getDefault();

        $this->view('admin/room-types/form', [
            'title' => 'Cập nhật hạng phòng',
            'activeMenu' => 'room-types',
            'roomType' => $roomType,
            'hotel' => $hotel,
            'formAction' => BASE_URL . '/admin/room-types/update',
        ]);
    }

    public function store(): void
    {
        $this->persist(null);
    }

    public function update(): void
    {
        $this->persist(max(0, (int)($_POST['id'] ?? 0)));
    }

    private function persist(?int $id): void
    {
        Auth::requireAdmin();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ.';
            $this->redirect('/admin/room-types');
        }

        $hotel = $this->hotelModel->getDefault();
        $hotelId = (int)($_POST['hotel_id'] ?? 0);
        if ($hotelId <= 0 && $hotel) {
            $hotelId = (int)$hotel['id'];
        }

        $data = [
            'hotel_id' => $hotelId > 0 ? $hotelId : 1,
            'name' => trim((string)($_POST['name'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
            'area' => trim((string)($_POST['area'] ?? '')) === '' ? null : (float)$_POST['area'],
            'max_guests' => (int)($_POST['max_guests'] ?? 2),
            'bed_type' => trim((string)($_POST['bed_type'] ?? '')),
            'base_price' => (float)($_POST['base_price'] ?? 0),
            'thumbnail' => trim((string)($_POST['thumbnail'] ?? '')),
            'status' => in_array($_POST['status'] ?? '', ['active', 'inactive'], true) ? $_POST['status'] : 'active',
        ];

        if ($data['name'] === '' || $data['base_price'] <= 0
            || $data['max_guests'] < 1 || $data['max_guests'] > 20
            || ($data['area'] !== null && $data['area'] <= 0)) {
            $_SESSION['flash_error'] = 'Vui lòng nhập tên hạng phòng, diện tích, sức chứa và giá niêm yết hợp lệ.';
            $this->redirect($id ? '/admin/room-types/edit?id=' . $id : '/admin/room-types/create');
        }

        try {
            $this->roomTypeModel->save($data, $id);
            $_SESSION['flash_success'] = $id ? 'Đã cập nhật hạng phòng thành công.' : 'Đã thêm hạng phòng mới thành công.';
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash_error'] = 'Không thể lưu hạng phòng. Vui lòng kiểm tra dữ liệu.';
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

        $this->roomTypeModel->toggleStatus(max(0, (int)($_POST['id'] ?? 0)));
        $_SESSION['flash_success'] = 'Đã cập nhật trạng thái hạng phòng.';
        $this->redirect('/admin/room-types');
    }
}

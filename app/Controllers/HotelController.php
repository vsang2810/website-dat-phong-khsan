<?php
declare(strict_types=1);

final class HotelController extends Controller
{
    private Hotel $hotelModel;

    public function __construct()
    {
        $this->hotelModel = new Hotel();
    }

    public function index(): void
    {
        Auth::requireAdmin();
        // Website chỉ quản lý 1 khách sạn -> Chuyển hướng sang Quản lý hạng phòng
        $this->redirect('/admin/room-types');
    }

    public function settings(): void
    {
        Auth::requireAdmin();
        $hotel = $this->hotelModel->getDefault();
        if (!$hotel) {
            $this->redirect('/admin/room-types');
        }

        $this->view('admin/hotels/settings', [
            'title' => 'Thông tin khách sạn',
            'activeMenu' => 'hotel-info',
            'hotel' => $hotel,
            'formAction' => BASE_URL . '/admin/hotel-info',
        ]);
    }

    public function updateSettings(): void
    {
        Auth::requireAdmin();
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Phiên làm việc không hợp lệ. Vui lòng thử lại.';
            $this->redirect('/admin/hotel-info');
        }

        $id = max(1, (int)($_POST['id'] ?? 1));
        $thumbnail = trim((string)($_POST['thumbnail'] ?? 'assets/images/hotel/lotte-hotel.jpg'));

        // Handle uploaded hotel image file
        if (!empty($_FILES['thumbnail_file']['name']) && $_FILES['thumbnail_file']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['thumbnail_file']['tmp_name'];
            $origName = $_FILES['thumbnail_file']['name'];
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if (in_array($ext, $allowed, true)) {
                $targetDir = BASE_PATH . '/public/assets/images/hotel';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $safeName = 'lotte-hotel.' . $ext;
                $targetFile = $targetDir . '/' . $safeName;
                if (move_uploaded_file($tmpName, $targetFile)) {
                    $thumbnail = 'assets/images/hotel/' . $safeName;
                }
            }
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
            'thumbnail' => $thumbnail,
            'check_in' => (string)($_POST['check_in_time'] ?? '14:00'),
            'check_out' => (string)($_POST['check_out_time'] ?? '12:00'),
            'status' => 'active',
        ];

        if ($data['name'] === '' || $data['address'] === '') {
            $_SESSION['flash_error'] = 'Tên khách sạn và địa chỉ là bắt buộc.';
            $this->redirect('/admin/hotel-info');
        }

        try {
            $this->hotelModel->save($data, $id);
            $_SESSION['flash_success'] = 'Cập nhật thông tin khách sạn thành công.';
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $_SESSION['flash_error'] = 'Không thể cập nhật thông tin khách sạn.';
        }

        $this->redirect('/admin/hotel-info');
    }

    public function create(): void
    {
        $this->redirect('/admin/room-types');
    }

    public function edit(): void
    {
        $this->redirect('/admin/hotel-info');
    }

    public function store(): void
    {
        $this->redirect('/admin/room-types');
    }

    public function update(): void
    {
        $this->redirect('/admin/room-types');
    }

    public function toggle(): void
    {
        $this->redirect('/admin/room-types');
    }
}

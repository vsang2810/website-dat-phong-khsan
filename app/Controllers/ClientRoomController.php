<?php
declare(strict_types=1);

final class ClientRoomController extends Controller
{
    private RoomType $roomTypeModel;
    private Hotel $hotelModel;
    private Review $reviewModel;
    private Wishlist $wishlistModel;

    public function __construct()
    {
        $this->roomTypeModel = new RoomType();
        $this->hotelModel = new Hotel();
        $this->reviewModel = new Review();
        $this->wishlistModel = new Wishlist();
    }

    public function index(): void
    {
        $filters = [
            'hotel_id' => trim((string)($_GET['hotel_id'] ?? '')),
            'guests' => trim((string)($_GET['guests'] ?? '')),
            'check_in' => trim((string)($_GET['check_in'] ?? '')),
            'check_out' => trim((string)($_GET['check_out'] ?? '')),
            'keyword' => trim((string)($_GET['q'] ?? '')),
            'min_price' => trim((string)($_GET['min_price'] ?? '')),
            'max_price' => trim((string)($_GET['max_price'] ?? '')),
            'bed_type' => trim((string)($_GET['bed_type'] ?? '')),
        ];

        // Default check-in/out if only one is filled
        if (!empty($filters['check_in']) && empty($filters['check_out'])) {
            $filters['check_out'] = date('Y-m-d', strtotime($filters['check_in'] . ' +1 day'));
        }

        $roomTypes = $this->roomTypeModel->getActiveList($filters);
        $hotels = $this->hotelModel->all();

        // Mark wishlisted rooms if user logged in
        $userId = Auth::id();
        if ($userId) {
            foreach ($roomTypes as &$rt) {
                $rt['is_wishlisted'] = $this->wishlistModel->isWishlisted($userId, (int)$rt['id']);
            }
        }

        $this->view('client/rooms/index', [
            'title' => 'Danh sách phòng & Suites sang trọng',
            'roomTypes' => $roomTypes,
            'hotels' => $hotels,
            'filters' => $filters,
            'activeNav' => 'rooms',
        ], 'client');
    }

    public function detail(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/rooms');
        }

        $room = $this->roomTypeModel->findWithDetails($id);
        if (!$room) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Không tìm thấy phòng'], 'client');
            return;
        }

        $checkIn = trim((string)($_GET['check_in'] ?? date('Y-m-d', strtotime('+1 day'))));
        $checkOut = trim((string)($_GET['check_out'] ?? date('Y-m-d', strtotime('+2 days'))));
        $guests = max(1, (int)($_GET['guests'] ?? 2));

        $availableCount = $this->roomTypeModel->checkAvailableRoomsCount($id, $checkIn, $checkOut);
        $reviews = $this->reviewModel->getByRoomType($id);
        $similarRooms = $this->roomTypeModel->getFeatured(3);

        $userId = Auth::id();
        $isWishlisted = $userId ? $this->wishlistModel->isWishlisted($userId, $id) : false;

        $this->view('client/rooms/detail', [
            'title' => $room['name'] . ' - LOTTE HOTEL HANOI',
            'room' => $room,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'guests' => $guests,
            'availableCount' => $availableCount,
            'reviews' => $reviews,
            'similarRooms' => $similarRooms,
            'isWishlisted' => $isWishlisted,
            'activeNav' => 'rooms',
        ], 'client');
    }

    public function checkAvailability(): void
    {
        $roomTypeId = (int)($_GET['room_type_id'] ?? 0);
        $checkIn = trim((string)($_GET['check_in'] ?? ''));
        $checkOut = trim((string)($_GET['check_out'] ?? ''));

        if ($roomTypeId <= 0 || empty($checkIn) || empty($checkOut)) {
            $this->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ.'], 400);
        }

        if (strtotime($checkOut) <= strtotime($checkIn)) {
            $this->json(['success' => false, 'message' => 'Ngày trả phòng phải sau ngày nhận phòng.'], 400);
        }

        $count = $this->roomTypeModel->checkAvailableRoomsCount($roomTypeId, $checkIn, $checkOut);
        $this->json([
            'success' => true,
            'available_count' => $count,
            'is_available' => $count > 0,
        ]);
    }

    public function toggleWishlist(): void
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Vui lòng đăng nhập để lưu phòng yêu thích.'], 401);
        }

        $roomTypeId = (int)($_POST['room_type_id'] ?? 0);
        if ($roomTypeId <= 0) {
            $this->json(['success' => false, 'message' => 'Loại phòng không hợp lệ.'], 400);
        }

        $userId = (int)Auth::id();
        $isAdded = $this->wishlistModel->toggle($userId, $roomTypeId);

        $this->json([
            'success' => true,
            'is_wishlisted' => $isAdded,
            'message' => $isAdded ? 'Đã thêm vào danh sách yêu thích!' : 'Đã xóa khỏi danh sách yêu thích.',
        ]);
    }
}

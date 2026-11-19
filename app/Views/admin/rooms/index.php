<?php
$esc = static fn($v): string => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$statusLabels = [
    'available' => ['Sẵn sàng', 'status-confirmed'],
    'maintenance' => ['Bảo trì', 'status-pending_payment'],
    'inactive' => ['Ngừng hoạt động', 'status-cancelled'],
    'occupied' => ['Đang sử dụng', 'status-checked_in'],
    'reserved' => ['Đã đặt hôm nay', 'status-checked_in'],
    'held' => ['Đang giữ chỗ', 'status-pending_hotel_payment'],
];
?>
<div class="page-heading">
    <div><span class="eyebrow">ROOM INVENTORY</span><h1>Quản lý phòng thực tế</h1>
    <p class="muted">Theo dõi mã phòng, tầng, loại phòng và tình trạng sử dụng theo đơn đặt phòng.</p></div>
    <a class="btn-primary" href="<?= BASE_URL ?>/admin/rooms/create">+ Thêm phòng</a>
</div>
<?php if (!empty($_SESSION['flash_success'])): ?><div class="alert-success"><?= $esc($_SESSION['flash_success']) ?></div><?php unset($_SESSION['flash_success']); endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?><div class="alert-error"><?= $esc($_SESSION['flash_error']) ?></div><?php unset($_SESSION['flash_error']); endif; ?>

<div class="panel booking-filter-panel">
    <form method="get" action="<?= BASE_URL ?>/admin/rooms" class="simple-filter">
        <input type="search" name="q" value="<?= $esc($filters['q']) ?>" placeholder="Tìm mã phòng, loại phòng, khách sạn">
        <select name="hotel_id"><option value="">Tất cả khách sạn</option>
            <?php foreach ($hotels as $hotel): ?><option value="<?= (int)$hotel['id'] ?>" <?= $filters['hotel_id']===(string)$hotel['id']?'selected':'' ?>><?= $esc($hotel['name']) ?></option><?php endforeach; ?>
        </select>
        <select name="room_type_id"><option value="">Tất cả loại phòng</option>
            <?php foreach ($roomTypes as $type): ?><option value="<?= (int)$type['id'] ?>" <?= $filters['room_type_id']===(string)$type['id']?'selected':'' ?>><?= $esc($type['hotel_name'].' — '.$type['name']) ?></option><?php endforeach; ?>
        </select>
        <select name="status"><option value="">Mọi trạng thái quản lý</option>
            <option value="available" <?= $filters['base_status']==='available'?'selected':'' ?>>Sẵn sàng</option>
            <option value="maintenance" <?= $filters['base_status']==='maintenance'?'selected':'' ?>>Bảo trì</option>
            <option value="inactive" <?= $filters['base_status']==='inactive'?'selected':'' ?>>Ngừng hoạt động</option>
        </select>
        <button class="btn-primary" type="submit">Lọc</button><a class="btn-reset" href="<?= BASE_URL ?>/admin/rooms">Đặt lại</a>
    </form>
</div>

<div class="panel booking-table-panel">
    <div class="panel-heading"><div><span class="eyebrow">ROOM LIST</span><h2>Danh sách phòng</h2></div><span class="subtle-tag"><?= count($rooms) ?> phòng</span></div>
    <div class="table-wrap"><table class="booking-management-table">
        <thead><tr><th>MÃ PHÒNG</th><th>KHÁCH SẠN / LOẠI PHÒNG</th><th>TẦNG</th><th>TÌNH TRẠNG</th><th>ĐƠN ĐANG LIÊN QUAN</th><th>THAO TÁC</th></tr></thead>
        <tbody>
        <?php if (!$rooms): ?><tr><td colspan="6" class="empty-state">Chưa có phòng phù hợp. Hãy thêm phòng thực tế đầu tiên.</td></tr>
        <?php else: foreach ($rooms as $room): $ds=$statusLabels[$room['display_status']] ?? $statusLabels[$room['status']]; ?>
            <tr>
                <td><strong><?= $esc($room['room_number']) ?></strong></td>
                <td><strong><?= $esc($room['hotel_name']) ?></strong><small><?= $esc($room['room_type_name']) ?></small></td>
                <td><?= $room['floor_number'] !== null ? 'Tầng '.(int)$room['floor_number'] : '—' ?></td>
                <td><span class="status <?= $ds[1] ?>"><?= $ds[0] ?></span>
                    <?php if ($room['display_status'] !== $room['status']): ?><small class="room-status-note">Trạng thái quản lý: <?= $esc($statusLabels[$room['status']][0]) ?></small><?php endif; ?>
                </td>
                <td><?php if ($room['current_booking_code']): ?><strong><?= $esc($room['current_booking_code']) ?></strong><small><?= $esc($room['current_guest'] ?? '') ?></small><?php else: ?>—<?php endif; ?></td>
                <td><div class="row-actions">
                    <a class="table-action" href="<?= BASE_URL ?>/admin/rooms/edit?id=<?= (int)$room['id'] ?>">Sửa</a>
                    <form method="post" action="<?= BASE_URL ?>/admin/rooms/status" class="room-status-form">
                        <input type="hidden" name="_csrf" value="<?= $esc(Csrf::token()) ?>"><input type="hidden" name="id" value="<?= (int)$room['id'] ?>">
                        <select name="status" aria-label="Đổi trạng thái phòng <?= $esc($room['room_number']) ?>">
                            <option value="available" <?= $room['status']==='available'?'selected':'' ?>>Sẵn sàng</option>
                            <option value="maintenance" <?= $room['status']==='maintenance'?'selected':'' ?>>Bảo trì</option>
                            <option value="inactive" <?= $room['status']==='inactive'?'selected':'' ?>>Ngừng hoạt động</option>
                        </select><button class="text-action" type="submit">Lưu</button>
                    </form>
                </div></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table></div>
    <p class="room-list-hint">“Đang sử dụng”, “Đã đặt hôm nay” và “Đang giữ chỗ” được hệ thống suy ra từ đơn đặt phòng; không phải trạng thái quản lý có thể chọn thủ công.</p>
</div>
<div class="module-shortcut"><span>Danh mục</span><strong>Loại phòng</strong><a href="<?= BASE_URL ?>/admin/room-types">Quản lý loại phòng →</a></div>

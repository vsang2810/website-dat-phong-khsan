<div class="page-heading">
    <div><span class="eyebrow">HOTEL MANAGEMENT</span><h1>Quản lý khách sạn</h1>
        <p class="muted">Quản lý thông tin cơ sở lưu trú và các loại phòng trực thuộc.</p></div>
    <a class="btn-primary" href="<?= BASE_URL ?>/admin/hotels/create">+ Thêm khách sạn</a>
</div>
<?php if (!empty($_SESSION['flash_success'])): ?><div class="alert-success"><?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?></div><?php unset($_SESSION['flash_success']); endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?><div class="alert-error"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div><?php unset($_SESSION['flash_error']); endif; ?>
<div class="panel booking-filter-panel">
    <form method="get" action="<?= BASE_URL ?>/admin/hotels" class="simple-filter">
        <input type="search" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="Tìm theo tên, địa chỉ, thành phố, số điện thoại">
        <button class="btn-primary" type="submit">Tìm kiếm</button>
        <a class="btn-reset" href="<?= BASE_URL ?>/admin/hotels">Đặt lại</a>
    </form>
</div>
<div class="panel booking-table-panel">
    <div class="panel-heading"><div><span class="eyebrow">HOTEL DIRECTORY</span><h2>Danh sách khách sạn</h2></div><span class="subtle-tag"><?= count($hotels) ?> khách sạn</span></div>
    <div class="table-wrap"><table class="booking-management-table">
        <thead><tr><th>KHÁCH SẠN</th><th>ĐỊA CHỈ</th><th>LIÊN HỆ</th><th>LOẠI PHÒNG</th><th>PHÒNG THỰC TẾ</th><th>TRẠNG THÁI</th><th>THAO TÁC</th></tr></thead>
        <tbody>
        <?php if (!$hotels): ?><tr><td colspan="7" class="empty-state">Chưa có khách sạn phù hợp.</td></tr>
        <?php else: foreach ($hotels as $hotel): ?>
            <tr>
                <td><strong><?= htmlspecialchars($hotel['name'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= str_repeat('★', (int)$hotel['star_rating']) ?> · <?= htmlspecialchars($hotel['city'] ?? '', ENT_QUOTES, 'UTF-8') ?></small></td>
                <td><?= htmlspecialchars($hotel['address'], ENT_QUOTES, 'UTF-8') ?><small><?= htmlspecialchars($hotel['country'] ?? '', ENT_QUOTES, 'UTF-8') ?></small></td>
                <td><?= htmlspecialchars($hotel['phone'] ?: '—', ENT_QUOTES, 'UTF-8') ?><small><?= htmlspecialchars($hotel['email'] ?: '—', ENT_QUOTES, 'UTF-8') ?></small></td>
                <td><?= (int)$hotel['room_type_count'] ?></td><td><?= (int)$hotel['room_count'] ?></td>
                <td><span class="status <?= $hotel['status'] === 'active' ? 'status-confirmed' : 'status-cancelled' ?>"><?= $hotel['status'] === 'active' ? 'Hoạt động' : 'Ngừng hoạt động' ?></span></td>
                <td><div class="row-actions"><a class="table-action" href="<?= BASE_URL ?>/admin/hotels/edit?id=<?= (int)$hotel['id'] ?>">Sửa</a>
                    <form method="post" action="<?= BASE_URL ?>/admin/hotels/toggle" onsubmit="return confirm('Thay đổi trạng thái khách sạn này?')">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="id" value="<?= (int)$hotel['id'] ?>">
                        <button class="text-action" type="submit"><?= $hotel['status'] === 'active' ? 'Ngừng' : 'Kích hoạt' ?></button>
                    </form></div></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table></div>
</div>
<div class="module-shortcut"><span>Tiếp theo</span><strong>Quản lý loại phòng</strong><a href="<?= BASE_URL ?>/admin/room-types">Mở danh sách loại phòng →</a></div>

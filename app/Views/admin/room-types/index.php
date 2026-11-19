<div class="page-heading"><div><span class="eyebrow">ROOM TYPE MANAGEMENT</span><h1>Quản lý loại phòng</h1><p class="muted">Thiết lập hạng phòng, tiện nghi cơ bản, sức chứa và giá niêm yết.</p></div><a class="btn-primary" href="<?= BASE_URL ?>/admin/room-types/create">+ Thêm loại phòng</a></div>
<?php if (!empty($_SESSION['flash_success'])): ?><div class="alert-success"><?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?></div><?php unset($_SESSION['flash_success']); endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?><div class="alert-error"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div><?php unset($_SESSION['flash_error']); endif; ?>
<div class="panel booking-filter-panel">
    <form method="get" action="<?= BASE_URL ?>/admin/room-types" class="simple-filter">
        <input type="search" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="Tìm theo tên loại phòng, khách sạn, loại giường">
        <select name="hotel_id"><option value="">Tất cả khách sạn</option><?php foreach ($hotels as $hotel): ?><option value="<?= (int)$hotel['id'] ?>" <?= (string)$hotelId===(string)$hotel['id']?'selected':'' ?>><?= htmlspecialchars($hotel['name'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select>
        <button class="btn-primary" type="submit">Lọc</button><a class="btn-reset" href="<?= BASE_URL ?>/admin/room-types">Đặt lại</a>
    </form>
</div>
<div class="panel booking-table-panel">
    <div class="panel-heading"><div><span class="eyebrow">ROOM CATEGORIES</span><h2>Danh sách loại phòng</h2></div><span class="subtle-tag"><?= count($roomTypes) ?> loại phòng</span></div>
    <div class="table-wrap"><table class="booking-management-table">
        <thead><tr><th>LOẠI PHÒNG</th><th>KHÁCH SẠN</th><th>DIỆN TÍCH</th><th>SỨC CHỨA</th><th>LOẠI GIƯỜNG</th><th>GIÁ / ĐÊM</th><th>SỐ PHÒNG</th><th>TRẠNG THÁI</th><th>THAO TÁC</th></tr></thead>
        <tbody>
        <?php if (!$roomTypes): ?><tr><td colspan="9" class="empty-state">Chưa có loại phòng phù hợp.</td></tr>
        <?php else: foreach ($roomTypes as $rt): ?>
            <tr>
                <td><strong><?= htmlspecialchars($rt['name'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars(mb_strimwidth((string)$rt['description'],0,65,'…','UTF-8'), ENT_QUOTES, 'UTF-8') ?></small></td>
                <td><?= htmlspecialchars($rt['hotel_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= $rt['area'] !== null ? number_format((float)$rt['area'], 0, ',', '.') . ' m²' : '—' ?></td>
                <td><?= (int)$rt['max_guests'] ?> khách</td><td><?= htmlspecialchars($rt['bed_type'] ?: '—', ENT_QUOTES, 'UTF-8') ?></td>
                <td class="amount"><?= number_format((float)$rt['base_price'], 0, ',', '.') ?> ₫</td><td><?= (int)$rt['room_count'] ?></td>
                <td><span class="status <?= $rt['status']==='active'?'status-confirmed':'status-cancelled' ?>"><?= $rt['status']==='active'?'Đang bán':'Tạm ngừng' ?></span></td>
                <td><div class="row-actions"><a class="table-action" href="<?= BASE_URL ?>/admin/room-types/edit?id=<?= (int)$rt['id'] ?>">Sửa</a>
                    <form method="post" action="<?= BASE_URL ?>/admin/room-types/toggle" onsubmit="return confirm('Thay đổi trạng thái loại phòng này?')">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="id" value="<?= (int)$rt['id'] ?>">
                        <button class="text-action" type="submit"><?= $rt['status']==='active'?'Ngừng':'Kích hoạt' ?></button>
                    </form></div></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table></div>
</div>
<div class="module-shortcut"><span>Quản lý cơ sở</span><strong>Khách sạn</strong><a href="<?= BASE_URL ?>/admin/hotels">Quay lại danh sách khách sạn →</a></div>

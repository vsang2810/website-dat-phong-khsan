<div class="page-heading">
    <div>
        <span class="eyebrow">ROOM CATEGORY MANAGEMENT</span>
        <h1>Quản lý hạng phòng</h1>
        <p class="muted">
            Quản lý các hạng phòng của <?= htmlspecialchars($hotel['name'] ?? 'LOTTE HOTEL HANOI') ?>: diện tích, sức chứa, loại giường, giá niêm yết và số lượng phòng thực tế.
        </p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a class="btn-primary" href="<?= BASE_URL ?>/admin/room-types/create">+ Thêm hạng phòng mới</a>
    </div>
</div>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert-success"><?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert-error"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<?php
    $totalCategories = count($roomTypes);
    $activeCategories = count(array_filter($roomTypes, fn($rt) => $rt['status'] === 'active'));
    $inactiveCategories = $totalCategories - $activeCategories;
    $totalRooms = array_sum(array_map(fn($rt) => (int)$rt['room_count'], $roomTypes));
?>

<!-- SUMMARY STATS -->
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 20px;">
    <div class="stat-card">
        <div class="stat-top"><span>Tổng số hạng phòng</span><span class="stat-icon sand">▣</span></div>
        <strong class="stat-value"><?= $totalCategories ?> <small>hạng</small></strong>
        <div class="stat-foot">Áp dụng cho toàn khách sạn</div>
    </div>
    <div class="stat-card">
        <div class="stat-top"><span>Đang kinh doanh</span><span class="stat-icon mint">✓</span></div>
        <strong class="stat-value" style="color: #23845b;"><?= $activeCategories ?> <small>hạng</small></strong>
        <div class="stat-foot">Khách có thể đặt online</div>
    </div>
    <div class="stat-card">
        <div class="stat-top"><span>Tạm ngừng bán</span><span class="stat-icon peach">✕</span></div>
        <strong class="stat-value" style="color: #cf3d3d;"><?= $inactiveCategories ?> <small>hạng</small></strong>
        <div class="stat-foot">Ẩn khỏi luồng đặt phòng</div>
    </div>
    <div class="stat-card">
        <div class="stat-top"><span>Tổng phòng thực tế</span><span class="stat-icon lavender">▦</span></div>
        <strong class="stat-value"><?= $totalRooms ?> <small>phòng</small></strong>
        <div class="stat-foot"><a href="<?= BASE_URL ?>/admin/rooms" style="text-decoration: underline;">Quản lý kho phòng →</a></div>
    </div>
</div>

<!-- FILTER -->
<div class="panel booking-filter-panel">
    <form method="get" action="<?= BASE_URL ?>/admin/room-types" class="simple-filter">
        <input type="search" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="Tìm theo tên hạng phòng, loại giường, mô tả...">
        <select name="status">
            <option value="">Tất cả trạng thái</option>
            <option value="active" <?= ($status === 'active') ? 'selected' : '' ?>>Đang bán (Active)</option>
            <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Tạm ngừng (Inactive)</option>
        </select>
        <button class="btn-primary" type="submit">Lọc</button>
        <a class="btn-reset" href="<?= BASE_URL ?>/admin/room-types">Đặt lại</a>
    </form>
</div>

<!-- LIST TABLE -->
<div class="panel booking-table-panel">
    <div class="panel-heading">
        <div>
            <span class="eyebrow">ROOM CATEGORIES</span>
            <h2>Danh sách các hạng phòng</h2>
        </div>
        <span class="subtle-tag"><?= count($roomTypes) ?> hạng phòng</span>
    </div>

    <div class="table-wrap">
        <table class="booking-management-table">
            <thead>
                <tr>
                    <th style="width: 70px;">ẢNH</th>
                    <th>TÊN HẠNG PHÒNG</th>
                    <th>DIỆN TÍCH</th>
                    <th>SỨC CHỨA</th>
                    <th>LOẠI GIƯỜNG</th>
                    <th style="text-align: right;">GIÁ NIÊM YẾT / ĐÊM</th>
                    <th style="text-align: center;">PHÒNG THỰC TẾ</th>
                    <th>TRẠNG THÁI</th>
                    <th style="text-align: right;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$roomTypes): ?>
                <tr><td colspan="9" class="empty-state">Chưa có hạng phòng nào phù hợp với điều kiện tìm kiếm.</td></tr>
            <?php else: foreach ($roomTypes as $rt): ?>
                <tr>
                    <td>
                        <img src="<?= BASE_URL . '/' . htmlspecialchars($rt['thumbnail'] ?: 'assets/images/hotel-banner.png') ?>" alt="<?= htmlspecialchars($rt['name']) ?>" style="width: 58px; height: 42px; object-fit: cover; border-radius: 4px; border: 1px solid var(--line);" onerror="this.src='<?= BASE_URL ?>/assets/images/hotel-banner.png'">
                    </td>
                    <td>
                        <strong style="font-size: 14px; color: var(--ink);"><?= htmlspecialchars($rt['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <small style="display: block; color: var(--muted); margin-top: 3px; max-width: 320px; white-space: normal; line-height: 1.4;">
                            <?= htmlspecialchars(mb_strimwidth((string)$rt['description'], 0, 90, '…', 'UTF-8'), ENT_QUOTES, 'UTF-8') ?>
                        </small>
                    </td>
                    <td><?= $rt['area'] !== null ? number_format((float)$rt['area'], 0, ',', '.') . ' m²' : '—' ?></td>
                    <td><strong><?= (int)$rt['max_guests'] ?></strong> khách</td>
                    <td><?= htmlspecialchars($rt['bed_type'] ?: '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="amount" style="text-align: right; font-weight: 700; color: #8c7962; font-size: 13px;">
                        <?= number_format((float)$rt['base_price'], 0, ',', '.') ?> ₫
                    </td>
                    <td style="text-align: center;">
                        <a href="<?= BASE_URL ?>/admin/rooms?room_type_id=<?= (int)$rt['id'] ?>" class="subtle-tag" title="Xem danh sách phòng thực tế" style="font-weight: 700; display: inline-block;">
                            <?= (int)$rt['room_count'] ?> phòng
                        </a>
                    </td>
                    <td>
                        <span class="status <?= $rt['status'] === 'active' ? 'status-confirmed' : 'status-cancelled' ?>">
                            <?= $rt['status'] === 'active' ? 'Đang bán' : 'Tạm ngừng' ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="row-actions" style="justify-content: flex-end;">
                            <a class="table-action" href="<?= BASE_URL ?>/admin/room-types/edit?id=<?= (int)$rt['id'] ?>">Sửa</a>
                            <form method="post" action="<?= BASE_URL ?>/admin/room-types/toggle" onsubmit="return confirm('Quý khách có chắc muốn thay đổi trạng thái bán của hạng phòng này?')">
                                <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= (int)$rt['id'] ?>">
                                <button class="text-action" type="submit"><?= $rt['status'] === 'active' ? 'Tạm ngừng' : 'Kích hoạt' ?></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="module-shortcut">
    <span>Quản lý phòng nghỉ</span>
    <strong>Phòng thực tế</strong>
    <a href="<?= BASE_URL ?>/admin/rooms">Chuyển sang Quản lý danh sách phòng thực tế →</a>
</div>

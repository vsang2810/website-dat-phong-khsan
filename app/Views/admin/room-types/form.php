<?php $v = static fn(string $key, string $default = ''): string => htmlspecialchars((string)($roomType[$key] ?? $default), ENT_QUOTES, 'UTF-8'); ?>
<div class="page-heading">
    <div>
        <span class="eyebrow">ROOM CATEGORY CONFIGURATION</span>
        <h1><?= $roomType ? 'Cập nhật hạng phòng' : 'Thêm hạng phòng mới' ?></h1>
        <p class="muted">
            Thiết lập tên hạng phòng, sức chứa, diện tích, loại giường và giá niêm yết cho khách sạn <?= htmlspecialchars($hotel['name'] ?? 'LOTTE HOTEL HANOI') ?>.
        </p>
    </div>
    <a class="btn-reset back-link" href="<?= BASE_URL ?>/admin/room-types">← Quay lại danh sách</a>
</div>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert-error"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<form class="panel management-form" method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="hotel_id" value="<?= (int)($hotel['id'] ?? 1) ?>">
    <?php if ($roomType): ?>
        <input type="hidden" name="id" value="<?= (int)$roomType['id'] ?>">
    <?php endif; ?>

    <div class="form-section-title">
        <h2>Cấu hình hạng phòng</h2>
        <p>Áp dụng cho khách sạn: <strong><?= htmlspecialchars($hotel['name'] ?? 'LOTTE HOTEL HANOI') ?></strong> (Đơn vị vận hành duy nhất)</p>
    </div>

    <div class="form-grid">
        <label class="wide">
            <span>Tên hạng phòng *</span>
            <input required maxlength="150" name="name" value="<?= $v('name') ?>" placeholder="Ví dụ: Deluxe Room, Executive Suite...">
        </label>

        <label>
            <span>Diện tích phòng (m²)</span>
            <input type="number" min="1" step="0.1" name="area" value="<?= $v('area') ?>" placeholder="Ví dụ: 42">
        </label>

        <label>
            <span>Sức chứa tối đa (Khách) *</span>
            <input required type="number" min="1" max="20" name="max_guests" value="<?= $v('max_guests', '2') ?>" placeholder="2">
        </label>

        <label>
            <span>Loại giường</span>
            <input maxlength="100" name="bed_type" value="<?= $v('bed_type') ?>" placeholder="Ví dụ: 1 giường đôi lớn (King)">
        </label>

        <label>
            <span>Giá niêm yết / đêm (₫) *</span>
            <input required type="number" min="1000" step="1000" name="base_price" value="<?= $v('base_price') ?>" placeholder="2500000">
        </label>

        <label>
            <span>Trạng thái kinh doanh</span>
            <select name="status">
                <option value="active" <?= ($roomType['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Đang bán (Hiển thị cho khách đặt)</option>
                <option value="inactive" <?= ($roomType['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Tạm ngừng (Ẩn khỏi trang đặt phòng)</option>
            </select>
        </label>

        <label class="wide">
            <span>Đường dẫn ảnh đại diện (Thumbnail)</span>
            <input name="thumbnail" value="<?= $v('thumbnail') ?>" placeholder="assets/images/rooms/deluxe.jpg">
            <small style="color: var(--muted); font-size: 11px; margin-top: 4px; display: block;">Ảnh mẫu hoặc đường dẫn tương đối trong thư mục public/assets/images/rooms/.</small>
        </label>

        <label class="wide">
            <span>Mô tả chi tiết hạng phòng</span>
            <textarea name="description" rows="4" placeholder="Mô tả phong cách kiến trúc, không gian phòng, tầm nhìn và các tiện ích nổi bật..."><?= $v('description') ?></textarea>
        </label>
    </div>

    <div class="form-footer">
        <a class="btn-reset" href="<?= BASE_URL ?>/admin/room-types">Hủy bỏ</a>
        <button class="btn-primary" type="submit"><?= $roomType ? 'Lưu thay đổi hạng phòng' : 'Tạo hạng phòng mới' ?></button>
    </div>
</form>

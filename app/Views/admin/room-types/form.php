<?php $v = static fn(string $key, string $default = ''): string => htmlspecialchars((string)($roomType[$key] ?? $default), ENT_QUOTES, 'UTF-8'); ?>
<div class="page-heading"><div><span class="eyebrow">ROOM TYPE INFORMATION</span><h1><?= $roomType ? 'Cập nhật loại phòng' : 'Thêm loại phòng mới' ?></h1><p class="muted">Loại phòng là nhóm cấu hình chung; số phòng và số phòng cụ thể được quản lý ở mục riêng.</p></div><a class="btn-reset back-link" href="<?= BASE_URL ?>/admin/room-types">← Quay lại</a></div>
<?php if (!empty($_SESSION['flash_error'])): ?><div class="alert-error"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div><?php unset($_SESSION['flash_error']); endif; ?>
<form class="panel management-form" method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($roomType): ?><input type="hidden" name="id" value="<?= (int)$roomType['id'] ?>"><?php endif; ?>
    <div class="form-section-title"><h2>Thông tin loại phòng</h2><p>Chọn khách sạn và cấu hình hạng phòng</p></div>
    <div class="form-grid">
        <label class="wide"><span>Khách sạn *</span><select required name="hotel_id"><option value="">Chọn khách sạn</option><?php foreach ($hotels as $hotel): ?><option value="<?= (int)$hotel['id'] ?>" <?= (string)($roomType['hotel_id'] ?? '')===(string)$hotel['id']?'selected':'' ?>><?= htmlspecialchars($hotel['name'], ENT_QUOTES, 'UTF-8') ?><?= $hotel['status']==='inactive'?' (Ngừng hoạt động)':'' ?></option><?php endforeach; ?></select></label>
        <label class="wide"><span>Tên loại phòng *</span><input required maxlength="150" name="name" value="<?= $v('name') ?>" placeholder="Deluxe Room"></label>
        <label><span>Diện tích (m²)</span><input type="number" min="1" step="0.01" name="area" value="<?= $v('area') ?>" placeholder="42"></label>
        <label><span>Sức chứa tối đa *</span><input required type="number" min="1" max="20" name="max_guests" value="<?= $v('max_guests','2') ?>"></label>
        <label><span>Loại giường</span><input maxlength="100" name="bed_type" value="<?= $v('bed_type') ?>" placeholder="1 giường đôi"></label>
        <label><span>Giá niêm yết / đêm (₫) *</span><input required type="number" min="1" step="1000" name="base_price" value="<?= $v('base_price') ?>" placeholder="2500000"></label>
        <label><span>Trạng thái</span><select name="status"><option value="active" <?= ($roomType['status'] ?? 'active')==='active'?'selected':'' ?>>Đang bán</option><option value="inactive" <?= ($roomType['status'] ?? '')==='inactive'?'selected':'' ?>>Tạm ngừng</option></select></label>
        <label class="wide"><span>Đường dẫn ảnh đại diện</span><input name="thumbnail" value="<?= $v('thumbnail') ?>" placeholder="assets/images/rooms/deluxe.jpg"></label>
        <label class="wide"><span>Mô tả</span><textarea name="description" rows="4" placeholder="Mô tả tiện nghi và đặc điểm loại phòng..."><?= $v('description') ?></textarea></label>
    </div>
    <div class="form-footer"><a class="btn-reset" href="<?= BASE_URL ?>/admin/room-types">Hủy</a><button class="btn-primary" type="submit"><?= $roomType ? 'Lưu thay đổi' : 'Tạo loại phòng' ?></button></div>
</form>

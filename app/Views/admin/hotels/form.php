<?php $v = static fn(string $key, string $default = ''): string => htmlspecialchars((string)($hotel[$key] ?? $default), ENT_QUOTES, 'UTF-8'); ?>
<div class="page-heading"><div><span class="eyebrow">HOTEL INFORMATION</span><h1><?= $hotel ? 'Cập nhật khách sạn' : 'Thêm khách sạn mới' ?></h1><p class="muted">Nhập thông tin cơ sở lưu trú. Các trường có dấu * là bắt buộc.</p></div><a class="btn-reset back-link" href="<?= BASE_URL ?>/admin/hotels">← Quay lại</a></div>
<?php if (!empty($_SESSION['flash_error'])): ?><div class="alert-error"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div><?php unset($_SESSION['flash_error']); endif; ?>
<form class="panel management-form" method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($hotel): ?><input type="hidden" name="id" value="<?= (int)$hotel['id'] ?>"><?php endif; ?>
    <div class="form-section-title"><h2>Thông tin cơ bản</h2><p>Thông tin nhận diện khách sạn</p></div>
    <div class="form-grid">
        <label class="wide"><span>Tên khách sạn *</span><input required maxlength="200" name="name" value="<?= $v('name') ?>" placeholder="Ví dụ: LOTTE HOTEL HANOI"></label>
        <label class="wide"><span>Địa chỉ *</span><input required maxlength="255" name="address" value="<?= $v('address') ?>" placeholder="Số nhà, đường, quận/huyện"></label>
        <label><span>Thành phố</span><input name="city" value="<?= $v('city') ?>" placeholder="Hà Nội"></label>
        <label><span>Quốc gia</span><input name="country" value="<?= $v('country', 'Vietnam') ?>"></label>
        <label><span>Số điện thoại</span><input name="phone" maxlength="20" value="<?= $v('phone') ?>"></label>
        <label><span>Email</span><input type="email" name="email" maxlength="150" value="<?= $v('email') ?>"></label>
        <label><span>Xếp hạng sao</span><select name="star_rating"><?php for ($i=1;$i<=5;$i++): ?><option value="<?= $i ?>" <?= (int)($hotel['star_rating'] ?? 5)===$i?'selected':'' ?>><?= $i ?> sao</option><?php endfor; ?></select></label>
        <label><span>Trạng thái</span><select name="status"><option value="active" <?= ($hotel['status'] ?? 'active')==='active'?'selected':'' ?>>Hoạt động</option><option value="inactive" <?= ($hotel['status'] ?? '')==='inactive'?'selected':'' ?>>Ngừng hoạt động</option></select></label>
        <label class="wide"><span>Đường dẫn ảnh đại diện</span><input name="thumbnail" value="<?= $v('thumbnail') ?>" placeholder="assets/images/hotel/cover.jpg"></label>
        <label class="wide"><span>Mô tả</span><textarea name="description" rows="4" placeholder="Giới thiệu về khách sạn..."><?= $v('description') ?></textarea></label>
    </div>
    <div class="form-section-title"><h2>Thời gian phục vụ</h2><p>Giờ nhận và trả phòng tiêu chuẩn</p></div>
    <div class="form-grid">
        <label><span>Giờ nhận phòng</span><input type="time" name="check_in_time" value="<?= substr($v('check_in_time', '14:00:00'),0,5) ?>"></label>
        <label><span>Giờ trả phòng</span><input type="time" name="check_out_time" value="<?= substr($v('check_out_time', '12:00:00'),0,5) ?>"></label>
    </div>
    <div class="form-footer"><a class="btn-reset" href="<?= BASE_URL ?>/admin/hotels">Hủy</a><button class="btn-primary" type="submit"><?= $hotel ? 'Lưu thay đổi' : 'Tạo khách sạn' ?></button></div>
</form>

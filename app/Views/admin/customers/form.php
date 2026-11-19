<?php $esc=static fn($v):string=>htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8'); ?>
<div class="page-heading"><div><span class="eyebrow">EDIT CUSTOMER</span><h1>Cập nhật khách hàng</h1><p class="muted">Chỉnh sửa thông tin hồ sơ tài khoản khách hàng.</p></div><a class="btn-reset" href="<?= BASE_URL ?>/admin/customers/show?id=<?= (int)$customer['id'] ?>">← Quay lại</a></div>
<?php if(!empty($_SESSION['flash_error'])): ?><div class="alert-error"><?= $esc($_SESSION['flash_error']) ?></div><?php unset($_SESSION['flash_error']); endif; ?>
<form class="panel management-form" method="post" action="<?= BASE_URL ?>/admin/customers/update">
<input type="hidden" name="_csrf" value="<?= $esc(Csrf::token()) ?>"><input type="hidden" name="id" value="<?= (int)$customer['id'] ?>">
<div class="form-section-title"><h2>Thông tin khách hàng</h2><p>Email và số điện thoại không được trùng với tài khoản khác.</p></div>
<div class="form-grid">
<label><span>Họ và tên *</span><input name="full_name" required maxlength="150" value="<?= $esc($customer['full_name']) ?>"></label>
<label><span>Email *</span><input type="email" name="email" required maxlength="150" value="<?= $esc($customer['email']) ?>"></label>
<label><span>Số điện thoại</span><input type="tel" name="phone" maxlength="20" value="<?= $esc($customer['phone']) ?>" pattern="[0-9+\s().-]{8,20}"></label>
<label><span>Giới tính</span><select name="gender"><option value="other" <?= $customer['gender']==='other'?'selected':'' ?>>Khác / Chưa xác định</option><option value="male" <?= $customer['gender']==='male'?'selected':'' ?>>Nam</option><option value="female" <?= $customer['gender']==='female'?'selected':'' ?>>Nữ</option></select></label>
</div>
<div class="workflow-hint hint-warn">Thay đổi thông tin hồ sơ không sửa tên, email hoặc số điện thoại đã được lưu trong các đơn đặt phòng trước đây. Lịch sử giao dịch được giữ nguyên.</div>
<div class="form-footer"><a class="btn-reset" href="<?= BASE_URL ?>/admin/customers/show?id=<?= (int)$customer['id'] ?>">Hủy</a><button class="btn-primary" type="submit">Lưu thay đổi</button></div>
</form>

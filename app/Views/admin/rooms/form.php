<?php $v = static fn(string $key, string $default = ''): string => htmlspecialchars((string)($room[$key] ?? $default), ENT_QUOTES, 'UTF-8'); ?>
<div class="page-heading"><div><span class="eyebrow">ROOM INFORMATION</span><h1><?= $room ? 'Cập nhật phòng' : 'Thêm phòng thực tế' ?></h1>
<p class="muted">Mỗi phòng có một mã riêng trong toàn hệ thống và thuộc một loại phòng cụ thể.</p></div>
<a class="btn-reset back-link" href="<?= BASE_URL ?>/admin/rooms">← Quay lại</a></div>
<?php if (!empty($_SESSION['flash_error'])): ?><div class="alert-error"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div><?php unset($_SESSION['flash_error']); endif; ?>
<form class="panel management-form" method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($room): ?><input type="hidden" name="id" value="<?= (int)$room['id'] ?>"><?php endif; ?>
    <div class="form-section-title"><h2>Thông tin nhận diện phòng</h2><p>Nhập mã phòng, tầng và loại phòng áp dụng</p></div>
    <div class="form-grid">
        <label><span>Mã phòng *</span><input required maxlength="20" name="room_number" value="<?= $v('room_number') ?>" placeholder="Ví dụ: 1205" pattern="[A-Za-z0-9-]{1,20}"><small class="field-hint">Chữ cái, chữ số hoặc dấu gạch ngang; không trùng mã phòng khác.</small></label>
        <label><span>Tầng</span><input type="number" min="0" max="200" name="floor_number" value="<?= $v('floor_number') ?>" placeholder="Ví dụ: 12"></label>
        <label class="wide"><span>Hạng phòng *</span><select required name="room_type_id"><option value="">Chọn hạng phòng</option>
            <?php foreach ($roomTypes as $type): ?><option value="<?= (int)$type['id'] ?>" <?= (string)($room['room_type_id'] ?? '')===(string)$type['id']?'selected':'' ?>><?= htmlspecialchars($type['name'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?>
        </select></label>
        <label class="wide"><span>Ghi chú</span><textarea name="note" rows="4" placeholder="Ví dụ: Hướng nhìn thành phố, gần thang máy..."><?= $v('note') ?></textarea></label>
    </div>
    <?php if ($room): ?><div class="workflow-hint hint-warn">Trạng thái phòng được quản lý tại danh sách phòng. Nếu phòng đã có lịch sử đặt phòng, hệ thống không cho đổi loại phòng để bảo toàn dữ liệu đơn cũ.</div><?php else: ?><div class="workflow-hint hint-good">Phòng mới được tạo ở trạng thái “Sẵn sàng”. Bạn có thể chuyển sang bảo trì hoặc ngừng hoạt động tại danh sách phòng.</div><?php endif; ?>
    <div class="form-footer"><a class="btn-reset" href="<?= BASE_URL ?>/admin/rooms">Hủy</a><button class="btn-primary" type="submit"><?= $room ? 'Lưu thay đổi' : 'Tạo phòng' ?></button></div>
</form>

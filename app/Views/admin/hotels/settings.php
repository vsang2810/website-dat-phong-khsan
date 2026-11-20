<?php $v = static fn(string $key, string $default = ''): string => htmlspecialchars((string)($hotel[$key] ?? $default), ENT_QUOTES, 'UTF-8'); ?>
<div class="page-heading">
    <div>
        <span class="eyebrow">HOTEL PROFILE</span>
        <h1>Thông tin khách sạn</h1>
        <p class="muted">Cấu hình thông tin thương hiệu, địa chỉ, hotline, giờ nhận/trả phòng của LOTTE HOTEL HANOI.</p>
    </div>
    <a class="btn-reset back-link" href="<?= BASE_URL ?>/admin/room-types">← Xem các hạng phòng</a>
</div>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert-success"><?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert-error"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<form class="panel management-form" method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="id" value="<?= (int)($hotel['id'] ?? 1) ?>">

    <div class="form-section-title">
        <h2>Thông tin chung</h2>
        <p>Thông tin này được hiển thị công khai trên trang chủ và trang liên hệ khách hàng.</p>
    </div>

    <div class="form-grid">
        <div class="wide" style="border: 1px dashed var(--line); border-radius: 8px; padding: 18px; background: #fafafa; margin-bottom: 12px;">
            <span style="font-weight: 700; font-size: 13px; display: block; margin-bottom: 10px;">Hình ảnh khách sạn (lotte-hotel.jpg)</span>
            <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                <?php if (!empty($hotel['thumbnail'])): ?>
                    <div>
                        <small style="display: block; color: var(--muted); margin-bottom: 6px;">Ảnh hiện tại:</small>
                        <img src="<?= htmlspecialchars(asset_url($hotel['thumbnail'])) ?>" alt="Hotel Preview" style="width: 180px; height: 110px; object-fit: cover; border-radius: 6px; border: 1px solid var(--line);">
                    </div>
                <?php endif; ?>

                <div style="flex: 1; min-width: 260px;">
                    <label style="margin-bottom: 10px; display: block;">
                        <span style="font-size: 12px; color: var(--ink);">Tải lên ảnh khách sạn mới từ máy tính:</span>
                        <input type="file" name="thumbnail_file" accept="image/*" style="padding: 6px; border: 1px solid var(--line); border-radius: 4px; width: 100%; background: #fff;">
                    </label>

                    <label style="display: block;">
                        <span style="font-size: 12px; color: var(--muted);">Hoặc đường dẫn / URL ảnh:</span>
                        <input name="thumbnail" value="<?= $v('thumbnail') ?>" placeholder="assets/images/hotel/lotte-hotel.jpg" style="padding: 8px 12px; border: 1px solid var(--line); border-radius: 4px; width: 100%;">
                    </label>
                </div>
            </div>
        </div>
        <label class="wide">
            <span>Tên khách sạn *</span>
            <input required maxlength="200" name="name" value="<?= $v('name') ?>" placeholder="LOTTE HOTEL HANOI">
        </label>

        <label class="wide">
            <span>Địa chỉ chi tiết *</span>
            <input required maxlength="255" name="address" value="<?= $v('address') ?>" placeholder="54 Liễu Giai, Ba Đình">
        </label>

        <label>
            <span>Thành phố</span>
            <input maxlength="100" name="city" value="<?= $v('city') ?>" placeholder="Hà Nội">
        </label>

        <label>
            <span>Quốc gia</span>
            <input maxlength="100" name="country" value="<?= $v('country', 'Vietnam') ?>">
        </label>

        <label>
            <span>Số điện thoại / Hotline</span>
            <input maxlength="20" name="phone" value="<?= $v('phone') ?>" placeholder="02433331000">
        </label>

        <label>
            <span>Email liên hệ</span>
            <input type="email" maxlength="150" name="email" value="<?= $v('email') ?>" placeholder="info@lottehotel.vn">
        </label>

        <label>
            <span>Xếp hạng sao (1 - 5)</span>
            <input type="number" min="1" max="5" name="star_rating" value="<?= $v('star_rating', '5') ?>">
        </label>


        <label>
            <span>Giờ nhận phòng tiêu chuẩn</span>
            <input type="time" name="check_in_time" value="<?= substr($v('check_in_time', '14:00'), 0, 5) ?>">
        </label>

        <label>
            <span>Giờ trả phòng tiêu chuẩn</span>
            <input type="time" name="check_out_time" value="<?= substr($v('check_out_time', '12:00'), 0, 5) ?>">
        </label>

        <label class="wide">
            <span>Giới thiệu tổng quan khách sạn</span>
            <textarea name="description" rows="4" placeholder="Mô tả phong cách, tiện nghi và không gian khách sạn..."><?= $v('description') ?></textarea>
        </label>
    </div>

    <div class="form-footer">
        <a class="btn-reset" href="<?= BASE_URL ?>/admin/room-types">Quay lại Hạng phòng</a>
        <button class="btn-primary" type="submit">Lưu thông tin khách sạn</button>
    </div>
</form>

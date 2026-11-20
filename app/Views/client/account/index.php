<div class="container" style="padding-top: 24px; padding-bottom: 70px;">
    <!-- BREADCRUMB -->
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/">Trang chủ</a> <span>/</span> <strong>Tài khoản của tôi</strong>
    </div>

    <div class="account-layout">
        <!-- SIDEBAR -->
        <div>
            <div class="account-nav">
                <div class="account-user-card">
                    <div class="account-user-avatar">
                        <?= htmlspecialchars(mb_strtoupper(mb_substr($customer['full_name'] ?? 'U', 0, 1))) ?>
                    </div>
                    <strong style="display: block; font-size: 16px; margin-bottom: 2px; color: var(--dark);">
                        <?= htmlspecialchars($customer['full_name'] ?? '') ?>
                    </strong>
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">
                        <?= htmlspecialchars($customer['email'] ?? '') ?>
                    </span>
                    <span class="status-badge status-confirmed" style="margin-top: 8px; font-size: 10px;">
                        Khách hàng thành viên
                    </span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <a href="<?= BASE_URL ?>/account?tab=bookings" class="account-nav-link <?= ($activeTab === 'bookings') ? 'active' : '' ?>">
                        📋 Lịch sử đặt phòng
                    </a>
                    <a href="<?= BASE_URL ?>/account?tab=profile" class="account-nav-link <?= ($activeTab === 'profile') ? 'active' : '' ?>">
                        👤 Thông tin cá nhân
                    </a>
                    <a href="<?= BASE_URL ?>/account?tab=password" class="account-nav-link <?= ($activeTab === 'password') ? 'active' : '' ?>">
                        🔒 Đổi mật khẩu
                    </a>
                    <a href="<?= BASE_URL ?>/account?tab=wishlist" class="account-nav-link <?= ($activeTab === 'wishlist') ? 'active' : '' ?>">
                        ❤️ Phòng yêu thích (<?= count($wishlist) ?>)
                    </a>
                    <?php if (Auth::isAdmin()): ?>
                        <div style="height: 1px; background: var(--border-light); margin: 6px 0;"></div>
                        <a href="<?= BASE_URL ?>/admin" class="account-nav-link" style="color: var(--primary); font-weight: 600;">
                            ⚙ Trang Quản trị hệ thống
                        </a>
                    <?php endif; ?>
                    <div style="height: 1px; background: var(--border-light); margin: 6px 0;"></div>
                    <form action="<?= BASE_URL ?>/logout" method="post" style="margin: 0;">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
                        <button type="submit" class="account-nav-link" style="width: 100%; border: none; background: none; color: var(--danger); cursor: pointer; text-align: left;">
                            ↪ Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- MAIN TAB CONTENT -->
        <div>
            <!-- TAB 1: BOOKING HISTORY -->
            <?php if ($activeTab === 'bookings'): ?>
                <div class="checkout-card" style="box-shadow: var(--shadow-sm);">
                    <div class="checkout-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0;">Lịch sử đơn đặt phòng (<?= count($bookings) ?>)</h3>
                        <a href="<?= BASE_URL ?>/rooms" class="btn btn-primary btn-sm">+ Đặt thêm phòng</a>
                    </div>

                    <?php if (empty($bookings)): ?>
                        <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                            <div style="font-size: 32px; margin-bottom: 10px;">🛎</div>
                            <p>Quý khách chưa có đơn đặt phòng nào.</p>
                            <a href="<?= BASE_URL ?>/rooms" class="btn btn-outline btn-sm">Khám phá các hạng phòng ngay</a>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="account-table">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Hạng phòng / Khách sạn</th>
                                        <th>Lưu trú</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th style="text-align: right;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $b): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= BASE_URL ?>/booking/success?code=<?= urlencode($b['booking_code']) ?>" style="font-weight: 700; color: var(--primary);">
                                                    <?= htmlspecialchars($b['booking_code']) ?>
                                                </a>
                                                <small style="display: block; font-size: 11px; color: var(--text-muted);"><?= date('d/m/Y H:i', strtotime($b['created_at'])) ?></small>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($b['room_name'] ?? 'Phòng khách sạn') ?></strong>
                                                <small style="display: block; color: var(--text-muted); font-size: 11px;"><?= htmlspecialchars($b['hotel_name'] ?? 'LOTTE HOTEL') ?></small>
                                            </td>
                                            <td>
                                                <div><?= date('d/m/Y', strtotime($b['check_in'])) ?> → <?= date('d/m/Y', strtotime($b['check_out'])) ?></div>
                                                <small style="color: var(--text-muted);"><?= (int)$b['number_of_nights'] ?> đêm · <?= (int)$b['total_guests'] ?> khách</small>
                                            </td>
                                            <td>
                                                <strong><?= number_format((float)$b['total_amount'], 0, ',', '.') ?> ₫</strong>
                                                <small style="display: block; font-size: 11px; color: var(--text-muted);">
                                                    <?= $b['payment_method'] === 'online' ? 'Online' : 'Tại quầy' ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if ($b['booking_status'] === 'confirmed'): ?>
                                                    <span class="status-badge status-confirmed">ĐÃ XÁC NHẬN</span>
                                                <?php elseif ($b['booking_status'] === 'pending_hotel_payment'): ?>
                                                    <span class="status-badge status-pending_hotel_payment">CHỜ THANH TOÁN</span>
                                                <?php elseif ($b['booking_status'] === 'checked_in'): ?>
                                                    <span class="status-badge status-checked_in">ĐÃ NHẬN PHÒNG</span>
                                                <?php elseif ($b['booking_status'] === 'completed'): ?>
                                                    <span class="status-badge status-completed">HOÀN TẤT</span>
                                                <?php elseif ($b['booking_status'] === 'cancelled'): ?>
                                                    <span class="status-badge status-cancelled">ĐÃ HỦY</span>
                                                <?php else: ?>
                                                    <span class="status-badge status-pending"><?= htmlspecialchars($b['booking_status']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                                    <a href="<?= BASE_URL ?>/booking/success?code=<?= urlencode($b['booking_code']) ?>" class="btn btn-outline btn-sm" title="Xem phiếu đặt">
                                                        👁 Xem
                                                    </a>

                                                    <?php
                                                        $canCancel = in_array($b['booking_status'], ['pending_payment', 'pending_hotel_payment', 'confirmed'], true)
                                                            && strtotime($b['check_in']) > strtotime(date('Y-m-d'));
                                                    ?>
                                                    <?php if ($canCancel): ?>
                                                        <form action="<?= BASE_URL ?>/booking/cancel" method="post" onsubmit="return confirm('Bạn có chắc muốn hủy đơn đặt phòng này?');" style="margin: 0;">
                                                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
                                                            <input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>">
                                                            <input type="hidden" name="redirect" value="/account?tab=bookings">
                                                            <button type="submit" class="btn btn-danger-outline btn-sm" title="Hủy đơn">
                                                                ✕
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            <!-- TAB 2: PROFILE -->
            <?php elseif ($activeTab === 'profile'): ?>
                <div class="checkout-card" style="box-shadow: var(--shadow-sm);">
                    <div class="checkout-card-header">
                        <h3 style="margin: 0;">Cập nhật thông tin cá nhân</h3>
                    </div>

                    <form action="<?= BASE_URL ?>/account/profile" method="post">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label" for="profileName">Họ và tên *</label>
                                <input type="text" name="full_name" id="profileName" class="form-control" value="<?= htmlspecialchars($customer['full_name'] ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="profileEmail">Địa chỉ email (Không thể thay đổi)</label>
                                <input type="email" id="profileEmail" class="form-control" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" disabled style="background: var(--bg-main); color: var(--text-muted);">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                            <div class="form-group">
                                <label class="form-label" for="profilePhone">Số điện thoại liên hệ</label>
                                <input type="tel" name="phone" id="profilePhone" class="form-control" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="profileGender">Giới tính</label>
                                <select name="gender" id="profileGender" class="form-select">
                                    <option value="male" <?= (($customer['gender'] ?? '') === 'male') ? 'selected' : '' ?>>Nam</option>
                                    <option value="female" <?= (($customer['gender'] ?? '') === 'female') ? 'selected' : '' ?>>Nữ</option>
                                    <option value="other" <?= (($customer['gender'] ?? '') === 'other') ? 'selected' : '' ?>>Khác</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="padding: 12px 28px;">
                            Lưu thay đổi thông tin
                        </button>
                    </form>
                </div>

            <!-- TAB 3: PASSWORD -->
            <?php elseif ($activeTab === 'password'): ?>
                <div class="checkout-card" style="box-shadow: var(--shadow-sm); max-width: 500px;">
                    <div class="checkout-card-header">
                        <h3 style="margin: 0;">Đổi mật khẩu tài khoản</h3>
                    </div>

                    <form action="<?= BASE_URL ?>/account/password" method="post">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label" for="currentPassword">Mật khẩu hiện tại *</label>
                            <input type="password" name="current_password" id="currentPassword" class="form-control" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label" for="newPassword">Mật khẩu mới *</label>
                            <input type="password" name="new_password" id="newPassword" class="form-control" minlength="6" required>
                            <small style="font-size: 11px; color: var(--text-muted);">Tối thiểu 6 ký tự</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 24px;">
                            <label class="form-label" for="confirmNewPassword">Xác nhận mật khẩu mới *</label>
                            <input type="password" name="new_password_confirmation" id="confirmNewPassword" class="form-control" minlength="6" required>
                        </div>

                        <button type="submit" class="btn btn-primary" style="padding: 12px 28px;">
                            Cập nhật mật khẩu
                        </button>
                    </form>
                </div>

            <!-- TAB 4: WISHLIST -->
            <?php elseif ($activeTab === 'wishlist'): ?>
                <div class="checkout-card" style="box-shadow: var(--shadow-sm);">
                    <div class="checkout-card-header">
                        <h3 style="margin: 0;">Danh sách phòng đã lưu (<?= count($wishlist) ?>)</h3>
                    </div>

                    <?php if (empty($wishlist)): ?>
                        <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                            <div style="font-size: 32px; margin-bottom: 10px;">♥</div>
                            <p>Quý khách chưa lưu phòng yêu thích nào.</p>
                            <a href="<?= BASE_URL ?>/rooms" class="btn btn-outline btn-sm">Khám phá ngay</a>
                        </div>
                    <?php else: ?>
                        <div class="rooms-grid" style="grid-template-columns: repeat(2, 1fr);">
                            <?php foreach ($wishlist as $room): ?>
                                <div class="room-card">
                                    <div class="room-image-wrap" style="height: 180px;">
                                        <img src="<?= htmlspecialchars(asset_url($room['thumbnail'] ?? '')) ?>" alt="<?= htmlspecialchars($room['name']) ?>" class="room-image" onerror="this.src='<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>'">
                                        <div class="room-price-badge">
                                            <?= number_format((float)$room['base_price'], 0, ',', '.') ?> ₫
                                        </div>
                                    </div>
                                    <div class="room-card-body" style="padding: 18px;">
                                        <h4 style="margin: 0 0 6px; font-size: 17px;"><?= htmlspecialchars($room['name']) ?></h4>
                                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 14px;">
                                            📐 <?= (float)$room['area'] ?> m² · 🛏 <?= htmlspecialchars($room['bed_type']) ?>
                                        </div>
                                        <div class="room-card-footer">
                                            <a href="<?= BASE_URL ?>/room-detail?id=<?= (int)$room['id'] ?>" class="btn btn-outline btn-sm btn-block">Xem chi tiết</a>
                                            <a href="<?= BASE_URL ?>/booking?room_type_id=<?= (int)$room['id'] ?>" class="btn btn-primary btn-sm btn-block">Đặt phòng</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container" style="padding-top: 24px; padding-bottom: 70px;">
    <!-- BREADCRUMB -->
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/">Trang chủ</a> <span>/</span>
        <a href="<?= BASE_URL ?>/rooms">Phòng nghỉ</a> <span>/</span>
        <strong>Đặt phòng trực tuyến</strong>
    </div>

    <!-- STEPPER -->
    <div class="booking-stepper">
        <div class="step-item active">
            <span class="step-number">1</span>
            <span>Chọn phòng &amp; Ngày</span>
        </div>
        <div class="step-item active">
            <span class="step-number">2</span>
            <span>Dịch vụ &amp; Ưu đãi</span>
        </div>
        <div class="step-item active">
            <span class="step-number">3</span>
            <span>Thông tin &amp; Thanh toán</span>
        </div>
    </div>

    <form id="bookingForm" action="<?= BASE_URL ?>/booking/checkout" method="post">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
        <input type="hidden" name="room_type_id" value="<?= (int)$room['id'] ?>">

        <div class="checkout-grid">
            <!-- LEFT COLUMN: FORM DETAILS -->
            <div>
                <!-- 1. ROOM & DATES CONFIRMATION -->
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h3>1. Hạng phòng &amp; Thời gian lưu trú</h3>
                    </div>

                    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border-light);">
                        <img src="<?= htmlspecialchars(asset_url($room['thumbnail'] ?? '')) ?>" alt="<?= htmlspecialchars($room['name']) ?>" style="width: 120px; height: 80px; object-fit: cover; border-radius: var(--radius-sm);" onerror="this.src='<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>'">
                        <div>
                            <span class="room-card-hotel"><?= htmlspecialchars($room['hotel_name']) ?></span>
                            <h4 style="margin: 0 0 6px; font-size: 18px;"><?= htmlspecialchars($room['name']) ?></h4>
                            <div class="room-specs tabular-nums" style="margin-bottom: 0;">
                                <span class="room-spec-item">
                                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                    <span><?= (float)$room['area'] ?> m²</span>
                                </span>
                                <span class="room-spec-item">
                                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                                    <span><?= htmlspecialchars($room['bed_type']) ?></span>
                                </span>
                                <span class="room-spec-item">
                                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <span>Tối đa <?= (int)$room['max_guests'] ?> khách</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label" for="bookingCheckIn">Ngày nhận phòng</label>
                            <input type="date" name="check_in" id="bookingCheckIn" class="form-control" value="<?= htmlspecialchars($checkIn) ?>" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="bookingCheckOut">Ngày trả phòng</label>
                            <input type="date" name="check_out" id="bookingCheckOut" class="form-control" value="<?= htmlspecialchars($checkOut) ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="bookingGuests">Số lượng khách</label>
                            <select name="total_guests" id="bookingGuests" class="form-select">
                                <?php for ($i = 1; $i <= (int)$room['max_guests']; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($guests == $i) ? 'selected' : '' ?>><?= $i ?> khách</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. EXTRA SERVICES -->
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h3>2. Chọn thêm dịch vụ đặc quyền (Tùy chọn)</h3>
                    </div>

                    <?php foreach ($services as $srv): ?>
                        <div class="service-check-item">
                            <div class="service-check-info">
                                <input type="checkbox" name="services[<?= (int)$srv['id'] ?>]" id="srv_<?= (int)$srv['id'] ?>" value="<?= (int)$srv['id'] ?>" class="js-service-check" data-price="<?= (float)$srv['price'] ?>" style="width: 18px; height: 18px; cursor: pointer;">
                                <label for="srv_<?= (int)$srv['id'] ?>" style="cursor: pointer; margin: 0;">
                                    <strong><?= htmlspecialchars($srv['name']) ?></strong>
                                    <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($srv['description']) ?></div>
                                </label>
                            </div>

                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div id="qty_wrap_<?= (int)$srv['id'] ?>" style="display: none; align-items: center; gap: 6px;">
                                    <span style="font-size: 12px; color: var(--text-muted);">Số lượng:</span>
                                    <input type="number" name="service_qty[<?= (int)$srv['id'] ?>]" id="service_qty_<?= (int)$srv['id'] ?>" value="1" min="1" max="10" class="form-control js-service-qty" style="width: 60px; height: 34px; padding: 4px 8px; text-align: center;">
                                </div>
                                <span class="service-check-price">+<?= number_format((float)$srv['price'], 0, ',', '.') ?> ₫</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- 3. PROMOTIONS & VOUCHERS -->
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h3>3. Mã ưu đãi &amp; Khuyến mãi</h3>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="flex: 1;">
                            <input type="text" name="promotion_code" id="voucherCodeInput" class="form-control" placeholder="Nhập mã ưu đãi (Ví dụ: WELCOME10)" style="text-transform: uppercase;">
                            <div id="voucherMessage" style="font-size: 12px; margin-top: 6px;"></div>
                        </div>
                        <button type="button" id="applyVoucherBtn" class="btn btn-outline" style="height: 48px; padding: 0 24px;">
                            Áp dụng
                        </button>
                    </div>

                    <?php if (!empty($promotions)): ?>
                        <div style="margin-top: 14px; font-size: 12px; color: var(--text-muted);">
                            Mã gợi ý hôm nay:
                            <?php foreach ($promotions as $p): ?>
                                <span style="display: inline-block; background: var(--primary-soft); color: var(--primary); padding: 2px 8px; border-radius: 4px; font-weight: 700; margin-left: 4px; cursor: pointer;" onclick="document.getElementById('voucherCodeInput').value='<?= htmlspecialchars($p['code']) ?>'; document.getElementById('applyVoucherBtn').click();">
                                    <?= htmlspecialchars($p['code']) ?> (-<?= (int)$p['discount_value'] ?><?= $p['discount_type'] === 'percentage' ? '%' : 'đ' ?>)
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 4. GUEST INFORMATION -->
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h3>4. Thông tin người đặt phòng</h3>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div class="form-group">
                            <label class="form-label" for="guestName">Họ và tên quý khách *</label>
                            <input type="text" name="guest_name" id="guestName" class="form-control" value="<?= htmlspecialchars($currentUser['full_name'] ?? '') ?>" placeholder="Nguyễn Văn A" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="guestEmail">Địa chỉ email nhận xác nhận *</label>
                            <input type="email" name="guest_email" id="guestEmail" class="form-control" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" placeholder="name@example.com" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 16px;">
                        <div class="form-group">
                            <label class="form-label" for="guestPhone">Số điện thoại liên hệ *</label>
                            <input type="tel" name="guest_phone" id="guestPhone" class="form-control" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>" placeholder="0901234567" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="specialRequest">Yêu cầu đặc biệt (Không bắt buộc)</label>
                        <textarea name="special_request" id="specialRequest" class="form-control" rows="3" placeholder="Ví dụ: Phòng tầng cao, 2 giường đơn, yêu cầu nôi em bé, check-in muộn..."></textarea>
                    </div>
                </div>

                <!-- 5. PAYMENT METHODS -->
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h3>5. Phương thức thanh toán</h3>
                    </div>

                    <div class="payment-methods-grid">
                        <label class="payment-radio-card active">
                            <div class="payment-radio-title">
                                <input type="radio" name="payment_method" value="online" checked>
                                <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                <span>Thanh toán trực tuyến</span>
                            </div>
                            <div class="payment-radio-desc">
                                Thanh toán nhanh qua Cổng chuyển khoản ngân hàng / Quét mã VietQR / Thẻ ATM, Visa, Master. Xác nhận đơn tự động tức thì.
                            </div>
                        </label>

                        <label class="payment-radio-card">
                            <div class="payment-radio-title">
                                <input type="radio" name="payment_method" value="pay_at_hotel">
                                <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/></svg>
                                <span>Thanh toán tại quầy</span>
                            </div>
                            <div class="payment-radio-desc">
                                Thanh toán bằng tiền mặt hoặc thẻ tín dụng khi quý khách đến làm thủ tục nhận phòng tại khách sạn.
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: STICKY ORDER SUMMARY -->
            <div>
                <div class="sticky-booking-card">
                    <h3 style="font-size: 18px; margin: 0 0 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">
                        Tóm tắt đơn đặt phòng
                    </h3>

                    <div style="margin-bottom: 16px;">
                        <strong style="display: block; font-size: 16px; color: var(--dark);"><?= htmlspecialchars($room['name']) ?></strong>
                        <span style="font-size: 12px; color: var(--primary);"><?= htmlspecialchars($room['hotel_name']) ?></span>
                    </div>

                    <div class="booking-calc-row">
                        <span>Giá phòng mỗi đêm:</span>
                        <strong id="calcBasePrice" data-price="<?= (float)$room['base_price'] ?>">
                            <?= number_format((float)$room['base_price'], 0, ',', '.') ?> ₫
                        </strong>
                    </div>

                    <div class="booking-calc-row">
                        <span>Thời gian lưu trú:</span>
                        <span><strong id="calcNights"><?= $nights ?></strong> đêm</span>
                    </div>

                    <div class="booking-calc-row">
                        <span>Tiền phòng tạm tính:</span>
                        <strong id="calcRoomSubtotal"><?= number_format((float)$room['base_price'] * $nights, 0, ',', '.') ?> ₫</strong>
                    </div>

                    <div class="booking-calc-row">
                        <span>Dịch vụ gia tăng:</span>
                        <strong id="calcServiceSubtotal">0 ₫</strong>
                    </div>

                    <div class="booking-calc-row" style="color: var(--success);">
                        <span>Chiết khấu ưu đãi:</span>
                        <strong id="calcDiscount">0 ₫</strong>
                    </div>

                    <div class="booking-calc-row total">
                        <span>Tổng thanh toán:</span>
                        <span style="font-size: 22px; color: var(--primary); font-family: var(--font-serif);" id="calcGrandTotal">
                            <?= number_format((float)$room['base_price'] * $nights, 0, ',', '.') ?> ₫
                        </span>
                    </div>

                    <div style="margin: 20px 0; font-size: 12px; color: var(--text-muted); line-height: 1.6; display: grid; gap: 6px;">
                        <div style="display:flex;align-items:center;gap:8px;"><svg class="ui-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><span>Giá đã bao gồm thuế VAT &amp; phí phục vụ</span></div>
                        <div style="display:flex;align-items:center;gap:8px;"><svg class="ui-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><span>Miễn phí WiFi tốc độ cao</span></div>
                        <div style="display:flex;align-items:center;gap:8px;"><svg class="ui-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><span>Hỗ trợ hủy phòng linh hoạt trước ngày nhận</span></div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 52px; font-size: 16px;">
                        Xác nhận đặt phòng
                    </button>

                    <div style="margin-top: 14px; display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 12px; color: var(--text-light);">
                        <svg class="ui-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Giao dịch được bảo mật</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

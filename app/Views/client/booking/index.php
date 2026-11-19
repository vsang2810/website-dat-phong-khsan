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
                        <img src="<?= BASE_URL . '/' . htmlspecialchars($room['thumbnail'] ?: 'assets/images/hotel-banner.png') ?>" alt="<?= htmlspecialchars($room['name']) ?>" style="width: 120px; height: 80px; object-fit: cover; border-radius: var(--radius-sm);" onerror="this.src='<?= BASE_URL ?>/assets/images/hotel-banner.png'">
                        <div>
                            <span class="room-card-hotel"><?= htmlspecialchars($room['hotel_name']) ?></span>
                            <h4 style="margin: 0 0 6px; font-size: 18px;"><?= htmlspecialchars($room['name']) ?></h4>
                            <div style="font-size: 13px; color: var(--text-muted);">
                                <span>📐 <?= (float)$room['area'] ?> m²</span> ·
                                <span>🛏 <?= htmlspecialchars($room['bed_type']) ?></span> ·
                                <span>👥 Tối đa <?= (int)$room['max_guests'] ?> khách</span>
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
                                💳 Thanh toán trực tuyến
                            </div>
                            <div class="payment-radio-desc">
                                Thanh toán nhanh qua Cổng chuyển khoản ngân hàng / Quét mã VietQR / Thẻ ATM, Visa, Master. Xác nhận đơn tự động tức thì.
                            </div>
                        </label>

                        <label class="payment-radio-card">
                            <div class="payment-radio-title">
                                <input type="radio" name="payment_method" value="pay_at_hotel">
                                🏨 Thanh toán tại quầy
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

                    <div style="margin: 20px 0; font-size: 12px; color: var(--text-muted); line-height: 1.5;">
                        ✓ Giá đã bao gồm thuế VAT &amp; phí phục vụ<br>
                        ✓ Miễn phí WiFi tốc độ cao<br>
                        ✓ Hỗ trợ hủy phòng linh hoạt trước ngày nhận
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 52px; font-size: 16px;">
                        Xác nhận đặt phòng ngay →
                    </button>

                    <div style="margin-top: 14px; text-align: center; font-size: 11px; color: var(--text-light);">
                        🔒 Giao dịch an toàn và bảo mật 100%
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

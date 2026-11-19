<div class="container-narrow" style="padding-top: 30px; padding-bottom: 70px;">
    <!-- BREADCRUMB -->
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/">Trang chủ</a> <span>/</span> <strong>Tra cứu đơn đặt phòng</strong>
    </div>

    <div class="section-header" style="margin-bottom: 30px; text-align: left;">
        <span class="section-eyebrow">BOOKING INQUIRY</span>
        <h1 style="font-size: 32px; margin: 0 0 8px;">Tra Cứu Đơn Đặt Phòng</h1>
        <p class="section-desc" style="margin: 0;">
            Quý khách có thể kiểm tra trạng thái phòng, thời gian nhận phòng hoặc yêu cầu hủy phòng bằng mã đặt phòng và Email/SĐT đăng ký.
        </p>
    </div>

    <!-- SEARCH FORM -->
    <div class="checkout-card" style="box-shadow: var(--shadow-sm); margin-bottom: 30px;">
        <form action="<?= BASE_URL ?>/booking/lookup" method="get">
            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: end;">
                <div class="form-group">
                    <label class="form-label" for="lookupCode">Mã đặt phòng *</label>
                    <input type="text" name="code" id="lookupCode" class="form-control" placeholder="Ví dụ: LOTTE202611001" value="<?= htmlspecialchars($code ?? '') ?>" style="text-transform: uppercase;" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="lookupContact">Email hoặc Số điện thoại *</label>
                    <input type="text" name="contact" id="lookupContact" class="form-control" placeholder="Email hoặc SĐT đặt phòng" value="<?= htmlspecialchars($contact ?? '') ?>" required>
                </div>

                <button type="submit" class="btn btn-primary" style="height: 48px; padding: 0 28px;">
                    Tra cứu ngay
                </button>
            </div>
        </form>
    </div>

    <!-- SEARCH RESULT -->
    <?php if ($booking): ?>
        <div class="receipt-card" style="margin-top: 20px;">
            <div class="receipt-top" style="padding: 24px; text-align: left; display: flex; justify-content: space-between; align-items: center; flex-wrap: gap: 14px;">
                <div>
                    <span style="font-size: 12px; color: var(--gold); letter-spacing: 1px; text-transform: uppercase;">CHI TIẾT ĐƠN ĐẶT</span>
                    <h2 style="color: #fff; font-size: 22px; margin: 4px 0 0;"><?= htmlspecialchars($booking['booking_code']) ?></h2>
                </div>

                <div>
                    <?php if ($booking['booking_status'] === 'confirmed'): ?>
                        <span class="status-badge status-confirmed">ĐÃ XÁC NHẬN</span>
                    <?php elseif ($booking['booking_status'] === 'pending_hotel_payment'): ?>
                        <span class="status-badge status-pending_hotel_payment">CHỜ THANH TOÁN TẠI KHÁCH SẠN</span>
                    <?php elseif ($booking['booking_status'] === 'checked_in'): ?>
                        <span class="status-badge status-checked_in">ĐÃ NHẬN PHÒNG</span>
                    <?php elseif ($booking['booking_status'] === 'completed'): ?>
                        <span class="status-badge status-completed">HOÀN TẤT KỲ NGHỈ</span>
                    <?php elseif ($booking['booking_status'] === 'cancelled'): ?>
                        <span class="status-badge status-cancelled">ĐÃ HỦY ĐƠN</span>
                    <?php else: ?>
                        <span class="status-badge status-pending"><?= htmlspecialchars($booking['booking_status']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="receipt-body">
                <div class="receipt-details-grid">
                    <div class="receipt-item">
                        <label>Khách hàng:</label>
                        <strong><?= htmlspecialchars($booking['guest_name']) ?></strong>
                    </div>
                    <div class="receipt-item">
                        <label>Khách sạn:</label>
                        <strong><?= htmlspecialchars($booking['hotel_name']) ?></strong>
                    </div>
                    <div class="receipt-item">
                        <label>Ngày nhận phòng:</label>
                        <strong><?= date('d/m/Y', strtotime($booking['check_in'])) ?> (14:00)</strong>
                    </div>
                    <div class="receipt-item">
                        <label>Ngày trả phòng:</label>
                        <strong><?= date('d/m/Y', strtotime($booking['check_out'])) ?> (12:00)</strong>
                    </div>
                    <div class="receipt-item">
                        <label>Thời gian lưu trú:</label>
                        <strong><?= (int)$booking['number_of_nights'] ?> đêm</strong> · <?= (int)$booking['total_guests'] ?> khách
                    </div>
                    <div class="receipt-item">
                        <label>Tổng thanh toán:</label>
                        <strong style="color: var(--primary); font-size: 16px;"><?= number_format((float)$booking['total_amount'], 0, ',', '.') ?> ₫</strong>
                    </div>
                </div>

                <!-- ROOMS & SERVICES TABLE -->
                <table class="receipt-table" style="margin-bottom: 24px;">
                    <thead>
                        <tr>
                            <th>Phòng &amp; Dịch vụ</th>
                            <th style="text-align: right;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($booking['rooms'])): ?>
                            <?php foreach ($booking['rooms'] as $r): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($r['room_name_snapshot'] ?: $r['room_type_name']) ?></strong>
                                        <?php if (!empty($r['room_number_snapshot'])): ?>
                                            <span style="color: var(--primary); font-size: 12px; margin-left: 8px;">(Phòng: <?= htmlspecialchars($r['room_number_snapshot']) ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: right;"><?= number_format((float)$r['subtotal'], 0, ',', '.') ?> ₫</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!empty($booking['services'])): ?>
                            <?php foreach ($booking['services'] as $srv): ?>
                                <tr>
                                    <td><?= htmlspecialchars($srv['service_name']) ?> (x<?= (int)$srv['quantity'] ?>)</td>
                                    <td style="text-align: right;"><?= number_format((float)$srv['subtotal'], 0, ',', '.') ?> ₫</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- CANCELLATION OPTION -->
                <?php
                    $isCancellable = in_array($booking['booking_status'], ['pending_payment', 'pending_hotel_payment', 'confirmed'], true)
                        && strtotime($booking['check_in']) > strtotime(date('Y-m-d'));
                ?>

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-light); padding-top: 20px; flex-wrap: wrap; gap: 14px;">
                    <a href="<?= BASE_URL ?>/booking/success?code=<?= urlencode($booking['booking_code']) ?>" class="btn btn-outline">
                        🖨 Xem phiếu đặt phòng chi tiết
                    </a>

                    <?php if ($isCancellable): ?>
                        <form action="<?= BASE_URL ?>/booking/cancel" method="post" onsubmit="return confirm('Quý khách có chắc chắn muốn hủy đơn đặt phòng này không? Thao tác này không thể hoàn tác.');">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
                            <input type="hidden" name="booking_id" value="<?= (int)$booking['id'] ?>">
                            <input type="hidden" name="code" value="<?= htmlspecialchars($booking['booking_code']) ?>">
                            <input type="hidden" name="contact" value="<?= htmlspecialchars($contact) ?>">
                            <input type="hidden" name="reason" value="Khách hủy từ trang tra cứu">
                            <button type="submit" class="btn btn-danger-outline">
                                ✕ Yêu cầu hủy đơn phòng
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php elseif ($searched): ?>
        <!-- Handled by flash error, can show support box -->
        <div style="background: #fff; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 30px; text-align: center;">
            <p style="color: var(--text-muted); margin-bottom: 14px;">Quý khách cần hỗ trợ kiểm tra lại thông tin đơn hàng? Vui lòng gọi trực tiếp hotline lễ tân:</p>
            <a href="tel:02433331000" class="btn btn-primary">📞 024 3333 1000</a>
        </div>
    <?php endif; ?>
</div>

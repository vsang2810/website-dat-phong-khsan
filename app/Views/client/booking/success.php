<div class="container confirmation-wrap">
    <div class="receipt-card">
        <!-- TOP HEADER -->
        <div class="receipt-top">
            <div class="receipt-badge-success">
                <svg class="ui-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h1 style="color: #fff; font-size: 28px; margin: 0 0 8px;">Đặt phòng thành công</h1>
            <p style="color: #c9cbcd; font-size: 14px; margin: 0;">
                Cảm ơn quý khách đã tin tưởng và lựa chọn LOTTE HOTEL HANOI. Chúng tôi đã gửi thông tin xác nhận qua email của quý khách.
            </p>
            <div class="receipt-code-highlight">
                Mã đặt phòng: <?= htmlspecialchars($booking['booking_code']) ?>
            </div>
        </div>

        <!-- RECEIPT BODY -->
        <div class="receipt-body">
            <!-- 1. GUEST & STAY DETAILS -->
            <div class="receipt-section-title">Thông tin kỳ nghỉ &amp; Khách lưu trú</div>
            <div class="receipt-details-grid">
                <div class="receipt-item">
                    <label>Khách sạn:</label>
                    <strong><?= htmlspecialchars($booking['hotel_name']) ?></strong>
                    <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($booking['hotel_address'] ?? '54 Liễu Giai, Ba Đình, Hà Nội') ?></div>
                </div>
                <div class="receipt-item">
                    <label>Họ và tên khách hàng:</label>
                    <strong><?= htmlspecialchars($booking['guest_name']) ?></strong>
                </div>
                <div class="receipt-item">
                    <label>Ngày nhận phòng (Check-in):</label>
                    <strong><?= date('d/m/Y', strtotime($booking['check_in'])) ?></strong> (từ 14:00)
                </div>
                <div class="receipt-item">
                    <label>Ngày trả phòng (Check-out):</label>
                    <strong><?= date('d/m/Y', strtotime($booking['check_out'])) ?></strong> (trước 12:00)
                </div>
                <div class="receipt-item">
                    <label>Thời gian lưu trú:</label>
                    <strong><?= (int)$booking['number_of_nights'] ?> đêm</strong> · <?= (int)$booking['total_guests'] ?> khách
                </div>
                <div class="receipt-item">
                    <label>Thông tin liên lạc:</label>
                    <div><?= htmlspecialchars($booking['guest_phone']) ?></div>
                    <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($booking['guest_email']) ?></div>
                </div>
            </div>

            <!-- 2. ROOM & PRICING TABLE -->
            <div class="receipt-section-title">Chi tiết phòng &amp; Dịch vụ</div>
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Hạng mục</th>
                        <th style="text-align: center;">Số lượng</th>
                        <th style="text-align: right;">Đơn giá</th>
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
                                        <div style="font-size: 11px; color: var(--primary);">Phòng số: <strong><?= htmlspecialchars($r['room_number_snapshot']) ?></strong></div>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;"><?= (int)$booking['number_of_nights'] ?> đêm</td>
                                <td style="text-align: right;"><?= number_format((float)$r['price_per_night'], 0, ',', '.') ?> ₫</td>
                                <td style="text-align: right;"><strong><?= number_format((float)$r['subtotal'], 0, ',', '.') ?> ₫</strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($booking['services'])): ?>
                        <?php foreach ($booking['services'] as $srv): ?>
                            <tr>
                                <td><?= htmlspecialchars($srv['service_name']) ?></td>
                                <td style="text-align: center;"><?= (int)$srv['quantity'] ?></td>
                                <td style="text-align: right;"><?= number_format((float)$srv['unit_price'], 0, ',', '.') ?> ₫</td>
                                <td style="text-align: right;"><?= number_format((float)$srv['subtotal'], 0, ',', '.') ?> ₫</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <tr>
                        <td colspan="3" style="text-align: right; border-top: 1px solid var(--border);">Tiền phòng tạm tính:</td>
                        <td style="text-align: right; border-top: 1px solid var(--border);"><?= number_format((float)$booking['subtotal'], 0, ',', '.') ?> ₫</td>
                    </tr>

                    <?php if ((float)$booking['service_amount'] > 0): ?>
                        <tr>
                            <td colspan="3" style="text-align: right;">Dịch vụ kèm theo:</td>
                            <td style="text-align: right;">+<?= number_format((float)$booking['service_amount'], 0, ',', '.') ?> ₫</td>
                        </tr>
                    <?php endif; ?>

                    <?php if ((float)$booking['discount_amount'] > 0): ?>
                        <tr>
                            <td colspan="3" style="text-align: right; color: var(--success);">
                                Giảm giá ưu đãi (<?= htmlspecialchars($booking['promotion_code'] ?? 'VOUCHER') ?>):
                            </td>
                            <td style="text-align: right; color: var(--success);">-<?= number_format((float)$booking['discount_amount'], 0, ',', '.') ?> ₫</td>
                        </tr>
                    <?php endif; ?>

                    <tr style="font-size: 16px; background: var(--primary-soft);">
                        <td colspan="3" style="text-align: right; font-weight: 600; color: var(--dark);">Tổng cộng:</td>
                        <td class="tabular-nums" style="text-align: right; font-weight: 600; color: var(--primary); font-size: 18px;">
                            <?= number_format((float)$booking['total_amount'], 0, ',', '.') ?> ₫
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- 3. PAYMENT STATUS & INSTRUCTIONS -->
            <div class="receipt-section-title">Tình trạng thanh toán &amp; Hướng dẫn</div>
            <div style="display: flex; justify-content: space-between; align-items: center; background: #faf8f5; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 16px 20px; margin-bottom: 24px;">
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block;">Hình thức &amp; Trạng thái:</label>
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; margin-top: 4px;">
                        <?php if ($booking['payment_method'] === 'online'): ?>
                            <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            <span>Thanh toán trực tuyến</span>
                        <?php else: ?>
                            <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/></svg>
                            <span>Thanh toán tại quầy khi nhận phòng</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <?php if ($booking['payment_status'] === 'paid'): ?>
                        <span class="status-badge status-paid" style="font-size: 13px; padding: 6px 14px;">Đã thanh toán</span>
                    <?php else: ?>
                        <span class="status-badge status-pending" style="font-size: 13px; padding: 6px 14px;">Chờ thanh toán tại quầy</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($booking['special_request'])): ?>
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 24px; padding: 12px; background: #fff; border: 1px solid var(--border-light); border-radius: var(--radius-sm);">
                    <strong>Ghi chú yêu cầu đặc biệt:</strong> <?= htmlspecialchars($booking['special_request']) ?>
                </div>
            <?php endif; ?>

            <!-- ACTION BUTTONS -->
            <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
                <button type="button" id="printReceiptBtn" class="btn btn-outline" style="padding: 12px 24px;">
                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    <span>In vé / Lưu phiếu xác nhận</span>
                </button>
                <a href="<?= BASE_URL ?>/booking/lookup?code=<?= urlencode($booking['booking_code']) ?>&contact=<?= urlencode($booking['guest_email']) ?>" class="btn btn-outline" style="padding: 12px 24px;">
                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <span>Tra cứu đơn hàng này</span>
                </a>
                <a href="<?= BASE_URL ?>/" class="btn btn-primary" style="padding: 12px 24px;">
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
</div>

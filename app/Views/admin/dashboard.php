<div class="page-heading">
    <div>
        <span class="eyebrow">OVERVIEW</span>
        <h1>Tổng quan</h1>
        <p class="muted">Chào mừng trở lại. Đây là tình hình hoạt động của khách sạn.</p>
    </div>
    <div class="heading-date"><span>HÔM NAY</span><strong><?= date('d.m.Y') ?></strong></div>
</div>

<div class="stats-grid">
    <article class="stat-card">
        <div class="stat-top"><span>Tổng đơn đặt phòng</span><span class="stat-icon sand">▤</span></div>
        <strong class="stat-value"><?= number_format($stats['bookings']) ?></strong>
        <span class="stat-foot">Tất cả đơn trong hệ thống</span>
    </article>
    <article class="stat-card">
        <div class="stat-top"><span>Khách hàng</span><span class="stat-icon lavender">♙</span></div>
        <strong class="stat-value"><?= number_format($stats['customers']) ?></strong>
        <span class="stat-foot">Tài khoản khách hàng</span>
    </article>
    <article class="stat-card">
        <div class="stat-top"><span>Phòng đang quản lý</span><span class="stat-icon mint">▣</span></div>
        <strong class="stat-value"><?= number_format($stats['rooms']) ?></strong>
        <span class="stat-foot">Không bao gồm phòng ngừng hoạt động</span>
    </article>
    <article class="stat-card">
        <div class="stat-top"><span>Doanh thu đã thanh toán</span><span class="stat-icon peach">₫</span></div>
        <strong class="stat-value"><?= number_format($stats['revenue'], 0, ',', '.') ?><small>₫</small></strong>
        <span class="stat-foot">Tổng giao dịch thành công</span>
    </article>
</div>

<div class="content-grid">
    <section class="panel booking-panel">
        <div class="panel-heading">
            <div><span class="eyebrow">LATEST ACTIVITY</span><h2>Đặt phòng gần đây</h2></div>
            <span class="subtle-tag">8 bản ghi mới nhất</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>MÃ ĐẶT PHÒNG</th><th>KHÁCH HÀNG</th><th>THỜI GIAN</th><th>TỔNG TIỀN</th><th>TRẠNG THÁI</th></tr></thead>
                <tbody>
                <?php if (!$recentBookings): ?>
                    <tr><td colspan="5" class="empty-state">Chưa có đơn đặt phòng.</td></tr>
                <?php else: foreach ($recentBookings as $booking): ?>
                    <tr>
                        <td><strong class="booking-code"><?= htmlspecialchars($booking['booking_code'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= $booking['payment_method'] === 'online' ? 'Online' : 'Tại khách sạn' ?></small></td>
                        <td><?= htmlspecialchars($booking['guest_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= date('d/m/Y', strtotime($booking['check_in'])) ?><small>đến <?= date('d/m/Y', strtotime($booking['check_out'])) ?></small></td>
                        <td class="amount"><?= number_format((float)$booking['total_amount'], 0, ',', '.') ?> ₫</td>
                        <td><span class="status status-<?= htmlspecialchars($booking['booking_status'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($booking['booking_status'], ENT_QUOTES, 'UTF-8') ?></span></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel status-panel">
        <div class="panel-heading"><div><span class="eyebrow">BOOKING SUMMARY</span><h2>Trạng thái đơn</h2></div></div>
        <?php
        $statusLabels = [
            'pending_payment' => 'Chờ thanh toán',
            'pending_hotel_payment' => 'Chờ thanh toán tại quầy',
            'confirmed' => 'Đã xác nhận',
            'checked_in' => 'Đã nhận phòng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];
        $statusTotal = array_sum(array_column($statusCounts, 'total'));
        ?>
        <div class="status-total"><strong><?= number_format($statusTotal) ?></strong><span>Tổng đơn</span></div>
        <div class="status-list">
            <?php foreach ($statusCounts as $item): ?>
                <div class="status-row"><span class="status-bullet"></span><span><?= htmlspecialchars($statusLabels[$item['booking_status']] ?? $item['booking_status'], ENT_QUOTES, 'UTF-8') ?></span><strong><?= number_format((int)$item['total']) ?></strong></div>
            <?php endforeach; ?>
            <?php if (!$statusCounts): ?><p class="muted">Chưa có dữ liệu trạng thái.</p><?php endif; ?>
        </div>
    </section>
</div>
<div class="dashboard-note"><span class="note-icon">i</span><div><strong>Dữ liệu trực tiếp từ MySQL</strong><p>Các chỉ số được truy vấn từ database <code>lotte_hotel_booking</code>. Các module quản trị khác sẽ được triển khai ở giai đoạn tiếp theo.</p></div></div>

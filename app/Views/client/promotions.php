<div class="container" style="padding-top: 30px; padding-bottom: 70px;">
    <!-- BREADCRUMB -->
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/">Trang chủ</a> <span>/</span> <strong>Ưu đãi &amp; Khuyến mãi</strong>
    </div>

    <div class="section-header" style="text-align: left; margin-left: 0; margin-bottom: 40px;">
        <span class="section-eyebrow">Ưu đãi đặt phòng</span>
        <h1 style="font-size: 36px; margin: 0 0 10px;">Chương trình ưu đãi nghỉ dưỡng</h1>
        <p class="section-desc" style="margin: 0;">
            Đặt phòng trực tiếp trên website chính thức để áp dụng các mã giảm giá và ưu đãi lưu trú hiện hành.
        </p>
    </div>

    <!-- PROMOTIONS GRID -->
    <?php if (empty($promotions)): ?>
        <div style="background: #fff; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 48px; text-align: center;">
            <p style="color: var(--text-muted); margin-bottom: 20px;">Hiện tại các chương trình ưu đãi đang được cập nhật. Quý khách vui lòng quay lại sau.</p>
            <a href="<?= BASE_URL ?>/rooms" class="btn btn-primary">Xem danh sách phòng nghỉ</a>
        </div>
    <?php else: ?>
        <div class="promotions-grid" style="margin-bottom: 50px;">
            <?php foreach ($promotions as $promo): ?>
                <div class="checkout-card" style="border: 1px solid var(--gold); background: #fff; box-shadow: var(--shadow-sm); position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px;">
                        <span class="promo-code-badge tabular-nums" style="margin: 0; font-size: 14px;">
                            Mã: <?= htmlspecialchars($promo['code']) ?>
                        </span>
                        <div style="text-align: right;">
                            <span class="tabular-nums" style="font-size: 24px; font-weight: 600; color: var(--primary); font-family: var(--font-serif);">
                                <?= $promo['discount_type'] === 'percentage' ? '-' . (int)$promo['discount_value'] . '%' : '-' . number_format((float)$promo['discount_value'], 0, ',', '.') . ' ₫' ?>
                            </span>
                        </div>
                    </div>

                    <h3 style="font-size: 20px; margin-bottom: 8px;"><?= htmlspecialchars($promo['name']) ?></h3>
                    <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 16px;">
                        <?= htmlspecialchars($promo['description']) ?>
                    </p>

                    <div class="tabular-nums" style="background: var(--primary-soft); padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px; color: var(--text); margin-bottom: 20px;">
                        <div>· Giá trị đơn hàng tối thiểu: <strong><?= number_format((float)$promo['min_order_value'], 0, ',', '.') ?> ₫</strong></div>
                        <?php if ($promo['max_discount']): ?>
                            <div>· Mức giảm tối đa: <strong><?= number_format((float)$promo['max_discount'], 0, ',', '.') ?> ₫</strong></div>
                        <?php endif; ?>
                        <div>· Hạn sử dụng: Đến hết <strong><?= date('d/m/Y', strtotime($promo['end_date'])) ?></strong></div>
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <a href="<?= BASE_URL ?>/rooms" class="btn btn-primary btn-block">
                            Đặt phòng và áp dụng mã
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

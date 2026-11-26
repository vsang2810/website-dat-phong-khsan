<div class="container" style="padding-top: 30px; padding-bottom: 70px;">
    <!-- BREADCRUMB -->
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/">Trang chủ</a> <span>/</span> <strong>Dịch vụ &amp; Tiện ích</strong>
    </div>

    <div class="section-header" style="text-align: left; margin-left: 0; margin-bottom: 40px;">
        <span class="section-eyebrow">Dịch vụ &amp; Tiện ích</span>
        <h1 style="font-size: 36px; margin: 0 0 10px;">Dịch vụ &amp; Trải nghiệm</h1>
        <p class="section-desc" style="margin: 0;">
            LOTTE HOTEL HANOI mang đến các dịch vụ lưu trú 5 sao, từ ẩm thực quốc tế, chăm sóc sức khỏe đến đưa đón sân bay.
        </p>
    </div>

    <!-- SERVICES GRID -->
    <div class="services-grid services-page-grid" style="margin-bottom: 60px;">
        <?php foreach ($services as $srv): ?>
            <div class="service-card" style="text-align: left; display: flex; flex-direction: column;">
                <div style="height: 180px; margin: -28px -24px 20px -24px; overflow: hidden; border-top-left-radius: var(--radius-md); border-top-right-radius: var(--radius-md);">
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($srv['image'] ?: 'assets/images/hotel-banner.png') ?>" alt="<?= htmlspecialchars($srv['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='<?= BASE_URL ?>/assets/images/hotel-banner.png'">
                </div>

                <h3 style="font-size: 20px; margin-bottom: 8px;"><?= htmlspecialchars($srv['name']) ?></h3>
                <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 20px; flex: 1;">
                    <?= htmlspecialchars($srv['description']) ?>
                </p>

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-light); padding-top: 14px;">
                    <div>
                        <small style="display: block; font-size: 12px; color: var(--text-muted);">Giá dịch vụ</small>
                        <strong class="tabular-nums" style="font-size: 18px; color: var(--primary); font-family: var(--font-serif);">
                            <?= number_format((float)$srv['price'], 0, ',', '.') ?> ₫
                        </strong>
                    </div>

                    <a href="<?= BASE_URL ?>/rooms" class="btn btn-outline btn-sm">
                        Đặt kèm phòng
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- EXTRA HOTEL FACILITIES BANNER -->
    <div style="background: #fff; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 40px; box-shadow: var(--shadow-sm);">
        <h2 style="font-size: 24px; margin-bottom: 24px; text-align: center;">Tiện ích dành cho khách lưu trú</h2>
        <div class="services-facilities-grid" style="text-align: center;">
            <div style="padding: 20px; background: var(--bg-main); border-radius: var(--radius-md);">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-soft); color: var(--primary); display: inline-grid; place-items: center; margin-bottom: 12px;">
                    <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>
                </div>
                <h4 style="font-size: 16px; margin-bottom: 6px;">Hồ bơi vô cực trên cao</h4>
                <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Tầm nhìn bao trọn thành phố, mở cửa từ 06:00 đến 21:00 hàng ngày.</p>
            </div>
            <div style="padding: 20px; background: var(--bg-main); border-radius: var(--radius-md);">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-soft); color: var(--primary); display: inline-grid; place-items: center; margin-bottom: 12px;">
                    <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6.5 6.5 11 11"/><path d="m21 21-1-1"/><path d="m3 3 1 1"/><path d="m18 22 4-4"/><path d="m2 6 4-4"/><path d="m3 10 7-7"/><path d="m14 21 7-7"/></svg>
                </div>
                <h4 style="font-size: 16px; margin-bottom: 6px;">Trung tâm thể hình</h4>
                <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Trang thiết bị TechnoGym hiện đại, hỗ trợ hướng dẫn viên tại chỗ.</p>
            </div>
            <div style="padding: 20px; background: var(--bg-main); border-radius: var(--radius-md);">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-soft); color: var(--primary); display: inline-grid; place-items: center; margin-bottom: 12px;">
                    <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 22h8"/><path d="M12 11v11"/><path d="m19 3-7 8-7-8Z"/></svg>
                </div>
                <h4 style="font-size: 16px; margin-bottom: 6px;">Top of Hanoi Sky Bar</h4>
                <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Quầy bar ngoài trời tại tầng 67 với đồ uống pha chế và nhạc sống.</p>
            </div>
            <div style="padding: 20px; background: var(--bg-main); border-radius: var(--radius-md);">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-soft); color: var(--primary); display: inline-grid; place-items: center; margin-bottom: 12px;">
                    <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 18h20"/><path d="M4 18a8 8 0 0 1 16 0"/><path d="M12 10V6"/><path d="M10 6h4"/></svg>
                </div>
                <h4 style="font-size: 16px; margin-bottom: 6px;">Dịch vụ Concierge 24/7</h4>
                <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Hỗ trợ đặt tour, vé máy bay và hướng dẫn tham quan Hà Nội.</p>
            </div>
        </div>
    </div>
</div>

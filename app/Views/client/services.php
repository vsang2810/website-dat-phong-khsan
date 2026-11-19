<div class="container" style="padding-top: 30px; padding-bottom: 70px;">
    <!-- BREADCRUMB -->
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/">Trang chủ</a> <span>/</span> <strong>Dịch vụ &amp; Tiện ích</strong>
    </div>

    <div class="section-header" style="text-align: left; margin-bottom: 40px;">
        <span class="section-eyebrow">WORLD-CLASS AMENITIES</span>
        <h1 style="font-size: 36px; margin: 0 0 10px;">Dịch Vụ &amp; Trải Nghiệm Đặc Quyền</h1>
        <p class="section-desc" style="margin: 0;">
            LOTTE HOTEL HANOI mang đến trọn vẹn các dịch vụ chuẩn mực 5 sao, từ ẩm thực tinh hoa quốc tế, chăm sóc sức khỏe toàn diện đến các tiện ích đưa đón chu đáo.
        </p>
    </div>

    <!-- SERVICES GRID -->
    <div class="services-grid" style="grid-template-columns: repeat(3, 1fr); gap: 28px; margin-bottom: 60px;">
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
                        <small style="display: block; font-size: 11px; color: var(--text-muted); text-transform: uppercase;">Giá dịch vụ</small>
                        <strong style="font-size: 18px; color: var(--primary); font-family: var(--font-serif);">
                            <?= number_format((float)$srv['price'], 0, ',', '.') ?> ₫
                        </strong>
                    </div>

                    <a href="<?= BASE_URL ?>/rooms" class="btn btn-outline btn-sm">
                        Đặt kèm phòng →
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- EXTRA HOTEL FACILITIES BANNER -->
    <div style="background: #fff; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 40px; box-shadow: var(--shadow-sm);">
        <h2 style="font-size: 24px; margin-bottom: 20px; text-align: center;">Tiện Ích Độc Quyền Dành Cho Khách Lưu Trú</h2>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; text-align: center;">
            <div style="padding: 20px; background: var(--bg-main); border-radius: var(--radius-md);">
                <div style="font-size: 32px; margin-bottom: 10px;">🏊</div>
                <h4 style="font-size: 16px; margin-bottom: 6px;">Hồ bơi vô cực trên cao</h4>
                <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Tầm nhìn bao trọn thành phố, mở cửa từ 06:00 đến 21:00 hàng ngày.</p>
            </div>
            <div style="padding: 20px; background: var(--bg-main); border-radius: var(--radius-md);">
                <div style="font-size: 32px; margin-bottom: 10px;">🏋️</div>
                <h4 style="font-size: 16px; margin-bottom: 6px;">Trung tâm Thể hình</h4>
                <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Trang thiết bị TechnoGym hiện đại nhất, huấn luyện viên chuyên nghiệp.</p>
            </div>
            <div style="padding: 20px; background: var(--bg-main); border-radius: var(--radius-md);">
                <div style="font-size: 32px; margin-bottom: 10px;">🍸</div>
                <h4 style="font-size: 16px; margin-bottom: 6px;">Top of Hanoi Sky Bar</h4>
                <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Quầy bar ngoài trời tại tầng 67 với âm nhạc và cocktail tuyệt hảo.</p>
            </div>
            <div style="padding: 20px; background: var(--bg-main); border-radius: var(--radius-md);">
                <div style="font-size: 32px; margin-bottom: 10px;">🛎</div>
                <h4 style="font-size: 16px; margin-bottom: 6px;">Dịch vụ Concierge 24/7</h4>
                <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Hỗ trợ đặt tour, vé máy bay và hướng dẫn tham quan Hà Nội.</p>
            </div>
        </div>
    </div>
</div>

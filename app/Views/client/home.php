<?php
    $hotelImg = !empty($hotel['thumbnail']) ? $hotel['thumbnail'] : 'assets/images/hotel/lotte-hotel.jpg';
    $heroBgUrl = asset_url($hotelImg);
?>
<!-- HERO SECTION -->
<section class="hero-section" style="background-image: linear-gradient(rgba(17, 20, 24, 0.65), rgba(17, 20, 24, 0.75)), url('<?= htmlspecialchars($heroBgUrl) ?>');">
    <div class="container">
        <div class="hero-content">
            <span class="hero-eyebrow">✦ CHUẨN MỰC NGHỈ DƯỠNG 5 SAO QUỐC TẾ ✦</span>
            <h1 class="hero-title">Tuyệt Tác Nghỉ Dưỡng Giữa Lòng Thủ Đô Hà Nội</h1>
            <p class="hero-lead">
                Khám phá trải nghiệm lưu trú thượng lưu tại LOTTE HOTEL HANOI với tầm nhìn toàn cảnh ngoạn mục, không gian kiến trúc đương đại và dịch vụ tận tâm tinh tế trong từng khoảnh khắc.
            </p>
            <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
                <a href="<?= BASE_URL ?>/rooms" class="btn btn-primary btn-lg">Khám phá phòng nghỉ →</a>
                <a href="<?= BASE_URL ?>/promotions" class="btn btn-outline-gold btn-lg">Xem ưu đãi độc quyền</a>
            </div>
        </div>
    </div>
</section>

<!-- QUICK BOOKING BAR -->
<div class="container booking-bar-wrapper">
    <div class="booking-bar">
        <form action="<?= BASE_URL ?>/rooms" method="get" class="booking-form-grid">
            <div class="form-group">
                <label class="form-label" for="roomSelect">Hạng phòng mong muốn</label>
                <select name="q" id="roomSelect" class="form-select">
                    <option value="">Tất cả các hạng phòng</option>
                    <?php foreach ($featuredRooms as $r): ?>
                        <option value="<?= htmlspecialchars($r['name']) ?>"><?= htmlspecialchars($r['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="heroCheckIn">Ngày nhận phòng</label>
                <input type="date" name="check_in" id="heroCheckIn" class="form-control" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" min="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="heroCheckOut">Ngày trả phòng</label>
                <input type="date" name="check_out" id="heroCheckOut" class="form-control" value="<?= date('Y-m-d', strtotime('+2 days')) ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="heroGuests">Số lượng khách</label>
                <select name="guests" id="heroGuests" class="form-select">
                    <option value="1">1 khách</option>
                    <option value="2" selected>2 khách</option>
                    <option value="3">3 khách</option>
                    <option value="4">4 khách trở lên</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="height: 48px; padding: 0 28px;">
                🔍 Tìm phòng trống
            </button>
        </form>
    </div>
</div>

<!-- FEATURED ROOMS SECTION -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">ACCOMMODATION &amp; SUITES</span>
            <h2 class="section-title">Hạng Phòng &amp; Suites Nổi Bật</h2>
            <p class="section-desc">Mỗi căn phòng tại LOTTE HOTEL HANOI là sự kết hợp hoàn mỹ giữa thiết kế hiện đại, nội thất tinh xảo và các tiện nghi cao cấp nhằm mang lại giấc ngủ trọn vẹn nhất cho quý khách.</p>
        </div>

        <div class="rooms-grid">
            <?php foreach ($featuredRooms as $room): ?>
                <div class="room-card">
                    <div class="room-image-wrap">
                        <img src="<?= htmlspecialchars(asset_url($room['thumbnail'] ?? '')) ?>" alt="<?= htmlspecialchars($room['name']) ?>" class="room-image" onerror="this.src='<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>'">
                        <span class="room-badge-avail">Còn phòng trống</span>
                        <div class="room-price-badge">
                            Từ <strong><?= number_format((float)$room['base_price'], 0, ',', '.') ?> ₫</strong> / đêm
                        </div>
                    </div>

                    <div class="room-card-body">
                        <span class="room-card-hotel"><?= htmlspecialchars($room['hotel_name']) ?></span>
                        <h3 class="room-card-title">
                            <a href="<?= BASE_URL ?>/room-detail?id=<?= (int)$room['id'] ?>"><?= htmlspecialchars($room['name']) ?></a>
                        </h3>

                        <div class="room-specs">
                            <div class="room-spec-item" title="Diện tích">
                                <span>📐</span> <?= (float)$room['area'] ?> m²
                            </div>
                            <div class="room-spec-item" title="Loại giường">
                                <span>🛏</span> <?= htmlspecialchars($room['bed_type']) ?>
                            </div>
                            <div class="room-spec-item" title="Sức chứa tối đa">
                                <span>👥</span> Tối đa <?= (int)$room['max_guests'] ?> khách
                            </div>
                        </div>

                        <?php if (!empty($room['amenities'])): ?>
                            <div class="room-amenity-tags">
                                <?php foreach (array_slice($room['amenities'], 0, 4) as $amenity): ?>
                                    <span class="amenity-tag">✓ <?= htmlspecialchars($amenity['name']) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="room-card-footer">
                            <a href="<?= BASE_URL ?>/room-detail?id=<?= (int)$room['id'] ?>" class="btn btn-outline btn-block">Xem chi tiết</a>
                            <a href="<?= BASE_URL ?>/booking?room_type_id=<?= (int)$room['id'] ?>" class="btn btn-primary btn-block">Đặt phòng ngay</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="<?= BASE_URL ?>/rooms" class="btn btn-outline" style="padding: 12px 34px;">
                Xem toàn bộ các hạng phòng nghỉ →
            </a>
        </div>
    </div>
</section>

<!-- ABOUT / INTRO SECTION -->
<section class="section-padding" style="background: #fff; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
    <div class="container">
        <div class="intro-grid">
            <div class="intro-img-wrap">
                <img src="<?= htmlspecialchars(asset_url($hotel['thumbnail'] ?? 'assets/images/hotel/lotte-hotel.jpg')) ?>" alt="<?= htmlspecialchars($hotel['name'] ?? 'Lotte Hotel Hanoi') ?>" style="height: 440px; width: 100%; object-fit: cover;" onerror="this.src='<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>'">
                <div class="intro-badge">
                    <strong>5-STAR LUXURY</strong>
                    <span>Biểu tượng lưu trú sang trọng tại Hà Nội</span>
                </div>
            </div>

            <div>
                <span class="section-eyebrow">HOSPITALITY REDEFINED</span>
                <h2 class="section-title">Kiến Tạo Kỳ Nghỉ Đáng Nhớ Cùng LOTTE HOTEL HANOI</h2>
                <p style="color: var(--text-muted); line-height: 1.8; margin-bottom: 20px;">
                    Tọa lạc từ tầng 33 đến 64 của tòa nhà Lotte Center Hanoi - biểu tượng tráng lệ của thủ đô, khách sạn sở hữu tầm nhìn panorama tuyệt đẹp nhìn ra toàn cảnh thành phố và Hồ Tây thanh bình.
                </p>
                <p style="color: var(--text-muted); line-height: 1.8;">
                    Chúng tôi tự hào kết hợp tinh hoa hiếu khách truyền thống châu Á cùng sự tiện nghi đỉnh cao quốc tế để mang lại không gian thư giãn hoàn hảo cho cả kỳ nghỉ gia đình lẫn chuyến công tác quan trọng.
                </p>

                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon">★</div>
                        <div class="feature-info">
                            <h4>Vị trí trung tâm vàng</h4>
                            <p>Cách Hồ Tây và các điểm văn hóa chỉ vài phút di chuyển.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">☕</div>
                        <div class="feature-info">
                            <h4>Ẩm thực thượng hạng</h4>
                            <p>Hệ thống nhà hàng quốc tế và quầy bar cao nhất thủ đô.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🏊</div>
                        <div class="feature-info">
                            <h4>Hồ bơi &amp; Spa</h4>
                            <p>Không gian thư giãn đẳng cấp phục vụ sức khỏe toàn diện.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🛡</div>
                        <div class="feature-info">
                            <h4>An toàn &amp; Bảo mật</h4>
                            <p>Hệ thống bảo vệ đa tầng và dịch vụ hỗ trợ lễ tân 24/7.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES SECTION -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">PREMIUM PRIVILEGES</span>
            <h2 class="section-title">Dịch Vụ &amp; Tiện Ích Đẳng Cấp</h2>
            <p class="section-desc">Nâng tầm trải nghiệm lưu trú của quý khách bằng các dịch vụ bổ sung tiện ích được thiết kế chuyên biệt.</p>
        </div>

        <div class="services-grid">
            <?php foreach ($services as $srv): ?>
                <div class="service-card">
                    <div class="service-icon-box">
                        <?php if (str_contains(mb_strtolower($srv['name']), 'sáng')): ?>🍳
                        <?php elseif (str_contains(mb_strtolower($srv['name']), 'sân bay') || str_contains(mb_strtolower($srv['name']), 'xe')): ?>🚘
                        <?php elseif (str_contains(mb_strtolower($srv['name']), 'spa')): ?>💆
                        <?php else: ?>🧺
                        <?php endif; ?>
                    </div>
                    <h3><?= htmlspecialchars($srv['name']) ?></h3>
                    <p><?= htmlspecialchars($srv['description']) ?></p>
                    <div class="service-price">
                        <?= number_format((float)$srv['price'], 0, ',', '.') ?> ₫ <small style="color: var(--text-muted); font-weight: normal;">/ lượt</small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PROMOTIONS BANNER -->
<?php if (!empty($promotions)): ?>
<section class="section-padding" style="padding-top: 0;">
    <div class="container">
        <div class="promo-banner">
            <span class="section-eyebrow" style="color: var(--gold);">EXCLUSIVE OFFERS</span>
            <h2 style="color: #fff; font-size: 30px; margin-bottom: 12px;">Ưu Đãi Nghỉ Dưỡng Đặc Quyền</h2>
            <p style="color: #c9cbcd; max-width: 600px; margin-bottom: 24px;">
                Nhận ngay chiết khấu đặc biệt khi đặt phòng trực tuyến tại website chính thức của LOTTE HOTEL HANOI.
            </p>

            <div class="promo-grid">
                <?php foreach ($promotions as $promo): ?>
                    <div class="promo-card">
                        <span class="promo-code-badge">MÃ: <?= htmlspecialchars($promo['code']) ?></span>
                        <h4><?= htmlspecialchars($promo['name']) ?></h4>
                        <p><?= htmlspecialchars($promo['description']) ?></p>
                        <div style="font-size: 12px; color: var(--gold); margin-bottom: 14px;">
                            Áp dụng đơn từ: <?= number_format((float)$promo['min_order_value'], 0, ',', '.') ?> ₫
                        </div>
                        <a href="<?= BASE_URL ?>/rooms" class="btn btn-outline-gold btn-sm">Áp dụng mã ngay →</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- REVIEWS SECTION -->
<?php if (!empty($reviews)): ?>
<section class="section-padding" style="background: #fff; border-top: 1px solid var(--border);">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">GUEST EXPERIENCES</span>
            <h2 class="section-title">Khách Hàng Nói Gì Về Chúng Tôi</h2>
            <p class="section-desc">Hàng nghìn du khách trong nước và quốc tế đã tin tưởng lựa chọn LOTTE HOTEL HANOI cho những chuyến đi đáng nhớ.</p>
        </div>

        <div class="reviews-grid">
            <?php foreach ($reviews as $rev): ?>
                <div class="review-card">
                    <div class="review-stars">
                        <?= str_repeat('★', (int)$rev['rating']) . str_repeat('☆', 5 - (int)$rev['rating']) ?>
                    </div>
                    <p class="review-text">"<?= htmlspecialchars($rev['comment'] ?? 'Dịch vụ rất tốt, phòng sạch sẽ và sang trọng.') ?>"</p>
                    <div class="review-author">
                        <div class="review-avatar">
                            <?= htmlspecialchars(mb_strtoupper(mb_substr($rev['guest_name'] ?? 'K', 0, 1))) ?>
                        </div>
                        <div class="review-author-info">
                            <strong><?= htmlspecialchars($rev['guest_name'] ?? 'Khách lưu trú') ?></strong>
                            <span>Đã ở tại: <?= htmlspecialchars($rev['room_name'] ?? 'LOTTE HOTEL') ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CALL TO ACTION / NEWSLETTER -->
<section class="section-padding" style="background: var(--dark); color: #fff; text-align: center;">
    <div class="container-narrow">
        <span class="section-eyebrow" style="color: var(--gold);">STAY CONNECTED</span>
        <h2 style="color: #fff; font-size: 32px; margin-bottom: 14px;">Sẵn Sàng Cho Kỳ Nghỉ Đáng Nhớ Của Bạn?</h2>
        <p style="color: #a0a6ab; font-size: 15px; margin-bottom: 30px;">
            Liên hệ đội ngũ chăm sóc khách hàng hoặc đặt phòng trực tuyến ngay hôm nay để nhận được mức giá và ưu đãi tốt nhất.
        </p>
        <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>/rooms" class="btn btn-primary btn-lg">Xem danh sách phòng nghỉ</a>
            <a href="<?= BASE_URL ?>/contact" class="btn btn-outline-gold btn-lg">Gửi yêu cầu hỗ trợ</a>
        </div>
    </div>
</section>

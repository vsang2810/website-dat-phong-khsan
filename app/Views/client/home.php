<?php
    $hotelImg = !empty($hotel['thumbnail']) ? $hotel['thumbnail'] : 'assets/images/hotel/lotte-hotel.jpg';
    $heroBgUrl = asset_url($hotelImg);
?>
<!-- HERO SECTION -->
<section class="hero-section" style="background-image: linear-gradient(90deg, rgba(17, 20, 24, 0.82) 0%, rgba(17, 20, 24, 0.58) 55%, rgba(17, 20, 24, 0.28) 100%), url('<?= htmlspecialchars($heroBgUrl) ?>');">
    <div class="container">
        <div class="hero-content">
            <span class="hero-eyebrow">54 Liễu Giai, Ba Đình · Tầng 33–64 Lotte Center</span>
            <h1 class="hero-title">Lưu trú giữa tầng mây Hà Nội</h1>
            <p class="hero-lead">
                318 phòng nghỉ nhìn ra Hồ Tây và toàn cảnh thành phố, kết hợp dịch vụ lễ tân 24/7 tại LOTTE HOTEL HANOI.
            </p>
            <div class="hero-actions">
                <a href="<?= BASE_URL ?>/rooms" class="btn btn-primary btn-lg">Đặt phòng ngay</a>
                <a href="<?= BASE_URL ?>/promotions" class="btn btn-outline-gold btn-lg">Xem ưu đãi</a>
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
                <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <span>Tìm phòng trống</span>
            </button>
        </form>
    </div>
</div>

<!-- FEATURED ROOMS SECTION -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Hạng phòng &amp; Suites</span>
            <h2 class="section-title">Không gian nghỉ dưỡng nổi bật</h2>
            <p class="section-desc">Các hạng phòng tại LOTTE HOTEL HANOI được bố trí trên tầng cao với đầy đủ tiện nghi lưu trú tiêu chuẩn 5 sao.</p>
        </div>

        <div class="rooms-grid rooms-grid-featured">
            <?php foreach ($featuredRooms as $room): ?>
                <div class="room-card">
                    <div class="room-image-wrap">
                        <img src="<?= htmlspecialchars(asset_url($room['thumbnail'] ?? '')) ?>" alt="<?= htmlspecialchars($room['name']) ?>" class="room-image" onerror="this.src='<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>'">
                        <span class="room-badge-avail">Còn phòng trống</span>
                        <div class="room-price-badge">
                            Từ <strong class="tabular-nums"><?= number_format((float)$room['base_price'], 0, ',', '.') ?> ₫</strong> / đêm
                        </div>
                    </div>

                    <div class="room-card-body">
                        <span class="room-card-hotel"><?= htmlspecialchars($room['hotel_name']) ?></span>
                        <h3 class="room-card-title">
                            <a href="<?= BASE_URL ?>/room-detail?id=<?= (int)$room['id'] ?>"><?= htmlspecialchars($room['name']) ?></a>
                        </h3>

                        <div class="room-specs tabular-nums">
                            <div class="room-spec-item" title="Diện tích">
                                <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                <span><?= (float)$room['area'] ?> m²</span>
                            </div>
                            <div class="room-spec-item" title="Loại giường">
                                <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                                <span><?= htmlspecialchars($room['bed_type']) ?></span>
                            </div>
                            <div class="room-spec-item" title="Sức chứa tối đa">
                                <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span>Tối đa <?= (int)$room['max_guests'] ?> khách</span>
                            </div>
                        </div>

                        <?php if (!empty($room['amenities'])): ?>
                            <div class="room-amenity-tags">
                                <?php foreach (array_slice($room['amenities'], 0, 4) as $amenity): ?>
                                    <span class="amenity-tag"><?= htmlspecialchars($amenity['name']) ?></span>
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
            <a href="<?= BASE_URL ?>/rooms" class="btn btn-outline" style="padding: 12px 32px;">
                Xem toàn bộ hạng phòng
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
                    <strong class="tabular-nums">Tầng 33 – 64</strong>
                    <span>Lotte Center Hanoi · 54 Liễu Giai</span>
                </div>
            </div>

            <div>
                <span class="section-eyebrow">Về khách sạn</span>
                <h2 class="section-title">Không gian lưu trú tại LOTTE HOTEL HANOI</h2>
                <p style="color: var(--text-muted); line-height: 1.7; margin-bottom: 16px;">
                    Tọa lạc từ tầng 33 đến tầng 64 của tòa nhà Lotte Center Hanoi, khách sạn có tầm nhìn rộng mở ra khu vực Ba Đình và Hồ Tây.
                </p>
                <p style="color: var(--text-muted); line-height: 1.7;">
                    Thiết kế phòng nghỉ chú trọng sự yên tĩnh, ánh sáng tự nhiên và tiện nghi đồng bộ cho cả kỳ nghỉ gia đình lẫn chuyến công tác.
                </p>

                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div class="feature-info">
                            <h4>Vị trí Ba Đình</h4>
                            <p>Kết nối nhanh tới Hồ Tây và khu trung tâm hành chính.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><line x1="6" x2="6" y1="2" y2="4"/><line x1="10" x2="10" y1="2" y2="4"/><line x1="14" x2="14" y1="2" y2="4"/></svg>
                        </div>
                        <div class="feature-info">
                            <h4>Nhà hàng &amp; Lounge</h4>
                            <p>Phục vụ buffet sáng và ẩm thực Á – Âu trên tầng cao.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22c4-4 6-7.5 6-11a6 6 0 1 0-12 0c0 3.5 2 7 6 11Z"/><path d="M12 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>
                        </div>
                        <div class="feature-info">
                            <h4>Hồ bơi &amp; Spa</h4>
                            <p>Khu vực thư giãn và chăm sóc sức khỏe dành riêng cho khách lưu trú.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <div class="feature-info">
                            <h4>Lễ tân 24/7</h4>
                            <p>Hỗ trợ nhận phòng, trả phòng và yêu cầu riêng mọi khung giờ.</p>
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
            <span class="section-eyebrow">Dịch vụ đi kèm</span>
            <h2 class="section-title">Tiện ích bổ sung khi đặt phòng</h2>
            <p class="section-desc">Quý khách có thể chọn thêm các dịch vụ dưới đây ngay trong bước đặt phòng trực tuyến.</p>
        </div>

        <div class="services-grid">
            <?php foreach ($services as $srv): ?>
                <div class="service-card">
                    <div class="service-icon-box">
                        <?php if (str_contains(mb_strtolower($srv['name']), 'sáng')): ?>
                            <svg class="ui-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                        <?php elseif (str_contains(mb_strtolower($srv['name']), 'sân bay') || str_contains(mb_strtolower($srv['name']), 'xe')): ?>
                            <svg class="ui-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                        <?php elseif (str_contains(mb_strtolower($srv['name']), 'spa')): ?>
                            <svg class="ui-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 7.5a4.5 4.5 0 1 1 4.5 4.5M12 7.5A4.5 4.5 0 1 0 7.5 12M12 7.5V9m-4.5 3a4.5 4.5 0 1 0 4.5 4.5M7.5 12H9m7.5 0a4.5 4.5 0 1 1-4.5 4.5m4.5-4.5H15m-3 4.5V15"/><circle cx="12" cy="12" r="1.5"/></svg>
                        <?php else: ?>
                            <svg class="ui-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
                        <?php endif; ?>
                    </div>
                    <h3><?= htmlspecialchars($srv['name']) ?></h3>
                    <p><?= htmlspecialchars($srv['description']) ?></p>
                    <div class="service-price tabular-nums">
                        <?= number_format((float)$srv['price'], 0, ',', '.') ?> ₫ <small style="color: var(--text-muted); font-weight: 400;">/ lượt</small>
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
            <span class="section-eyebrow" style="color: var(--gold);">Ưu đãi hiện hành</span>
            <h2 style="color: #fff; font-size: 28px; font-weight: 600; margin-bottom: 12px;">Mã giảm giá đặt phòng trực tuyến</h2>
            <p style="color: #c9cbcd; max-width: 580px; margin-bottom: 24px;">
                Nhập mã ưu đãi ở bước thanh toán để được áp dụng mức chiết khấu trực tiếp trên tổng giá phòng.
            </p>

            <div class="promo-grid">
                <?php foreach ($promotions as $promo): ?>
                    <div class="promo-card">
                        <span class="promo-code-badge tabular-nums">Mã: <?= htmlspecialchars($promo['code']) ?></span>
                        <h4><?= htmlspecialchars($promo['name']) ?></h4>
                        <p><?= htmlspecialchars($promo['description']) ?></p>
                        <div class="tabular-nums" style="font-size: 12px; color: var(--gold); margin-bottom: 16px;">
                            Áp dụng đơn từ <?= number_format((float)$promo['min_order_value'], 0, ',', '.') ?> ₫
                        </div>
                        <a href="<?= BASE_URL ?>/rooms" class="btn btn-outline-gold btn-sm">Áp dụng mã</a>
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
            <span class="section-eyebrow">Đánh giá từ khách lưu trú</span>
            <h2 class="section-title">Phản hồi sau kỳ nghỉ</h2>
            <p class="section-desc">Chia sẻ thực tế từ khách hàng đã hoàn thành lưu trú tại LOTTE HOTEL HANOI.</p>
        </div>

        <div class="reviews-grid">
            <?php foreach ($reviews as $rev): ?>
                <div class="review-card">
                    <div class="review-stars" aria-label="<?= (int)$rev['rating'] ?> trên 5 sao">
                        <?= str_repeat('★', (int)$rev['rating']) . str_repeat('☆', 5 - (int)$rev['rating']) ?>
                    </div>
                    <p class="review-text">“<?= htmlspecialchars($rev['comment'] ?? 'Dịch vụ chu đáo, phòng sạch sẽ và yên tĩnh.') ?>”</p>
                    <div class="review-author">
                        <div class="review-avatar">
                            <?= htmlspecialchars(mb_strtoupper(mb_substr($rev['guest_name'] ?? 'K', 0, 1))) ?>
                        </div>
                        <div class="review-author-info">
                            <strong><?= htmlspecialchars($rev['guest_name'] ?? 'Khách lưu trú') ?></strong>
                            <span><?= htmlspecialchars($rev['room_name'] ?? 'LOTTE HOTEL') ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CALL TO ACTION -->
<section class="section-padding" style="background: var(--dark); color: #fff; text-align: center;">
    <div class="container-narrow">
        <span class="section-eyebrow" style="color: var(--gold);">Đặt phòng &amp; Hỗ trợ</span>
        <h2 style="color: #fff; font-size: 30px; font-weight: 600; margin-bottom: 12px;">Cần tư vấn cho lịch trình sắp tới</h2>
        <p style="color: #a0a6ab; font-size: 15px; margin-bottom: 28px;">
            Kiểm tra phòng trống trực tuyến hoặc liên hệ bộ phận lễ tân 24/7 qua hotline <span class="tabular-nums">024 3333 1000</span>.
        </p>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>/rooms" class="btn btn-primary btn-lg">Xem danh sách phòng</a>
            <a href="<?= BASE_URL ?>/contact" class="btn btn-outline-gold btn-lg">Liên hệ hỗ trợ</a>
        </div>
    </div>
</section>

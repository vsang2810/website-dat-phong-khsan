<div class="container" style="padding-top: 24px; padding-bottom: 70px;">
    <!-- BREADCRUMB & HEADER -->
    <div class="detail-header">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Trang chủ</a>
            <span>/</span>
            <a href="<?= BASE_URL ?>/rooms">Phòng nghỉ</a>
            <span>/</span>
            <strong><?= htmlspecialchars($room['name']) ?></strong>
        </div>

        <div class="detail-title-row">
            <div>
                <span class="section-eyebrow"><?= htmlspecialchars($room['hotel_name']) ?> · <?= htmlspecialchars($room['hotel_address']) ?></span>
                <h1><?= htmlspecialchars($room['name']) ?></h1>
                <div style="display: flex; align-items: center; gap: 12px; font-size: 13px; color: var(--text-muted);">
                    <span style="display: inline-flex; align-items: center; gap: 2px; color: var(--gold);" aria-label="5 sao">
                        <?php for ($s = 0; $s < 5; $s++): ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <?php endfor; ?>
                    </span>
                    <span>Khách sạn 5 sao</span>
                    <span>·</span>
                    <span>Tầm nhìn toàn cảnh thành phố</span>
                </div>
            </div>

            <div class="detail-price-box">
                <span class="price-label">Giá niêm yết tiêu chuẩn</span>
                <div class="price-value tabular-nums"><?= number_format((float)$room['base_price'], 0, ',', '.') ?> ₫ <small style="font-size: 14px; font-weight: normal; color: var(--text-muted);">/ đêm</small></div>
            </div>
        </div>
    </div>

    <!-- GALLERY SECTION -->
    <div class="gallery-grid">
        <div class="gallery-main">
            <img id="detailMainImage" src="<?= htmlspecialchars(asset_url($room['thumbnail'] ?? '')) ?>" alt="<?= htmlspecialchars($room['name']) ?>" onerror="this.src='<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>'">
        </div>
        <div class="gallery-thumbs">
            <?php if (!empty($room['images'])): ?>
                <?php foreach (array_slice($room['images'], 0, 2) as $img): ?>
                    <div class="gallery-thumb-item">
                        <img src="<?= htmlspecialchars(asset_url($img['image_url'] ?? '')) ?>" alt="Thumbnail" class="js-gallery-thumb" style="cursor: pointer;" onerror="this.src='<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>'">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="gallery-thumb-item">
                    <img src="<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>" alt="Thumbnail" class="js-gallery-thumb" style="cursor: pointer;">
                </div>
                <div class="gallery-thumb-item">
                    <img src="<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>" alt="Thumbnail" class="js-gallery-thumb" style="cursor: pointer;">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- CONTENT & BOOKING WIDGET -->
    <div class="detail-layout">
        <!-- LEFT: ROOM INFO -->
        <div class="detail-main-info">
            <!-- SPECS BAR -->
            <div class="detail-specs-bar tabular-nums">
                <div class="detail-spec">
                    <div class="detail-spec-icon">
                        <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                    </div>
                    <div class="detail-spec-text">
                        <span>Diện tích</span>
                        <strong><?= (float)$room['area'] ?> m²</strong>
                    </div>
                </div>
                <div class="detail-spec">
                    <div class="detail-spec-icon">
                        <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                    </div>
                    <div class="detail-spec-text">
                        <span>Loại giường</span>
                        <strong><?= htmlspecialchars($room['bed_type']) ?></strong>
                    </div>
                </div>
                <div class="detail-spec">
                    <div class="detail-spec-icon">
                        <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div class="detail-spec-text">
                        <span>Sức chứa</span>
                        <strong>Tối đa <?= (int)$room['max_guests'] ?> khách</strong>
                    </div>
                </div>
                <div class="detail-spec">
                    <div class="detail-spec-icon">
                        <svg class="ui-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    </div>
                    <div class="detail-spec-text">
                        <span>Tầm nhìn</span>
                        <strong>Thành phố / Hồ Tây</strong>
                    </div>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="checkout-card">
                <div class="checkout-card-header">
                    <h3>Giới thiệu không gian phòng</h3>
                </div>
                <p style="font-size: 15px; line-height: 1.8; color: var(--text);">
                    <?= nl2br(htmlspecialchars($room['description'] ?: 'Không gian nghỉ ngơi thiết kế tối giản, thanh lịch, kết hợp giữa phong cách hiện đại và sự tiện nghi tối đa. Trang bị đầy đủ giường nệm cao cấp, góc làm việc yên tĩnh, phòng tắm sang trọng và cửa kính lớn nhìn trọn khung cảnh thủ đô.')) ?>
                </p>
                <p style="font-size: 15px; line-height: 1.8; color: var(--text-muted);">
                    Phòng được chăm sóc và khử khuẩn theo tiêu chuẩn 5 sao quốc tế của tập đoàn LOTTE. Đội ngũ nhân viên luôn sẵn sàng phục vụ 24/7 để mang đến cho quý khách trải nghiệm lưu trú không tì vết.
                </p>
            </div>

            <!-- AMENITIES -->
            <div class="checkout-card">
                <div class="checkout-card-header">
                    <h3>Tiện nghi &amp; Dịch vụ trong phòng</h3>
                </div>
                <div class="amenities-grid">
                    <?php
                        $checkSvg = '<svg class="ui-icon icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>';
                    ?>
                    <?php if (!empty($room['amenities'])): ?>
                        <?php foreach ($room['amenities'] as $am): ?>
                            <div class="amenity-item">
                                <?= $checkSvg ?>
                                <div>
                                    <strong><?= htmlspecialchars($am['name']) ?></strong>
                                    <?php if (!empty($am['description'])): ?>
                                        <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($am['description']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="amenity-item"><?= $checkSvg ?><span>WiFi tốc độ cao miễn phí</span></div>
                        <div class="amenity-item"><?= $checkSvg ?><span>Điều hòa hai chiều thông minh</span></div>
                        <div class="amenity-item"><?= $checkSvg ?><span>Smart TV 55 inch Ultra HD</span></div>
                        <div class="amenity-item"><?= $checkSvg ?><span>Minibar cao cấp</span></div>
                        <div class="amenity-item"><?= $checkSvg ?><span>Bồn tắm nằm và vòi sen đứng</span></div>
                        <div class="amenity-item"><?= $checkSvg ?><span>Két an toàn điện tử</span></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- POLICIES -->
            <div class="checkout-card">
                <div class="checkout-card-header">
                    <h3>Quy định &amp; Chính sách lưu trú</h3>
                </div>
                <ul style="padding-left: 20px; font-size: 14px; color: var(--text-muted); line-height: 1.8; margin: 0;">
                    <li><strong>Giờ nhận phòng (Check-in):</strong> từ 14:00 mỗi ngày.</li>
                    <li><strong>Giờ trả phòng (Check-out):</strong> trước 12:00 trưa mỗi ngày.</li>
                    <li><strong>Chính sách hủy phòng:</strong> Miễn phí hủy trước ngày nhận phòng theo quy định.</li>
                    <li><strong>Quy định chung:</strong> Không hút thuốc trong phòng, không mang theo thú cưng. Vui lòng xuất trình CCCD/Hộ chiếu khi làm thủ tục nhận phòng tại quầy lễ tân.</li>
                </ul>
            </div>

            <!-- REVIEWS -->
            <div class="checkout-card">
                <div class="checkout-card-header">
                    <h3>Đánh giá từ khách lưu trú (<?= count($reviews) ?>)</h3>
                </div>
                <?php if (empty($reviews)): ?>
                    <p style="color: var(--text-muted); margin: 0;">Chưa có đánh giá nào cho hạng phòng này. Hãy là người đầu tiên trải nghiệm và chia sẻ cảm nhận.</p>
                <?php else: ?>
                    <div style="display: grid; gap: 16px;">
                        <?php foreach ($reviews as $rev): ?>
                            <div style="border-bottom: 1px solid var(--border-light); padding-bottom: 14px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                    <strong><?= htmlspecialchars($rev['guest_name']) ?></strong>
                                    <span style="display: inline-flex; align-items: center; gap: 2px; color: var(--gold);" aria-label="<?= (int)$rev['rating'] ?> sao">
                                        <?php for ($s = 0; $s < (int)$rev['rating']; $s++): ?>
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <div style="font-size: 13px; color: var(--text); line-height: 1.5;">
                                    "<?= htmlspecialchars($rev['comment']) ?>"
                                </div>
                                <small class="tabular-nums" style="font-size: 11px; color: var(--text-muted);"><?= date('d/m/Y', strtotime($rev['created_at'])) ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT: STICKY BOOKING CARD -->
        <div>
            <div class="sticky-booking-card">
                <div style="border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 16px;">
                    <div style="font-size: 12px; color: var(--text-muted); font-weight: 500;">Giá niêm yết từ khách sạn</div>
                    <div class="tabular-nums" style="font-size: 26px; font-weight: 600; color: var(--primary); font-family: var(--font-serif); margin-top: 4px;">
                        <?= number_format((float)$room['base_price'], 0, ',', '.') ?> ₫ <span style="font-size: 14px; font-weight: normal; color: var(--text-muted);">/ đêm</span>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/booking" method="get">
                    <input type="hidden" name="room_type_id" value="<?= (int)$room['id'] ?>">

                    <div class="form-group" style="margin-bottom: 14px;">
                        <label class="form-label" for="detailCheckIn">Ngày nhận phòng</label>
                        <input type="date" name="check_in" id="detailCheckIn" class="form-control" value="<?= htmlspecialchars($checkIn) ?>" min="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 14px;">
                        <label class="form-label" for="detailCheckOut">Ngày trả phòng</label>
                        <input type="date" name="check_out" id="detailCheckOut" class="form-control" value="<?= htmlspecialchars($checkOut) ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label" for="detailGuests">Số lượng khách</label>
                        <select name="guests" id="detailGuests" class="form-select">
                            <?php for ($i = 1; $i <= (int)$room['max_guests']; $i++): ?>
                                <option value="<?= $i ?>" <?= ($guests == $i) ? 'selected' : '' ?>><?= $i ?> khách</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div style="background: var(--primary-soft); padding: 14px; border-radius: var(--radius-sm); margin-bottom: 20px;">
                        <div class="booking-calc-row">
                            <span>Tình trạng phòng:</span>
                            <strong style="color: var(--success);">
                                <?= $availableCount > 0 ? "Còn {$availableCount} phòng trống" : "Hết phòng trong ngày chọn" ?>
                            </strong>
                        </div>
                        <div class="booking-calc-row">
                            <span>Đảm bảo giữ phòng:</span>
                            <span>Xác nhận tức thì</span>
                        </div>
                    </div>

                    <?php if ($availableCount > 0): ?>
                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 12px;">
                            Tiến hành đặt phòng
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-dark btn-block btn-lg" disabled style="margin-bottom: 12px; opacity: 0.6; cursor: not-allowed;">
                            Hết phòng ngày này
                        </button>
                    <?php endif; ?>

                    <button type="button" class="btn btn-outline btn-block js-wishlist-btn <?= $isWishlisted ? 'active' : '' ?>" data-room-type-id="<?= (int)$room['id'] ?>">
                        <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        <span><?= $isWishlisted ? 'Đã lưu trong yêu thích' : 'Lưu vào phòng yêu thích' ?></span>
                    </button>
                </form>

                <div style="border-top: 1px solid var(--border-light); margin-top: 20px; padding-top: 16px; font-size: 12px; color: var(--text-muted); text-align: center;">
                    Cần hỗ trợ tư vấn gấp? Gọi ngay hotline: <br>
                    <a href="tel:02433331000" style="color: var(--primary); font-weight: 700; font-size: 15px;">024 3333 1000</a>
                </div>
            </div>
        </div>
    </div>
</div>

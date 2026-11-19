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
                    <span style="color: #f59e0b;">★★★★★</span>
                    <span>Khách sạn 5 sao cao cấp</span>
                    <span>•</span>
                    <span>Tầm nhìn toàn cảnh thành phố</span>
                </div>
            </div>

            <div class="detail-price-box">
                <span class="price-label">Giá niêm yết tiêu chuẩn</span>
                <div class="price-value"><?= number_format((float)$room['base_price'], 0, ',', '.') ?> ₫ <small style="font-size: 14px; font-weight: normal; color: var(--text-muted);">/ đêm</small></div>
            </div>
        </div>
    </div>

    <!-- GALLERY SECTION -->
    <div class="gallery-grid">
        <div class="gallery-main">
            <img id="detailMainImage" src="<?= BASE_URL . '/' . htmlspecialchars($room['thumbnail'] ?: 'assets/images/hotel-banner.png') ?>" alt="<?= htmlspecialchars($room['name']) ?>" onerror="this.src='<?= BASE_URL ?>/assets/images/hotel-banner.png'">
        </div>
        <div class="gallery-thumbs">
            <?php if (!empty($room['images'])): ?>
                <?php foreach (array_slice($room['images'], 0, 2) as $img): ?>
                    <div class="gallery-thumb-item">
                        <img src="<?= BASE_URL . '/' . htmlspecialchars($img['image_url']) ?>" alt="Thumbnail" class="js-gallery-thumb" style="cursor: pointer;" onerror="this.src='<?= BASE_URL ?>/assets/images/hotel-banner.png'">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="gallery-thumb-item">
                    <img src="<?= BASE_URL ?>/assets/images/hotel-banner.png" alt="Thumbnail" class="js-gallery-thumb" style="cursor: pointer;">
                </div>
                <div class="gallery-thumb-item">
                    <img src="<?= BASE_URL ?>/assets/images/hotel-banner.png" alt="Thumbnail" class="js-gallery-thumb" style="cursor: pointer;">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- CONTENT & BOOKING WIDGET -->
    <div class="detail-layout">
        <!-- LEFT: ROOM INFO -->
        <div class="detail-main-info">
            <!-- SPECS BAR -->
            <div class="detail-specs-bar">
                <div class="detail-spec">
                    <div class="detail-spec-icon">📐</div>
                    <div class="detail-spec-text">
                        <span>Diện tích</span>
                        <strong><?= (float)$room['area'] ?> m²</strong>
                    </div>
                </div>
                <div class="detail-spec">
                    <div class="detail-spec-icon">🛏</div>
                    <div class="detail-spec-text">
                        <span>Loại giường</span>
                        <strong><?= htmlspecialchars($room['bed_type']) ?></strong>
                    </div>
                </div>
                <div class="detail-spec">
                    <div class="detail-spec-icon">👥</div>
                    <div class="detail-spec-text">
                        <span>Sức chứa</span>
                        <strong>Tối đa <?= (int)$room['max_guests'] ?> khách</strong>
                    </div>
                </div>
                <div class="detail-spec">
                    <div class="detail-spec-icon">🏙</div>
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
                    <?php if (!empty($room['amenities'])): ?>
                        <?php foreach ($room['amenities'] as $am): ?>
                            <div class="amenity-item">
                                <span class="icon">✓</span>
                                <div>
                                    <strong><?= htmlspecialchars($am['name']) ?></strong>
                                    <?php if (!empty($am['description'])): ?>
                                        <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($am['description']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="amenity-item"><span class="icon">✓</span> WiFi tốc độ cao miễn phí</div>
                        <div class="amenity-item"><span class="icon">✓</span> Điều hòa hai chiều thông minh</div>
                        <div class="amenity-item"><span class="icon">✓</span> Smart TV 55 inch Ultra HD</div>
                        <div class="amenity-item"><span class="icon">✓</span> Minibar cao cấp</div>
                        <div class="amenity-item"><span class="icon">✓</span> Bồn tắm nằm và vòi sen đứng</div>
                        <div class="amenity-item"><span class="icon">✓</span> Két an toàn điện tử</div>
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
                    <p style="color: var(--text-muted); margin: 0;">Chưa có đánh giá nào cho hạng phòng này. Hãy là người đầu tiên trải nghiệm và chia sẻ cảm nhận!</p>
                <?php else: ?>
                    <div style="display: grid; gap: 16px;">
                        <?php foreach ($reviews as $rev): ?>
                            <div style="border-bottom: 1px solid var(--border-light); padding-bottom: 14px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                    <strong><?= htmlspecialchars($rev['guest_name']) ?></strong>
                                    <span style="color: #f59e0b;"><?= str_repeat('★', (int)$rev['rating']) ?></span>
                                </div>
                                <div style="font-size: 13px; color: var(--text); line-height: 1.5;">
                                    "<?= htmlspecialchars($rev['comment']) ?>"
                                </div>
                                <small style="font-size: 11px; color: var(--text-muted);"><?= date('d/m/Y', strtotime($rev['created_at'])) ?></small>
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
                    <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Giá tốt nhất từ khách sạn</div>
                    <div style="font-size: 26px; font-weight: 700; color: var(--primary); font-family: var(--font-serif); margin-top: 4px;">
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
                            Tiến hành đặt phòng →
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-dark btn-block btn-lg" disabled style="margin-bottom: 12px; opacity: 0.6; cursor: not-allowed;">
                            Hết phòng ngày này
                        </button>
                    <?php endif; ?>

                    <button type="button" class="btn btn-outline btn-block js-wishlist-btn <?= $isWishlisted ? 'active' : '' ?>" data-room-type-id="<?= (int)$room['id'] ?>">
                        ♥ <?= $isWishlisted ? 'Đã lưu trong yêu thích' : 'Lưu vào phòng yêu thích' ?>
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

<div class="container" style="padding-top: 30px; padding-bottom: 70px;">
    <!-- PAGE HEADER -->
    <div class="section-header" style="text-align: left; margin-bottom: 30px; max-width: 100%;">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Trang chủ</a> <span>/</span> <strong>Phòng &amp; Hạng phòng</strong>
        </div>
        <span class="section-eyebrow">Hạng phòng &amp; Suites</span>
        <h1 style="font-size: 36px; margin: 0 0 10px;">Hạng phòng &amp; Suites</h1>
        <p class="section-desc" style="margin: 0;">
            Trải nghiệm không gian nghỉ ngơi yên bình với tầm nhìn bao quát toàn cảnh thành phố Hà Nội và các tiện ích công nghệ cao.
        </p>
    </div>

    <!-- FILTER BAR -->
    <div class="booking-bar" style="margin-bottom: 40px; box-shadow: var(--shadow-sm);">
        <form action="<?= BASE_URL ?>/rooms" method="get" class="booking-form-grid rooms-filter-grid">
            <div class="form-group">
                <label class="form-label" for="filterSearch">Tìm kiếm hạng phòng</label>
                <input type="text" name="q" id="filterSearch" class="form-control" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" placeholder="Tên phòng, giường...">
            </div>

            <div class="form-group">
                <label class="form-label" for="filterCheckIn">Ngày nhận</label>
                <input type="date" name="check_in" id="filterCheckIn" class="form-control" value="<?= htmlspecialchars($filters['check_in'] ?: date('Y-m-d', strtotime('+1 day'))) ?>" min="<?= date('Y-m-d') ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="filterCheckOut">Ngày trả</label>
                <input type="date" name="check_out" id="filterCheckOut" class="form-control" value="<?= htmlspecialchars($filters['check_out'] ?: date('Y-m-d', strtotime('+2 days'))) ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="filterGuests">Số khách</label>
                <select name="guests" id="filterGuests" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="1" <?= ($filters['guests'] === '1') ? 'selected' : '' ?>>1 khách</option>
                    <option value="2" <?= ($filters['guests'] === '2') ? 'selected' : '' ?>>2 khách</option>
                    <option value="3" <?= ($filters['guests'] === '3') ? 'selected' : '' ?>>3 khách</option>
                    <option value="4" <?= ($filters['guests'] === '4') ? 'selected' : '' ?>>4 khách trở lên</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="filterBed">Loại giường</label>
                <select name="bed_type" id="filterBed" class="form-select">
                    <option value="">Tất cả loại giường</option>
                    <option value="đôi" <?= (str_contains($filters['bed_type'], 'đôi')) ? 'selected' : '' ?>>Giường đôi</option>
                    <option value="đơn" <?= (str_contains($filters['bed_type'], 'đơn')) ? 'selected' : '' ?>>Giường đơn</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="height: 48px; padding: 0 20px;">
                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <span>Lọc phòng</span>
                </button>
                <a href="<?= BASE_URL ?>/rooms" class="btn btn-outline" style="height: 48px; padding: 0 14px;" title="Đặt lại bộ lọc" aria-label="Đặt lại bộ lọc">
                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                </a>
            </div>
        </form>
    </div>

    <!-- ROOM RESULTS -->
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 14px; color: var(--text-muted);" class="tabular-nums">
            Tìm thấy <strong><?= count($roomTypes) ?></strong> hạng phòng phù hợp
            <?php if (!empty($filters['check_in']) && !empty($filters['check_out'])): ?>
                (từ <strong><?= date('d/m/Y', strtotime($filters['check_in'])) ?></strong> đến <strong><?= date('d/m/Y', strtotime($filters['check_out'])) ?></strong>)
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($roomTypes)): ?>
        <div style="background: #fff; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 48px; text-align: center;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-soft); color: var(--primary); display: inline-grid; place-items: center; margin-bottom: 16px;">
                <svg class="ui-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
            </div>
            <h3>Không tìm thấy phòng phù hợp với tiêu chí lọc</h3>
            <p style="color: var(--text-muted); margin-bottom: 24px;">Quý khách vui lòng thử thay đổi khoảng ngày, số lượng khách hoặc bỏ bớt các điều kiện lọc.</p>
            <a href="<?= BASE_URL ?>/rooms" class="btn btn-primary">Xem toàn bộ phòng</a>
        </div>
    <?php else: ?>
        <div class="rooms-grid">
            <?php foreach ($roomTypes as $room): ?>
                <?php
                    $isAvailable = !isset($room['available_count']) || (int)$room['available_count'] > 0;
                    $availText = isset($room['available_count']) ? 'Còn ' . (int)$room['available_count'] . ' phòng trống' : 'Có phòng sẵn sàng';
                ?>
                <div class="room-card">
                    <div class="room-image-wrap">
                        <img src="<?= htmlspecialchars(asset_url($room['thumbnail'] ?? '')) ?>" alt="<?= htmlspecialchars($room['name']) ?>" class="room-image" onerror="this.src='<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>'">

                        <?php if ($isAvailable): ?>
                            <span class="room-badge-avail tabular-nums"><?= $availText ?></span>
                        <?php else: ?>
                            <span class="room-badge-soldout">Hết phòng tạm thời</span>
                        <?php endif; ?>

                        <button type="button" class="room-wishlist-btn js-wishlist-btn <?= !empty($room['is_wishlisted']) ? 'active' : '' ?>" data-room-type-id="<?= (int)$room['id'] ?>" title="Lưu vào yêu thích" aria-label="Lưu vào yêu thích">
                            <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </button>

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
                            <div class="room-spec-item" title="Diện tích phòng">
                                <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                <span><?= (float)$room['area'] ?> m²</span>
                            </div>
                            <div class="room-spec-item" title="Loại giường">
                                <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                                <span><?= htmlspecialchars($room['bed_type']) ?></span>
                            </div>
                            <div class="room-spec-item" title="Số khách tối đa">
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
                            <a href="<?= BASE_URL ?>/room-detail?id=<?= (int)$room['id'] ?>&check_in=<?= urlencode($filters['check_in'] ?? '') ?>&check_out=<?= urlencode($filters['check_out'] ?? '') ?>&guests=<?= urlencode($filters['guests'] ?? '') ?>" class="btn btn-outline btn-block">
                                Chi tiết
                            </a>
                            <?php if ($isAvailable): ?>
                                <a href="<?= BASE_URL ?>/booking?room_type_id=<?= (int)$room['id'] ?>&check_in=<?= urlencode($filters['check_in'] ?? '') ?>&check_out=<?= urlencode($filters['check_out'] ?? '') ?>&guests=<?= urlencode($filters['guests'] ?? '') ?>" class="btn btn-primary btn-block">
                                    Đặt ngay
                                </a>
                            <?php else: ?>
                                <button type="button" class="btn btn-dark btn-block" disabled style="opacity: 0.6; cursor: not-allowed;">
                                    Hết phòng
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

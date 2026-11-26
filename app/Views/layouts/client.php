<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'LOTTE HOTEL HANOI', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('assets/css/client.css')) ?>">
    <script>
        window.BASE_URL = <?= json_encode(BASE_URL) ?>;
    </script>
</head>
<body>

<!-- TOP UTILITY BAR -->
<div class="top-bar">
    <div class="container">
        <div class="top-bar-left">
            <span class="top-bar-item">
                <svg class="ui-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>54 Liễu Giai, Ba Đình, Hà Nội</span>
            </span>
            <span class="top-bar-item">
                <svg class="ui-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <span>Hotline: <a href="tel:02433331000" class="top-bar-gold tabular-nums">024 3333 1000</a></span>
            </span>
        </div>
        <div class="top-bar-right">
            <span class="top-bar-item tabular-nums">
                <svg class="ui-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Check-in 14:00 · Check-out 12:00</span>
            </span>
            <span class="top-bar-item">Khách sạn 5 sao</span>
            <?php if (Auth::isAdmin()): ?>
                <a href="<?= BASE_URL ?>/admin" class="top-bar-gold" style="text-decoration: underline;">Trang Quản trị (Admin)</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- SITE HEADER -->
<header class="site-header">
    <div class="container">
        <a href="<?= BASE_URL ?>/" class="brand">
            <span class="brand-mark">L</span>
            <div class="brand-text">
                <span class="brand-title">LOTTE HOTEL</span>
                <span class="brand-sub">HANOI · VIETNAM</span>
            </div>
        </a>

        <nav>
            <ul class="nav-links">
                <li><a href="<?= BASE_URL ?>/" class="nav-link <?= ($activeNav ?? '') === 'home' ? 'active' : '' ?>">Trang chủ</a></li>
                <li><a href="<?= BASE_URL ?>/rooms" class="nav-link <?= ($activeNav ?? '') === 'rooms' ? 'active' : '' ?>">Phòng &amp; Suites</a></li>
                <li><a href="<?= BASE_URL ?>/services" class="nav-link <?= ($activeNav ?? '') === 'services' ? 'active' : '' ?>">Dịch vụ &amp; Tiện ích</a></li>
                <li><a href="<?= BASE_URL ?>/promotions" class="nav-link <?= ($activeNav ?? '') === 'promotions' ? 'active' : '' ?>">Ưu đãi</a></li>
                <li><a href="<?= BASE_URL ?>/booking/lookup" class="nav-link <?= ($activeNav ?? '') === 'lookup' ? 'active' : '' ?>">Tra cứu đơn</a></li>
                <li><a href="<?= BASE_URL ?>/about" class="nav-link <?= ($activeNav ?? '') === 'about' ? 'active' : '' ?>">Về khách sạn</a></li>
                <li><a href="<?= BASE_URL ?>/contact" class="nav-link <?= ($activeNav ?? '') === 'contact' ? 'active' : '' ?>">Liên hệ</a></li>
            </ul>
        </nav>

        <div class="header-actions">
            <?php if (Auth::check()): ?>
                <?php $user = Auth::user(); ?>
                <div class="user-menu-wrap">
                    <button class="user-menu-btn" type="button">
                        <span class="user-avatar-circle"><?= htmlspecialchars(mb_strtoupper(mb_substr($user['full_name'] ?? 'U', 0, 1))) ?></span>
                        <span><?= htmlspecialchars(mb_substr($user['full_name'] ?? 'Khách', 0, 15)) ?></span>
                        <small>▾</small>
                    </button>
                    <div class="user-dropdown">
                        <div class="user-dropdown-header">
                            <span class="user-dropdown-name"><?= htmlspecialchars($user['full_name'] ?? '') ?></span>
                            <span class="user-dropdown-email"><?= htmlspecialchars($user['email'] ?? '') ?></span>
                        </div>
                        <a href="<?= BASE_URL ?>/account" style="display:flex;align-items:center;gap:8px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><span>Tài khoản của tôi</span></a>
                        <a href="<?= BASE_URL ?>/my-bookings" style="display:flex;align-items:center;gap:8px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg><span>Lịch sử đặt phòng</span></a>
                        <a href="<?= BASE_URL ?>/wishlist" style="display:flex;align-items:center;gap:8px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg><span>Phòng yêu thích</span></a>
                        <?php if (Auth::isAdmin()): ?>
                            <div class="user-dropdown-divider"></div>
                            <a href="<?= BASE_URL ?>/admin" style="display:flex;align-items:center;gap:8px;color: var(--primary); font-weight: 600;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg><span>Quản trị hệ thống</span></a>
                        <?php endif; ?>
                        <div class="user-dropdown-divider"></div>
                        <form method="post" action="<?= BASE_URL ?>/logout" style="margin: 0;">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
                            <button type="submit" style="display:flex;align-items:center;gap:8px;color: var(--danger);"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg><span>Đăng xuất</span></button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="btn btn-outline btn-sm">Đăng nhập</a>
                <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-sm">Đăng ký</a>
            <?php endif; ?>

            <a href="<?= BASE_URL ?>/rooms" class="btn btn-dark btn-sm">Đặt phòng ngay</a>

            <button class="mobile-toggle" id="mobileMenuBtn" aria-label="Menu di động" aria-expanded="false">
                <svg class="ui-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
            </button>
        </div>
    </div>
</header>

<!-- MOBILE NAV DRAWER (STT 36: Responsive toàn website) -->
<div id="mobileNavDrawer" class="mobile-nav-drawer" role="navigation" aria-label="Menu di động">
    <div class="mobile-nav-header">
        <div class="brand">
            <span class="brand-mark" style="width:32px;height:32px;font-size:16px;">L</span>
            <div class="brand-text">
                <span class="brand-title" style="font-size:14px;">LOTTE HOTEL</span>
                <span class="brand-sub" style="font-size:10px;">Hanoi · Vietnam</span>
            </div>
        </div>
        <button id="mobileNavClose" aria-label="Đóng menu" style="background:none;border:none;cursor:pointer;color:var(--text-muted);display:inline-flex;align-items:center;justify-content:center;padding:4px;">
            <svg class="ui-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
    </div>
    <ul class="mobile-nav-links">
        <li><a href="<?= BASE_URL ?>/" class="<?= ($activeNav ?? '') === 'home' ? 'active' : '' ?>" style="display:flex;align-items:center;gap:10px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg><span>Trang chủ</span></a></li>
        <li><a href="<?= BASE_URL ?>/rooms" class="<?= ($activeNav ?? '') === 'rooms' ? 'active' : '' ?>" style="display:flex;align-items:center;gap:10px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg><span>Phòng &amp; Suites</span></a></li>
        <li><a href="<?= BASE_URL ?>/services" class="<?= ($activeNav ?? '') === 'services' ? 'active' : '' ?>" style="display:flex;align-items:center;gap:10px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg><span>Dịch vụ &amp; Tiện ích</span></a></li>
        <li><a href="<?= BASE_URL ?>/promotions" class="<?= ($activeNav ?? '') === 'promotions' ? 'active' : '' ?>" style="display:flex;align-items:center;gap:10px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg><span>Ưu đãi</span></a></li>
        <li><a href="<?= BASE_URL ?>/booking/lookup" class="<?= ($activeNav ?? '') === 'lookup' ? 'active' : '' ?>" style="display:flex;align-items:center;gap:10px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg><span>Tra cứu đơn</span></a></li>
        <li><a href="<?= BASE_URL ?>/about" class="<?= ($activeNav ?? '') === 'about' ? 'active' : '' ?>" style="display:flex;align-items:center;gap:10px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg><span>Về khách sạn</span></a></li>
        <li><a href="<?= BASE_URL ?>/contact" class="<?= ($activeNav ?? '') === 'contact' ? 'active' : '' ?>" style="display:flex;align-items:center;gap:10px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg><span>Liên hệ</span></a></li>
    </ul>
    <div style="padding: 20px 24px; border-top: 1px solid var(--border);">
        <?php if (Auth::check()): ?>
            <a href="<?= BASE_URL ?>/account" class="btn btn-outline" style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;margin-bottom:10px;">Tài khoản</a>
            <a href="<?= BASE_URL ?>/my-bookings" class="btn btn-outline" style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;margin-bottom:10px;">Lịch sử đặt phòng</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/login" class="btn btn-outline" style="display:block;width:100%;text-align:center;margin-bottom:10px;">Đăng nhập</a>
            <a href="<?= BASE_URL ?>/register" class="btn btn-primary" style="display:block;width:100%;text-align:center;margin-bottom:10px;">Đăng ký</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/rooms" class="btn btn-dark" style="display:block;width:100%;text-align:center;">Đặt phòng ngay</a>
    </div>
</div>
<div id="mobileNavOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1099;"></div>

<!-- FLASH MESSAGES -->
<?php if (!empty($_SESSION['flash_success']) || !empty($_SESSION['flash_error']) || !empty($_SESSION['flash_warning'])): ?>
    <div class="container flash-container">
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success">
                <span style="display:inline-flex;align-items:center;gap:8px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><?= htmlspecialchars($_SESSION['flash_success']) ?></span>
                <?php unset($_SESSION['flash_success']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger">
                <span style="display:inline-flex;align-items:center;gap:8px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg><?= htmlspecialchars($_SESSION['flash_error']) ?></span>
                <?php unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_warning'])): ?>
            <div class="alert alert-warning">
                <span style="display:inline-flex;align-items:center;gap:8px;"><svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg><?= htmlspecialchars($_SESSION['flash_warning']) ?></span>
                <?php unset($_SESSION['flash_warning']); ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- MAIN CONTENT -->
<main class="site-main">
    <?= $content ?>
</main>

<!-- SITE FOOTER -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="brand" style="margin-bottom: 16px;">
                    <span class="brand-mark" style="background: rgba(255,255,255,0.08); border-color: var(--gold); color: var(--gold);">L</span>
                    <div class="brand-text">
                        <span class="brand-title" style="color: #fff;">LOTTE HOTEL</span>
                        <span class="brand-sub" style="color: var(--gold);">HANOI · 5-STAR LUXURY</span>
                    </div>
                </div>
                <p style="font-size: 13px; line-height: 1.6; color: #a5abb0;">
                    Tọa lạc tại tòa tháp biểu tượng giữa trung tâm thủ đô Hà Nội, LOTTE HOTEL HANOI mang tới chuẩn mực nghỉ dưỡng đẳng cấp với không gian sang trọng, dịch vụ chuyên nghiệp và tầm nhìn ngoạn mục ra toàn cảnh thành phố.
                </p>
            </div>

            <div class="footer-col">
                <h4>Khám phá</h4>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/rooms">Phòng &amp; Hạng phòng</a></li>
                    <li><a href="<?= BASE_URL ?>/services">Ẩm thực &amp; Spa</a></li>
                    <li><a href="<?= BASE_URL ?>/promotions">Chương trình ưu đãi</a></li>
                    <li><a href="<?= BASE_URL ?>/booking/lookup">Tra cứu đơn đặt</a></li>
                    <li><a href="<?= BASE_URL ?>/about">Về LOTTE HOTEL</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Chính sách &amp; Dịch vụ</h4>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/about#policies">Chính sách nhận / trả phòng</a></li>
                    <li><a href="<?= BASE_URL ?>/about#guarantee">Cam kết bảo mật thông tin</a></li>
                    <li><a href="<?= BASE_URL ?>/services">Dịch vụ đưa đón sân bay</a></li>
                    <li><a href="<?= BASE_URL ?>/contact">Hỗ trợ khách hàng 24/7</a></li>
                    <li><a href="<?= BASE_URL ?>/admin/login">Dành cho Quản trị viên</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Thông tin liên hệ</h4>
                <div class="footer-contact-item">
                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>54 Liễu Giai, Phường Cống Vị, Quận Ba Đình, Hà Nội, Việt Nam</span>
                </div>
                <div class="footer-contact-item">
                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>Điện thoại: <strong class="tabular-nums">024 3333 1000</strong></span>
                </div>
                <div class="footer-contact-item">
                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    <span>Email: <strong>info@lottehotel.vn</strong></span>
                </div>
                <div class="footer-contact-item">
                    <svg class="ui-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    <span>Website: <strong>www.lottehotel.vn</strong></span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div>© <?= date('Y') ?> LOTTE HOTEL HANOI. Tất cả các quyền được bảo lưu. Phát triển trên nền tảng PHP MVC &amp; MySQL.</div>
            <div>Tiêu chuẩn 5 sao Quốc tế · Hệ thống Đặt phòng An toàn &amp; Bảo mật</div>
        </div>
    </div>
</footer>

<script src="<?= htmlspecialchars(asset_url('assets/js/client.js')) ?>"></script>
</body>
</html>

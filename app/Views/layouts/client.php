<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'LOTTE HOTEL HANOI', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/client.css">
    <script>
        window.BASE_URL = <?= json_encode(BASE_URL) ?>;
    </script>
</head>
<body>

<!-- TOP UTILITY BAR -->
<div class="top-bar">
    <div class="container">
        <div class="top-bar-left">
            <span class="top-bar-item">📍 54 Liễu Giai, Ba Đình, Hà Nội</span>
            <span class="top-bar-item">📞 Hotline: <a href="tel:02433331000" class="top-bar-gold">024 3333 1000</a></span>
        </div>
        <div class="top-bar-right">
            <span class="top-bar-item">🕒 Check-in 14:00 · Check-out 12:00</span>
            <span class="top-bar-item">✦ Tiêu chuẩn 5 sao quốc tế</span>
            <?php if (Auth::isAdmin()): ?>
                <a href="<?= BASE_URL ?>/admin" class="top-bar-gold" style="text-decoration: underline;">⚙ Trang Quản trị (Admin)</a>
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
                        <a href="<?= BASE_URL ?>/account">👤 Tài khoản của tôi</a>
                        <a href="<?= BASE_URL ?>/my-bookings">📋 Lịch sử đặt phòng</a>
                        <a href="<?= BASE_URL ?>/wishlist">❤️ Phòng yêu thích</a>
                        <?php if (Auth::isAdmin()): ?>
                            <div class="user-dropdown-divider"></div>
                            <a href="<?= BASE_URL ?>/admin" style="color: var(--primary); font-weight: 600;">⚙ Quản trị hệ thống</a>
                        <?php endif; ?>
                        <div class="user-dropdown-divider"></div>
                        <form method="post" action="<?= BASE_URL ?>/logout" style="margin: 0;">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">
                            <button type="submit" style="color: var(--danger);">↪ Đăng xuất</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="btn btn-outline btn-sm">Đăng nhập</a>
                <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-sm">Đăng ký</a>
            <?php endif; ?>

            <a href="<?= BASE_URL ?>/rooms" class="btn btn-dark btn-sm">Đặt phòng ngay</a>

            <button class="mobile-toggle" id="mobileMenuBtn" aria-label="Menu di động">☰</button>
        </div>
    </div>
</header>

<!-- FLASH MESSAGES -->
<?php if (!empty($_SESSION['flash_success']) || !empty($_SESSION['flash_error']) || !empty($_SESSION['flash_warning'])): ?>
    <div class="container flash-container">
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success">
                <span>✓ <?= htmlspecialchars($_SESSION['flash_success']) ?></span>
                <?php unset($_SESSION['flash_success']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger">
                <span>⚠ <?= htmlspecialchars($_SESSION['flash_error']) ?></span>
                <?php unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_warning'])): ?>
            <div class="alert alert-warning">
                <span>ℹ <?= htmlspecialchars($_SESSION['flash_warning']) ?></span>
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
                    <span>📍</span>
                    <span>54 Liễu Giai, Phường Cống Vị, Quận Ba Đình, Hà Nội, Việt Nam</span>
                </div>
                <div class="footer-contact-item">
                    <span>📞</span>
                    <span>Điện thoại: <strong>024 3333 1000</strong></span>
                </div>
                <div class="footer-contact-item">
                    <span>✉</span>
                    <span>Email: <strong>info@lottehotel.vn</strong></span>
                </div>
                <div class="footer-contact-item">
                    <span>🌐</span>
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

<script src="<?= BASE_URL ?>/assets/js/client.js"></script>
</body>
</html>

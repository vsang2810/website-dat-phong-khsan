<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin', ENT_QUOTES, 'UTF-8') ?> | Lotte Hotel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body class="admin-body">
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="<?= BASE_URL ?>/admin">
            <span class="brand-mark">L</span>
            <span><strong>LOTTE</strong><small>HOTEL HANOI</small></span>
        </a>
        <div class="sidebar-label">WORKSPACE</div>
        <nav class="nav-menu">
            <a class="nav-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin"><span class="nav-icon">▦</span> Tổng quan</a>
            <a class="nav-link <?= ($activeMenu ?? '') === 'bookings' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/bookings"><span class="nav-icon">▤</span> Đặt phòng</a>
            <a class="nav-link <?= in_array(($activeMenu ?? ''), ['hotels', 'room-types'], true) ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/hotels"><span class="nav-icon">▣</span> Khách sạn &amp; loại phòng</a>
            <a class="nav-link <?= ($activeMenu ?? '') === 'rooms' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/rooms"><span class="nav-icon">▦</span> Phòng thực tế</a>
            <a class="nav-link <?= ($activeMenu ?? '') === 'customers' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/customers"><span class="nav-icon">♙</span> Khách hàng</a>
            <a class="nav-link nav-coming" href="#" aria-disabled="true"><span class="nav-icon">◫</span> Thanh toán <em>Sắp có</em></a>
            <a class="nav-link nav-coming" href="#" aria-disabled="true"><span class="nav-icon">◇</span> Khuyến mãi <em>Sắp có</em></a>
        </nav>
        <div class="sidebar-bottom">
            <div class="sidebar-label">SYSTEM</div>
            <a class="nav-link nav-coming" href="#" aria-disabled="true"><span class="nav-icon">⚙</span> Cài đặt <em>Sắp có</em></a>
            <div class="sidebar-profile">
                <div class="avatar"><?= htmlspecialchars(mb_strtoupper(mb_substr(Auth::user()['full_name'] ?? 'A', 0, 1)), ENT_QUOTES, 'UTF-8') ?></div>
                <div class="profile-info">
                    <strong><?= htmlspecialchars(Auth::user()['full_name'] ?? 'Administrator', ENT_QUOTES, 'UTF-8') ?></strong>
                    <small><?= htmlspecialchars(Auth::user()['role'] ?? 'admin', ENT_QUOTES, 'UTF-8') ?></small>
                </div>
                <form method="post" action="<?= BASE_URL ?>/logout">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                    <button class="logout-button" title="Đăng xuất" aria-label="Đăng xuất">↪</button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main-area">
        <header class="topbar">
            <button class="mobile-menu" id="menuToggle" aria-label="Mở menu">☰</button>
            <div class="breadcrumb">Workspace <span>/</span> <strong><?= htmlspecialchars($title ?? '', ENT_QUOTES, 'UTF-8') ?></strong></div>
            <div class="topbar-right">
                <span class="live-dot"></span><span class="system-status">Hệ thống hoạt động</span>
                <div class="topbar-date"><?= date('d/m/Y') ?></div>
            </div>
        </header>
        <section class="page-content"><?= $content ?></section>
        <footer class="footer">© <?= date('Y') ?> Lotte Hotel Hanoi · Admin Management</footer>
    </main>
</div>
<script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
</body>
</html>

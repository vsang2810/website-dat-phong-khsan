<div class="login-page">
    <div class="login-visual">
        <div class="login-visual-overlay"></div>
        <div class="login-brand"><span class="brand-mark">L</span><span><strong>LOTTE</strong><small>HOTEL HANOI</small></span></div>
        <div class="login-quote">
            <span class="eyebrow">HOSPITALITY, REDEFINED</span>
            <h1>Every detail<br>matters.</h1>
            <p>Không gian quản trị dành cho đội ngũ vận hành Lotte Hotel Hanoi.</p>
        </div>
        <span class="visual-caption">LOTTE HOTEL HANOI · MANAGEMENT PORTAL</span>
    </div>
    <div class="login-panel">
        <div class="login-form-wrap">
            <div class="login-mobile-brand"><span class="brand-mark">L</span><strong>LOTTE HOTEL HANOI</strong></div>
            <span class="eyebrow">WELCOME BACK</span>
            <h2>Đăng nhập</h2>
            <p class="muted">Đăng nhập để tiếp tục vào hệ thống quản trị.</p>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="alert-error"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/login" class="login-form">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                <label for="email">Địa chỉ email</label>
                <input id="email" type="email" name="email" placeholder="admin@lottehotel.vn" autocomplete="username" required>
                <label for="password">Mật khẩu</label>
                <input id="password" type="password" name="password" placeholder="Nhập mật khẩu" autocomplete="current-password" required>
                <button class="btn-primary btn-login" type="submit">Đăng nhập <span>→</span></button>
            </form>
            <div class="login-note">Chỉ tài khoản có quyền Admin hoặc Staff mới được truy cập.</div>
        </div>
        <div class="login-footer">© <?= date('Y') ?> Lotte Hotel Hanoi</div>
    </div>
</div>

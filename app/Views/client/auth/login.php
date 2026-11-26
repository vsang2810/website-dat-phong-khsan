<div class="container-narrow" style="padding-top: 50px; padding-bottom: 80px; max-width: 480px;">
    <div class="checkout-card" style="box-shadow: var(--shadow-md); padding: 36px;">
        <div style="text-align: center; margin-bottom: 28px;">
            <div class="brand-mark" style="margin: 0 auto 14px; width: 50px; height: 50px; font-size: 28px;">L</div>
            <span class="section-eyebrow" style="margin-bottom: 6px;">WELCOME TO LOTTE</span>
            <h1 style="font-size: 26px; margin: 0 0 6px;">Đăng Nhập Tài Khoản</h1>
            <p style="color: var(--text-muted); font-size: 14px; margin: 0;">
                Truy cập để quản lý đơn đặt phòng và nhận ưu đãi độc quyền.
            </p>
        </div>

        <form action="<?= BASE_URL ?>/login" method="post">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" for="loginEmail">Địa chỉ email</label>
                <input type="email" name="email" id="loginEmail" class="form-control" placeholder="name@example.com" required autocomplete="username">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" for="loginPassword">Mật khẩu</label>
                <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Nhập mật khẩu" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 48px; margin-bottom: 16px;">
                Đăng nhập
            </button>
        </form>

        <div style="text-align: center; font-size: 13px; color: var(--text-muted); margin-top: 20px; border-top: 1px solid var(--border-light); padding-top: 18px;">
            Chưa có tài khoản thành viên?
            <a href="<?= BASE_URL ?>/register" style="color: var(--primary); font-weight: 700; margin-left: 4px;">Đăng ký ngay</a>
        </div>

        <div style="margin-top: 20px; background: var(--primary-soft); padding: 12px; border-radius: var(--radius-sm); font-size: 12px; color: var(--text-muted); text-align: center;">
            Quản trị viên hoặc nhân viên khách sạn có thể đăng nhập bằng tài khoản Admin để chuyển hướng trực tiếp vào Hệ thống Quản trị.
        </div>
    </div>
</div>

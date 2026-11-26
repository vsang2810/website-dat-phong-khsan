<div class="container-narrow" style="padding-top: 50px; padding-bottom: 80px; max-width: 520px;">
    <div class="checkout-card" style="box-shadow: var(--shadow-md); padding: 36px;">
        <div style="text-align: center; margin-bottom: 28px;">
            <div class="brand-mark" style="margin: 0 auto 14px; width: 50px; height: 50px; font-size: 28px;">L</div>
            <span class="section-eyebrow" style="margin-bottom: 6px;">JOIN LOTTE REWARDS</span>
            <h1 style="font-size: 26px; margin: 0 0 6px;">Đăng Ký Tài Khoản</h1>
            <p style="color: var(--text-muted); font-size: 14px; margin: 0;">
                Trở thành thành viên để tích lũy quyền lợi và đặt phòng với giá tốt nhất.
            </p>
        </div>

        <form action="<?= BASE_URL ?>/register" method="post">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" for="regFullName">Họ và tên quý khách *</label>
                <input type="text" name="full_name" id="regFullName" class="form-control" placeholder="Nguyễn Văn A" required>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" for="regEmail">Địa chỉ email *</label>
                <input type="email" name="email" id="regEmail" class="form-control" placeholder="name@example.com" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label" for="regPhone">Số điện thoại *</label>
                    <input type="tel" name="phone" id="regPhone" class="form-control" placeholder="0901234567" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="regGender">Giới tính</label>
                    <select name="gender" id="regGender" class="form-select">
                        <option value="male">Nam</option>
                        <option value="female">Nữ</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                <div class="form-group">
                    <label class="form-label" for="regPassword">Mật khẩu *</label>
                    <input type="password" name="password" id="regPassword" class="form-control" placeholder="Tối thiểu 6 ký tự" minlength="6" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="regPasswordConfirm">Xác nhận mật khẩu *</label>
                    <input type="password" name="password_confirmation" id="regPasswordConfirm" class="form-control" placeholder="Nhập lại mật khẩu" minlength="6" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 48px; margin-bottom: 16px;">
                Hoàn tất đăng ký
            </button>
        </form>

        <div style="text-align: center; font-size: 13px; color: var(--text-muted); margin-top: 20px; border-top: 1px solid var(--border-light); padding-top: 18px;">
            Đã có tài khoản?
            <a href="<?= BASE_URL ?>/login" style="color: var(--primary); font-weight: 700; margin-left: 4px;">Đăng nhập ngay</a>
        </div>
    </div>
</div>

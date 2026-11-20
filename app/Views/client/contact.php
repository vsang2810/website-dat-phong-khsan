<div class="container" style="padding-top: 30px; padding-bottom: 70px;">
    <!-- BREADCRUMB -->
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/">Trang chủ</a> <span>/</span> <strong>Liên hệ &amp; Hỗ trợ</strong>
    </div>

    <div class="section-header" style="text-align: left; margin-bottom: 40px;">
        <span class="section-eyebrow">CUSTOMER SERVICE</span>
        <h1 style="font-size: 36px; margin: 0 0 10px;">Liên Hệ &amp; Hỗ Trợ Đặt Phòng</h1>
        <p class="section-desc" style="margin: 0;">
            Đội ngũ Chăm sóc Khách hàng của LOTTE HOTEL HANOI luôn sẵn sàng phục vụ 24/7 để hỗ trợ giải đáp mọi thắc mắc của quý khách.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 40px; margin-bottom: 60px;">
        <!-- CONTACT INFO -->
        <div>
            <div class="checkout-card" style="box-shadow: var(--shadow-sm); height: 100%;">
                <h3 style="font-size: 20px; margin-bottom: 20px;">LOTTE HOTEL HANOI</h3>

                <div style="display: grid; gap: 20px; font-size: 14px; margin-bottom: 30px;">
                    <div style="display: flex; gap: 14px;">
                        <span style="font-size: 20px; color: var(--primary);">📍</span>
                        <div>
                            <strong>Địa chỉ khách sạn:</strong>
                            <div style="color: var(--text-muted); margin-top: 2px;">
                                54 Liễu Giai, Phường Cống Vị, Quận Ba Đình, Hà Nội, Việt Nam
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 14px;">
                        <span style="font-size: 20px; color: var(--primary);">📞</span>
                        <div>
                            <strong>Tổng đài / Lễ tân:</strong>
                            <div style="color: var(--text-muted); margin-top: 2px;">
                                <a href="tel:02433331000" style="color: var(--primary); font-weight: 700; font-size: 16px;">024 3333 1000</a> (24/7)
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 14px;">
                        <span style="font-size: 20px; color: var(--primary);">✉</span>
                        <div>
                            <strong>Email hỗ trợ:</strong>
                            <div style="color: var(--text-muted); margin-top: 2px;">
                                <a href="mailto:info@lottehotel.vn">info@lottehotel.vn</a>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 14px;">
                        <span style="font-size: 20px; color: var(--primary);">🕒</span>
                        <div>
                            <strong>Giờ làm việc:</strong>
                            <div style="color: var(--text-muted); margin-top: 2px;">
                                Bộ phận Lễ tân &amp; Đặt phòng hoạt động liên tục 24/7.
                            </div>
                        </div>
                    </div>
                </div>

                <div style="background: var(--primary-soft); padding: 18px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                    <strong style="color: var(--dark); display: block; margin-bottom: 6px;">Quý khách cần tra cứu đơn đặt phòng?</strong>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">
                        Kiểm tra trạng thái phòng nhanh chóng bằng mã đặt phòng và email cá nhân.
                    </p>
                    <a href="<?= BASE_URL ?>/booking/lookup" class="btn btn-outline btn-sm">Tra cứu đơn ngay →</a>
                </div>
            </div>
        </div>

        <!-- CONTACT FORM -->
        <div>
            <div class="checkout-card" style="box-shadow: var(--shadow-sm);">
                <h3 style="font-size: 20px; margin-bottom: 8px;">Gửi Tin Nhắn Cho Khách Sạn</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 24px;">
                    Điền thông tin vào biểu mẫu dưới đây, chúng tôi sẽ phản hồi quý khách trong thời gian sớm nhất.
                </p>

                <form action="<?= BASE_URL ?>/contact" method="post">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" for="contactName">Họ và tên của quý khách *</label>
                        <input type="text" name="name" id="contactName" class="form-control" placeholder="Nguyễn Văn A" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" for="contactEmail">Địa chỉ email liên hệ *</label>
                        <input type="email" name="email" id="contactEmail" class="form-control" placeholder="name@example.com" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label" for="contactMessage">Nội dung tin nhắn / Yêu cầu tư vấn *</label>
                        <textarea name="message" id="contactMessage" class="form-control" rows="5" placeholder="Quý khách cần hỗ trợ thêm thông tin gì về dịch vụ, đặt phòng hoặc các sự kiện tại khách sạn?..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; height: 48px;">
                        Gửi tin nhắn liên hệ →
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

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

    <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 40px; margin-bottom: 60px;" class="contact-layout">
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

                    <div style="display: flex; gap: 14px;">
                        <span style="font-size: 20px; color: var(--primary);">🌐</span>
                        <div>
                            <strong>Mạng xã hội:</strong>
                            <div style="color: var(--text-muted); margin-top: 6px; display: flex; gap: 10px; flex-wrap: wrap;">
                                <a href="#" style="display:inline-flex;align-items:center;gap:5px;background:var(--primary-soft);border:1px solid var(--border);padding:5px 12px;border-radius:20px;font-size:12px;color:var(--dark);">📘 Facebook</a>
                                <a href="#" style="display:inline-flex;align-items:center;gap:5px;background:var(--primary-soft);border:1px solid var(--border);padding:5px 12px;border-radius:20px;font-size:12px;color:var(--dark);">📸 Instagram</a>
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

        <!-- CONTACT FORM (STT 33: validate + lưu DB) -->
        <div>
            <div class="checkout-card" style="box-shadow: var(--shadow-sm);">
                <h3 style="font-size: 20px; margin-bottom: 8px;">Gửi Tin Nhắn Cho Khách Sạn</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 24px;">
                    Điền thông tin vào biểu mẫu dưới đây, chúng tôi sẽ phản hồi quý khách trong thời gian sớm nhất.
                </p>

                <?php if (!empty($contactSuccess)): ?>
                <div style="background:#eaf5ef;border:1px solid #a3d9b7;border-radius:var(--radius-sm);padding:16px;margin-bottom:20px;display:flex;gap:12px;align-items:flex-start;">
                    <span style="font-size:20px;">✅</span>
                    <div>
                        <strong style="color:#23845b;display:block;margin-bottom:4px;">Gửi thành công!</strong>
                        <span style="font-size:13px;color:#2f3438;"><?= htmlspecialchars($contactSuccess) ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($contactError)): ?>
                <div style="background:var(--danger-bg);border:1px solid #f0b8b8;border-radius:var(--radius-sm);padding:16px;margin-bottom:20px;display:flex;gap:12px;align-items:flex-start;">
                    <span style="font-size:20px;">⚠</span>
                    <span style="font-size:13px;color:var(--danger);"><?= htmlspecialchars($contactError) ?></span>
                </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/contact" method="post" id="contactForm" novalidate>
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token()) ?>">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;" class="contact-name-phone-grid">
                        <div class="form-group">
                            <label class="form-label" for="contactName">Họ và tên *</label>
                            <input type="text" name="name" id="contactName" class="form-control" placeholder="Nguyễn Văn A" required minlength="2" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                            <div id="nameError" style="color:var(--danger);font-size:12px;margin-top:4px;display:none;">Vui lòng nhập họ tên (ít nhất 2 ký tự).</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="contactPhone">Số điện thoại</label>
                            <input type="tel" name="phone" id="contactPhone" class="form-control" placeholder="0901 234 567" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" for="contactEmail">Địa chỉ email liên hệ *</label>
                        <input type="email" name="email" id="contactEmail" class="form-control" placeholder="name@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        <div id="emailError" style="color:var(--danger);font-size:12px;margin-top:4px;display:none;">Vui lòng nhập địa chỉ email hợp lệ.</div>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" for="contactSubject">Chủ đề yêu cầu</label>
                        <select name="subject" id="contactSubject" class="form-select">
                            <option value="">-- Chọn chủ đề --</option>
                            <option value="booking" <?= (($_POST['subject'] ?? '') === 'booking') ? 'selected' : '' ?>>Hỗ trợ đặt phòng</option>
                            <option value="cancel" <?= (($_POST['subject'] ?? '') === 'cancel') ? 'selected' : '' ?>>Huỷ / Thay đổi đặt phòng</option>
                            <option value="services" <?= (($_POST['subject'] ?? '') === 'services') ? 'selected' : '' ?>>Thông tin dịch vụ tiện ích</option>
                            <option value="complaint" <?= (($_POST['subject'] ?? '') === 'complaint') ? 'selected' : '' ?>>Góp ý / Khiếu nại</option>
                            <option value="other" <?= (($_POST['subject'] ?? '') === 'other') ? 'selected' : '' ?>>Yêu cầu khác</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label" for="contactMessage">Nội dung tin nhắn *</label>
                        <textarea name="message" id="contactMessage" class="form-control" rows="5" placeholder="Quý khách cần hỗ trợ thêm thông tin gì về dịch vụ, đặt phòng hoặc các sự kiện tại khách sạn?..." required minlength="10" maxlength="1000"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        <div id="messageError" style="color:var(--danger);font-size:12px;margin-top:4px;display:none;">Vui lòng nhập nội dung (ít nhất 10 ký tự).</div>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:4px;text-align:right;">
                            <span id="charCount">0</span> / 1000 ký tự
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" id="contactSubmitBtn" style="width:100%;height:48px;">
                        ✉ Gửi tin nhắn liên hệ
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- STT 35: Google Maps Embed + Thông tin địa chỉ liên hệ đầy đủ -->
    <div style="margin-bottom: 60px;" id="map-section">
        <div class="section-header" style="text-align: left; margin-bottom: 24px;">
            <span class="section-eyebrow">VỊ TRÍ KHÁCH SẠN</span>
            <h2 style="font-size: 26px; margin: 0;">Tìm Chúng Tôi Trên Bản Đồ</h2>
        </div>
        <div style="border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-md); border: 1px solid var(--border);">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.908707012019!2d105.8151398!3d21.0286203!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab4b34a9fd91%3A0x4e1e1951a5a16ca1!2sLotte%20Hotel%20Hanoi!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s"
                width="100%"
                height="420"
                style="border:0;display:block;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Vị trí LOTTE HOTEL HANOI trên Google Maps">
            </iframe>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:24px;" class="map-info-grid">
            <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-md);padding:20px;text-align:center;">
                <div style="font-size:28px;margin-bottom:8px;">📍</div>
                <strong style="display:block;margin-bottom:6px;color:var(--dark);">Địa chỉ</strong>
                <p style="font-size:13px;color:var(--text-muted);margin:0;">54 Liễu Giai, Phường Cống Vị, Quận Ba Đình, Hà Nội</p>
            </div>
            <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-md);padding:20px;text-align:center;">
                <div style="font-size:28px;margin-bottom:8px;">🚗</div>
                <strong style="display:block;margin-bottom:6px;color:var(--dark);">Phương tiện di chuyển</strong>
                <p style="font-size:13px;color:var(--text-muted);margin:0;">Cách sân bay Nội Bài ~35km · Cách ga Hà Nội ~6km · Xe buýt tuyến 09, 33</p>
            </div>
            <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-md);padding:20px;text-align:center;">
                <div style="font-size:28px;margin-bottom:8px;">⏰</div>
                <strong style="display:block;margin-bottom:6px;color:var(--dark);">Giờ Check-in / Check-out</strong>
                <p style="font-size:13px;color:var(--text-muted);margin:0;">Nhận phòng: 14:00 · Trả phòng: 12:00 · Lễ tân 24/7</p>
            </div>
        </div>
    </div>
</div>

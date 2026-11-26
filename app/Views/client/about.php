<div class="container" style="padding-top: 30px; padding-bottom: 70px;">
    <!-- BREADCRUMB -->
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>/">Trang chủ</a> <span>/</span> <strong>Về khách sạn</strong>
    </div>

    <!-- HERO ABOUT -->
    <div class="section-header" style="text-align: left; margin-bottom: 40px;">
        <span class="section-eyebrow">LOTTE HOTEL HANOI</span>
        <h1 style="font-size: 38px; margin: 0 0 14px;">Tuyệt Tác Kiến Trúc &amp; Biểu Tượng Hiếu Khách</h1>
        <p class="section-desc" style="margin: 0; font-size: 16px; max-width: 800px;">
            LOTTE HOTEL HANOI tự hào là khách sạn 5 sao sang trọng bậc nhất thủ đô, kết nối hài hòa giữa nét đẹp truyền thống Việt Nam và chuẩn mực phục vụ quốc tế đẳng cấp.
        </p>
    </div>

    <!-- STT 13: Gallery hình ảnh khách sạn -->
    <div style="margin-bottom: 50px;">
        <div style="display: grid; grid-template-columns: 1.6fr 1fr; grid-template-rows: 210px 210px; gap: 12px; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md);" class="about-gallery-grid">
            <!-- Ảnh lớn bên trái -->
            <div style="grid-row: 1 / 3; overflow: hidden; position: relative;">
                <img id="aboutMainImage" src="<?= htmlspecialchars(asset_url($hotel['thumbnail'] ?? 'assets/images/hotel/lotte-hotel.jpg')) ?>" alt="<?= htmlspecialchars($hotel['name'] ?? 'LOTTE HOTEL HANOI') ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onerror="this.src='<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>'">
                <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.6));padding:20px 24px;">
                    <span style="color:#fff;font-size:14px;font-weight:600;">📸 Toàn cảnh LOTTE HOTEL HANOI</span>
                </div>
            </div>
            <!-- 2 ảnh phụ bên phải -->
            <div style="overflow: hidden; position: relative; cursor: pointer;" class="about-gallery-thumb" data-src="<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>">
                <img src="<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>" alt="Tiền sảnh khách sạn" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.5));padding:12px 16px;">
                    <span style="color:#fff;font-size:12px;">Sảnh đón khách & Lễ tân</span>
                </div>
            </div>
            <div style="overflow: hidden; position: relative; cursor: pointer;" class="about-gallery-thumb" data-src="<?= htmlspecialchars(asset_url('assets/images/rooms/executive.jpg')) ?>">
                <img src="<?= htmlspecialchars(asset_url('assets/images/rooms/executive.jpg')) ?>" alt="Phòng nghỉ Executive" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.5));padding:12px 16px;">
                    <span style="color:#fff;font-size:12px;">Phòng nghỉ cao cấp</span>
                </div>
            </div>
        </div>
        <!-- Thumbnail strip -->
        <div style="display: flex; gap: 10px; margin-top: 12px; overflow-x: auto; padding-bottom: 4px;" class="about-thumb-strip">
            <div class="about-thumb-item" style="flex:0 0 100px;height:68px;border-radius:var(--radius-sm);overflow:hidden;cursor:pointer;border:2px solid var(--primary);opacity:1;">
                <img src="<?= htmlspecialchars(asset_url($hotel['thumbnail'] ?? 'assets/images/hotel/lotte-hotel.jpg')) ?>" alt="Toàn cảnh" style="width:100%;height:100%;object-fit:cover;" class="js-about-thumb" onerror="this.src='<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>'">
            </div>
            <div class="about-thumb-item" style="flex:0 0 100px;height:68px;border-radius:var(--radius-sm);overflow:hidden;cursor:pointer;border:2px solid transparent;opacity:0.7;">
                <img src="<?= htmlspecialchars(asset_url('assets/images/hotel-banner.png')) ?>" alt="Tiền sảnh" style="width:100%;height:100%;object-fit:cover;" class="js-about-thumb">
            </div>
            <div class="about-thumb-item" style="flex:0 0 100px;height:68px;border-radius:var(--radius-sm);overflow:hidden;cursor:pointer;border:2px solid transparent;opacity:0.7;">
                <img src="<?= htmlspecialchars(asset_url('assets/images/rooms/executive.jpg')) ?>" alt="Phòng Executive" style="width:100%;height:100%;object-fit:cover;" class="js-about-thumb">
            </div>
            <div class="about-thumb-item" style="flex:0 0 100px;height:68px;border-radius:var(--radius-sm);overflow:hidden;cursor:pointer;border:2px solid transparent;opacity:0.7;">
                <img src="<?= htmlspecialchars(asset_url('assets/images/rooms/deluxe.jpg')) ?>" alt="Phòng Deluxe" style="width:100%;height:100%;object-fit:cover;" class="js-about-thumb">
            </div>
            <div class="about-thumb-item" style="flex:0 0 100px;height:68px;border-radius:var(--radius-sm);overflow:hidden;cursor:pointer;border:2px solid transparent;opacity:0.7;">
                <img src="<?= htmlspecialchars(asset_url('assets/images/rooms/presidential.jpg')) ?>" alt="Phòng Presidential" style="width:100%;height:100%;object-fit:cover;" class="js-about-thumb">
            </div>
            <div class="about-thumb-item" style="flex:0 0 100px;height:68px;border-radius:var(--radius-sm);overflow:hidden;cursor:pointer;border:2px solid transparent;opacity:0.7;">
                <img src="<?= htmlspecialchars(asset_url('assets/images/services/spa.jpg')) ?>" alt="Spa & Wellness" style="width:100%;height:100%;object-fit:cover;" class="js-about-thumb">
            </div>
        </div>
    </div>

    <!-- MAIN STORY -->
    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 50px; margin-bottom: 60px; align-items: start;" class="about-story-grid">
        <div>
            <h2 style="font-size: 26px; margin-bottom: 16px;">Vị Thế Biểu Tượng Giữa Lòng Hà Nội</h2>
            <p style="font-size: 15px; line-height: 1.8; color: var(--text-muted); margin-bottom: 18px;">
                Tọa lạc tại góc giao cắt giữa phố Liễu Giai và Đào Tấn - một trong những vị trí trung tâm sầm uất và đắt giá nhất Hà Nội, khách sạn LOTTE HOTEL HANOI sở hữu 318 phòng nghỉ sang trọng được bố trí từ tầng 33 đến tầng 64 của tòa cao ốc 65 tầng.
            </p>
            <p style="font-size: 15px; line-height: 1.8; color: var(--text-muted); margin-bottom: 18px;">
                Tòa nhà được lấy cảm hứng từ tà áo dài truyền thống của người phụ nữ Việt Nam, kết hợp cùng kỹ thuật xây dựng hiện đại tiên tiến. Mỗi góc nhìn từ khách sạn đều mở ra bức tranh toàn cảnh ngoạn mục của thủ đô nghìn năm văn hiến cùng vẻ đẹp êm đềm của Hồ Tây lộng gió.
            </p>
            <p style="font-size: 15px; line-height: 1.8; color: var(--text-muted);">
                Dưới sự quản lý chuyên nghiệp từ tập đoàn khách sạn LOTTE Hotels &amp; Resorts hàng đầu châu Á, chúng tôi cam kết đem lại cho quý khách sự thư thái và thăng hoa trong từng trải nghiệm.
            </p>
        </div>

        <div style="background: #fff; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 30px; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 18px; margin-bottom: 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">
                Thông Số Khách Sạn
            </h3>
            <div style="display: grid; gap: 14px; font-size: 14px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Tiêu chuẩn:</span>
                    <strong>5 Sao Quốc Tế</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Địa chỉ:</span>
                    <strong>54 Liễu Giai, Ba Đình, Hà Nội</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Số tầng khách sạn:</span>
                    <strong>Tầng 33 – 64 (Lotte Center)</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Giờ nhận phòng:</span>
                    <strong>14:00</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Giờ trả phòng:</span>
                    <strong>12:00</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Điện thoại liên hệ:</span>
                    <strong>024 3333 1000</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Email hỗ trợ:</span>
                    <strong>info@lottehotel.vn</strong>
                </div>
            </div>
            <div style="margin-top: 24px;">
                <a href="<?= BASE_URL ?>/rooms" class="btn btn-primary btn-block">Đặt phòng ngay</a>
            </div>
        </div>
    </div>

    <!-- FACILITIES HIGHLIGHTS (STT 14 bổ sung) -->
    <div style="margin-bottom: 60px;">
        <div class="section-header" style="text-align: left; margin-bottom: 30px;">
            <span class="section-eyebrow">WORLD-CLASS FACILITIES</span>
            <h2 style="font-size: 26px; margin: 0;">Tiện Ích & Trải Nghiệm Đẳng Cấp</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;" class="about-facilities-grid">
            <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-md);padding:28px 20px;text-align:center;transition:box-shadow 0.2s;">
                <div style="font-size:36px;margin-bottom:12px;">🏊</div>
                <h4 style="font-size:16px;margin-bottom:8px;">Hồ Bơi Vô Cực</h4>
                <p style="font-size:13px;color:var(--text-muted);margin:0;">Hồ bơi trên cao tầng 34 với tầm nhìn toàn cảnh thành phố.</p>
            </div>
            <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-md);padding:28px 20px;text-align:center;transition:box-shadow 0.2s;">
                <div style="font-size:36px;margin-bottom:12px;">💆</div>
                <h4 style="font-size:16px;margin-bottom:8px;">Evian Spa</h4>
                <p style="font-size:13px;color:var(--text-muted);margin:0;">Trung tâm chăm sóc sức khỏe cao cấp với liệu pháp quốc tế.</p>
            </div>
            <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-md);padding:28px 20px;text-align:center;transition:box-shadow 0.2s;">
                <div style="font-size:36px;margin-bottom:12px;">🍽</div>
                <h4 style="font-size:16px;margin-bottom:8px;">Nhà Hàng & Bar</h4>
                <p style="font-size:13px;color:var(--text-muted);margin:0;">5 nhà hàng và quán bar phục vụ ẩm thực Á-Âu đa dạng.</p>
            </div>
            <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-md);padding:28px 20px;text-align:center;transition:box-shadow 0.2s;">
                <div style="font-size:36px;margin-bottom:12px;">🏋️</div>
                <h4 style="font-size:16px;margin-bottom:8px;">Fitness Center</h4>
                <p style="font-size:13px;color:var(--text-muted);margin:0;">Phòng tập hiện đại trang bị thiết bị Technogym hàng đầu.</p>
            </div>
        </div>
    </div>

    <!-- POLICIES SECTION -->
    <div id="policies" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 40px; margin-bottom: 40px;">
        <h2 style="font-size: 24px; margin-bottom: 20px;">Quy Định &amp; Chính Sách Hoạt Động</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; font-size: 14px; line-height: 1.8; color: var(--text-muted);" class="about-policies-grid">
            <div>
                <h4 style="color: var(--dark); margin-bottom: 8px;">1. Thủ tục nhận và trả phòng</h4>
                <p>Khách hàng vui lòng xuất trình giấy tờ tùy thân hợp lệ (CCCD hoặc Hộ chiếu) khi làm thủ tục check-in tại quầy lễ tân tầng 38. Trường hợp nhận phòng sớm hoặc trả phòng muộn tùy thuộc vào tình trạng phòng sẵn có của khách sạn.</p>
            </div>
            <div>
                <h4 style="color: var(--dark); margin-bottom: 8px;">2. Chính sách hủy phòng &amp; Đổi lịch</h4>
                <p>Khách hàng có thể hủy phòng trực tuyến hoàn toàn miễn phí trước ngày nhận phòng ít nhất 24 giờ. Đối với các đơn đặt phòng trong dịp lễ tết hoặc chương trình khuyến mại đặc biệt, vui lòng kiểm tra điều kiện riêng của từng mã ưu đãi.</p>
            </div>
            <div>
                <h4 style="color: var(--dark); margin-bottom: 8px;">3. Phương thức thanh toán</h4>
                <p>Hỗ trợ thanh toán trực tuyến (QR Code, chuyển khoản ngân hàng) hoặc thanh toán trực tiếp tại quầy lễ tân. Chấp nhận tiền mặt VNĐ, thẻ Visa/Mastercard/JCB/AMEX.</p>
            </div>
            <div id="guarantee">
                <h4 style="color: var(--dark); margin-bottom: 8px;">4. Cam kết bảo mật</h4>
                <p>Thông tin cá nhân và thanh toán của quý khách được bảo vệ bằng mã hóa SSL 256-bit. Chúng tôi cam kết không chia sẻ thông tin với bên thứ ba khi chưa có sự đồng ý.</p>
            </div>
        </div>
    </div>
</div>

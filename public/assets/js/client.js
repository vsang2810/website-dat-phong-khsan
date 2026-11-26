/**
 * LOTTE HOTEL HANOI - CLIENT INTERACTIONS JS
 * STT 37: Kiểm thử & hoàn thiện các chức năng JavaScript
 */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle + overlay close
    const mobileBtn    = document.getElementById('mobileMenuBtn');
    const mobileMenu   = document.getElementById('mobileNavDrawer');
    const mobileClose  = document.getElementById('mobileNavClose');
    const mobileOverlay = document.getElementById('mobileNavOverlay');

    function openMobileNav() {
        if (!mobileMenu) return;
        mobileMenu.classList.add('open');
        if (mobileOverlay) mobileOverlay.style.display = 'block';
        if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }
    function closeMobileNav() {
        if (!mobileMenu) return;
        mobileMenu.classList.remove('open');
        if (mobileOverlay) mobileOverlay.style.display = 'none';
        if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    if (mobileBtn)     mobileBtn.addEventListener('click', openMobileNav);
    if (mobileClose)   mobileClose.addEventListener('click', closeMobileNav);
    if (mobileOverlay) mobileOverlay.addEventListener('click', closeMobileNav);
    if (mobileMenu)    mobileMenu.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMobileNav));

    // 2. User dropdown menu (desktop)
    const userMenuBtn  = document.querySelector('.user-menu-btn');
    const userDropdown = document.querySelector('.user-dropdown');
    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = userDropdown.classList.toggle('show');
            userMenuBtn.setAttribute('aria-expanded', String(isOpen));
        });
        document.addEventListener('click', () => {
            if (userDropdown.classList.contains('show')) {
                userDropdown.classList.remove('show');
                userMenuBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // 3. Wishlist Toggle AJAX
    document.querySelectorAll('.js-wishlist-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const roomTypeId = btn.dataset.roomTypeId;
            if (!roomTypeId) return;

            try {
                const formData = new FormData();
                formData.append('room_type_id', roomTypeId);

                const res = await fetch(`${window.BASE_URL || ''}/api/wishlist-toggle`, {
                    method: 'POST',
                    body: formData,
                });

                if (res.status === 401) {
                    showToast('Quý khách vui lòng đăng nhập để lưu phòng yêu thích!');
                    setTimeout(() => { window.location.href = `${window.BASE_URL || ''}/login`; }, 1200);
                    return;
                }

                const data = await res.json();
                if (data.success) {
                    btn.classList.toggle('active', data.is_wishlisted);
                    btn.setAttribute('title', data.is_wishlisted ? 'Đã lưu yêu thích' : 'Lưu vào yêu thích');
                    showToast(data.message);
                } else {
                    showToast(data.message || 'Có lỗi xảy ra.', 'error');
                }
            } catch (err) {
                console.error('Wishlist error:', err);
                showToast('Lỗi kết nối. Vui lòng thử lại.', 'error');
            }
        });
    });

    // 4. Dynamic Price Calculation on Booking Page
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        initBookingCalculator();
    }

    // 5. Print Booking Receipt
    const printBtn = document.getElementById('printReceiptBtn');
    if (printBtn) {
        printBtn.addEventListener('click', () => {
            window.print();
        });
    }

    // 6. Image gallery click on detail page
    const mainImg = document.getElementById('detailMainImage');
    document.querySelectorAll('.js-gallery-thumb').forEach(thumb => {
        thumb.addEventListener('click', () => {
            if (mainImg) {
                mainImg.src = thumb.src;
                // Active state cho thumb được chọn
                document.querySelectorAll('.js-gallery-thumb').forEach(t => t.style.outline = '');
                thumb.style.outline = '2px solid var(--primary, #8c7962)';
            }
        });
    });

    // 7. Contact Form Validation (STT 33 & 37)
    initContactForm();

    // 8. Auto-dismiss flash alerts sau 5 giây
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // 9. Smooth scroll cho anchor links (#)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // 10. STT 38: Validate ngày check-out phải sau check-in
    const globalCheckIn  = document.getElementById('heroCheckIn')  || document.getElementById('filterCheckIn')  || document.getElementById('bookingCheckIn');
    const globalCheckOut = document.getElementById('heroCheckOut') || document.getElementById('filterCheckOut') || document.getElementById('bookingCheckOut');
    if (globalCheckIn && globalCheckOut) {
        globalCheckIn.addEventListener('change', () => {
            if (globalCheckIn.value >= globalCheckOut.value) {
                const nextDay = new Date(globalCheckIn.value);
                nextDay.setDate(nextDay.getDate() + 1);
                globalCheckOut.value = nextDay.toISOString().split('T')[0];
                globalCheckOut.min   = nextDay.toISOString().split('T')[0];
            } else {
                globalCheckOut.min = new Date(new Date(globalCheckIn.value).getTime() + 86400000).toISOString().split('T')[0];
            }
        });
    }
});

// ─── Contact Form Validation (STT 33 & 37) ───────────────────────────────────
function initContactForm() {
    const form       = document.getElementById('contactForm');
    const nameInput  = document.getElementById('contactName');
    const emailInput = document.getElementById('contactEmail');
    const msgInput   = document.getElementById('contactMessage');
    const charCount  = document.getElementById('charCount');
    const nameErr    = document.getElementById('nameError');
    const emailErr   = document.getElementById('emailError');
    const msgErr     = document.getElementById('messageError');
    const submitBtn  = document.getElementById('contactSubmitBtn');

    if (!form) return;

    // Đếm ký tự textarea
    if (msgInput && charCount) {
        msgInput.addEventListener('input', () => {
            const len = msgInput.value.length;
            charCount.textContent = len;
            charCount.style.color = len > 950 ? 'var(--danger)' : 'var(--text-muted)';
        });
    }

    // Real-time validation
    function validateName() {
        const ok = nameInput && nameInput.value.trim().length >= 2;
        if (nameErr) nameErr.style.display = ok ? 'none' : 'block';
        if (nameInput) nameInput.style.borderColor = ok ? '' : 'var(--danger)';
        return ok;
    }
    function validateEmail() {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const ok = emailInput && re.test(emailInput.value.trim());
        if (emailErr) emailErr.style.display = ok ? 'none' : 'block';
        if (emailInput) emailInput.style.borderColor = ok ? '' : 'var(--danger)';
        return ok;
    }
    function validateMsg() {
        const ok = msgInput && msgInput.value.trim().length >= 10;
        if (msgErr) msgErr.style.display = ok ? 'none' : 'block';
        if (msgInput) msgInput.style.borderColor = ok ? '' : 'var(--danger)';
        return ok;
    }

    if (nameInput)  nameInput.addEventListener('blur', validateName);
    if (emailInput) emailInput.addEventListener('blur', validateEmail);
    if (msgInput)   msgInput.addEventListener('blur', validateMsg);

    // Submit
    form.addEventListener('submit', (e) => {
        const ok = validateName() & validateEmail() & validateMsg();
        if (!ok) {
            e.preventDefault();
            // Scroll tới lỗi đầu tiên
            const firstErr = form.querySelector('[style*="border-color: var(--danger)"]');
            if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Đang gửi...';
        }
    });
}

// ─── Booking Price Calculator (STT 38) ───────────────────────────────────────
function initBookingCalculator() {
    const basePriceEl        = document.getElementById('calcBasePrice');
    const nightsEl           = document.getElementById('calcNights');
    const roomSubtotalEl     = document.getElementById('calcRoomSubtotal');
    const serviceSubtotalEl  = document.getElementById('calcServiceSubtotal');
    const discountEl         = document.getElementById('calcDiscount');
    const grandTotalEl       = document.getElementById('calcGrandTotal');

    const checkInInput  = document.getElementById('bookingCheckIn');
    const checkOutInput = document.getElementById('bookingCheckOut');
    const basePrice     = parseFloat(basePriceEl ? basePriceEl.dataset.price : 0) || 0;

    let appliedDiscount = 0;

    function recalculate() {
        if (!checkInInput || !checkOutInput) return;

        const inDate  = new Date(checkInInput.value);
        const outDate = new Date(checkOutInput.value);

        let nights = 1;
        if (inDate && outDate && outDate > inDate) {
            const diffTime = Math.abs(outDate - inDate);
            nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;
        }

        if (nightsEl) nightsEl.textContent = nights;

        const roomSub = basePrice * nights;
        if (roomSubtotalEl) roomSubtotalEl.textContent = formatCurrency(roomSub);

        // Services
        let servicesTotal = 0;
        document.querySelectorAll('.js-service-check:checked').forEach(cb => {
            const srvPrice = parseFloat(cb.dataset.price) || 0;
            const srvId    = cb.value;
            const qtyInput = document.getElementById(`service_qty_${srvId}`);
            const qty      = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
            servicesTotal += srvPrice * qty;
        });

        if (serviceSubtotalEl) serviceSubtotalEl.textContent = formatCurrency(servicesTotal);

        const grand = Math.max(0, roomSub - appliedDiscount + servicesTotal);
        if (grandTotalEl) grandTotalEl.textContent = formatCurrency(grand);
    }

    // Check-in/out validation tự động
    if (checkInInput && checkOutInput) {
        checkInInput.addEventListener('change', () => {
            if (checkInInput.value >= checkOutInput.value) {
                const next = new Date(checkInInput.value);
                next.setDate(next.getDate() + 1);
                checkOutInput.value = next.toISOString().split('T')[0];
                checkOutInput.min   = next.toISOString().split('T')[0];
            }
            recalculate();
        });
        checkOutInput.addEventListener('change', recalculate);
    }

    document.querySelectorAll('.js-service-check').forEach(cb => {
        cb.addEventListener('change', (e) => {
            const itemCard = cb.closest('.service-check-item');
            if (itemCard) itemCard.classList.toggle('selected', cb.checked);

            const srvId   = cb.value;
            const qtyWrap = document.getElementById(`qty_wrap_${srvId}`);
            if (qtyWrap) qtyWrap.style.display = cb.checked ? 'flex' : 'none';

            recalculate();
        });
    });

    document.querySelectorAll('.js-service-qty').forEach(qtyInput => {
        qtyInput.addEventListener('input', recalculate);
    });

    // Voucher AJAX
    const applyVoucherBtn = document.getElementById('applyVoucherBtn');
    const voucherInput    = document.getElementById('voucherCodeInput');
    const voucherMsg      = document.getElementById('voucherMessage');

    if (applyVoucherBtn && voucherInput) {
        applyVoucherBtn.addEventListener('click', async () => {
            const code = voucherInput.value.trim().toUpperCase();
            voucherInput.value = code;
            if (!code) {
                if (voucherMsg) { voucherMsg.className = 'voucher-msg-error'; voucherMsg.textContent = 'Vui lòng nhập mã ưu đãi.'; }
                return;
            }

            const inDate  = new Date(checkInInput ? checkInInput.value : '');
            const outDate = new Date(checkOutInput ? checkOutInput.value : '');
            const diffMs  = Math.abs(outDate - inDate);
            const nights  = Math.max(1, Math.ceil(diffMs / (1000 * 60 * 60 * 24)) || 1);
            const subtotal = basePrice * nights;

            const formData = new FormData();
            formData.append('code', code);
            formData.append('subtotal', subtotal);

            try {
                applyVoucherBtn.disabled    = true;
                applyVoucherBtn.textContent = 'Đang kiểm tra...';

                const res  = await fetch(`${window.BASE_URL || ''}/api/check-promotion`, { method: 'POST', body: formData });
                const data = await res.json();

                if (data.valid) {
                    appliedDiscount = parseFloat(data.discount) || 0;
                    if (discountEl)  discountEl.textContent = '-' + formatCurrency(appliedDiscount);
                    if (voucherMsg) {
                        voucherMsg.className  = 'voucher-msg-success';
                        voucherMsg.textContent = `✓ Áp dụng thành công! Giảm ${formatCurrency(appliedDiscount)}`;
                    }
                } else {
                    appliedDiscount = 0;
                    if (discountEl)  discountEl.textContent = formatCurrency(0);
                    if (voucherMsg) {
                        voucherMsg.className  = 'voucher-msg-error';
                        voucherMsg.textContent = data.message || 'Mã ưu đãi không hợp lệ.';
                    }
                }
                recalculate();
            } catch (err) {
                console.error('Voucher error:', err);
                if (voucherMsg) { voucherMsg.className = 'voucher-msg-error'; voucherMsg.textContent = 'Lỗi kết nối. Vui lòng thử lại.'; }
            } finally {
                applyVoucherBtn.disabled    = false;
                applyVoucherBtn.textContent = 'Áp dụng';
            }
        });
    }

    recalculate();
}

// ─── Helpers ─────────────────────────────────────────────────────────────────
function formatCurrency(num) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(num)) + ' ₫';
}

function showToast(msg, type = 'success') {
    let toast = document.getElementById('siteToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'siteToast';
        toast.style.cssText = `
            position: fixed;
            bottom: 24px;
            right: 24px;
            max-width: 320px;
            background: #1b1e21;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.25);
            font-size: 14px;
            z-index: 9999;
            transition: opacity 0.3s, transform 0.3s;
            border-left: 4px solid #8c7962;
            line-height: 1.4;
        `;
        document.body.appendChild(toast);
    }
    toast.style.borderLeftColor = type === 'error' ? '#cf3d3d' : '#8c7962';
    toast.textContent = msg;
    toast.style.opacity   = '1';
    toast.style.transform = 'translateY(0)';
    toast.style.display   = 'block';

    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => {
        toast.style.opacity   = '0';
        toast.style.transform = 'translateY(8px)';
        setTimeout(() => { toast.style.display = 'none'; }, 300);
    }, 3500);
}


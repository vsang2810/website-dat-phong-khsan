/**
 * LOTTE HOTEL HANOI - CLIENT INTERACTIONS JS
 */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileNavDrawer');
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });
    }

    // 2. Wishlist Toggle AJAX
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
                    alert('Quý khách vui lòng đăng nhập để lưu phòng yêu thích!');
                    window.location.href = `${window.BASE_URL || ''}/login`;
                    return;
                }

                const data = await res.json();
                if (data.success) {
                    btn.classList.toggle('active', data.is_wishlisted);
                    btn.setAttribute('title', data.is_wishlisted ? 'Đã lưu yêu thích' : 'Lưu vào yêu thích');
                    showToast(data.message);
                } else {
                    alert(data.message || 'Có lỗi xảy ra.');
                }
            } catch (err) {
                console.error(err);
            }
        });
    });

    // 3. Dynamic Price Calculation on Booking Page
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        initBookingCalculator();
    }

    // 4. Print Booking Receipt
    const printBtn = document.getElementById('printReceiptBtn');
    if (printBtn) {
        printBtn.addEventListener('click', () => {
            window.print();
        });
    }

    // 5. Image gallery click on detail page
    const mainImg = document.getElementById('detailMainImage');
    document.querySelectorAll('.js-gallery-thumb').forEach(thumb => {
        thumb.addEventListener('click', () => {
            if (mainImg) {
                mainImg.src = thumb.src;
            }
        });
    });
});

function initBookingCalculator() {
    const basePriceEl = document.getElementById('calcBasePrice');
    const nightsEl = document.getElementById('calcNights');
    const roomSubtotalEl = document.getElementById('calcRoomSubtotal');
    const serviceSubtotalEl = document.getElementById('calcServiceSubtotal');
    const discountEl = document.getElementById('calcDiscount');
    const grandTotalEl = document.getElementById('calcGrandTotal');

    const checkInInput = document.getElementById('bookingCheckIn');
    const checkOutInput = document.getElementById('bookingCheckOut');
    const basePrice = parseFloat(basePriceEl ? basePriceEl.dataset.price : 0) || 0;

    let appliedDiscount = 0;

    function recalculate() {
        if (!checkInInput || !checkOutInput) return;

        const inDate = new Date(checkInInput.value);
        const outDate = new Date(checkOutInput.value);

        let nights = 1;
        if (inDate && outDate && outDate > inDate) {
            const diffTime = Math.abs(outDate - inDate);
            nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;
        }

        if (nightsEl) nightsEl.textContent = nights;

        const roomSub = basePrice * nights;
        if (roomSubtotalEl) {
            roomSubtotalEl.textContent = formatCurrency(roomSub);
        }

        // Services
        let servicesTotal = 0;
        document.querySelectorAll('.js-service-check:checked').forEach(cb => {
            const srvPrice = parseFloat(cb.dataset.price) || 0;
            const srvId = cb.value;
            const qtyInput = document.getElementById(`service_qty_${srvId}`);
            const qty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
            servicesTotal += srvPrice * qty;
        });

        if (serviceSubtotalEl) {
            serviceSubtotalEl.textContent = formatCurrency(servicesTotal);
        }

        const grand = Math.max(0, roomSub - appliedDiscount + servicesTotal);
        if (grandTotalEl) {
            grandTotalEl.textContent = formatCurrency(grand);
        }
    }

    // Listeners for dates and services
    if (checkInInput) checkInInput.addEventListener('change', recalculate);
    if (checkOutInput) checkOutInput.addEventListener('change', recalculate);

    document.querySelectorAll('.js-service-check').forEach(cb => {
        cb.addEventListener('change', (e) => {
            const itemCard = cb.closest('.service-check-item');
            if (itemCard) itemCard.classList.toggle('selected', cb.checked);

            const srvId = cb.value;
            const qtyWrap = document.getElementById(`qty_wrap_${srvId}`);
            if (qtyWrap) {
                qtyWrap.style.display = cb.checked ? 'flex' : 'none';
            }
            recalculate();
        });
    });

    document.querySelectorAll('.js-service-qty').forEach(qtyInput => {
        qtyInput.addEventListener('input', recalculate);
    });

    // Voucher apply button
    const applyVoucherBtn = document.getElementById('applyVoucherBtn');
    const voucherInput = document.getElementById('voucherCodeInput');
    const voucherMsg = document.getElementById('voucherMessage');

    if (applyVoucherBtn && voucherInput) {
        applyVoucherBtn.addEventListener('click', async () => {
            const code = voucherInput.value.trim();
            if (!code) {
                if (voucherMsg) voucherMsg.textContent = 'Vui lòng nhập mã ưu đãi.';
                return;
            }

            // Calculate current roomSubtotal
            const inDate = new Date(checkInInput.value);
            const outDate = new Date(checkOutInput.value);
            const diffTime = Math.abs(outDate - inDate);
            const nights = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1);
            const subtotal = basePrice * nights;

            const formData = new FormData();
            formData.append('code', code);
            formData.append('subtotal', subtotal);

            try {
                applyVoucherBtn.disabled = true;
                applyVoucherBtn.textContent = 'Đang kiểm tra...';

                const res = await fetch(`${window.BASE_URL || ''}/api/check-promotion`, {
                    method: 'POST',
                    body: formData,
                });
                const data = await res.json();

                if (data.valid) {
                    appliedDiscount = parseFloat(data.discount) || 0;
                    if (discountEl) discountEl.textContent = '-' + formatCurrency(appliedDiscount);
                    if (voucherMsg) {
                        voucherMsg.className = 'voucher-msg-success';
                        voucherMsg.textContent = `Áp dụng thành công! Giảm ${formatCurrency(appliedDiscount)}`;
                    }
                    recalculate();
                } else {
                    appliedDiscount = 0;
                    if (discountEl) discountEl.textContent = '0 ₫';
                    if (voucherMsg) {
                        voucherMsg.className = 'voucher-msg-error';
                        voucherMsg.textContent = data.message || 'Mã ưu đãi không hợp lệ.';
                    }
                    recalculate();
                }
            } catch (err) {
                console.error(err);
            } finally {
                applyVoucherBtn.disabled = false;
                applyVoucherBtn.textContent = 'Áp dụng';
            }
        });
    }

    recalculate();
}

function formatCurrency(num) {
    return new Intl.NumberFormat('vi-VN').format(Math.round(num)) + ' ₫';
}

function showToast(msg) {
    let toast = document.getElementById('siteToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'siteToast';
        toast.style.cssText = `
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #1b1e21;
            color: #fff;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            font-size: 14px;
            z-index: 9999;
            transition: opacity 0.3s;
            border-left: 4px solid #8c7962;
        `;
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.style.opacity = '1';
    toast.style.display = 'block';

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.style.display = 'none', 300);
    }, 3000);
}

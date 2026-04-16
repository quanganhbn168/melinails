document.addEventListener('DOMContentLoaded', function () {
    const cartState = window.CartState;
    const cartContainer = document.getElementById('order-summary-list');
    const form = document.getElementById('checkout-form');

    if (!cartState || !cartContainer || !form) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryTotal = document.getElementById('summary-total');
    const formatCurrency = (number) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);

    const renderSummary = (cart) => {
        const items = cart?.items || [];
        cartContainer.innerHTML = '';

        if (!items.length) {
            cartContainer.innerHTML = '<li class="list-group-item">Giỏ hàng trống</li>';
            submitBtn.disabled = true;
            submitBtn.classList.add('disabled');
            if (summarySubtotal) summarySubtotal.textContent = formatCurrency(0);
            if (summaryTotal) summaryTotal.textContent = formatCurrency(0);
            return;
        }

        submitBtn.disabled = false;
        submitBtn.classList.remove('disabled');

        let total = 0;
        items.forEach((item) => {
            const productName = item.name || 'Sản phẩm';
            const unitPrice = Number(item.price || 0);
            const qty = Number(item.quantity || 1);
            const subtotal = unitPrice * qty;
            total += subtotal;

            const variantText = item.variant_text || '';
            const itemHtml = `
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        ${productName}
                        ${variantText ? `<small class="d-block text-muted">${variantText}</small>` : ''}
                        <small class="d-block text-muted">SL: ${qty}</small>
                    </div>
                    <span class="item-total" data-total="${subtotal}">${formatCurrency(subtotal)}</span>
                </li>`;
            cartContainer.insertAdjacentHTML('beforeend', itemHtml);
        });

        if (summarySubtotal) summarySubtotal.textContent = formatCurrency(total);
        if (summaryTotal) summaryTotal.textContent = formatCurrency(total);
    };

    cartState.onUpdated(renderSummary);
    cartState.load().catch((error) => {
        cartState.handleError(error, 'Không thể tải dữ liệu giỏ hàng.');
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const phoneInput = document.getElementById('customer_phone');
        const nameInput = document.getElementById('customer_name');
        const addrInput = document.getElementById('customer_address');
        const phoneRegex = /^(0[3|5|7|8|9])[0-9]{8}$|^\+84[3|5|7|8|9][0-9]{8}$/;
        let isValid = true;

        form.querySelectorAll('.text-danger.validation-err').forEach((el) => el.remove());
        form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));

        const showError = (input, message) => {
            isValid = false;
            input.classList.add('is-invalid');
            const err = document.createElement('small');
            err.className = 'text-danger validation-err d-block mt-1';
            err.innerText = message;
            input.parentNode.appendChild(err);
        };

        if (!nameInput.value || nameInput.value.length < 2) {
            showError(nameInput, 'Vui lòng nhập họ tên hợp lệ.');
        }

        if (!phoneInput.value || !phoneRegex.test(phoneInput.value)) {
            showError(phoneInput, 'Số điện thoại không hợp lệ (ví dụ: 098xxxxxxx)');
        }

        if (!addrInput.value || addrInput.value.length < 10) {
            showError(addrInput, 'Vui lòng nhập địa chỉ cụ thể.');
        }

        if (isValid) {
            form.submit();
        }
    });
});


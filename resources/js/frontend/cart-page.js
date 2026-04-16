document.addEventListener('DOMContentLoaded', function () {
    const cartState = window.CartState;
    const cartTbody = document.getElementById('cart-items-container');
    const itemTemplate = document.getElementById('cart-item-template');

    if (!cartState || !cartTbody || !itemTemplate) return;

    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryTotal = document.getElementById('summary-total');
    const formatCurrency = (number) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number);

    const renderCart = (cart) => {
        const items = cart?.items || [];
        const total = Number(cart?.total_price || 0);
        cartTbody.innerHTML = '';

        if (!items.length) {
            cartTbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">Giỏ hàng của bạn đang trống.</td></tr>';
        } else {
            let html = '';
            items.forEach((item) => {
                const unitPrice = Number(item.price || 0);
                const qty = Number(item.quantity || 1);
                html += itemTemplate.innerHTML
                    .replace(/__ID__/g, item.id)
                    .replace(/__IMAGE__/g, item.image || '')
                    .replace(/__NAME__/g, item.name || 'Sản phẩm')
                    .replace(/__VARIANT__/g, item.variant_text || '')
                    .replace(/__PRICE_RAW__/g, unitPrice)
                    .replace(/__PRICE__/g, formatCurrency(unitPrice))
                    .replace(/__QUANTITY__/g, qty)
                    .replace(/__SUBTOTAL__/g, formatCurrency(unitPrice * qty));
            });
            cartTbody.innerHTML = html;
        }

        if (summarySubtotal) summarySubtotal.textContent = formatCurrency(total);
        if (summaryTotal) summaryTotal.textContent = formatCurrency(total);
    };

    cartState.onUpdated(renderCart);

    cartTbody.addEventListener('click', function (event) {
        const plusBtn = event.target.closest('.btn-plus');
        const minusBtn = event.target.closest('.btn-minus');
        const removeBtn = event.target.closest('.remove-item-btn');

        if (plusBtn || minusBtn) {
            event.preventDefault();
            const row = event.target.closest('.cart-item-row');
            const input = row?.querySelector('.quantity-input');
            if (!row || !input) return;

            let quantity = parseInt(input.value || '0', 10) || 0;
            quantity = plusBtn ? quantity + 1 : Math.max(0, quantity - 1);
            input.value = String(quantity);

            cartState.updateItem({ itemId: row.dataset.id, quantity }).catch((error) => {
                cartState.handleError(error, 'Không thể cập nhật số lượng.');
            });
            return;
        }

        if (removeBtn) {
            event.preventDefault();
            const row = removeBtn.closest('.cart-item-row');
            if (!row) return;
            if (!confirm('Bạn chắc chắn muốn xóa sản phẩm này?')) return;

            cartState.removeItem({ itemId: row.dataset.id }).catch((error) => {
                cartState.handleError(error, 'Không thể xóa sản phẩm, vui lòng thử lại.');
            });
        }
    });

    cartState.load().catch((error) => {
        cartState.handleError(error, 'Không thể tải dữ liệu giỏ hàng.');
    });
});


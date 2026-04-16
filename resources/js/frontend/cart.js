document.addEventListener('DOMContentLoaded', function() {
    const cartState = window.CartState;
    if (!cartState) return;

    const offcanvasBody = document.querySelector('.cart-offcanvas-wrapper .offcanvas-body');
    const offcanvasFooter = document.querySelector('.cart-offcanvas-wrapper .offcanvas-footer');
    const cartCountSpan = document.querySelector('.cart-action .cart-count');
    const cartTotalSpan = document.querySelector('.cart-offcanvas-wrapper .total-price');
    const itemTemplate = document.getElementById('guest-cart-item-template'); 

    const renderOffCanvasCart = (cartData) => {
        if (!offcanvasBody || !itemTemplate) return;
        offcanvasBody.innerHTML = '';
        const { items = [], total_quantity = 0, total_price = 0 } = cartData;
        
        if (items.length === 0) {
            offcanvasBody.innerHTML = '<p class="text-center p-4">Giỏ hàng của bạn đang trống.</p>';
            if(offcanvasFooter) offcanvasFooter.style.display = 'none';
        } else {
            let html = '';
            items.forEach(item => {
                const itemId = item.id;
                const itemHtml = itemTemplate.innerHTML
                .replace(/__ID__/g, itemId) 
                .replace(/__NAME__/g, item.name)
                .replace(/__PRICE__/g, Number(item.price).toLocaleString('vi-VN'))
                .replace(/__QUANTITY__/g, item.quantity)
                .replace(/__IMAGE__/g, item.image)
                .replace(/__URL__/g, `/san-pham/${item.slug}`)
                .replace(/__VARIANT__/g, item.variant_text || '');
                html += itemHtml;
            });
            offcanvasBody.innerHTML = html;
            if(offcanvasFooter) offcanvasFooter.style.display = 'block';
        }
        if (cartCountSpan) cartCountSpan.innerText = total_quantity;
        if (cartTotalSpan) cartTotalSpan.innerText = Number(total_price).toLocaleString('vi-VN') + 'đ';
    };

    cartState.onUpdated((cart) => {
        renderOffCanvasCart(cart);
    });

    document.addEventListener('click', function(e) {
        const btnAdd = e.target.closest('.btn-add-to-cart');
        if (btnAdd) {
            e.preventDefault();
            const el = btnAdd;
            const quantity  = parseInt(el.dataset.quantity || '1', 10) || 1;
            const productId = el.dataset.id;
            const variantId = el.dataset.variantId || null;
            cartState
                .addItem({ productId, variantId, quantity })
                .then(() => {
                    document.body.classList.add('show-cart-offcanvas');
                })
                .catch((error) => cartState.handleError(error));
        }

        const btnRemove = e.target.closest('.cart-offcanvas-wrapper .item-remove');
        if (btnRemove) {
            e.preventDefault();
            const itemId = btnRemove.dataset.itemId;
            if (!itemId) return;
            cartState.removeItem({ itemId }).catch((error) => {
                cartState.handleError(error, 'Không thể xóa sản phẩm, vui lòng thử lại.');
            });
        }
    });

    cartState.load().catch((error) => {
        cartState.handleError(error, 'Không thể tải dữ liệu giỏ hàng.');
    });
});
const CART_EVENTS = {
    UPDATED: 'cart:updated',
};

function createCartState() {
    let cart = {
        items: [],
        total_price: 0,
        total_quantity: 0,
    };
    let hasLoaded = false;
    let loadPromise = null;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const emitUpdate = () => {
        window.dispatchEvent(new CustomEvent(CART_EVENTS.UPDATED, { detail: { cart } }));
    };

    const requestJson = async (url, options = {}) => {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                ...(options.headers || {}),
            },
            ...options,
        });

        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            const message = data?.message || 'Đã xảy ra lỗi khi xử lý giỏ hàng.';
            throw new Error(message);
        }

        return data;
    };

    const setCartFromResponse = (payload) => {
        if (payload?.cart) {
            cart = payload.cart;
            emitUpdate();
        }
        return cart;
    };

    const notifySuccess = (message) => {
        if (typeof Swal === 'undefined') {
            return;
        }

        Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 1800,
            timerProgressBar: true,
        }).fire({
            icon: 'success',
            title: message,
        });
    };

    const notifyError = (message) => {
        if (typeof Swal === 'undefined') {
            return;
        }

        Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 2200,
            timerProgressBar: true,
        }).fire({
            icon: 'error',
            title: message,
        });
    };

    return {
        getCart() {
            return cart;
        },
        onUpdated(callback) {
            const handler = (event) => callback(event.detail.cart);
            window.addEventListener(CART_EVENTS.UPDATED, handler);
            return () => window.removeEventListener(CART_EVENTS.UPDATED, handler);
        },
        async load() {
            if (hasLoaded) {
                return cart;
            }

            if (loadPromise) {
                return loadPromise;
            }

            loadPromise = requestJson('/gio-hang/data', { method: 'GET' })
                .then((payload) => {
                    hasLoaded = true;
                    return setCartFromResponse(payload);
                })
                .finally(() => {
                    loadPromise = null;
                });

            return loadPromise;
        },
        async addItem({ productId, variantId = null, quantity = 1 }) {
            const payload = await requestJson('/cart/add', {
                method: 'POST',
                body: JSON.stringify({
                    product_id: productId,
                    variant_id: variantId,
                    quantity,
                }),
            });
            notifySuccess(payload?.message || 'Sản phẩm đã được thêm vào giỏ.');
            return setCartFromResponse(payload);
        },
        async updateItem({ itemId, quantity }) {
            const payload = await requestJson(`/cart/update/${itemId}`, {
                method: 'PUT',
                body: JSON.stringify({ quantity }),
            });
            return setCartFromResponse(payload);
        },
        async removeItem({ itemId }) {
            const payload = await requestJson(`/cart/remove/${itemId}`, { method: 'DELETE' });
            return setCartFromResponse(payload);
        },
        handleError(error, fallback = 'Đã xảy ra lỗi, vui lòng thử lại.') {
            notifyError(error?.message || fallback);
        },
    };
}

window.CartState = window.CartState || createCartState();


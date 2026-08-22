import '../scss/front.scss';

import * as bootstrap from 'bootstrap';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import './front-confirm.js';
import './phone-mask.js';
import './fade-in.js';

window.bootstrap = bootstrap;
window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination, Autoplay };

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
        new bootstrap.Tooltip(el);
    });

    // Флеш-сообщения сервера показываем как самоисчезающие тосты.
    document.querySelectorAll('#front-toast-container .toast').forEach((el) => {
        new bootstrap.Toast(el).show();
    });

    // Уведомление о добавлении товара в корзину (событие из Livewire-компонента AddToCart).
    const cartToastEl = document.getElementById('cart-toast');
    if (cartToastEl) {
        const cartToast = new bootstrap.Toast(cartToastEl);
        const cartToastBody = document.getElementById('cart-toast-body');

        window.addEventListener('cart-item-added', (event) => {
            const detail = event.detail ?? {};
            cartToastBody.textContent = detail.message ?? detail[0] ?? 'Товар добавлен в корзину';
            cartToast.show();
        });
    }
});

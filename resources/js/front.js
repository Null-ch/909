import '../scss/front.scss';

import * as bootstrap from 'bootstrap';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import './front-confirm.js';
import './phone-mask.js';

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
});

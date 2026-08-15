import '../scss/front.scss';

import * as bootstrap from 'bootstrap';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

window.bootstrap = bootstrap;
window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination, Autoplay };

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
        new bootstrap.Tooltip(el);
    });
});

// Плавное появление карточек при скролле (не трогает CSS-переходы hover-состояний).
const FADE_SELECTOR = '.product-card, .home-benefits__item, .home-categories__card';

if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            const el = entry.target;

            if (!entry.isIntersecting) {
                return;
            }

            observer.unobserve(el);

            requestAnimationFrame(() => {
                el.classList.add('fade-in-run');
            });

            el.addEventListener('transitionend', () => {
                el.classList.remove('fade-in-init', 'fade-in-run');
                el.style.removeProperty('transition-delay');
            }, { once: true });
        });
    }, { threshold: 0.15 });

    const observeNew = (root) => {
        const elements = root.matches?.(FADE_SELECTOR) ? [root] : Array.from(root.querySelectorAll(FADE_SELECTOR));

        elements.forEach((el, index) => {
            if (el.dataset.fadeObserved) {
                return;
            }

            el.dataset.fadeObserved = '1';
            el.classList.add('fade-in-init');
            el.style.transitionDelay = `${(index % 8) * 0.1}s`;
            observer.observe(el);
        });
    };

    document.addEventListener('DOMContentLoaded', () => observeNew(document));

    new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    observeNew(node);
                }
            });
        });
    }).observe(document.body, { childList: true, subtree: true });
}

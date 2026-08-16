import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/front.js',
                'resources/js/admin.js',
                'resources/js/admin-auth.js',
                'resources/js/admin-categories-index.js',
                'resources/js/admin-categories-form.js',
                'resources/js/admin-products-index.js',
                'resources/js/admin-products-form.js',
                'resources/js/admin-settings-form.js',
                'resources/js/admin-orders-index.js',
                'resources/js/admin-activity-logs-index.js',
                'resources/js/admin-dashboard.js',
                'resources/js/admin-delivery-methods-index.js',
                'resources/js/admin-delivery-methods-form.js',
                'resources/js/admin-banners-form.js',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                    optimizedFallbacks: false,
                }),
            ],
        }),
        tailwindcss(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Bootstrap 5 and Swiper's own stylesheets still use the legacy
                // @import API internally, so these warnings come from vendor code
                // we don't control, not from our own SCSS.
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'if-function'],
                quietDeps: true,
            },
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

import 'gentelella/scss/v4/main.scss';
import '../scss/admin-overrides.scss';
import { mountShell } from 'gentelella/v4/shell';
import { showToast } from 'gentelella/v4/toast';
import './admin-confirm.js';

mountShell();

// Флеш-сообщения сервера показываем как самоисчезающие тосты.
const flashDataEl = document.querySelector('[data-admin-flash]');

if (flashDataEl) {
    try {
        const flash = JSON.parse(flashDataEl.textContent || '{}');

        if (flash.success) {
            showToast(flash.success, { variant: 'success', duration: 4000 });
        }

        if (flash.error) {
            showToast(flash.error, { variant: 'error', duration: 4000 });
        }
    } catch {
        // Игнорируем некорректный JSON во флеш-данных.
    }
}

// Gentelella вешает на .tb-avatar демо-меню с несуществующими ссылками.
// У ссылки на профиль снимаем перехватчик, чтобы работал обычный переход.
document.querySelectorAll('a.tb-avatar').forEach((link) => {
    link.replaceWith(link.cloneNode(true));
});

import 'gentelella/scss/v4/main.scss';
import '../scss/admin-overrides.scss';
import { mountShell } from 'gentelella/v4/shell';
import './admin-confirm.js';

mountShell();

// Уведомления показываем под заголовком страницы, а не над ним.
const flashMessages = document.querySelector('.admin-flash-messages');
const pageHeader = document.querySelector('.page-header');

if (flashMessages) {
    if (pageHeader) {
        pageHeader.insertAdjacentElement('afterend', flashMessages);
    } else {
        const pageWrapper = document.querySelector('.page-wrapper');
        pageWrapper?.insertBefore(flashMessages, pageWrapper.firstChild);
    }
}

// Gentelella вешает на .tb-avatar демо-меню с несуществующими ссылками.
// У ссылки на профиль снимаем перехватчик, чтобы работал обычный переход.
document.querySelectorAll('a.tb-avatar').forEach((link) => {
    link.replaceWith(link.cloneNode(true));
});

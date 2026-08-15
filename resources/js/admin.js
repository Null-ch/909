import 'gentelella/scss/v4/main.scss';
import { mountShell } from 'gentelella/v4/shell';
import './admin-confirm.js';

mountShell();

// Gentelella вешает на .tb-avatar демо-меню с несуществующими ссылками.
// У ссылки на профиль снимаем перехватчик, чтобы работал обычный переход.
document.querySelectorAll('a.tb-avatar').forEach((link) => {
    link.replaceWith(link.cloneNode(true));
});

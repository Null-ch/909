import { showModal } from 'gentelella/v4/modal';

document.addEventListener('submit', (event) => {
    const form = event.target.closest('form[data-confirm]');

    if (!form || form.dataset.confirmed === '1') {
        return;
    }

    event.preventDefault();

    const message = form.dataset.confirm || 'Вы уверены, что хотите выполнить это действие?';

    showModal({
        title: 'Подтверждение удаления',
        body: `<p style="margin:0;color:var(--text-muted)">${escapeHtml(message)}</p>`,
        size: 'sm',
        actions: [
            {
                label: 'Отмена',
                variant: 'outline',
            },
            {
                label: 'Удалить',
                variant: 'danger',
                action: () => {
                    form.dataset.confirmed = '1';
                    form.submit();
                },
            },
        ],
    });
});

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

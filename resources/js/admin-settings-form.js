import { initFormControls } from 'gentelella/v4/form-controls';

initFormControls();

const tabsRoot = document.querySelector('[data-settings-tabs]');

if (tabsRoot) {
    const tabButtons = tabsRoot.querySelectorAll('[data-tab-target]');
    const tabPanels = tabsRoot.querySelectorAll('[data-tab-panel]');

    tabButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const target = button.dataset.tabTarget;

            tabButtons.forEach((item) => {
                item.classList.toggle('active', item === button);
                item.setAttribute('aria-selected', item === button ? 'true' : 'false');
            });

            tabPanels.forEach((panel) => {
                panel.classList.toggle('active', panel.dataset.tabPanel === target);
            });
        });
    });
}

const form = document.getElementById('settings-form');

form?.addEventListener('submit', () => {
    form.querySelectorAll('[data-rich-text]').forEach((wrap) => {
        const editor = wrap.querySelector('.rt-editor');
        const textarea = wrap.querySelector('textarea');

        if (editor && textarea) {
            textarea.value = editor.innerHTML;
        }
    });
});

document.querySelectorAll('[data-image-preview]').forEach((input) => {
    const previewId = input.dataset.imagePreview;
    const preview = previewId ? document.getElementById(previewId) : null;

    if (!preview) {
        return;
    }

    input.addEventListener('change', () => {
        const file = input.files?.[0];

        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            preview.innerHTML = `<img src="${event.target?.result}" alt="Предпросмотр" style="max-width:320px;border-radius:var(--radius);border:1px solid var(--border-color-light)">`;
        };
        reader.readAsDataURL(file);
    });
});

import { initFormControls } from 'gentelella/v4/form-controls';
import { initImageDropzones } from './admin-image-dropzone.js';

initFormControls();
initImageDropzones();

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


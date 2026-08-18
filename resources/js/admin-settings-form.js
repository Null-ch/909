import { initFormControls } from 'gentelella/v4/form-controls';
import { initImageDropzones } from './admin-image-dropzone.js';
import { initAdminTabs } from './admin-tabs.js';

initFormControls();
initImageDropzones();
initAdminTabs();

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


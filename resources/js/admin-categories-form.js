import { initFormControls } from 'gentelella/v4/form-controls';
import { initImageDropzones } from './admin-image-dropzone.js';

initFormControls();
initImageDropzones();

document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', () => {
        form.querySelectorAll('[data-rich-text]').forEach((wrap) => {
            const editor = wrap.querySelector('.rt-editor');
            const textarea = wrap.querySelector('textarea');

            if (editor && textarea) {
                textarea.value = editor.innerHTML;
            }
        });
    });
});

const slugSource = document.querySelector('[data-slug-source]');
const slugTarget = document.querySelector('[data-slug-target]');
let slugEdited = slugTarget?.value?.length > 0;

slugTarget?.addEventListener('input', () => {
    slugEdited = true;
});

slugSource?.addEventListener('input', () => {
    if (slugEdited || !slugTarget) {
        return;
    }

    slugTarget.value = transliterateSlug(slugSource.value);
});

function transliterateSlug(value) {
    const map = {
        а: 'a', б: 'b', в: 'v', г: 'g', д: 'd', е: 'e', ё: 'e', ж: 'zh', з: 'z',
        и: 'i', й: 'y', к: 'k', л: 'l', м: 'm', н: 'n', о: 'o', п: 'p', р: 'r',
        с: 's', т: 't', у: 'u', ф: 'f', х: 'h', ц: 'ts', ч: 'ch', ш: 'sh', щ: 'sch',
        ъ: '', ы: 'y', ь: '', э: 'e', ю: 'yu', я: 'ya',
    };

    return value
        .toLowerCase()
        .split('')
        .map((char) => map[char] ?? char)
        .join('')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
        .replace(/-{2,}/g, '-');
}

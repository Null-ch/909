import { initFormControls } from 'gentelella/v4/form-controls';

initFormControls();

const container = document.querySelector('[data-rates-container]');
const template = document.getElementById('delivery-rate-template');

function reindexRateRows() {
    container?.querySelectorAll('[data-rate-row]').forEach((row, index) => {
        row.querySelectorAll('[name^="rates["]').forEach((input) => {
            input.name = input.name.replace(/rates\[\d+]/, `rates[${index}]`);
        });
    });
}

function bindRateRow(row) {
    row.querySelector('[data-remove-rate-row]')?.addEventListener('click', () => {
        const rows = container?.querySelectorAll('[data-rate-row]') ?? [];

        if (rows.length <= 1) {
            return;
        }

        row.remove();
        reindexRateRows();
    });
}

container?.querySelectorAll('[data-rate-row]').forEach(bindRateRow);

document.querySelector('[data-add-rate-row]')?.addEventListener('click', () => {
    if (!container || !template) {
        return;
    }

    const index = container.querySelectorAll('[data-rate-row]').length;
    const fragment = template.content.cloneNode(true);
    const row = fragment.querySelector('[data-rate-row]');

    row.querySelectorAll('[data-field]').forEach((input) => {
        const field = input.dataset.field;
        input.name = `rates[${index}][${field}]`;

        if (field === 'is_active') {
            input.checked = true;
        }
    });

    container.appendChild(fragment);
    bindRateRow(container.querySelectorAll('[data-rate-row]')[index]);
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

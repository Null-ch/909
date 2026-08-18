import { initFormControls } from 'gentelella/v4/form-controls';
import { initAdminTabs } from './admin-tabs.js';

initFormControls();
initAdminTabs();

document.querySelectorAll('.multi-select .ms-search').forEach((input) => {
    input.setAttribute('placeholder', 'Поиск категории...');
    input.setAttribute('aria-label', 'Поиск категории');
});

const form = document.querySelector('form[enctype="multipart/form-data"]');
const attributesList = document.getElementById('attributes-list');
const addAttributeBtn = document.getElementById('add-attribute');
const imagesInput = document.getElementById('images');
const dropzone = document.getElementById('gallery-dropzone');
const newGalleryPreview = document.getElementById('new-gallery-preview');
const newMainImagePicker = document.getElementById('new-main-image-picker');
const newMainImageOptions = document.getElementById('new-main-image-options');
let attributeIndex = attributesList?.querySelectorAll('.attribute-row').length || 0;
let newImageFiles = [];

form?.addEventListener('submit', () => {
    form.querySelectorAll('[data-rich-text]').forEach((wrap) => {
        const editor = wrap.querySelector('.rt-editor');
        const textarea = wrap.querySelector('textarea');
        if (editor && textarea) {
            textarea.value = editor.innerHTML;
        }
    });

    syncExistingGalleryOrder();
    appendNewImagesToForm();
});

addAttributeBtn?.addEventListener('click', () => {
    const row = document.createElement('div');
    row.className = 'form-row attribute-row';
    row.style.marginBottom = '8px';
    row.innerHTML = `
        <div class="form-group"><input type="text" name="attributes[${attributeIndex}][name]" class="form-control" placeholder="Название"></div>
        <div class="form-group"><input type="text" name="attributes[${attributeIndex}][value]" class="form-control" placeholder="Значение"></div>
        <div class="form-group" style="flex:0 0 auto"><button type="button" class="btn btn-outline btn-sm remove-attribute">×</button></div>
    `;
    attributesList?.appendChild(row);
    attributeIndex++;
});

attributesList?.addEventListener('click', (event) => {
    const button = event.target.closest('.remove-attribute');
    if (!button) return;
    const rows = attributesList.querySelectorAll('.attribute-row');
    if (rows.length > 1) {
        button.closest('.attribute-row')?.remove();
    } else {
        button.closest('.attribute-row')?.querySelectorAll('input').forEach((input) => { input.value = ''; });
    }
});

const slugSource = document.querySelector('[data-slug-source]');
const slugTarget = document.querySelector('[data-slug-target]');
let slugEdited = (slugTarget?.value || '').length > 0;

slugTarget?.addEventListener('input', () => { slugEdited = true; });
slugSource?.addEventListener('input', () => {
    if (!slugEdited && slugTarget) {
        slugTarget.value = transliterateSlug(slugSource.value);
    }
});

dropzone?.addEventListener('click', (event) => {
    if (event.target.closest('#gallery-browse-btn')) {
        return;
    }
    imagesInput?.click();
});

document.getElementById('gallery-browse-btn')?.addEventListener('click', (event) => {
    event.stopPropagation();
    imagesInput?.click();
});

dropzone?.addEventListener('dragover', (event) => {
    event.preventDefault();
    dropzone.classList.add('is-dragover');
});

dropzone?.addEventListener('dragleave', () => {
    dropzone.classList.remove('is-dragover');
});

dropzone?.addEventListener('drop', (event) => {
    event.preventDefault();
    dropzone.classList.remove('is-dragover');
    handleNewFiles(event.dataTransfer?.files);
});

imagesInput?.addEventListener('change', () => handleNewFiles(imagesInput.files));

function handleNewFiles(fileList) {
    if (!fileList) return;
    newImageFiles = [...newImageFiles, ...Array.from(fileList)];
    renderNewGalleryPreview();
}

function renderNewGalleryPreview() {
    if (!newGalleryPreview || !newMainImageOptions || !newMainImagePicker) return;

    newGalleryPreview.innerHTML = '';
    newMainImageOptions.innerHTML = '';
    newMainImagePicker.style.display = newImageFiles.length ? 'block' : 'none';

    newImageFiles.forEach((file, index) => {
        const card = document.createElement('div');
        card.className = 'card';
        card.style.padding = '8px';
        const img = document.createElement('img');
        img.style.cssText = 'width:100%;height:100px;object-fit:cover;border-radius:6px';
        img.src = URL.createObjectURL(file);
        card.appendChild(img);

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline btn-sm';
        removeBtn.style.marginTop = '8px';
        removeBtn.textContent = 'Убрать';
        removeBtn.addEventListener('click', () => {
            newImageFiles = newImageFiles.filter((_, i) => i !== index);
            renderNewGalleryPreview();
        });
        card.appendChild(removeBtn);
        newGalleryPreview.appendChild(card);

        const label = document.createElement('label');
        label.className = 'form-check';
        label.style.fontSize = '12px';
        label.innerHTML = `<input type="radio" name="main_new_image" value="${index}" ${index === 0 ? 'checked' : ''}> Новое #${index + 1}`;
        newMainImageOptions.appendChild(label);
    });
}

function appendNewImagesToForm() {
    if (!form) return;

    form.querySelectorAll('input[data-dynamic-image="1"]').forEach((input) => input.remove());

    const dataTransfer = new DataTransfer();
    newImageFiles.forEach((file) => dataTransfer.items.add(file));

    if (imagesInput) {
        imagesInput.files = dataTransfer.files;
    }
}

function syncExistingGalleryOrder() {
    const gallery = document.getElementById('existing-gallery');
    if (!gallery || !form) return;

    form.querySelectorAll('input[name="image_order[]"]').forEach((input) => input.remove());

    gallery.querySelectorAll('.gallery-item').forEach((item) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'image_order[]';
        input.value = item.dataset.imageId;
        form.appendChild(input);
    });
}

const existingGallery = document.getElementById('existing-gallery');
let draggedItem = null;

existingGallery?.addEventListener('dragstart', (event) => {
    draggedItem = event.target.closest('.gallery-item');
});

existingGallery?.addEventListener('dragover', (event) => {
    event.preventDefault();
    const target = event.target.closest('.gallery-item');
    if (!draggedItem || !target || draggedItem === target) return;
    const items = [...existingGallery.querySelectorAll('.gallery-item')];
    const draggedIndex = items.indexOf(draggedItem);
    const targetIndex = items.indexOf(target);
    if (draggedIndex < targetIndex) {
        target.after(draggedItem);
    } else {
        target.before(draggedItem);
    }
});

function transliterateSlug(value) {
    const map = {
        а: 'a', б: 'b', в: 'v', г: 'g', д: 'd', е: 'e', ё: 'e', ж: 'zh', з: 'z',
        и: 'i', й: 'y', к: 'k', л: 'l', м: 'm', н: 'n', о: 'o', п: 'p', р: 'r',
        с: 's', т: 't', у: 'u', ф: 'f', х: 'h', ц: 'ts', ч: 'ch', ш: 'sh', щ: 'sch',
        ъ: '', ы: 'y', ь: '', э: 'e', ю: 'yu', я: 'ya',
    };

    return value.toLowerCase().split('').map((char) => map[char] ?? char).join('')
        .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').replace(/-{2,}/g, '-');
}

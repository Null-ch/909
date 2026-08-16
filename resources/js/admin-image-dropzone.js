// Shared drag & drop image picker for single-image admin forms
// (banner, category, settings logo/favicon). Replaces the native
// file input chrome with a styled dropzone + preview.

export function initImageDropzones(scope = document) {
    scope.querySelectorAll('[data-image-dropzone]').forEach((dropzone) => initImageDropzone(dropzone));
}

function initImageDropzone(dropzone) {
    const input = dropzone.querySelector('input[type="file"]');
    const preview = dropzone.querySelector('[data-dropzone-preview]');

    if (!input || !preview) {
        return;
    }

    const originalPreviewHtml = preview.innerHTML;

    if (preview.querySelector('img')) {
        dropzone.classList.add('has-image');
    }

    const openPicker = () => input.click();

    dropzone.addEventListener('click', (event) => {
        if (event.target.closest('[data-dropzone-remove]')) {
            return;
        }

        openPicker();
    });

    dropzone.querySelector('[data-dropzone-browse]')?.addEventListener('click', (event) => {
        event.stopPropagation();
        openPicker();
    });

    dropzone.querySelector('[data-dropzone-remove]')?.addEventListener('click', (event) => {
        event.stopPropagation();
        input.value = '';
        preview.innerHTML = originalPreviewHtml;
        dropzone.classList.toggle('has-image', !!preview.querySelector('img'));
    });

    ['dragover', 'dragenter'].forEach((type) => {
        dropzone.addEventListener(type, (event) => {
            event.preventDefault();
            dropzone.classList.add('is-dragover');
        });
    });

    ['dragleave', 'dragend'].forEach((type) => {
        dropzone.addEventListener(type, () => dropzone.classList.remove('is-dragover'));
    });

    dropzone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropzone.classList.remove('is-dragover');

        const file = event.dataTransfer?.files?.[0];

        if (!file) {
            return;
        }

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        input.files = dataTransfer.files;
        renderPreview(file);
    });

    input.addEventListener('change', () => {
        const file = input.files?.[0];

        if (file) {
            renderPreview(file);
        }
    });

    function renderPreview(file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            preview.innerHTML = `<img src="${event.target?.result}" alt="Предпросмотр">`;
            dropzone.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    }
}

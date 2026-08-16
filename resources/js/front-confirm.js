// Custom pop-up confirmation dialog (Bootstrap modal) used in place of
// native window.confirm() / wire:confirm across the storefront.
import { Modal } from 'bootstrap';

let modalEl;
let modal;

function ensureModal() {
    if (modalEl) {
        return modal;
    }

    modalEl = document.createElement('div');
    modalEl.className = 'modal fade';
    modalEl.tabIndex = -1;
    modalEl.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body py-4 text-center" data-confirm-message></div>
                <div class="modal-footer border-0 justify-content-center pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" data-confirm-accept>Подтвердить</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modalEl);
    modal = new Modal(modalEl);
    return modal;
}

export function confirmAction(message, onConfirm) {
    ensureModal();
    modalEl.querySelector('[data-confirm-message]').textContent = message;

    const acceptBtn = modalEl.querySelector('[data-confirm-accept]');
    acceptBtn.addEventListener('click', () => {
        modal.hide();
        onConfirm();
    }, { once: true });

    modal.show();
}

window.confirmAction = confirmAction;

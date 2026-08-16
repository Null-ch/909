function formatRuPhone(value) {
    let digits = value.replace(/\D/g, '');

    if (digits.startsWith('8')) {
        digits = '7' + digits.slice(1);
    } else if (digits.length > 0 && !digits.startsWith('7')) {
        digits = '7' + digits;
    }

    digits = digits.slice(0, 11);
    const rest = digits.slice(1);

    let formatted = '+7';
    if (rest.length > 0) formatted += ' (' + rest.slice(0, 3);
    if (rest.length >= 3) formatted += ')';
    if (rest.length > 3) formatted += ' ' + rest.slice(3, 6);
    if (rest.length > 6) formatted += '-' + rest.slice(6, 8);
    if (rest.length > 8) formatted += '-' + rest.slice(8, 10);

    return formatted;
}

function digitsBeforeCaret(value, caret) {
    return value.slice(0, caret).replace(/\D/g, '').length;
}

function caretForDigitCount(formatted, digitCount) {
    if (digitCount <= 0) {
        return Math.min(2, formatted.length);
    }

    let seen = 0;
    for (let i = 0; i < formatted.length; i++) {
        if (/\d/.test(formatted[i])) {
            seen++;
            if (seen === digitCount) {
                return i + 1;
            }
        }
    }

    return formatted.length;
}

function reformat(input) {
    const caret = input.selectionStart ?? input.value.length;
    const digitCount = digitsBeforeCaret(input.value, caret);

    input.value = formatRuPhone(input.value);

    const newCaret = caretForDigitCount(input.value, digitCount);
    input.setSelectionRange(newCaret, newCaret);
}

export function initPhoneMasks(root = document) {
    root.querySelectorAll('.js-phone-input').forEach((input) => {
        if (input.dataset.phoneMaskBound) {
            return;
        }
        input.dataset.phoneMaskBound = '1';

        if (input.value) {
            input.value = formatRuPhone(input.value);
        }

        input.addEventListener('focus', () => {
            if (!input.value) {
                input.value = '+7 (';
                input.setSelectionRange(4, 4);
            }
        });

        input.addEventListener('input', () => reformat(input));

        input.addEventListener('blur', () => {
            const digits = input.value.replace(/\D/g, '');
            if (digits === '' || digits === '7') {
                input.value = '';
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => initPhoneMasks());
document.addEventListener('livewire:navigated', () => initPhoneMasks());
document.addEventListener('livewire:morph', () => initPhoneMasks());

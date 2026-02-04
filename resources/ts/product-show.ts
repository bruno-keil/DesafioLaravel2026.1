import { initUserMenu } from './user-menu';

const qty = document.querySelector<HTMLElement>('[data-qty]');

if (qty) {
    const input = qty.querySelector<HTMLInputElement>('[data-input]');
    const minus = qty.querySelector<HTMLButtonElement>('[data-minus]');
    const plus = qty.querySelector<HTMLButtonElement>('[data-plus]');

    if (input && minus && plus) {
        const min = Number(input.min || 1);
        const max = Number(input.max || 1);

        const clamp = (value: number) => Math.min(max, Math.max(min, value));

        minus.addEventListener('click', () => {
            const next = clamp(Number(input.value) - 1);
            input.value = String(next);
        });

        plus.addEventListener('click', () => {
            const next = clamp(Number(input.value) + 1);
            input.value = String(next);
        });

        input.addEventListener('change', () => {
            const value = clamp(Number(input.value) || min);
            input.value = String(value);
        });
    }
}

initUserMenu();

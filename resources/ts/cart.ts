import { initUserMenu } from './user-menu';

const formatBRL = (value: number) =>
    new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);

const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content;
const summarySubtotal = document.querySelector<HTMLElement>('[data-subtotal]');

const recalcSubtotal = () => {
    if (!summarySubtotal) return;
    const items = Array.from(document.querySelectorAll<HTMLElement>('[data-cart-item]'));
    const total = items.reduce((sum, item) => {
        const price = Number(item.dataset.price || 0);
        const input = item.querySelector<HTMLInputElement>('[data-input]');
        const qty = input ? Number(input.value || 0) : 0;
        return sum + price * qty;
    }, 0);
    summarySubtotal.textContent = formatBRL(total);
};

const syncQuantity = async (form: HTMLFormElement, nextValue: number) => {
    const input = form.querySelector<HTMLInputElement>('[data-input]');
    if (!input) return;

    input.value = String(nextValue);
    const item = form.closest<HTMLElement>('[data-cart-item]');
    if (item) {
        const price = Number(item.dataset.price || 0);
        const lineTotal = item.querySelector<HTMLElement>('[data-line-total]');
        if (lineTotal) {
            lineTotal.textContent = formatBRL(price * nextValue);
        }
    }
    recalcSubtotal();

    if (!csrfToken) return;
    const url = form.action;
    const payload = new FormData();
    payload.append('quantity', String(nextValue));
    payload.append('_method', 'PATCH');

    try {
        await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: payload,
        });
    } catch (error) {
        // Silent failure keeps UI responsive; user can refresh to recover.
    }
};

document.querySelectorAll<HTMLElement>('[data-photo]').forEach((el) => {
    const url = el.dataset.photo;
    if (url) {
        el.style.backgroundImage = `url('${url}')`;
    }
});

document.querySelectorAll<HTMLElement>('[data-cart-item]').forEach((item) => {
    const form = item.querySelector<HTMLFormElement>('[data-cart-form]');
    const input = item.querySelector<HTMLInputElement>('[data-input]');
    const minus = item.querySelector<HTMLButtonElement>('[data-minus]');
    const plus = item.querySelector<HTMLButtonElement>('[data-plus]');
    if (!form || !input || !minus || !plus) return;

    const min = Number(input.min || 1);
    const max = Number(input.max || 1);
    const clamp = (value: number) => Math.min(max, Math.max(min, value));

    minus.addEventListener('click', () => {
        const next = clamp(Number(input.value) - 1);
        syncQuantity(form, next);
    });

    plus.addEventListener('click', () => {
        const next = clamp(Number(input.value) + 1);
        syncQuantity(form, next);
    });

    input.addEventListener('change', () => {
        const next = clamp(Number(input.value) || min);
        syncQuantity(form, next);
    });
});

initUserMenu();

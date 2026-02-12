import { initUserMenu } from './user-menu';

const formatBRL = (value: number) =>
    new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);

const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content;
const summarySubtotal = document.querySelector<HTMLElement>('[data-subtotal]');

const showToast = (message: string, type: 'error' | 'success' = 'error') => {
    const existing = document.querySelector('[data-toast]');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.setAttribute('data-toast', '');
    const colors = type === 'error'
        ? 'border-red-400/30 bg-red-500/10 text-red-200'
        : 'border-emerald-400/30 bg-emerald-500/10 text-emerald-200';
    toast.className = `fixed top-6 right-6 z-50 rounded-xl border ${colors} px-5 py-3 text-sm shadow-lg transition-opacity duration-300`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
};

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

    const previousValue = Number(input.value);
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
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: payload,
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
    } catch {
        input.value = String(previousValue);
        if (item) {
            const price = Number(item.dataset.price || 0);
            const lineTotal = item.querySelector<HTMLElement>('[data-line-total]');
            if (lineTotal) {
                lineTotal.textContent = formatBRL(price * previousValue);
            }
        }
        recalcSubtotal();
        showToast('Erro ao atualizar quantidade. Tente novamente.');
    }
};

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

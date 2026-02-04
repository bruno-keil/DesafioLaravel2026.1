export const initUserMenu = () => {
    const trigger = document.querySelector<HTMLElement>('[data-user-trigger]');
    const modal = document.querySelector<HTMLElement>('[data-user-modal]');
    if (!trigger || !modal) {
        return;
    }

    const closeBtn = modal.querySelector<HTMLElement>('[data-user-close]');

    const open = () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };

    const close = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    trigger.addEventListener('click', open);
    closeBtn?.addEventListener('click', close);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            close();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            close();
        }
    });
};

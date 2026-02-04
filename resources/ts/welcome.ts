import { initUserMenu } from './user-menu';

type Slide = {
    title: string;
    subtitle: string;
    tag: string;
    image: string;
    link: string;
};

const hero = document.querySelector<HTMLElement>('[data-carousel]');
const dataEl = document.getElementById('hero-slides-data');

initUserMenu();

if (hero && dataEl?.textContent) {
    const slides: Slide[] = JSON.parse(dataEl.textContent);
    const titleEl = hero.querySelector<HTMLElement>('[data-hero-title]');
    const subEl = hero.querySelector<HTMLElement>('[data-hero-sub]');
    const tagEl = hero.querySelector<HTMLElement>('[data-hero-tag]');
    const linkEl = hero.querySelector<HTMLAnchorElement>('[data-hero-link]');
    const buttons = Array.from(hero.querySelectorAll<HTMLButtonElement>('[data-index]'));

    if (slides.length && titleEl && subEl && tagEl && linkEl) {
        let current = 0;
        let timer: number | null = null;
        const fadeTargets = [titleEl, subEl, tagEl];

        const setActiveButton = (index: number) => {
            buttons.forEach((btn, idx) => {
                btn.dataset.active = idx === index ? 'true' : 'false';
            });
        };

        const applySlide = (index: number) => {
            const slide = slides[index];
            hero.style.setProperty('--hero-image', `url('${slide.image}')`);
            titleEl.textContent = slide.title;
            subEl.textContent = slide.subtitle;
            tagEl.textContent = slide.tag;
            linkEl.href = slide.link;
            setActiveButton(index);
        };

        const setSlide = (index: number) => {
            current = index;
            fadeTargets.forEach((el) => el.classList.add('opacity-0', 'translate-y-2'));
            window.setTimeout(() => {
                applySlide(index);
                fadeTargets.forEach((el) => el.classList.remove('opacity-0', 'translate-y-2'));
            }, 180);
        };

        const start = () => {
            timer = window.setInterval(() => {
                setSlide((current + 1) % slides.length);
            }, 6000);
        };

        const stop = () => {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        };

        buttons.forEach((btn) => {
            btn.addEventListener('click', () => {
                stop();
                setSlide(Number(btn.dataset.index));
                start();
            });
        });

        hero.addEventListener('mouseenter', stop);
        hero.addEventListener('mouseleave', start);

        applySlide(0);
        start();
    }
}

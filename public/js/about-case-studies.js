(() => {
    const cards = document.getElementById('about-case-cards');
    if (!cards) return;

    const controls = document.querySelectorAll('[data-case-direction]');
    const updateControls = () => {
        // The full desktop row fits on screen: rotate it to reveal the next entry.
        const scrollable = cards.scrollWidth > cards.clientWidth + 1;
        controls.forEach(button => {
            button.setAttribute('aria-controls', cards.id);
            button.dataset.scrollable = String(scrollable);
        });
    };
    controls.forEach(button => button.addEventListener('click', () => {
        const direction = Number(button.dataset.caseDirection);
        const scrollable = cards.scrollWidth > cards.clientWidth + 1;
        if (scrollable) {
            const step = cards.firstElementChild.getBoundingClientRect().width + parseFloat(getComputedStyle(cards).columnGap);
            const end = cards.scrollWidth - cards.clientWidth;
            const next = direction > 0 && cards.scrollLeft >= end - 2 ? 0
                : direction < 0 && cards.scrollLeft <= 2 ? end : cards.scrollLeft + direction * step;
            cards.scrollTo({ left: next, behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' });
        } else if (direction > 0) {
            cards.append(cards.firstElementChild);
        } else {
            cards.prepend(cards.lastElementChild);
        }
    }));
    updateControls();
    window.addEventListener('resize', updateControls);
})();

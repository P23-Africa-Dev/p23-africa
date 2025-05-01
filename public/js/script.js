const slides = {
    countries: ['05', 10, 15, 20],
    businesses: [1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000],
    partnership: [100, 200, 300, 400, 500, 600],
    network: [1000, 2000, 3000, 4000, 5000]
};

const totalDuration = 4000;
const pauseTime = 5000;
const heightRem = 2.5;

function createNumberList(id, values) {
    const container = document.getElementById(id);
    container.style.overflow = 'hidden';
    container.style.height = `${heightRem}rem`;

    const span = document.createElement('span');
    span.classList.add('slide');
    span.style.display = 'block';

    values.forEach(val => {
        const div = document.createElement('div');
        div.textContent = `+${val}`;
        div.style.height = `${heightRem}rem`;
        div.style.lineHeight = `${heightRem}rem`;
        span.appendChild(div);
    });

    container.appendChild(span);
}

function animateSlide(id, values) {
    const container = document.getElementById(id);
    const slide = container.querySelector('.slide');
    const steps = values.length;
    const interval = totalDuration / (steps - 1);
    let index = 0;

    // Set transition style
    slide.style.transition = `transform ${interval}ms ease-in-out`;
    slide.style.transform = 'translateY(0)'; // Start at first value

    function step() {
        index++;
        if (index < steps) {
            slide.style.transform = `translateY(-${index * heightRem}rem)`;
            setTimeout(step, interval);
        } else {
            // At the end, reset after pause
            setTimeout(() => {
                slide.style.transition = 'none';
                slide.style.transform = 'translateY(0)';
                index = 0;
                void slide.offsetWidth; // force reflow
                slide.style.transition = `transform ${interval}ms ease-in-out`;
                setTimeout(step, interval);
            }, pauseTime);
        }
    }

    // Start first step shortly after load to allow rendering
    setTimeout(step, interval);
}

window.onload = () => {
    for (let id in slides) {
        createNumberList(id, slides[id]);
    }

    for (let id in slides) {
        animateSlide(id, slides[id]);
    }
};

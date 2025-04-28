const slides = {
    countries: [0, 5, 10, 15, 20],
    businesses: [1000, 2000, 5000, 7000, 8000],
    partnership: [100, 200, 400, 500, 600],
    network: [1000, 2000, 3000, 4000, 5000]
};

const totalDuration = 4000; // total time for each animation (excluding pause)
const pauseTime = 5000;
const heightRem = 2.5;

function createNumberList(id, values) {
    const span = document.createElement('span');
    span.classList.add('slide');
    values.forEach(val => {
        const div = document.createElement('div');
        div.textContent = `+${val}`;
        span.appendChild(div);
    });
    document.getElementById(id).appendChild(span);
}

function animateSlide(id, values) {
    const container = document.getElementById(id);
    const slide = container.querySelector('.slide');
    const steps = values.length;
    const interval = totalDuration / (steps - 1);
    let index = 0;

    // Start with the first value already displayed
    slide.style.transform = 'translateY(0)';

    function step() {
        if (index < steps - 1) {
            index++;
            slide.style.transform = `translateY(-${index * heightRem}rem)`;
            setTimeout(step, interval);
        } else {
            // Pause at the end
            setTimeout(() => {
                // Reset to start
                slide.style.transition = 'none';
                slide.style.transform = 'translateY(0)';
                index = 0;
                void slide.offsetWidth; // force reflow
                slide.style.transition = 'transform 0.5s ease-in-out';
                setTimeout(step, interval); // restart
            }, pauseTime);
        }
    }

    // Start animation immediately
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
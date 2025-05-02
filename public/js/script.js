const slides = {
    countries: ['00', '05', 10, 15, 20],
    businesses: [1000, 2000, 4000, 6000, 8000],
    partnership: [100, 200, 400, 500, 600],
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





// NAVBAR
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const menuToggle = document.getElementById('menuToggle');
const closeBtn = document.getElementById('closeBtn');
const mainMenu = document.getElementById('mainMenu');
const submenuElements = document.querySelectorAll('.submenu');

function openSidebar() {
  sidebar.classList.add('active');
  overlay.classList.add('active');
  mainMenu.classList.remove('hide');
  submenuElements.forEach(sm => sm.classList.remove('active'));
}

function closeSidebar() {
  sidebar.classList.remove('active');
  overlay.classList.remove('active');
  submenuElements.forEach(sm => sm.classList.remove('active'));
  mainMenu.classList.remove('hide');
}

menuToggle.addEventListener('click', openSidebar);
closeBtn.addEventListener('click', closeSidebar);
overlay.addEventListener('click', closeSidebar);

document.querySelectorAll('[data-target]').forEach(item => {
  item.addEventListener('click', () => {
    const target = document.getElementById(item.getAttribute('data-target'));
    mainMenu.classList.add('hide');
    submenuElements.forEach(sm => sm.classList.remove('active'));
    target.classList.add('active');
  });
});

document.getElementById('backBtn1').addEventListener('click', () => {
  submenuElements[0].classList.remove('active');
  mainMenu.classList.remove('hide');
});

document.getElementById('backBtn2').addEventListener('click', () => {
  submenuElements[1].classList.remove('active');
  mainMenu.classList.remove('hide');
});

document.getElementById('backBtn3').addEventListener('click', () => {
  submenuElements[2].classList.remove('active');
  mainMenu.classList.remove('hide');
});
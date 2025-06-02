

// NAVBAR
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");
const menuToggle = document.getElementById("menuToggle");
const closeBtn = document.getElementById("closeBtn");
const mainMenu = document.getElementById("mainMenu");
const submenuElements = document.querySelectorAll(".submenu");

function openSidebar() {
    sidebar.classList.add("active");
    overlay.classList.add("active");
    mainMenu.classList.remove("hide");
    submenuElements.forEach((sm) => sm.classList.remove("active"));
}

function closeSidebar() {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
    submenuElements.forEach((sm) => sm.classList.remove("active"));
    mainMenu.classList.remove("hide");
}

menuToggle.addEventListener("click", openSidebar);
closeBtn.addEventListener("click", closeSidebar);
overlay.addEventListener("click", closeSidebar);

document.querySelectorAll("[data-target]").forEach((item) => {
    item.addEventListener("click", () => {
        const target = document.getElementById(
            item.getAttribute("data-target")
        );
        mainMenu.classList.add("hide");
        submenuElements.forEach((sm) => sm.classList.remove("active"));
        target.classList.add("active");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const someElement0 = document.getElementById("backBtn1");

    if (someElement0) {
        someElement0.addEventListener("click", function () {
            submenuElements[0].classList.remove("active");
            mainMenu.classList.remove("hide");
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const someElement = document.getElementById("backBtn2");

    if (someElement) {
        someElement.addEventListener("click", function () {
            submenuElements[1].classList.remove("active");
            mainMenu.classList.remove("hide");
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const someElement2 = document.getElementById("backBtn3");

    if (someElement2) {
        someElement2.addEventListener("click", function () {
            submenuElements[2].classList.remove("active");
            mainMenu.classList.remove("hide");
        });
    }
});

// All event page
function switchTab(tabId, button) {
    // Hide all panels
    document
        .querySelectorAll(".tab-panel")
        .forEach((panel) => panel.classList.remove("active"));
    document.getElementById(tabId).classList.add("active");

    // Remove active from all buttons
    document
        .querySelectorAll(".tab-btn")
        .forEach((btn) => btn.classList.remove("active"));
    button.classList.add("active");
}

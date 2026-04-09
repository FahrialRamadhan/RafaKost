import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const slider = document.getElementById('slider');

window.scrollRightFunc = function () {
    slider.scrollBy({
        left: 250,
        behavior: 'smooth'
    });
}

window.scrollLeftFunc = function () {
    slider.scrollBy({
        left: -250,
        behavior: 'smooth'
    });
}

document.addEventListener('DOMContentLoaded', function () {

    let index = 0;
    const slider = document.getElementById('testiSlider');

    window.nextTesti = function () {
        if (!slider) return;

        if (index < slider.children.length - 1) {
            index++;
        }

        moveSlide();
    }

    window.prevTesti = function () {
        if (index > 0) {
            index--;
        }

        moveSlide();
    }

    function moveSlide() {
        const slide = slider.children[0];
        const gap = 24; // gap-6
        const width = slide.offsetWidth + gap;

        slider.style.transform = `translateX(-${index * width}px)`;
    }

});
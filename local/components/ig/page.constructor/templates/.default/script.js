'use strict';

let hoverGridCells = document.getElementsByClassName('js-grid-item');

for (let cell of hoverGridCells) {
    if (cell.getElementsByClassName('js-grid-item-overlay')) {
        cell.addEventListener('mouseenter', e => {
            cell.classList.add('grid-item--hover');
        });
        cell.addEventListener('mouseleave', e => {
            cell.classList.remove('grid-item--hover');
        });
    }
}

let sliderAwait = setInterval(
    function () {
        if (typeof tns === 'function') {
            let sliders = document.getElementsByClassName('js-slider');
            if (sliders.length > 0) {
                for (let slider of sliders) {
                    let controls = getNextSibling(slider, '.js-slider-controls');
                    let sliderObj = tns({
                        container: slider,
                        autoWidth: true,
                        autoHeight: true,
                        mouseDrag: true,
                        controls: false,
                        navContainer: controls

                    });
                }
            }
            clearInterval(sliderAwait);
        }
    }, 100
);

let getNextSibling = function (node, selector) {
    let sibling = node.nextElementSibling;
    while (sibling) {
        if (sibling.matches(selector)) {
            return sibling;
        }
        sibling = sibling.nextElementSibling
    }
};
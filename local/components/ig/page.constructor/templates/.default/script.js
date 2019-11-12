'use strict';

let hoverGridCells = document.getElementsByClassName('js-grid-item');

console.log(hoverGridCells);
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
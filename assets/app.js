import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.querySelectorAll('.dropdown').forEach((dropdown) => {

    const button = dropdown.querySelector('[data-bs-toggle="dropdown"]');

    const menu = dropdown.querySelector('.task-dropdown-fixed');

    if (!button || !menu) {

        return;

    }

    button.addEventListener('show.bs.dropdown', () => {

        const rect = button.getBoundingClientRect();

        const menuWidth = 220;

        menu.style.top = `${rect.bottom + 8}px`;

        menu.style.left = `${rect.left - menuWidth + rect.width}px`;

    });

});
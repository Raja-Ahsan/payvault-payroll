import './bootstrap';

import Alpine from 'alpinejs';
import { initFlowbite } from 'flowbite';

window.Alpine = Alpine;

// Initialize Flowbite interactive components (tabs, dropdowns, etc.)
document.addEventListener('DOMContentLoaded', () => {
    initFlowbite();
});

Alpine.start();

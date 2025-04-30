// resources/js/app.js
import './bootstrap';
import Alpine from 'alpinejs';
import htmx from 'htmx.org';
import { initializeSweetAlert2 } from './sweetalert2'; // Import the new file

window.Alpine = Alpine;
window.htmx = htmx;

Alpine.start();

// Initialize SweetAlert2 logic
initializeSweetAlert2();

console.log('App initialized with Alpine, HTMX, and SweetAlert2');

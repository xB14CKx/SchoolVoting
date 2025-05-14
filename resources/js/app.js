// resources/js/app.js
import './bootstrap';
import Alpine from 'alpinejs';
import htmx from 'htmx.org';
import { initializeSweetAlert2 } from './sweetalert2'; // Import the new file
import Swal from 'sweetalert2';
import './sweetalert2.js'; 
import '../css/sweetalert-custom.css';

window.Alpine = Alpine;
window.htmx = htmx;

Alpine.start();

// Initialize SweetAlert2 logic
initializeSweetAlert2();

console.log('App initialized with Alpine, HTMX, and SweetAlert2');

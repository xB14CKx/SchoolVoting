// resources/js/app.js
import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import htmx from 'htmx.org';

window.Alpine = Alpine;
window.htmx = htmx;

Alpine.start();

document.body.addEventListener('htmx:afterRequest', function(event) {
    // Get the URL of the request
    const requestUrl = event.detail.xhr.responseURL;
    const eligibilityCheckUrl = window.eligibilityCheckUrl;

    // Only process responses from the eligibility check endpoint
    if (!requestUrl.includes(eligibilityCheckUrl)) {
        console.log('Skipping SweetAlert2 for non-eligibility request:', requestUrl);
        return;
    }

    const response = event.detail.xhr.response;
    console.log('Raw HTMX Response:', response);

    if (!response) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No response from server. Please try again.',
            confirmButtonText: 'OK'
        });
        return;
    }

    try {
        const data = JSON.parse(response);
        console.log('Parsed HTMX Response:', data);

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = data.redirect;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'OK'
            });
        }
    } catch (e) {
        console.error('Failed to parse HTMX response:', e);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Invalid response from server. Please try again.',
            confirmButtonText: 'OK'
        });
    }
});

console.log('App initialized with Alpine, HTMX, and SweetAlert2');

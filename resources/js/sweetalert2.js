// resources/js/sweetalert2.js

import Swal from 'sweetalert2';
import htmx from 'htmx.org';

export function initializeSweetAlert2() {
    // Show success popup before swapping (for success case)
    document.body.addEventListener('htmx:beforeSwap', function(event) {
        const requestUrl = event.detail.xhr.responseURL;
        const eligibilityCheckUrl = window.eligibilityCheckUrl;

        // Only process responses from the eligibility check endpoint
        if (!requestUrl.includes(eligibilityCheckUrl)) {
            console.log('Skipping SweetAlert2 for non-eligibility request:', requestUrl);
            return;
        }

        const response = event.detail.xhr.response;
        console.log('Raw HTMX Response:', response);

        // Check if the response contains an error message (indicating an error case)
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = response;
        const errorElement = tempDiv.querySelector('#error-message-data');

        if (errorElement) {
            // Error case: prevent swap for now, we’ll handle it in htmx:afterSwap
            console.log('Error detected in response:', errorElement.dataset.error);
        } else {
            // Success case: show success popup before swapping
            console.log('Success case, showing success popup');
            event.detail.shouldSwap = false; // Prevent immediate swap
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'You are eligible! Loading registration form...',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // Manually swap the body content after the popup
                const target = document.querySelector('body');
                target.outerHTML = response;
                htmx.process(document.body); // Reprocess HTMX on new content
            });
        }
    });

    // Handle errors after swapping (for error case)
    document.body.addEventListener('htmx:afterSwap', function(event) {
        const requestUrl = event.detail.xhr.responseURL;
        const eligibilityCheckUrl = window.eligibilityCheckUrl;

        // Only process responses from the eligibility check endpoint
        if (!requestUrl.includes(eligibilityCheckUrl)) {
            console.log('Skipping SweetAlert2 for non-eligibility request:', requestUrl);
            return;
        }

        // Check if the swapped content contains an error message
        const errorElement = document.querySelector('#error-message-data');
        if (errorElement) {
            const errorMessage = errorElement.dataset.error;
            console.log('Showing error popup:', errorMessage);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                confirmButtonText: 'OK'
            }).then(() => {
                const form = document.querySelector('.eligibility-form');
                if (form) {
                    form.reset();
                }
            });
        }
    });
}

console.log('SweetAlert2 logic initialized');

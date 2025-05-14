import Swal from 'sweetalert2';
import htmx from 'htmx.org';

export function initializeSweetAlert2() {
    // Eligibility Check Popups (Existing Logic)
    document.body.addEventListener('htmx:beforeSwap', function(event) {
        const requestUrl = event.detail.xhr.responseURL;
        const eligibilityCheckUrl = window.eligibilityCheckUrl;

        if (!requestUrl.includes(eligibilityCheckUrl)) {
            console.log('Skipping SweetAlert2 for non-eligibility request:', requestUrl);
            return;
        }

        const response = event.detail.xhr.response;
        console.log('Raw HTMX Response:', response);

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = response;
        const errorElement = tempDiv.querySelector('#error-message-data');

        if (errorElement) {
            console.log('Error detected in response:', errorElement.dataset.error);
        } else {
            console.log('Success case, showing success popup');
            event.detail.shouldSwap = false;
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'You are eligible! Loading registration form...',
                timer: 1500,
                showConfirmButton: false,
                background: '#222831',
                iconColor: '#f7bd03',
                customClass: {
                    popup: 'my-swal-popup',
                    confirmButton: 'my-swal-confirm',
                    cancelButton: 'my-swal-cancel'
                }
            }).then(() => {
                const target = document.querySelector('body');
                target.outerHTML = response;
                htmx.process(document.body);
            });
        }
    });

    document.body.addEventListener('htmx:afterSwap', function(event) {
        const requestUrl = event.detail.xhr.responseURL;
        const eligibilityCheckUrl = window.eligibilityCheckUrl;

        if (!requestUrl.includes(eligibilityCheckUrl)) {
            console.log('Skipping SweetAlert2 for non-eligibility request:', requestUrl);
            return;
        }

        const errorElement = document.querySelector('#error-message-data');
        if (errorElement) {
            const errorMessage = errorElement.dataset.error;
            console.log('Showing error popup:', errorMessage);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                confirmButtonText: 'OK',
                background: '#222831',
                color: '#fff',
                iconColor: '#f7bd03',
                customClass: {
                    popup: 'my-swal-popup',
                    confirmButton: 'my-swal-confirm',
                    cancelButton: 'my-swal-cancel'
                }
            }).then(() => {
                const form = document.querySelector('.eligibility-form');
                if (form) {
                    form.reset();
                }
            });
        }
    });

    // File Upload Popups
    document.body.addEventListener('htmx:afterRequest', function(event) {
        const requestUrl = event.detail.xhr.responseURL;
        const uploadUrl = '/upload'; // Use relative URL to match route('upload.store')

        if (!requestUrl.includes(uploadUrl)) {
            return;
        }

        console.log('File upload request detected:', requestUrl);

        const trigger = event.detail.xhr.getResponseHeader('HX-Trigger');
        if (trigger) {
            try {
                const triggerData = JSON.parse(trigger);
                if (triggerData.uploadSuccess) {
                    const { added, skipped } = triggerData.uploadSuccess;
                    Swal.fire({
                        icon: 'success',
                        title: 'File Uploaded!',
                        text: `Added ${added} new students, skipped ${skipped} duplicates or invalid records.`,
                        confirmButtonColor: '#f7bd03',
                        background: '#222831',
                        color: '#fff',
                        iconColor: '#f7bd03',
                        customClass: {
                            popup: 'my-swal-popup',
                            confirmButton: 'my-swal-confirm',
                            cancelButton: 'my-swal-cancel'
                        }
                    }).then(() => {
                        const selectedYear = document.getElementById("yearDropdownButton")
                            ?.querySelector(".button-text")
                            ?.textContent.match(/\d+/)[0] || '2025';
                        htmx.ajax('GET', `/fetch-students?year=${selectedYear}`, {
                            target: "#studentTableBody",
                            swap: "innerHTML"
                        });
                    });
                    return;
                }
            } catch (e) {
                console.error('Failed to parse HX-Trigger:', e);
            }
        }

        // Fallback for error cases (e.g., validation or server errors)
        if (event.detail.xhr.status !== 200) {
            let errorMessage = 'There was an error uploading the file.';
            try {
                const response = JSON.parse(event.detail.xhr.response);
                errorMessage = response.message || errorMessage;
            } catch (e) {
                console.error('Failed to parse error response:', e);
            }
            Swal.fire({
                icon: 'error',
                title: 'Upload Failed',
                text: errorMessage,
                confirmButtonColor: '#d33',
                background: '#222831',
                color: '#fff',
                iconColor: '#f7bd03',
                customClass: {
                    popup: 'my-swal-popup',
                    confirmButton: 'my-swal-confirm',
                    cancelButton: 'my-swal-cancel'
                }
            });
        }
    });

    // Vote Submission Popups
    document.addEventListener('vote:submit', function(event) {
        const { success, message, error } = event.detail;

        if (success) {
            Swal.fire({
                icon: 'success',
                title: 'Votes Submitted!',
                text: 'Your votes have been recorded successfully.',
                confirmButtonColor: '#f7bd03',
                background: '#222831',
                color: '#fff',
                iconColor: '#f7bd03',
                customClass: {
                    popup: 'my-swal-popup',
                    confirmButton: 'my-swal-confirm',
                    cancelButton: 'my-swal-cancel'
                }
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: message || error || 'An error occurred while submitting your votes.',
                confirmButtonColor: '#d33',
                background: '#222831',
                color: '#fff',
                iconColor: '#f7bd03',
                customClass: {
                    popup: 'my-swal-popup',
                    confirmButton: 'my-swal-confirm',
                    cancelButton: 'my-swal-cancel'
                }
            });
        }
    });

    document.addEventListener('vote:noVotes', function() {
        Swal.fire({
            icon: 'warning',
            title: 'No Votes Selected',
            text: 'Please select at least one candidate to vote for.',
            confirmButtonColor: '#f7bd03',
            background: '#222831',
            color: '#fff',
            iconColor: '#f7bd03',
            customClass: {
                popup: 'my-swal-popup',
                confirmButton: 'my-swal-confirm',
                cancelButton: 'my-swal-cancel'
            }
        });
    });
}

console.log('SweetAlert2 logic initialized');

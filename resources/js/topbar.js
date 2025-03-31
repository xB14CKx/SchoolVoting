// resources/js/topbar.js
document.addEventListener('DOMContentLoaded', () => {
    console.log('topbar.js loaded');

    function initializeTopbar() {
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');

        if (hamburger && navLinks) {
            console.log('Hamburger and nav-links found');

            // Toggle nav links on hamburger click
            hamburger.addEventListener('click', () => {
                console.log('Hamburger clicked, toggling nav-links');
                navLinks.classList.toggle('active');
            });

            // Close menu when a link is clicked
            navLinks.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    console.log('Nav link clicked, closing nav-links');
                    navLinks.classList.remove('active');
                });
            });
        } else {
            console.error('Hamburger or nav-links not found:', { hamburger, navLinks });
        }
    }

    // Initialize on page load
    initializeTopbar();

    // Reinitialize after HTMX updates the DOM
    document.addEventListener('htmx:afterSwap', () => {
        console.log('HTMX afterSwap event, reinitializing topbar');
        initializeTopbar();
    });
});
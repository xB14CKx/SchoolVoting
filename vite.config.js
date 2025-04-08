import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/Landing.css',
                'resources/css/eligibility.css',
                'resources/css/about.css',
                'resources/css/contact.css',
                'resources/css/login.css',
                'resources/css/registration.css',
                'resources/js/app.js',
                'resources/js/topbar.js',
                'resources/css/sidebar.css',
                'resources/css/sidebar-large.css',
                'resources/css/sidebar.css'
            ],
            refresh: true,
        }),
    ],
});

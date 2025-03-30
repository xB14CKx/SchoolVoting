import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/Landing.css',
                'resources/css/eligibility.css',
                'resources/css/about.css',
                'resources/css/contact.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // server: {
    //     host: '192.168.137.1',
    //     port: 5173,
    //     strictPort: true
    // },
     server: {
         host: '192.168.1.4',
         port: 5173,
         strictPort: true
     },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/topbar.css',
                'resources/css/Landing.css',
                'resources/css/eligibility.css',
                'resources/css/about.css',
                'resources/css/contact.css',
                'resources/css/logIn.css',
                'resources/css/registration.css',
                'resources/js/app.js',
                'resources/js/topbar.js',
                'resources/css/file-upload.css'
            ],
            refresh: true,
        }),
    ],
});

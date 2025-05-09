import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST || '192.168.1.4',
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    forceTLS: false, // Explicitly disable TLS since we're using http
    enabledTransports: ['ws'], // Use only ws for non-SSL
    disableStats: true,
    encrypted: false, // No encryption since we're using http
});

// Debugging WebSocket connection
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Successfully connected to Reverb WebSocket!');
});
window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('Reverb WebSocket connection error:', err);
});
window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.log('Disconnected from Reverb WebSocket.');
});

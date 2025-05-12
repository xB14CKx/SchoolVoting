import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Log the app key and host for debugging
console.log('REVERB_APP_KEY:', import.meta.env.VITE_REVERB_APP_KEY || 'gxpixtbqhuc9t2htp2qv');
console.log('REVERB_HOST:', import.meta.env.VITE_REVERB_HOST || '192.168.1.4');
console.log('REVERB_PORT:', import.meta.env.VITE_REVERB_PORT || 8080);

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY || 'gxpixtbqhuc9t2htp2qv',
    wsHost: import.meta.env.VITE_REVERB_HOST || '192.168.1.4',
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    forceTLS: false,
    enabledTransports: ['ws'],
    disableStats: true,
    encrypted: false,
});

// Debugging WebSocket connection
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('Successfully connected to Reverb WebSocket!');
});

window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('Reverb WebSocket connection error:', JSON.stringify(err, null, 2));
    if (err.error && err.error.message) {
        console.error('Error message:', err.error.message);
    }
});

window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.log('Disconnected from Reverb WebSocket.');
});

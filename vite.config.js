import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // TAMBAHKAN BLOK SERVER DI BAWAH INI
    server: {
        watch: {
            ignored: ['**/storage/**', '**/public/storage/**'],
        },
    },
});
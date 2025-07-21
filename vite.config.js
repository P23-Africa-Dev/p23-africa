import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/brn.css",
                "resources/js/brn.js",
            ],
            refresh: true,
        }),
    ],
});

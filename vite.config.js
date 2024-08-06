import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/file_import.js',
                'resources/js/product.js',
                'resources/css/app.css',
                'resources/css/import.css',
                'resources/css/product.css',
            ],
            refresh: true,
        }),
    ],
});

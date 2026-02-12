import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/ts/welcome.ts',
                'resources/ts/cart.ts',
                'resources/ts/product-index.ts',
                'resources/ts/product-show.ts',
            ],
            refresh: true,
        }),
    ],
});

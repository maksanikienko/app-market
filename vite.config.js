import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { google } from 'laravel-vite-plugin/fonts';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
            fonts: [
                // Rendered by the @fonts Blade directive → --font-onest / --font-cormorant
                google('Onest', {
                    weights: [400, 500, 600, 700],
                    subsets: ['latin', 'latin-ext', 'cyrillic'],
                    preload: [{ weight: 400 }],
                    fallbacks: ['system-ui', 'sans-serif'],
                }),
                google('Cormorant Garamond', {
                    alias: 'cormorant',
                    weights: [500, 600],
                    styles: ['normal', 'italic'],
                    subsets: ['latin', 'latin-ext', 'cyrillic'],
                    preload: false,
                    fallbacks: ['Georgia', 'serif'],
                }),
            ],
        }),
        vue(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(import.meta.dirname, 'resources/js'),
        },
    },
});

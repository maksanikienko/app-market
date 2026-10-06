import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { local } from 'laravel-vite-plugin/fonts';
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
                // Satoshi (Fontshare, ITF Free Font License) — rendered by the @fonts Blade directive
                local('Satoshi', {
                    variants: [300, 400, 500, 700].map(weight => ({
                        src: `resources/fonts/satoshi/Satoshi-${weight}.woff2`,
                        weight,
                    })),
                    preload: [{ weight: 400 }],
                    fallbacks: ['system-ui', 'sans-serif'],
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

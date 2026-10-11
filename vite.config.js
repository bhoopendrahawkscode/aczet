import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import createSvgSpritePlugin from 'vite-plugin-svg-sprite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        createSvgSpritePlugin({
            inputDir: 'resources/icons',
            outputDir: 'public/build/sprites',
            spriteName: 'sprite.svg',
            symbolId: 'icon-[name]',
        }),
    ],
});
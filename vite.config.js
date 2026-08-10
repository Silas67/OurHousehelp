import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        // CRITICAL: public/build is not a pure build output directory in this
        // project - it also holds hand-authored site assets (css/, js/,
        // vendor/, image/) and user uploads. Vite empties its outDir by
        // default, which deletes all of that. Never set this to true unless
        // public/build contains nothing but Vite output.
        emptyOutDir: false,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Bootstrap + FontAwesome for the Blade layouts, kept separate
                // from the Tailwind/Jetstream entry points above.
                'resources/css/ui.css',
                'resources/js/ui.js',
            ],
            refresh: true,
        }),
    ],
});

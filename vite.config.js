import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'vendor/resma/filament-awin-theme/resources/css/theme.css' // <-- AGREGA ESTA LÍNEA
            ],
            refresh: true,
        }),
        
        tailwindcss(),
    ],
});
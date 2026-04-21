import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
    // server: { // [AGREGAR ESTA SECCIÓN]
    //     host: '0.0.0.0', // Permite conexiones externas al contenedor
    //     port: 5173,      // Puerto por defecto de Vite
    //     strictPort: true,
    //     hmr: {
    //         host: 'localhost', // Cómo el navegador se comunica con Vite
    //     },
    //     watch: {
    //         usePolling: true, // Necesario para que detecte cambios en Docker (Windows/Mac)
    //         ignored: ['**/storage/framework/views/**'],
    //     },
    // },
});

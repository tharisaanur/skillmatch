import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
           input: [
    'resources/css/global.css',
    'resources/css/theme.css',
    'resources/css/hero.css',
    'resources/css/auth.css',
    'resources/css/login.css',
    'resources/css/register.css',
    'resources/css/complete-profile.css',
    'resources/css/profile.css',
    'resources/css/edit-profile.css',
    'resources/css/tentang-kami.css',
    'resources/css/explore.css',
    'resources/css/detail.css',
    'resources/css/projects.css',
    'resources/js/hero.js',
    'resources/js/auth.js',
    'resources/js/edit-profile.js',
],
            refresh: true,
        }),
    ],
});

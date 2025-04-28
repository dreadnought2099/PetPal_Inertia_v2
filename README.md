INERTIA.JS Setup


composer create-project laravel/laravel your-project-name
cd your-project-name


npm install vue@latest


composer require inertiajs/inertia-laravel


Create a new file at resources/views/app.blade.php:

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>



php artisan inertia:middleware

Add the middleware to your bootstrap/app.php:

use App\Http\Middleware\HandleInertiaRequests;
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        HandleInertiaRequests::class,
    ]);
})


npm install @inertiajs/vue3

Update your resources/js/app.js:

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})

npm install @vitejs/plugin-vue

Update your vite.config.js:

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue()
    ],
});

Testing the Setup
1. Create a Test Page
Create a new Vue component at resources/js/Pages/Home.vue:

<template>
    <h1>Hello world</h1>
</template>
2. Add Route
Update your routes/web.php:

use Inertia\Inertia;
Route::get('/', function () {
    return Inertia::render('Home');
});

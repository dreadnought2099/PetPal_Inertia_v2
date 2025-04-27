import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';

// For progress bar during page loads
InertiaProgress.init({
    color: '#ff5733',
    showSpinner: false,
    delay: 100,
    includeCSS: false,
    ease: 'ease-in-out',
    speed: 800,
});

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)          // Use the Inertia plugin
            .mount(el);           // Mount the app to the DOM element
    },
});

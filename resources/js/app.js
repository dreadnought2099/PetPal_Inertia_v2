import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

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
    progress: {
        color: 'white',
        ease: 'ease-in-out',
        speed: 800, 
        includeCSS: true,
        showSpinner: false,
      },
});

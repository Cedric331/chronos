require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Notifications from '@kyvg/vue3-notification';
import { Bar } from 'vue-chartjs'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(Notifications)
            .use(plugin)
            .use(Bar)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: 'red' });

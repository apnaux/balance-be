import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import '@tabler/icons-webfont/dist/tabler-icons.css';

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
  title: (text) => import.meta.env.VITE_APP_NAME + (text ? `: ${text}` : '')
})

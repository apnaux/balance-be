import { createApp, h } from 'vue'
import { createPinia } from 'pinia'
import { createInertiaApp } from '@inertiajs/vue3'

// Tabler Icons
import '@tabler/icons-webfont/dist/tabler-icons.css';

// PrimeVue
import PrimeVue from 'primevue/config';
import Material from '@primeuix/themes/material';

// Vue Date Picker
import '@vuepic/vue-datepicker/dist/main.css'

const pinia = createPinia();
createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(PrimeVue, {
        ripple: true,
        theme: {
          preset: Material
        }
      })
      .use(plugin)
      .use(pinia)
      .mount(el)
  },
  title: (text) => import.meta.env.VITE_APP_NAME + (text ? `: ${text}` : '')
})

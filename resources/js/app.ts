import '../css/app.css';
import 'leaflet/dist/leaflet.css';
import 'leaflet.fullscreen/Control.FullScreen.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { MotionPlugin } from '@vueuse/motion';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
  interface ImportMetaEnv {
    readonly VITE_APP_NAME: string;

    [key: string]: string | boolean | undefined;
  }

  interface ImportMeta {
    readonly env: ImportMetaEnv;
    readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
  }
}

const appName = import.meta.env.VITE_APP_NAME || 'Citizen Alliance';

// Register components for both Inertia and Blade pages
const registerComponents = (app: ReturnType<typeof createApp>) => {
  app
    .use(ZiggyVue)
    .use(MotionPlugin);

  return app;
};

// Initialize theme first
initializeTheme();

// Setup for Inertia pages
createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });
    registerComponents(app);
    app.use(plugin);
    app.mount(el);
  },
  progress: {
    color: '#4B5563'
  }
});

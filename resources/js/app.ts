import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

// Import components
import HeroSlider from '@/components/HeroSlider.vue';
import Newsletter from '@/components/Newsletter.vue';
import ImpactCard from '@/components/ImpactCard.vue';
import CounterAnimation from '@/components/CounterAnimation.vue';

// Types for Vite
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

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Register components for both Inertia and Blade pages
const registerComponents = (app: ReturnType<typeof createApp>) => {
  app.use(ZiggyVue)
    .component('hero-slider', HeroSlider)
    .component('newsletter', Newsletter)
    .component('impact-card', ImpactCard)
    .component('counter-animation', CounterAnimation);

  return app;
};

// Handle Inertia setup first
const setupInertia = async () => {
  try {
    await createInertiaApp({
      title: (title) => `${title} - ${appName}`,
      resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
      setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        registerComponents(app);
        app.mount(el);
      },
      progress: {
        color: '#4B5563',
      },
    });
  } catch (error) {
    console.error('Inertia setup error:', error);
    // If Inertia setup fails, we'll still try to set up the Blade app
    setupBladeApp();
  }
};

// Setup for Blade pages
const setupBladeApp = () => {
  const bladeAppElement = document.querySelector('#app:not([data-page])');
  if (bladeAppElement) {
    const bladeApp = createApp({
      template: '<div><slot></slot></div>'
    });
    registerComponents(bladeApp);
    bladeApp.mount(bladeAppElement);
  }
};

// Initialize the application
const init = async () => {
  // Initialize theme first
  initializeTheme();

  // Check if this is an Inertia page
  const inertiaEl = document.getElementById('app')?.getAttribute('data-page');

  if (inertiaEl) {
    // This is an Inertia page
    await setupInertia();
  } else {
    // This is a Blade page
    setupBladeApp();
  }
};

// Start the application
init().catch(console.error);

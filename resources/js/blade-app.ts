import '../css/app.css';

import AppFooter from '@/components/AppFooter.vue';
import CounterAnimation from '@/components/CounterAnimation.vue';
import HeroSlider from '@/components/HeroSlider.vue';
import ImpactCard from '@/components/ImpactCard.vue';
import ModeSwitch from '@/components/ModeSwitch.vue';
import Newsletter from '@/components/Newsletter.vue';
import { createApp } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import GuestAppHeader from '@/components/GuestAppHeader.vue';

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

// This will set light / dark mode on page load...
initializeTheme();

// Register components for both Inertia and Blade pages
const registerComponents = (app: ReturnType<typeof createApp>) => {
    app.use(ZiggyVue)
        .component('hero-slider', HeroSlider)
        .component('newsletter', Newsletter)
        .component('impact-card', ImpactCard)
        .component('toggle-dark', ModeSwitch)
        .component('app-footer', AppFooter)
        .component('app-header', GuestAppHeader)
        .component('counter-animation', CounterAnimation);

    return app;
};

const setupBladeApp = () => {
    const bladeApp = createApp({});
    registerComponents(bladeApp);
    bladeApp.mount('#blade_app');
};

setupBladeApp();

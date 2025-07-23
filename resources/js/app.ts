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

// Import components for global registration
import AppFooter from '@/components/AppFooter.vue';
import CounterAnimation from '@/components/CounterAnimation.vue';
import CustomButton from '@/components/CustomButton.vue';
import GuestAppHeader from '@/components/GuestAppHeader.vue';
import HeroSlider from '@/components/HeroSlider.vue';
import ImpactCard from '@/components/ImpactCard.vue';
import ModeSwitch from '@/components/ModeSwitch.vue';
import Newsletter from '@/components/Newsletter.vue';
import ImpactSection from '@/components/about/ImpactSection.vue';
import ProgramSection from '@/components/ProgramSection.vue';
import AboutBase from '@/components/about/AboutBase.vue';
import ProjectHero from '@/components/projects/ProjectHero.vue';
import ImpactStats from '@/components/projects/ImpactStats.vue';
import ProjectsGrid from '@/components/projects/ProjectsGrid.vue';
import CallToAction from '@/components/projects/CallToAction.vue';
import LeafletMap from '@/components/LeafletMap.vue';
import LikeButton from '@/components/LikeButton.vue';
import Comments from '@/components/Comments.vue';
import BlogPostHeader from '@/components/BlogPostHeader.vue';
import ToastMessages from '@/components/ToastMessages.vue';
import AuthDialog from '@/components/AuthDialog.vue';
import TableOfContents from '@/components/TableOfContents.vue';
import ShareButton from '@/components/ShareButton.vue';
import ContactForm from '@/components/ContactForm.vue';
import { Toaster } from 'vue-sonner';
import AboutSection from '@/components/about/AboutSection.vue';
import { Library } from 'lucide-vue-next';

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
    .use(MotionPlugin)
    .component('hero-slider', HeroSlider)
    .component('newsletter', Newsletter)
    .component('impact-card', ImpactCard)
    .component('toggle-dark', ModeSwitch)
    .component('app-footer', AppFooter)
    .component('app-header', GuestAppHeader)
    .component('program-section', ProgramSection)
    .component('custom-button', CustomButton)
    .component('about-base', AboutBase)
    .component('about-section', AboutSection)
    .component('impact-section', ImpactSection)
    .component('counter-animation', CounterAnimation)
    .component('project-hero', ProjectHero)
    .component('project-impact-stats', ImpactStats)
    .component('projects-grid', ProjectsGrid)
    .component('project-call-to-action', CallToAction)
    .component('leaflet-map', LeafletMap)
    .component('like-button', LikeButton)
    .component('comments', Comments)
    .component('share-button', ShareButton)
    .component('blog-post-header', BlogPostHeader)
    .component('table-of-contents', TableOfContents)
    .component('toast-messages', ToastMessages)
    .component('contact-form', ContactForm)
    .component('toaster', Toaster)
    .component('auth-dialog', AuthDialog)
    .component('library', Library);

  return app;
};

// Initialize theme first
initializeTheme();

// Check if we're in a blade context (has blade_app element)
const bladeAppElement = document.getElementById('blade_app');
if (bladeAppElement) {
  // Setup for Blade pages
  const bladeApp = createApp({});
  registerComponents(bladeApp);
  bladeApp.mount('#blade_app');
} else {
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
}

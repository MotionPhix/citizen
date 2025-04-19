import '../css/app.css';
import 'leaflet/dist/leaflet.css';
import 'leaflet.fullscreen/Control.FullScreen.css';

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
import LeafletMap from '@/components/LeafletMap.vue'

import { createApp } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { MotionPlugin } from '@vueuse/motion';
import LikeButton from '@/components/LikeButton.vue';
import Comments from '@/components/Comments.vue';
import BlogPostHeader from '@/components/BlogPostHeader.vue';
import ToastMessages from '@/components/ToastMessages.vue';
import AuthDialog from '@/components/AuthDialog.vue';
import TableOfContents from '@/components/TableOfContents.vue';
import ShareButton from '@/components/ShareButton.vue';
import ContactForm from '@/components/ContactForm.vue';
import { Toaster } from 'vue-sonner';

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
    .component('impact-section', ImpactSection)
    .component('counter-animation', CounterAnimation)
    .component('project-hero', ProjectHero)
    .component('project-impact-stats', ImpactStats)
    .component('projects-grid', ProjectsGrid)
    .component('project-call-to-action', CallToAction)
    .component('leaflet-map', LeafletMap)
    // Register new components
    .component('like-button', LikeButton)
    .component('comments', Comments)
    .component('share-button', ShareButton)
    .component('blog-post-header', BlogPostHeader)
    .component('table-of-contents', TableOfContents)
    .component('toast-messages', ToastMessages)
    .component('contact-form', ContactForm)
    .component('toaster', Toaster)
    .component('auth-dialog', AuthDialog);

  return app;
};

const setupBladeApp = () => {
  const bladeApp = createApp({});
  registerComponents(bladeApp);
  bladeApp.mount('#blade_app');
};

setupBladeApp();

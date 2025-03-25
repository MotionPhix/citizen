import '../css/app.css';

import AppFooter from '@/components/AppFooter.vue';
import CounterAnimation from '@/components/CounterAnimation.vue';
import CustomButton from '@/components/CustomButton.vue';
import GuestAppHeader from '@/components/GuestAppHeader.vue';
import HeroSlider from '@/components/HeroSlider.vue';
import ImpactCard from '@/components/ImpactCard.vue';
import ModeSwitch from '@/components/ModeSwitch.vue';
import Newsletter from '@/components/Newsletter.vue';
import ImpactSection from '@/components/about/ImpactSection.vue';
import ProgramSection from '../views/components/ProgramSection.vue';
import AboutBase from '@/components/about/AboutBase.vue';
import ProjectHero from '@/Components/Projects/ProjectHero.vue'
import ImpactStats from '@/Components/Projects/ImpactStats.vue'
import ProjectsGrid from '@/Components/Projects/ProjectsGrid.vue'
import CallToAction from '@/Components/Projects/CallToAction.vue'

import { createApp } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

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
    .component('program-section', ProgramSection)
    .component('custom-button', CustomButton)
    .component('about-base', AboutBase)
    .component('impact-section', ImpactSection)
    .component('counter-animation', CounterAnimation)
    .component('project-hero', ProjectHero)
    .component('project-impact-stats', ImpactStats)
    .component('projects-grid', ProjectsGrid)
    .component('project-call-to-action', CallToAction);

  return app;
};

const setupBladeApp = () => {
  const bladeApp = createApp({});
  registerComponents(bladeApp);
  bladeApp.mount('#blade_app');
};

setupBladeApp();

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import GuestLayout from '@/layouts/GuestLayout.vue'
import HeroSlider from '@/components/HeroSlider.vue'
import ImpactStats from '@/components/ImpactStats.vue'
import AboutSection from '@/components/about/AboutSection.vue'
import Newsletter from '@/components/Newsletter.vue'
import Testimonials from '@/components/Testimonials.vue'
import ContentSection from '@/components/ContentSection.vue'
import {
  Library,
  MessageSquare,
  Megaphone,
  ArrowRight,
  Phone,
  Mail,
  CheckCircle,
  Users,
  Target,
  Heart,
  Lightbulb
} from 'lucide-vue-next'

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger)

interface Slide {
  image: string
  title: string
  description: string
}

interface Approach {
  title: string
  description: string
  icon: string
}

interface Metric {
  id: number
  title: string
  value: string
  description: string
  icon: string
  is_published: boolean
  sort_order: number
}

interface Props {
  slides: Slide[]
  approaches: Approach[]
  metrics: Metric[]
}

const props = defineProps<Props>()

const getIconComponent = (iconName: string) => {
  switch (iconName) {
    case 'chat-bubble-left-right':
      return MessageSquare
    case 'megaphone':
      return Megaphone
    default:
      return Library
  }
}

const testimonials = [
  {
    name: 'Katerina',
    role: 'Phone Victim',
    avatar: '/images/testimonials/katerina.jpg',
    quote: 'Citizen Alliance stood by me when the police took my phones. Their support brought justice and gave me my voice back. I\'m truly grateful.'
  },
  {
    name: 'Kola Dymon',
    role: 'Farm Land Owner',
    avatar: '/images/testimonials/kola-dymon.jpg',
    quote: 'Losing my farm felt like losing my future. Citizen Alliance didn\'t just fight for my land—they fought for my right to live with dignity. Thanks to their support, my farm is mine again'
  }
]

// Animation refs
const approachesRef = ref(null)
const statsRef = ref(null)
const aboutRef = ref(null)

// GSAP Animations
onMounted(() => {
  // Animate approaches cards
  gsap.fromTo('.approach-card',
    {
      opacity: 0,
      y: 50,
      scale: 0.9
    },
    {
      opacity: 1,
      y: 0,
      scale: 1,
      duration: 0.8,
      stagger: 0.2,
      ease: "power2.out",
      scrollTrigger: {
        trigger: '.approaches-section',
        start: 'top 80%',
        end: 'bottom 20%',
        toggleActions: 'play none none reverse'
      }
    }
  )

  // Animate stats section
  gsap.fromTo('.stats-container',
    {
      opacity: 0,
      y: 30
    },
    {
      opacity: 1,
      y: 0,
      duration: 1,
      ease: "power2.out",
      scrollTrigger: {
        trigger: '.stats-container',
        start: 'top 85%',
        toggleActions: 'play none none reverse'
      }
    }
  )

  // Animate about section
  gsap.fromTo('.about-content',
    {
      opacity: 0,
      x: -50
    },
    {
      opacity: 1,
      x: 0,
      duration: 1,
      ease: "power2.out",
      scrollTrigger: {
        trigger: '.about-section',
        start: 'top 75%',
        toggleActions: 'play none none reverse'
      }
    }
  )

  // Animate CTA buttons
  gsap.fromTo('.cta-button',
    {
      opacity: 0,
      scale: 0.8
    },
    {
      opacity: 1,
      scale: 1,
      duration: 0.6,
      stagger: 0.1,
      ease: "back.out(1.7)",
      scrollTrigger: {
        trigger: '.cta-section',
        start: 'top 80%',
        toggleActions: 'play none none reverse'
      }
    }
  )
})
</script>

<template>
  <Head>
    <title>Home - Citizen Alliance</title>
    <meta
      name="description"
      content="Citizen Alliance (CA) is a coalition of civil society organizations and citizen groups established in 2012 as a citizen-led engagement initiative on development and governance processes."
    />
    <meta property="og:title" content="Home - Citizen Alliance" />
    <meta property="og:description" content="Empowering citizens for lasting change in Malawi since 2012" />
  </Head>

  <GuestLayout>
    <!-- Hero Slider Section - Full Width -->
    <HeroSlider :slides="slides" />

    <!-- Impact Statistics Section -->
    <ContentSection background="white" padding="lg">
      <div class="stats-container">
        <ImpactStats :metrics="metrics" />
      </div>
    </ContentSection>

    <!-- Our Approaches Section -->
    <ContentSection background="gradient" padding="lg" class="approaches-section">
      <div class="text-center mb-12">
        <div class="flex items-center justify-center mb-4">
          <div class="w-12 h-1 bg-ca-primary rounded-full mr-4"></div>
          <span class="text-ca-primary font-medium uppercase tracking-wide text-sm">Our Methods</span>
          <div class="w-12 h-1 bg-ca-primary rounded-full ml-4"></div>
        </div>
        <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Strategic Approaches
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed">
          We employ three strategic approaches to facilitate meaningful citizen engagement
          and promote democratic governance across Malawi.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="(approach, index) in approaches"
          :key="approach.title"
          class="approach-card bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-ca-primary/50 dark:hover:border-ca-highlight/50 transition-all duration-300 group"
        >
          <div class="w-12 h-12 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mb-6 group-hover:bg-ca-primary group-hover:text-white transition-all duration-300">
            <component :is="getIconComponent(approach.icon)" class="w-6 h-6" />
          </div>

          <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white mb-4">
            {{ approach.title }}
          </h3>

          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            {{ approach.description }}
          </p>
        </div>
      </div>

      <!-- Call to Action -->
      <div class="text-center mt-12 cta-section">
        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
          Want to learn more about how we're making a difference?
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a
            href="/projects"
            class="cta-button inline-flex items-center px-8 py-4 bg-ca-primary text-white font-semibold rounded-xl hover:bg-ca-primary/90 transition-all duration-300 shadow-lg hover:shadow-xl"
          >
            Explore Our Projects
            <ArrowRight class="ml-2 h-5 w-5" />
          </a>
          <a
            href="/about"
            class="cta-button inline-flex items-center px-8 py-4 bg-white dark:bg-gray-800 text-ca-primary dark:text-ca-highlight font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300 border border-ca-primary/20 dark:border-ca-highlight/20"
          >
            Learn Our Story
          </a>
        </div>
      </div>
    </ContentSection>

    <!-- About Section with Hero Image -->
    <ContentSection background="white" padding="lg" class="about-section">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <!-- Content -->
        <div class="about-content">
          <div class="mb-8">
            <div class="flex items-center mb-4">
              <div class="w-12 h-1 bg-ca-primary rounded-full mr-4"></div>
              <span class="text-ca-primary font-medium uppercase tracking-wide text-sm">Who We Are</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-6">
              Empowering Citizens for
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-ca-primary to-ca-highlight">
                Lasting Change
              </span>
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
              Citizen Alliance (CA) is a coalition of civil society organizations and citizen groups
              established in 2012 as a citizen-led engagement initiative on development and governance processes.
            </p>
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
              We strengthen citizen participation in governance and development through advocacy,
              capacity building, and fostering meaningful dialogue between citizens and duty bearers.
            </p>
          </div>

          <!-- Key Features -->
          <div class="space-y-4">
            <div class="flex items-start">
              <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center mr-3 mt-1">
                <CheckCircle class="w-4 h-4" />
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Citizen Engagement</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">Strengthening democratic participation across communities</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mr-3 mt-1">
                <Users class="w-4 h-4" />
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Capacity Building</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">Training and empowering local leaders and communities</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="w-6 h-6 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full flex items-center justify-center mr-3 mt-1">
                <Target class="w-4 h-4" />
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Advocacy & Policy</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">Influencing policy for sustainable development</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Image -->
        <div class="relative">
          <div class="aspect-w-4 aspect-h-5 rounded-xl overflow-hidden">
            <img
              src="/images/about-alliance.jpg"
              alt="Citizen Alliance community work"
              class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
          </div>

          <!-- Floating Card -->
          <div class="absolute -bottom-6 -right-6 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
              <Heart class="h-8 w-8 text-red-500 mr-3" />
              <div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">12+</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Years of Impact</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </ContentSection>

    <!-- About Section Component -->
    <ContentSection background="gradient" padding="lg">
      <AboutSection />
    </ContentSection>

    <!-- Testimonials Section -->
    <ContentSection background="white" padding="lg">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Stories of Impact
        </h2>

        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
          Real stories from the communities we serve and <br class="hidden sm:inline"> the lives we've touched.
        </p>
      </div>

      <Testimonials :testimonials="testimonials" />
    </ContentSection>

    <!-- Contact & Support Section -->
    <ContentSection background="primary" padding="lg" class="text-white">
      <div class="text-center max-w-4xl mx-auto">
        <div class="mb-8">
          <h2 class="text-3xl md:text-4xl font-display font-bold mb-4">
            Get Involved with Citizen Alliance
          </h2>
          <p class="text-xl text-white/90 max-w-2xl mx-auto leading-relaxed">
            Join us in our mission to empower citizens and drive positive change across Malawi.
            Together, we can build stronger communities and better governance.
          </p>
        </div>

        <!-- Contact Information -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
          <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">

            <section class="mx-auto flex flex-col items-start">
              <h3 class="text-lg font-semibold mb-2 flex items-center gap-x-4">
                <Phone class="size-6" /> <span>Call Us</span>
              </h3>

              <a
                href="tel:+265991602233"
                class="text-white/90 hover:text-white transition-colors">
                +265 (0) 991 602 233
              </a>
            </section>

          </div>

          <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
            <section class="mx-auto flex flex-col items-start">

              <h3 class="text-lg font-semibold mb-2 flex items-center gap-x-4">
                <Mail class="size-6 mx-auto" />
                <span>Email Us</span>
              </h3>

              <a
                href="mailto:support@citizenalliancemw.org"
                class="text-white/90 hover:text-white transition-colors">
                support@citizenalliancemw.org
              </a>
            </section>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a
            href="https://wa.me/+265991602233"
            class="inline-flex items-center px-8 py-4 bg-white text-ca-primary font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            WhatsApp Us
          </a>
          <a
            href="/contact"
            class="inline-flex items-center px-8 py-4 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition-all duration-300 backdrop-blur-sm border border-white/20"
          >
            Contact Form
            <ArrowRight class="ml-2 h-5 w-5" />
          </a>
        </div>
      </div>
    </ContentSection>

    <!-- Newsletter Section -->
    <ContentSection background="gradient" padding="lg">
      <div class="text-center max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Stay Connected
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
          Join our community and stay informed about our initiatives, impact stories, and opportunities to get involved.
        </p>
        <Newsletter />
      </div>
    </ContentSection>
  </GuestLayout>
</template>

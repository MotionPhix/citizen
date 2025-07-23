<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { onMounted } from 'vue'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import GuestLayout from '@/layouts/GuestLayout.vue'
import ContentSection from '@/components/ContentSection.vue'
import ContactForm from '@/components/ContactForm.vue'
import LeafletMap from '@/components/LeafletMap.vue'
import {
  MapPin,
  Phone,
  Mail,
  ArrowRight,
  Clock,
  MessageSquare,
  Users,
  Handshake,
  Send
} from 'lucide-vue-next'

interface Honeypot {
  nameFieldName: string
  validFromFieldName: string
  encryptedValidFrom: string
}

interface Props {
  honeypot: Honeypot
  message?: string
}

defineProps<Props>()

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger)

// GSAP Animations
onMounted(() => {
  // Animate contact cards
  gsap.fromTo('.contact-card',
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
        trigger: '.contact-cards-section',
        start: 'top 80%',
        toggleActions: 'play none none reverse'
      }
    }
  )

  // Animate form section
  gsap.fromTo('.contact-form-section',
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
        trigger: '.contact-form-section',
        start: 'top 75%',
        toggleActions: 'play none none reverse'
      }
    }
  )

  // Animate hero content
  gsap.fromTo('.hero-content',
    {
      opacity: 0,
      y: 30
    },
    {
      opacity: 1,
      y: 0,
      duration: 1,
      ease: "power2.out",
      delay: 0.3
    }
  )

  // Animate CTA section
  gsap.fromTo('.cta-content',
    {
      opacity: 0,
      scale: 0.9
    },
    {
      opacity: 1,
      scale: 1,
      duration: 0.8,
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
    <title>Contact Us - Citizen Alliance</title>
    <meta
      name="description"
      content="Get in touch with Citizen Alliance for inquiries, collaborations, or support. We're here to help you make a difference."
    />
    <meta property="og:title" content="Contact Us - Citizen Alliance" />
    <meta property="og:description" content="Connect with us to discuss partnerships, support, or learn more about our initiatives" />
  </Head>

  <GuestLayout>
    <!-- Hero Section -->
    <section class="relative min-h-[80vh] flex items-center bg-gradient-to-br from-ca-primary via-ca-primary to-ca-highlight overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
      </div>

      <!-- Content -->
      <div class="relative w-full">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div class="hero-content">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-bold text-white mb-6 leading-tight">
              Let's Connect and <br class="hidden sm:inline">
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">
                Make Change
              </span>
            </h1>

            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto mb-8 leading-relaxed">
              Whether you have questions, want to collaborate, or need support, we're here to help you make a meaningful impact in your community.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <a
                href="#contact-form"
                class="inline-flex items-center px-8 py-4 bg-white text-ca-primary font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl"
              >
                Send Message
                <Send class="ml-2 h-5 w-5" />
              </a>
              <a
                href="#contact-info"
                class="inline-flex items-center px-8 py-4 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition-all duration-300 backdrop-blur-sm border border-white/20"
              >
                Contact Info
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Information Section -->
    <ContentSection background="white" padding="lg" id="contact-info" class="contact-cards-section">
      <div class="text-center mb-12">
        <div class="flex items-center justify-center mb-4">
          <div class="w-12 h-1 bg-ca-primary rounded-full mr-4"></div>
          <span class="text-ca-primary font-medium uppercase tracking-wide text-sm">Get In Touch</span>
          <div class="w-12 h-1 bg-ca-primary rounded-full ml-4"></div>
        </div>
        <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Multiple Ways to Reach Us
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed">
          Choose the most convenient way to connect with our team. We're committed to responding promptly to all inquiries.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Address -->
        <div class="contact-card bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-ca-primary/50 dark:hover:border-ca-highlight/50 transition-all duration-300 group text-center">
          <div class="w-16 h-16 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:bg-ca-primary group-hover:text-white transition-all duration-300">
            <MapPin class="w-8 h-8" />
          </div>
          <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white mb-4">Visit Our Office</h3>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            Area 47/2/113, Lilongwe Street<br>
            P.O. Box 619<br>
            Lilongwe, Malawi
          </p>
        </div>

        <!-- Phone -->
        <div class="contact-card bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-ca-primary/50 dark:hover:border-ca-highlight/50 transition-all duration-300 group text-center">
          <div class="w-16 h-16 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:bg-ca-primary group-hover:text-white transition-all duration-300">
            <Phone class="w-8 h-8" />
          </div>
          <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white mb-4">Call Us</h3>
          <p class="text-gray-600 dark:text-gray-300 mb-2">
            <a href="tel:+265991602233" class="hover:text-ca-primary dark:hover:text-ca-highlight transition-colors font-medium">
              +265 (0) 991 602 233
            </a>
          </p>
          <div class="flex items-center justify-center text-sm text-gray-500 dark:text-gray-400">
            <Clock class="w-4 h-4 mr-2" />
            Mon-Fri: 8AM-4PM
          </div>
        </div>

        <!-- Email -->
        <div class="contact-card bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-ca-primary/50 dark:hover:border-ca-highlight/50 transition-all duration-300 group text-center">
          <div class="w-16 h-16 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:bg-ca-primary group-hover:text-white transition-all duration-300">
            <Mail class="w-8 h-8" />
          </div>
          <h3 class="text-xl font-display font-semibold text-gray-900 dark:text-white mb-4">Email Us</h3>
          <p class="text-gray-600 dark:text-gray-300">
            <a href="mailto:citizenalliance12@gmail.com" class="hover:text-ca-primary dark:hover:text-ca-highlight transition-colors font-medium break-all">
              citizenalliance12@gmail.com
            </a>
          </p>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
            We respond within 24 hours
          </p>
        </div>
      </div>
    </ContentSection>

    <!-- Contact Form Section -->
    <ContentSection background="gradient" padding="lg" id="contact-form">
      <div class="grid lg:grid-cols-2 gap-12 items-start">
        <!-- Form Content -->
        <div class="contact-form-section">
          <div class="mb-8">
            <div class="flex items-center mb-4">
              <div class="w-12 h-1 bg-ca-primary rounded-full mr-4"></div>
              <span class="text-ca-primary font-medium uppercase tracking-wide text-sm">Send Message</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-6">
              Ready to Make a Difference?
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed mb-6">
              Fill out the form and we'll get back to you as soon as possible. Whether you're interested in partnerships, volunteering, or learning more about our work, we'd love to hear from you.
            </p>

            <!-- Why Contact Us -->
            <div class="space-y-4">
              <div class="flex items-start">
                <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center mr-3 mt-1">
                  <Users class="w-4 h-4" />
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900 dark:text-white">Partnership Opportunities</h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Collaborate with us on community development initiatives</p>
                </div>
              </div>
              <div class="flex items-start">
                <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mr-3 mt-1">
                  <Handshake class="w-4 h-4" />
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900 dark:text-white">Support & Assistance</h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Get help with governance and development processes</p>
                </div>
              </div>
              <div class="flex items-start">
                <div class="w-6 h-6 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full flex items-center justify-center mr-3 mt-1">
                  <MessageSquare class="w-4 h-4" />
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900 dark:text-white">General Inquiries</h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Ask questions about our programs and services</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700">
          <ContactForm :honeypot="honeypot" />
        </div>
      </div>
    </ContentSection>

    <!-- Map Section -->
    <section>
      <div class="w-full h-[500px] relative">
        <LeafletMap />
      </div>
    </section>

    <!-- Call to Action Section -->
    <ContentSection background="primary" padding="lg" class="text-white cta-section">
      <div class="text-center max-w-4xl mx-auto">
        <div class="cta-content">
          <h2 class="text-3xl md:text-4xl font-display font-bold mb-6">
            Join Our Mission
          </h2>
          <p class="text-xl text-white/90 max-w-2xl mx-auto leading-relaxed mb-8">
            Partner with us to empower citizens and drive sustainable development across Malawi.
            Together, we can create lasting positive change in our communities.
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a
              href="/about"
              class="inline-flex items-center px-8 py-4 bg-white text-ca-primary font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl"
            >
              Learn About Us
              <ArrowRight class="ml-2 h-5 w-5" />
            </a>
            <a
              href="/projects"
              class="inline-flex items-center px-8 py-4 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition-all duration-300 backdrop-blur-sm border border-white/20"
            >
              View Our Projects
            </a>
          </div>
        </div>
      </div>
    </ContentSection>
  </GuestLayout>
</template>

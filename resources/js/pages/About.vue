<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import GuestLayout from '@/layouts/GuestLayout.vue'
import ContentSection from '@/components/ContentSection.vue'
import TeamMemberCard from '@/components/about/TeamMemberCard.vue'
import TimelineCard from '@/components/about/TimelineCard.vue'
import PartnerCard from '@/components/about/PartnerCard.vue'
import Newsletter from '@/components/Newsletter.vue'
import {
  Eye,
  Flag,
  Scale,
  HandIcon,
  Lightbulb,
  Users,
  Target,
  Award,
  TrendingUp,
  Heart,
  GraduationCap,
  Building,
  ArrowRight,
  CheckCircle
} from 'lucide-vue-next'

interface Value {
  icon: string
  title: string
  description: string
}

interface TeamMember {
  name: string
  position: string
  image: string
  bio: string
  linkedin?: string
  twitter?: string
}

interface TimelineItem {
  year: string
  title: string
  description: string
}

interface Partner {
  name: string
  logo: string
  website: string
}

interface Props {
  values: Value[]
  team: TeamMember[]
  timeline: TimelineItem[]
  partners: Partner[]
  subscriberCount: number
}

const props = defineProps<Props>()

// Get icon component based on string
const getIconComponent = (iconName: string) => {
  switch (iconName) {
    case 'scale':
      return Scale
    case 'users':
      return Users
    case 'light-bulb':
      return Lightbulb
    case 'hand':
      return HandIcon
    default:
      return Target
  }
}

// Impact statistics
const impactStats = computed(() => [
  {
    id: 1,
    title: 'Citizens Reached',
    value: '50,000+',
    description: 'Across all regions of Malawi',
    icon: Users,
    color: 'blue'
  },
  {
    id: 2,
    title: 'Training Sessions',
    value: '450+',
    description: 'For local leaders and communities',
    icon: GraduationCap,
    color: 'green'
  },
  {
    id: 3,
    title: 'Partner Organizations',
    value: '25+',
    description: 'International and local partnerships',
    icon: Building,
    color: 'purple'
  },
  {
    id: 4,
    title: 'Years of Impact',
    value: '12+',
    description: 'Continuous community service',
    icon: Award,
    color: 'orange'
  }
])

// Key achievements
const achievements = [
  {
    icon: GraduationCap,
    title: 'Education Initiatives',
    description: '32 schools supported with resources and training programs',
    stats: '32 Schools'
  },
  {
    icon: Heart,
    title: 'Healthcare Programs',
    description: '85 medical camps organized in rural communities',
    stats: '85 Camps'
  },
  {
    icon: Users,
    title: 'Community Development',
    description: '450+ training sessions conducted for local leaders',
    stats: '450+ Sessions'
  }
]
</script>

<template>
  <Head>
    <title>About Citizen Alliance</title>
    <meta
      name="description"
      content="Discover our mission to empower citizens and drive positive change across Malawi through effective governance and community development initiatives."
    />
    <meta property="og:title" content="About Citizen Alliance" />
    <meta property="og:description" content="Empowering citizens for lasting change in Malawi since 2012" />
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
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-bold text-white mb-6 leading-tight">
            Empowering Citizens for
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">
              Lasting Change
            </span>
          </h1>

          <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto mb-8 leading-relaxed">
            Since 2012, we've been at the forefront of strengthening citizen participation in governance and fostering sustainable development across Malawi.
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a
              href="#mission"
              class="inline-flex items-center px-8 py-4 bg-white text-ca-primary font-semibold rounded-xl hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl"
            >
              Learn Our Story
              <ArrowRight class="ml-2 h-5 w-5" />
            </a>
            <a
              href="#team"
              class="inline-flex items-center px-8 py-4 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition-all duration-300 backdrop-blur-sm border border-white/20"
            >
              Meet Our Team
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Impact Statistics -->
    <ContentSection background="white" padding="lg">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Our Impact in Numbers
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Measurable results from over a decade of dedicated community service and advocacy.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          v-for="stat in impactStats"
          :key="stat.id"
          class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 border border-gray-200 dark:border-gray-600"
        >
          <div class="flex justify-center mb-4">
            <div :class="[
              'w-12 h-12 rounded-full flex items-center justify-center',
              stat.color === 'blue' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' :
              stat.color === 'green' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' :
              stat.color === 'purple' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400' :
              'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400'
            ]">
              <component :is="stat.icon" class="h-6 w-6" />
            </div>
          </div>
          <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            {{ stat.value }}
          </div>
          <div class="text-sm font-medium text-gray-900 dark:text-white mb-1">
            {{ stat.title }}
          </div>
          <div class="text-xs text-gray-600 dark:text-gray-400">
            {{ stat.description }}
          </div>
        </div>
      </div>
    </ContentSection>

    <!-- Mission & Vision Section -->
    <ContentSection background="gradient" padding="lg" id="mission">
      <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
        <!-- Vision Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300">
          <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mr-4">
              <Eye class="w-6 h-6" />
            </div>
            <h3 class="text-2xl font-display font-bold text-gray-900 dark:text-white">Our Vision</h3>
          </div>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            A transformed Malawi where citizens are empowered, actively engaged in governance, and their voices shape the decisions that affect their lives.
          </p>
        </div>

        <!-- Mission Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300">
          <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mr-4">
              <Flag class="w-6 h-6" />
            </div>
            <h3 class="text-2xl font-display font-bold text-gray-900 dark:text-white">Our Mission</h3>
          </div>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            To strengthen citizen participation in governance and development processes through advocacy, capacity building, and fostering meaningful dialogue between citizens and duty bearers.
          </p>
        </div>
      </div>
    </ContentSection>

    <!-- Core Values Section -->
    <ContentSection background="white" padding="lg">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Our Core Values
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          The principles that guide our work and shape our approach to community development.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          v-for="(value, index) in values"
          :key="index"
          class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-ca-primary/50 dark:hover:border-ca-highlight/50 transition-all duration-300 group"
        >
          <div class="w-12 h-12 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-xl flex items-center justify-center mb-4 group-hover:bg-ca-primary group-hover:text-white transition-all duration-300">
            <component :is="getIconComponent(value.icon)" class="w-6 h-6" />
          </div>
          <h3 class="text-lg font-display font-semibold text-gray-900 dark:text-white mb-3">
            {{ value.title }}
          </h3>
          <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
            {{ value.description }}
          </p>
        </div>
      </div>
    </ContentSection>

    <!-- Key Achievements Section -->
    <ContentSection background="gradient" padding="lg">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <!-- Content -->
        <div>
          <div class="mb-8">
            <div class="flex items-center mb-4">
              <div class="w-12 h-1 bg-ca-primary rounded-full mr-4"></div>
              <span class="text-ca-primary font-medium uppercase tracking-wide text-sm">Our Impact</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
              Creating Lasting Change in Communities
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
              Through our programs and initiatives, we've touched thousands of lives, empowering individuals to take active roles in their communities' development and governance processes.
            </p>
          </div>

          <div class="space-y-6">
            <div
              v-for="(achievement, index) in achievements"
              :key="index"
              class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700"
            >
              <div class="flex items-start">
                <div class="w-10 h-10 bg-ca-primary/10 dark:bg-ca-primary/20 text-ca-primary rounded-lg flex items-center justify-center mr-4 mt-1">
                  <component :is="achievement.icon" class="w-5 h-5" />
                </div>
                <div class="flex-1">
                  <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                      {{ achievement.title }}
                    </h3>
                    <span class="text-sm font-bold text-ca-primary bg-ca-primary/10 px-3 py-1 rounded-full">
                      {{ achievement.stats }}
                    </span>
                  </div>
                  <p class="text-gray-600 dark:text-gray-300 text-sm">
                    {{ achievement.description }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Image -->
        <div class="relative">
          <div class="aspect-w-4 aspect-h-5 rounded-xl overflow-hidden">
            <img
              src="/images/about-us.jpg"
              alt="Community impact"
              class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
          </div>

          <!-- Floating Stats -->
          <div class="absolute -bottom-6 -left-6 bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
              <TrendingUp class="h-8 w-8 text-green-500 mr-3" />
              <div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ subscriberCount.toLocaleString() }}+</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Newsletter Subscribers</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </ContentSection>

    <!-- Team Section -->
    <ContentSection background="white" padding="lg" id="team">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Meet Our Board Members
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Dedicated professionals committed to driving positive change in our communities.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <TeamMemberCard
          v-for="(member, index) in team"
          :key="member.name"
          :member="member"
          :index="index"
        />
      </div>
    </ContentSection>

    <!-- Timeline Section -->
    <ContentSection background="gradient" padding="lg">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Our Journey
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          A decade of growth, impact, and community transformation.
        </p>
      </div>

      <div class="max-w-4xl mx-auto">
        <div class="relative">
          <!-- Timeline Line -->
          <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-ca-primary to-ca-highlight rounded-full"></div>

          <!-- Timeline Items -->
          <div class="space-y-12">
            <TimelineCard
              v-for="(item, index) in timeline"
              :key="item.year"
              :item="item"
              :index="index"
              :is-last="index === timeline.length - 1"
            />
          </div>
        </div>
      </div>
    </ContentSection>

    <!-- Partners Section -->
    <ContentSection background="white" padding="lg">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-4">
          Our Partners
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
          Working together with leading organizations to amplify our impact.
        </p>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <PartnerCard
          v-for="(partner, index) in partners"
          :key="partner.name"
          :partner="partner"
          :index="index"
        />
      </div>
    </ContentSection>

    <!-- Newsletter CTA Section -->
    <ContentSection background="primary" padding="lg" class="text-white">
      <div class="text-center max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-display font-bold mb-4">
          Stay Connected
        </h2>

        <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
          Join our community and stay informed about our initiatives and impact.
        </p>

        <Newsletter />

        <p class="mt-4 text-sm text-white/80">
          Join {{ subscriberCount.toLocaleString() }}+ members who receive our updates. No spam, unsubscribe anytime.
        </p>
      </div>
    </ContentSection>
  </GuestLayout>
</template>

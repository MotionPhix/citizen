<script setup lang="ts">
</script>

<template>
  <section id="about" class="py-24 text-ca-secondary dark:text-ca-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Content Side -->
        <div class="order-2 lg:order-1">
          <div class="mb-8">
            <span class="text-blue-600 font-semibold text-lg tracking-wide">About Us</span>
            <h2 class="font-display text-4xl md:text-5xl font-bold mt-4 mb-6">
              Who We Are
            </h2>
          </div>

          <div class="prose prose-lg text-gray-600 dark:text-gray-100 mb-8">
            <p class="mb-6 leading-relaxed">
              <strong class="text-gray-900 dark:text-gray-300">Citizen Alliance (CA)</strong> is a coalition of civil society organizations
              and citizen groups established in 2012 as a citizen-led engagement initiative on development and governance processes.
              The organization is registered under the laws of Malawi as a company limited by guarantee and operates in all
              the districts through Citizen Forums (CFs).
            </p>

            <p class="mb-6 leading-relaxed">
              CA was established against a background of increased poverty, worsening corruption and continued exclusion
              of marginalized groups such as women, youth and persons with disabilities in decision making. Despite the
              improved legal and policy environment following the transition to multiparty democracy, majority of citizens
              are yet to benefit from the democratic promise including the decentralization programme.
            </p>

            <p class="leading-relaxed">
              The CA journey seeks to intervene by empowering citizens to actively participate in development and governance
              processes through enhancing social accountability, capacity building and advocacy.
            </p>
          </div>

          <div class="my-4 border border-gray-300 dark:border-gray-700"></div>

          <!-- Key Stats -->
          <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="backdrop-blur-sm">
              <div class="text-3xl font-bold text-blue-600 mb-2">12+</div>
              <div class="text-gray-700 dark:text-gray-200 font-medium">Years of Impact</div>
            </div>

            <div class="backdrop-blur-sm">
              <div class="text-3xl font-bold text-green-600 mb-2">28</div>
              <div class="text-gray-700 dark:text-gray-200 font-medium">Districts Reached</div>
            </div>
          </div>

          <!-- Call to Action -->
<!--          <div class="flex flex-col sm:flex-row gap-4">-->
<!--            <a-->
<!--              href="/projects"-->
<!--              class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-4 rounded-full text-lg font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center"-->
<!--            >-->
<!--              Explore Our Projects-->
<!--            </a>-->

<!--            <a-->
<!--              href="organization/#team"-->
<!--              class="bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-800 px-8 py-4 rounded-full text-lg font-semibold transition-all duration-300 hover:bg-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center"-->
<!--            >-->
<!--              Meet Our Team-->
<!--            </a>-->
<!--          </div>-->
        </div>

        <!-- Visual Side -->
        <div class="order-1 lg:order-2">
          <div class="relative">
            <!-- Main Image -->
            <div class="relative bg-gradient-to-br from-blue-400 to-blue-600 rounded-3xl overflow-hidden shadow-2xl">
              <img
                src="/images/about-alliance.jpg"
                alt="Citizen Alliance team in action"
                class="w-full h-96 lg:h-[500px] object-cover mix-blend-overlay">
              <div class="absolute inset-0 bg-gradient-to-t from-blue-900/50 to-transparent"></div>
            </div>

            <!-- Floating Cards -->
            <div class="absolute -top-6 -left-6 bg-white rounded-2xl p-6 shadow-xl border border-gray-100 transform rotate-3 hover:rotate-0 transition-transform duration-300">
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div>
                  <div class="font-semibold text-gray-900">Governance</div>
                  <div class="text-sm text-gray-600">& Democracy</div>
                </div>
              </div>
            </div>

            <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl p-6 shadow-xl border border-gray-100 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                  </svg>
                </div>
                <div>
                  <div class="font-semibold text-gray-900">Citizens</div>
                  <div class="text-sm text-gray-600">Empowerment</div>
                </div>
              </div>
            </div>

            <!-- Background decoration -->
            <div class="absolute -z-10 top-8 left-8 w-72 h-72 bg-gradient-to-br from-blue-200 to-blue-300 rounded-full opacity-20 blur-3xl"></div>
          </div>
        </div>
      </div>

      <!-- Mission, Vision, Values -->
<!--      <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8">-->
<!--        <div class="text-center group">-->
<!--          <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">-->
<!--            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>-->
<!--            </svg>-->
<!--          </div>-->
<!--          <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>-->
<!--          <p class="text-gray-600 leading-relaxed">-->
<!--            To facilitate citizen-led engagement in development and governance processes, promoting transparency,-->
<!--            accountability, and meaningful participation in democratic institutions.-->
<!--          </p>-->
<!--        </div>-->

<!--        <div class="text-center group">-->
<!--          <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">-->
<!--            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>-->
<!--              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>-->
<!--            </svg>-->
<!--          </div>-->
<!--          <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>-->
<!--          <p class="text-gray-600 leading-relaxed">-->
<!--            A Malawi where all citizens actively participate in governance processes and development initiatives,-->
<!--            creating a just, equitable, and prosperous society for all.-->
<!--          </p>-->
<!--        </div>-->

<!--        <div class="text-center group">-->
<!--          <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">-->
<!--            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
<!--              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>-->
<!--            </svg>-->
<!--          </div>-->
<!--          <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Values</h3>-->
<!--          <p class="text-gray-600 leading-relaxed">-->
<!--            Transparency, accountability, inclusivity, social justice, and democratic participation guide our work-->
<!--            in empowering communities and strengthening governance structures.-->
<!--          </p>-->
<!--        </div>-->
<!--      </div>-->
    </div>
  </section>
</template>

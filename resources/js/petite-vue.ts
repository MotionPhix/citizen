import { createApp } from 'petite-vue'
import '../css/app.css';
import Swiper from 'swiper'
import { EffectFade, Autoplay, Navigation } from 'swiper/modules'

// Hero slider handler
function heroSlider(slides) {
  return {
    slides,
    swiper: null,
    currentIndex: 0,
    slideOffset: new Array(slides.length).fill(0),

    isActive(index) {
      return this.currentIndex === index
    },

    mounted() {
      // Initialize Swiper when component is mounted
      this.initSwiper()
    },

    initSwiper() {
      this.swiper = new Swiper('.hero-swiper', {
        modules: [Navigation, EffectFade, Autoplay], // Remove Parallax
        effect: 'fade',
        speed: 1000,
        loop: true,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      })
    },

    nextSlide() {
      if (this.swiper) {
        this.swiper.slideNext()
      }
    },

    prevSlide() {
      if (this.swiper) {
        this.swiper.slidePrev()
      }
    },

    // Clean up when component is destroyed
    beforeUnmount() {
      if (this.swiper) {
        this.swiper.destroy()
      }
    }
  }
}

// Impact Card
function impactCard(data) {
  return {
    icon: data.icon,
    title: data.title,
    metric: data.metric,
    description: data.description,
  }
}

// Counter Animation
function counterAnimation({ end, duration }) {
  return {
    currentValue: 0,
    formattedValue: '0',
    start() {
      const startTime = performance.now()
      const updateCounter = (currentTime) => {
        const elapsed = currentTime - startTime
        const progress = Math.min(elapsed / duration, 1)

        this.currentValue = Math.floor(progress * end)
        this.formattedValue = this.currentValue.toLocaleString()

        if (progress < 1) {
          requestAnimationFrame(updateCounter)
        }
      }
      requestAnimationFrame(updateCounter)
    }
  }
}

// Dark mode handler
function darkMode() {
  return {
    dark: localStorage.getItem('darkMode') === 'true',
    toggle() {
      this.dark = !this.dark
      localStorage.setItem('darkMode', this.dark.toString())
      document.documentElement.classList.toggle('dark', this.dark)
    },
    init() {
      // Initialize dark mode on mount
      this.dark = localStorage.getItem('darkMode') === 'true'
      document.documentElement.classList.toggle('dark', this.dark)
    }
  }
}

// Newsletter form handler
function newsletter() {
  return {
    email: '',
    submitted: false,
    loading: false,
    error: null,
    async submit() {
      if (!this.email) return

      this.loading = true
      this.error = null

      try {
        const response = await fetch('/api/newsletter', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ email: this.email })
        })

        if (!response.ok) throw new Error('Subscription failed')

        this.submitted = true
        this.email = ''
      } catch (e) {
        this.error = 'Failed to subscribe. Please try again.'
      } finally {
        this.loading = false
      }
    }
  }
}

// Hero slider handler
/*function heroSlider(slides: any[]) {
  return {
    currentSlide: 0,
    slides,
    timer: null as any,
    init() {
      this.startSlideShow()
    },
    startSlideShow() {
      this.timer = setInterval(() => {
        this.next()
      }, 5000)
    },
    stopSlideShow() {
      clearInterval(this.timer)
    },
    next() {
      this.currentSlide = (this.currentSlide + 1) % this.slides.length
    },
    prev() {
      this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length
    }
  }
}*/

// Mobile menu handler
function mobileMenu() {
  return {
    isOpen: false,
    toggle() {
      this.isOpen = !this.isOpen
    }
  }
}

// Mount Petite Vue with all components
createApp({
  darkMode,
  counterAnimation,
  impactCard,
  newsletter,
  heroSlider,
  mobileMenu
}).mount()

import { createApp } from 'petite-vue'
import '../css/app.css';
import Swiper from 'swiper'
import { Pagination, EffectFade, Autoplay } from 'swiper/modules'
import { BriefcaseIcon, ChartLineIcon, HandshakeIcon, UserIcon } from 'lucide-vue-next';

// Hero slider handler
function heroSlider(slides) {
  return {
    slides,
    currentIndex: 0,
    swiper: null,
    slideOffset: new Array(slides.length).fill(0),

    isActive(index) {
      return this.currentIndex === index
    },

    initSwiper() {
      this.swiper = new Swiper('.hero-swiper', {
        modules: [Pagination, EffectFade, Autoplay],
        effect: 'fade',
        speed: 1000,
        loop: true,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        on: {
          slideChange: () => {
            this.currentIndex = this.swiper.realIndex
          },
          setTranslate: () => {
            // Parallax effect
            const slides = this.swiper.slides
            for (let i = 0; i < slides.length; i++) {
              const slideProgress = slides[i].progress
              const innerOffset = this.swiper.width * 0.5
              const innerTranslate = slideProgress * innerOffset
              this.slideOffset[i] = innerTranslate * 0.8
            }
          }
        }
      })
    },

    nextSlide() {
      this.swiper?.slideNext()
    },

    prevSlide() {
      this.swiper?.slidePrev()
    },

    destroy() {
      this.swiper?.destroy()
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

    get icons() {
      const iconMap = {
        'users': UserIcon,
        'chart': ChartLineIcon,
        'handshake': HandshakeIcon,
      }
      return iconMap[this.icon] || BriefcaseIcon // default icon
    }
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

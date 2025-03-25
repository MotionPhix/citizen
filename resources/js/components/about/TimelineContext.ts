import { ref, provide, inject } from 'vue'

const TimelineSymbol = Symbol()

export function provideTimeline() {
  const activeIndex = ref(0)

  provide(TimelineSymbol, {
    activeIndex
  })

  return {
    activeIndex
  }
}

export function useTimelineContext() {
  const context = inject(TimelineSymbol)

  if (!context) {
    throw new Error('useTimelineContext must be used within a Timeline component')
  }

  return context
}

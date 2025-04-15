import { createEventHook } from '@vueuse/core'

interface ToastPayload {
  type: 'success' | 'error' | 'info'
  message: string
}

const toastHook = createEventHook<ToastPayload>()

export function useToast() {
  const showToast = (payload: ToastPayload) => {
    toastHook.trigger(payload)
  }

  return {
    showToast,
    onToast: toastHook.on
  }
}

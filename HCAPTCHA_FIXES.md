# hCaptcha Integration Fixes

## Issues Identified and Fixed

### 1. Script Loading Timing Issues
**Problem**: hCaptcha script was loading before the API was ready, causing "should not render before js api is fully loaded" errors.

**Solution**: 
- Changed to explicit rendering with `render=explicit&onload=hCaptchaLoaded`
- Added proper callback handling for when the API is ready
- Removed duplicate script loading from `app.blade.php`

### 2. DOM Element Access Errors
**Problem**: Trying to access DOM elements before they were available, causing "Cannot set properties of undefined" errors.

**Solution**:
- Added proper DOM readiness checks in `initHCaptcha()`
- Implemented retry mechanism if container element is not found
- Added container clearing before re-rendering

### 3. Widget Management Issues
**Problem**: Multiple widgets being created without proper cleanup, causing memory leaks and conflicts.

**Solution**:
- Proper widget removal before creating new ones
- Better error handling for widget operations
- Null checks before widget operations

### 4. Dark Mode Theme Switching
**Problem**: Theme switching was causing widget errors and not properly updating.

**Solution**:
- Improved dark mode watcher with proper cleanup
- Added fallback retry mechanism
- Used `flush: 'post'` to ensure DOM updates are complete

### 5. Vue Watcher Errors
**Problem**: Unhandled errors in Vue watchers causing console warnings.

**Solution**:
- Added comprehensive error handling in watchers
- Proper cleanup of widget references
- Timeout-based retry mechanisms

## Key Changes Made

### 1. Script Loading (`loadHCaptchaScript`)
```typescript
// Before: Basic script loading
script.src = 'https://js.hcaptcha.com/1/api.js'

// After: Explicit rendering with callback
script.src = 'https://js.hcaptcha.com/1/api.js?render=explicit&onload=hCaptchaLoaded'
window.hCaptchaLoaded = () => {
  delete window.hCaptchaLoaded
  resolve()
}
```

### 2. Widget Initialization (`initHCaptcha`)
```typescript
// Added DOM readiness check
const container = document.getElementById('hcaptcha-container')
if (!container) {
  console.warn('hCaptcha container not found, retrying...')
  setTimeout(initHCaptcha, 100)
  return
}

// Added proper cleanup
if (hcaptchaWidgetId.value) {
  try {
    window.hcaptcha.remove(hcaptchaWidgetId.value)
  } catch (e) {
    // Ignore errors when removing non-existent widgets
  }
}

// Clear container before rendering
container.innerHTML = ''
```

### 3. Dark Mode Watcher
```typescript
// Added proper cleanup and retry mechanism
watch(isDark, (newValue) => {
  if (window.hcaptcha && hcaptchaWidgetId.value) {
    try {
      window.hcaptcha.remove(hcaptchaWidgetId.value)
      hcaptchaWidgetId.value = null
      setTimeout(() => {
        initHCaptcha()
      }, 100)
    } catch (error) {
      console.warn('Error updating hCaptcha theme:', error)
      // Fallback: try to reinitialize
      hcaptchaWidgetId.value = null
      setTimeout(() => {
        initHCaptcha()
      }, 500)
    }
  }
}, { flush: 'post' })
```

### 4. Type Declarations
```typescript
declare global {
  interface Window {
    hcaptcha: {
      render: (container: string, params: any) => string
      reset: (widgetId?: string) => void
      remove: (widgetId: string) => void
      execute: (widgetId?: string) => void
      getResponse: (widgetId?: string) => string
    }
    hCaptchaLoaded?: () => void  // Added for callback
  }
}
```

### 5. Removed Duplicate Script Loading
Removed the global hCaptcha script from `app.blade.php` to prevent conflicts:
```html
<!-- Before -->
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>

<!-- After -->
<!-- hCaptcha script is loaded dynamically by ContactForm.vue component -->
```

## Testing Checklist

After applying these fixes, test the following:

### ✅ Basic Functionality
- [ ] hCaptcha loads without console errors
- [ ] Widget renders correctly on page load
- [ ] Form submission works with completed captcha
- [ ] Form validation prevents submission without captcha

### ✅ Theme Switching
- [ ] Dark mode toggle works without errors
- [ ] hCaptcha theme updates correctly
- [ ] No duplicate widgets created during theme changes

### ✅ Error Handling
- [ ] No console errors during normal operation
- [ ] Graceful handling of network issues
- [ ] Proper error messages for users

### ✅ Edge Cases
- [ ] Page refresh works correctly
- [ ] Multiple form instances (if applicable)
- [ ] Browser back/forward navigation
- [ ] Mobile device testing

## Configuration Requirements

Ensure these environment variables are set:

```env
# Frontend (Vite)
VITE_HCAPTCHA_SITEKEY=your_hcaptcha_site_key

# Backend (Laravel)
HCAPTCHA_SITEKEY=your_hcaptcha_site_key
HCAPTCHA_SECRET=your_hcaptcha_secret_key
```

## Expected Behavior

After these fixes:

1. **Clean Loading**: hCaptcha loads without timing errors
2. **Proper Rendering**: Widget appears correctly with appropriate theme
3. **Smooth Theme Switching**: Dark/light mode changes work seamlessly
4. **Error-Free Operation**: No console warnings or errors
5. **Reliable Submission**: Form submission works consistently

## Monitoring

Watch for these in browser console:
- ✅ "hCaptcha widget initialized successfully"
- ❌ No "[hCaptcha] should not render before js api is fully loaded" errors
- ❌ No "Cannot set properties of undefined" errors
- ❌ No Vue watcher errors

The contact form should now work flawlessly with proper hCaptcha integration!

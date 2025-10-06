import Swup from 'https://unpkg.com/swup@4?module';

// Initialize Swup
const swup = new Swup({
  containers: ['#swup'],
  animationSelector: '[class*="transition-"]'
});

// Initialize AOS
document.addEventListener('DOMContentLoaded', function() {
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true,
      offset: 100
    });
  }
});

// Reinitialize AOS after Swup page transitions
swup.hooks.on('page:view', () => {
  if (typeof AOS !== 'undefined') {
    AOS.refresh();
  }
});

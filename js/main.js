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

// Close mobile menu on link click (for persistent navigation)
swup.hooks.on('link:click', () => {
  const navbarCollapse = document.querySelector('.navbar-collapse');

  if (navbarCollapse && navbarCollapse.classList.contains('show')) {
    // Manually trigger smooth fade out
    navbarCollapse.classList.remove('show');
    navbarCollapse.classList.add('collapsing');

    // Remove collapsing class after transition completes
    setTimeout(() => {
      navbarCollapse.classList.remove('collapsing');
    }, 100); // Match the transition duration in CSS
  }
});

// Reinitialize AOS after Swup page transitions
swup.hooks.on('page:view', () => {
  if (typeof AOS !== 'undefined') {
    AOS.refresh();
  }
});

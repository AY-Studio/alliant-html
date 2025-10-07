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
  const navbarToggler = document.querySelector('.navbar-toggler');

  if (navbarCollapse && navbarCollapse.classList.contains('show')) {
    // Use Bootstrap's collapse method to close the menu
    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse) || new bootstrap.Collapse(navbarCollapse, {
      toggle: false
    });
    bsCollapse.hide();
  }
});

// Reinitialize AOS after Swup page transitions
swup.hooks.on('page:view', () => {
  if (typeof AOS !== 'undefined') {
    AOS.refresh();
  }
});

// Initialize Swup
const swup = new window.Swup({
  containers: ['#swup'],
  animationSelector: '[class*="transition-"]'
});

// Close mobile menu on link click (for persistent navigation)
swup.hooks.on('link:click', () => {
  const navbarCollapse = document.querySelector('.navbar-collapse');
  const navbarToggler = document.querySelector('.navbar-toggler');

  if (navbarCollapse && navbarCollapse.classList.contains('show')) {
    // Manually trigger smooth fade out
    navbarCollapse.classList.remove('show');
    navbarCollapse.classList.add('collapsing');

    if (navbarToggler) {
      navbarToggler.classList.add('collapsed');
      navbarToggler.setAttribute('aria-expanded', 'false');
    }

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

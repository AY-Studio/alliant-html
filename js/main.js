// Initialize Swup
const swup = new window.Swup({
  containers: ['#swup'],
  animationSelector: '[class*="transition-"]'
});

// Navbar shrink on scroll
const navbar = document.querySelector('.navbar');

const updateNavbarState = () => {
  if (!navbar) return;
  if (window.scrollY > 20) {
    navbar.classList.add('navbar-shrink');
  } else {
    navbar.classList.remove('navbar-shrink');
  }
};

window.addEventListener('scroll', updateNavbarState, { passive: true });
updateNavbarState();

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

  updateNavbarState();
});

// Initialize Swup
const swup = new window.Swup({
  containers: ['#swup'],
  animationSelector: '[class*="transition-"]'
});

// Navbar shrink on scroll with hysteresis to avoid jitter near the top
const navbar = document.querySelector('.navbar');
const SHRINK_ADD_OFFSET = 90; // scrollY to apply shrink
const SHRINK_REMOVE_OFFSET = 30; // scrollY to remove shrink
let isNavbarShrunk = false;
let isScrollTicking = false;

const updateNavbarState = () => {
  if (!navbar) return;
  const scrollPosition = window.scrollY || window.pageYOffset;

  if (!isNavbarShrunk && scrollPosition > SHRINK_ADD_OFFSET) {
    navbar.classList.add('navbar-shrink');
    isNavbarShrunk = true;
  } else if (isNavbarShrunk && scrollPosition < SHRINK_REMOVE_OFFSET) {
    navbar.classList.remove('navbar-shrink');
    isNavbarShrunk = false;
  }
};

const handleScroll = () => {
  if (isScrollTicking) return;
  isScrollTicking = true;
  window.requestAnimationFrame(() => {
    updateNavbarState();
    isScrollTicking = false;
  });
};

window.addEventListener('scroll', handleScroll, { passive: true });
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

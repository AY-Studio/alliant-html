(function () {
  const initSwup = () => {
    if (!window.Swup || document.getElementById('swup') === null) {
      return null;
    }
    return new window.Swup({
      containers: ['#swup'],
      animationSelector: '[class*="transition-"]'
    });
  };

  const initAOS = () => {
    if (window.AOS) {
      window.AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
      });
    }
  };

  const navbar = document.querySelector('.navbar');
  const SHRINK_ADD_OFFSET = 90;
  const SHRINK_REMOVE_OFFSET = 30;
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

  const collapseNav = () => {
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navbarToggler = document.querySelector('.navbar-toggler');

    if (navbarCollapse && navbarCollapse.classList.contains('show')) {
      navbarCollapse.classList.remove('show');
      navbarCollapse.classList.add('collapsing');

      if (navbarToggler) {
        navbarToggler.classList.add('collapsed');
        navbarToggler.setAttribute('aria-expanded', 'false');
      }

      setTimeout(() => {
        navbarCollapse.classList.remove('collapsing');
      }, 100);
    }
  };

  document.addEventListener('scroll', handleScroll, { passive: true });
  updateNavbarState();

  const swup = initSwup();
  initAOS();

  const navbarToggler = document.querySelector('.navbar-toggler');
  const navbarCollapse = document.querySelector('#navbarNav');

  if (navbarToggler && navbarCollapse) {
    navbarToggler.addEventListener('click', () => {
      const expanded = navbarToggler.getAttribute('aria-expanded') === 'true';
      if (expanded) {
        navbarCollapse.classList.remove('show');
        navbarToggler.classList.add('collapsed');
        navbarToggler.setAttribute('aria-expanded', 'false');
      } else {
        navbarCollapse.classList.add('show');
        navbarToggler.classList.remove('collapsed');
        navbarToggler.setAttribute('aria-expanded', 'true');
      }
    });
  }

  if (swup) {
    swup.hooks.on('link:click', collapseNav);
    swup.hooks.on('page:view', () => {
      initAOS();
      updateNavbarState();
    });
  }
})();

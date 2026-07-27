/**
 * Homepage Swiper initializers.
 * Requires Swiper bundle to be loaded before this file.
 */
(function () {
  function isVisible(el) {
    if (!el) return false;
    const style = window.getComputedStyle(el);
    return style.display !== 'none' && style.visibility !== 'hidden' && el.offsetWidth > 0;
  }

  function pauseOnHover(swiper) {
    if (!swiper?.el || !swiper.params?.autoplay) return;
    swiper.el.addEventListener('mouseenter', () => {
      if (swiper.autoplay?.running) swiper.autoplay.stop();
    });
    swiper.el.addEventListener('mouseleave', () => {
      if (swiper.autoplay && !swiper.autoplay.running) swiper.autoplay.start();
    });
  }

  function initFlashSaleSwipers() {
    document.querySelectorAll('.flash-sale-swiper').forEach((el) => {
      if (el.swiper || !isVisible(el)) return;

      const slideCount = el.querySelectorAll('.swiper-slide').length;
      if (slideCount === 0) return;

      const swiper = new Swiper(el, {
        slidesPerView: 'auto',
        spaceBetween: 0,
        watchOverflow: false,
        grabCursor: true,
        allowTouchMove: true,
        resistanceRatio: 0.65,
        loop: false,
        autoplay: slideCount > 4
          ? { delay: 2500, disableOnInteraction: false, pauseOnMouseEnter: true }
          : false,
        navigation: {
          nextEl: el.querySelector('.swiper-button-next'),
          prevEl: el.querySelector('.swiper-button-prev'),
        },
      });
      pauseOnHover(swiper);
    });
  }

  function initCategorySwiper() {
    const el = document.querySelector('.category-swiper');
    if (!el) return;

    if (!isVisible(el)) {
      if (el.swiper) {
        el.swiper.destroy(true, true);
      }
      return;
    }

    if (el.swiper) {
      el.swiper.update();
      return;
    }

    const slideCount = el.querySelectorAll('.swiper-slide').length;
    if (slideCount === 0) return;

    const swiper = new Swiper(el, {
      slidesPerView: 'auto',
      spaceBetween: 0,
      watchOverflow: false,
      grabCursor: true,
      allowTouchMove: true,
      resistanceRatio: 0.65,
      loop: false,
      autoplay: slideCount > 5
        ? { delay: 2000, disableOnInteraction: false, pauseOnMouseEnter: true }
        : false,
    });
    pauseOnHover(swiper);
  }

  function initHeroSwiper() {
    const heroEl = document.querySelector('.hero-swiper');
    if (!heroEl || heroEl.swiper || !isVisible(heroEl)) return;

    const heroSlides = heroEl.querySelectorAll('.swiper-slide').length;
    const heroSwiper = new Swiper(heroEl, {
      effect: 'fade',
      fadeEffect: { crossFade: true },
      speed: 700,
      loop: heroSlides > 1,
      allowTouchMove: true,
      autoplay: heroSlides > 1
        ? { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true }
        : false,
      pagination: {
        el: heroEl.querySelector('.swiper-pagination'),
        clickable: true,
      },
      navigation: {
        nextEl: heroEl.querySelector('.swiper-button-next'),
        prevEl: heroEl.querySelector('.swiper-button-prev'),
      },
    });
    pauseOnHover(heroSwiper);
  }

  function initProductCommonSwipers() {
    document.querySelectorAll('.productCommonSwiper').forEach((el) => {
      if (el.swiper || !isVisible(el)) return;

      const slideCount = el.querySelectorAll('.swiper-slide').length;
      const swiper = new Swiper(el, {
        slidesPerView: 2,
        spaceBetween: 8,
        watchOverflow: false,
        grabCursor: true,
        allowTouchMove: true,
        loop: false,
        autoplay: slideCount > 2
          ? { delay: 2000, disableOnInteraction: false, pauseOnMouseEnter: true }
          : false,
        breakpoints: {
          680: { slidesPerView: 3, spaceBetween: 8 },
          768: { slidesPerView: 3, spaceBetween: 10 },
          1024: { slidesPerView: 4, spaceBetween: 12 },
          1280: { slidesPerView: 5, spaceBetween: 12 },
        },
      });
      pauseOnHover(swiper);
    });
  }

  function initHomeSliders() {
    if (typeof Swiper === 'undefined') {
      console.warn('Swiper is not loaded; sliders disabled.');
      return;
    }

    initHeroSwiper();
    initFlashSaleSwipers();
    initCategorySwiper();
    initProductCommonSwipers();
  }

  let resizeTimer;
  function onResize() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      if (typeof Swiper === 'undefined') return;
      initFlashSaleSwipers();
      initCategorySwiper();
      document.querySelectorAll('.flash-sale-swiper').forEach((el) => {
        if (el.swiper && isVisible(el)) el.swiper.update();
      });
    }, 150);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHomeSliders);
  } else {
    initHomeSliders();
  }

  window.addEventListener('resize', onResize);
  window.addEventListener('orientationchange', onResize);
})();

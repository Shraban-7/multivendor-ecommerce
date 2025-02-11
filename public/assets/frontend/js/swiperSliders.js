/*======================
  Home Page 
  ======================
*/

// Light Deals Responsive Swiper
let lightDealsSwiper = new Swiper(".lightDealsSwiper", {
  slidesPerView: 1,
  spaceBetween: 5,
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
  },
  grabCursor: true,
  loop: true,
  breakpoints: {
    680: {
      slidesPerView: 2,
    },
    768: {
      slidesPerView: 2,
      spaceBetween: 5,
    },
    1024: {
      slidesPerView: 3,
      spaceBetween: 10,
    },
    1280: {
      slidesPerView: 4,
    },
  },
});

// Categores (Interest) Responsive Swiper
let categoriesSwiper = new Swiper(".categoriesSwiper", {
  slidesPerView: 3,
  spaceBetween: 2,
  autoplay: {
    delay: 1500,
    disableOnInteraction: false,
  },
  grabCursor: true,
  loop: true,
  breakpoints: {
    680: {
      slidesPerView: 4,
    },
    768: {
      spaceBetween: 5,
      slidesPerView: 5,
    },
    1024: {
      slidesPerView: 5,
    },
    1280: {
      slidesPerView: 6,
    },
  },
});

// 5 Slide Re-Usable Responsive Swiper
let fiveSlideSwiper = new Swiper(".fiveSlideSwiper", {
  slidesPerView: 2,
  spaceBetween: 5,
  autoplay: {
    delay: 2000,
    disableOnInteraction: false,
  },
  grabCursor: true,
  loop: true,
  breakpoints: {
    680: {
      slidesPerView: 3,
    },
    768: {
      spaceBetween: 15,
      slidesPerView: 3,
    },
    1024: {
      slidesPerView: 4,
      spaceBetween: 20,
    },
    1280: {
      slidesPerView: 5,
      spaceBetween: 30,
    },
  },
});

// 5 slide Product Common Swiper
let productCommonSwiper = new Swiper(".productCommonSwiper", {
  slidesPerView: 2,
  spaceBetween: 3,
  autoplay: {
    delay: 2000,
    disableOnInteraction: false,
  },
  grabCursor: true,
  loop: true,
  breakpoints: {
    680: {
      slidesPerView: 3,
    },
    768: {
      slidesPerView: 3,
      spaceBetween: 7,
    },
    1024: {
      slidesPerView: 4,
      spaceBetween: 8,
    },
    1280: {
      slidesPerView: 5,
      spaceBetween: 10,
    },
  },
});

// 3 slide Featured Product Videos Swiper
let featuredVideoSwiper = new Swiper(".featuredVideoSwiper", {
  slidesPerView: 1,
  spaceBetween: 5,
  autoplay: {
    delay: 2000,
    disableOnInteraction: true,
  },
  grabCursor: true,
  loop: true,
  breakpoints: {
    640: {
      spaceBetween: 10,
      slidesPerView: 2,
    },
    768: {
      slidesPerView: 2,
      spaceBetween: 15,
    },
    1024: {
      slidesPerView: 3,
      spaceBetween: 20,
    },
    1280: {
      slidesPerView: 3,
      spaceBetween: 30,
    },
  },
});

/*======================
  Product Details Page 
  ======================
*/

// Vertical Product Image Thumbnails
const productThumbs = new Swiper(".product-thumbnails", {
  spaceBetween: 10,
  slidesPerView: 5,
  watchSlidesProgress: true,
  direction: "horizontal",
  spaceBetween: 10,
  grabCursor: true,
  breakpoints: {
    1024: {
      direction: "vertical",
      spaceBetween: 5,
    },
    1280: {
      direction: "vertical",
      spaceBetween: 10,
    },
  },
});

// Product Images Slider
const productSwiper = new Swiper(".product-swiper", {
  spaceBetween: 10,
  grabCursor: true,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  thumbs: {
    swiper: productThumbs,
  },
});

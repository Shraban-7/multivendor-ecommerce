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
const productCommonSwipers = [];
document.querySelectorAll(".productCommonSwiper").forEach((el) => {
    const swiper = new Swiper(el, {
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

    productCommonSwipers.push(swiper);
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

// pause on hover slider
function addPauseOnHover(swiperInstance) {
    if (
        swiperInstance &&
        swiperInstance.el instanceof Element &&
        swiperInstance.params?.autoplay
    ) {
        const el = swiperInstance.el;
        el.addEventListener("mouseenter", () => swiperInstance.autoplay.stop());
        el.addEventListener("mouseleave", () => swiperInstance.autoplay.start());
    } else {
        console.warn("Invalid Swiper instance:", swiperInstance);
    }
}


[
    lightDealsSwiper,
    categoriesSwiper,
    fiveSlideSwiper,
    ...productCommonSwipers,
    featuredVideoSwiper,
].forEach(addPauseOnHover);









document
  .querySelectorAll(".product-multi-slider-container")
  .forEach((container) => {
    // find the two inner swiper elements
    const thumbsEl = container.querySelector(".product-thumbnails");
    const mainEl = container.querySelector(".product-swiper");

    // init thumbnail swiper
    const thumbsSwiper = new Swiper(thumbsEl, {
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

    // init main swiper, linking the thumbs
    const mainSwiper = new Swiper(mainEl, {
      spaceBetween: 10,
      grabCursor: true,
      navigation: {
        nextEl: mainEl.querySelector(".swiper-button-next"),
        prevEl: mainEl.querySelector(".swiper-button-prev"),
      },
      thumbs: {
        swiper: thumbsSwiper,
      },
    });
  });

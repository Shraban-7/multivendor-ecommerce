document.addEventListener('DOMContentLoaded', () => {

  // Flash sale slider autoplay
  document.querySelectorAll('.flash-slider-track').forEach(track => {
    const wrapper = track.parentElement;
    const prev = wrapper.querySelector('.flash-slider-prev');
    const next = wrapper.querySelector('.flash-slider-next');

    const scrollAmount = () => {
      const card = track.querySelector('.flex-shrink-0');
      return card ? card.offsetWidth + 12 : 200;
    };

    const updateBtns = () => {
      if (prev) prev.disabled = track.scrollLeft <= 0;
      if (next) next.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;
    };

    if (prev) prev.addEventListener('click', () => { track.scrollBy({ left: -scrollAmount(), behavior: 'smooth' }); setTimeout(updateBtns, 100); });
    if (next) next.addEventListener('click', () => { track.scrollBy({ left: scrollAmount(), behavior: 'smooth' }); setTimeout(updateBtns, 100); });
    track.addEventListener('scroll', updateBtns);
    setTimeout(updateBtns, 200);

    let autoplay = setInterval(() => {
      if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 2) {
        track.scrollTo({ left: 0, behavior: 'smooth' });
      } else {
        track.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
      }
    }, 1500);

    wrapper.addEventListener('mouseenter', () => clearInterval(autoplay));
    wrapper.addEventListener('mouseleave', () => {
      autoplay = setInterval(() => {
        if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 2) {
          track.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          track.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
        }
      }, 1500);
    });
  });

  // Category slider autoplay
  const catTrack = document.getElementById('category-slider-track');
  if (catTrack) {
    let catAutoplay = setInterval(() => {
      const card = catTrack.querySelector('.shrink-0');
      if (!card) return;
      const step = card.offsetWidth + 12;
      if (catTrack.scrollLeft + catTrack.clientWidth >= catTrack.scrollWidth - 2) {
        catTrack.scrollTo({ left: 0, behavior: 'smooth' });
      } else {
        catTrack.scrollBy({ left: step, behavior: 'smooth' });
      }
    }, 2000);

    catTrack.addEventListener('mouseenter', () => clearInterval(catAutoplay));
    catTrack.addEventListener('mouseleave', () => {
      catAutoplay = setInterval(() => {
        const card = catTrack.querySelector('.shrink-0');
        if (!card) return;
        const step = card.offsetWidth + 12;
        if (catTrack.scrollLeft + catTrack.clientWidth >= catTrack.scrollWidth - 2) {
          catTrack.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          catTrack.scrollBy({ left: step, behavior: 'smooth' });
        }
      }, 2000);
    });
  }
});

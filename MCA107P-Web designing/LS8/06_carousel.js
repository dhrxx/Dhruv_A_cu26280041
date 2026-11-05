// Create an interactive hero carousel showcasing client products and services using JavaScript and CSS transitions.
"use strict";

(() => {
  const carousel = document.querySelector(".carousel");
  if (!carousel) return;

  const slides = Array.from(carousel.querySelectorAll("[data-slide]"));
  const count = carousel.querySelector("[data-carousel-count]");
  const previous = carousel.querySelector("[data-carousel-prev]");
  const next = carousel.querySelector("[data-carousel-next]");
  const pause = carousel.querySelector("[data-carousel-pause]");
  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  if (slides.length < 2) return;

  let activeIndex = slides.findIndex((slide) => slide.classList.contains("is-active"));
  let timer = null;
  let paused = reducedMotion.matches;
  const interval = 6000;

  function showSlide(index) {
    activeIndex = (index + slides.length) % slides.length;
    slides.forEach((slide, position) => {
      const active = position === activeIndex;
      slide.hidden = !active;
      slide.classList.toggle("is-active", active);
    });
    if (count) count.textContent = `${String(activeIndex + 1).padStart(2, "0")} / ${String(slides.length).padStart(2, "0")}`;
  }

  function stopTimer() {
    if (timer !== null) window.clearInterval(timer);
    timer = null;
  }

  function startTimer() {
    stopTimer();
    if (!paused) timer = window.setInterval(() => showSlide(activeIndex + 1), interval);
  }

  function setPaused(value) {
    paused = value;
    if (pause) {
      pause.setAttribute("aria-pressed", String(paused));
      pause.textContent = paused ? "Play" : "Pause";
    }
    if (paused) stopTimer();
    else startTimer();
  }

  previous?.addEventListener("click", () => {
    showSlide(activeIndex - 1);
    startTimer();
  });
  next?.addEventListener("click", () => {
    showSlide(activeIndex + 1);
    startTimer();
  });
  pause?.addEventListener("click", () => setPaused(!paused));
  carousel.addEventListener("mouseenter", stopTimer);
  carousel.addEventListener("mouseleave", startTimer);
  carousel.addEventListener("focusin", stopTimer);
  carousel.addEventListener("focusout", startTimer);
  reducedMotion.addEventListener("change", (event) => {
    if (event.matches) setPaused(true);
  });

  showSlide(Math.max(activeIndex, 0));
  setPaused(paused);
})();

// Incorporate customer testimonial grids with interactive rating stars.
"use strict";

(() => {
  document.querySelectorAll("[data-rating]").forEach((group) => {
    const stars = Array.from(group.querySelectorAll("[data-star]"));
    const result = group.querySelector("[data-rating-result]");
    stars.forEach((star) => {
      star.addEventListener("click", () => {
        const selected = Number(star.dataset.star);
        stars.forEach((item) => {
          const active = Number(item.dataset.star) <= selected;
          item.textContent = active ? "★" : "☆";
          item.setAttribute("aria-pressed", String(Number(item.dataset.star) === selected));
        });
        if (result) result.textContent = `${selected} out of 5. Sample rating only; it is not saved.`;
      });
    });
  });

  document.querySelectorAll("[data-rate]").forEach((button) => {
    button.addEventListener("click", () => {
      const pressed = button.getAttribute("aria-pressed") === "true";
      const count = button.querySelector("[data-rate-count]");
      if (!count) return;

      count.textContent = String(Math.max(0, Number(count.textContent) + (pressed ? -1 : 1)));
      button.setAttribute("aria-pressed", String(!pressed));
      button.setAttribute("aria-label", pressed ? "Mark review helpful" : "Marked review helpful");
    });
  });
})();

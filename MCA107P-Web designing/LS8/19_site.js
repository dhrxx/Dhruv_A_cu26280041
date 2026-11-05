// Write clean, well-commented HTML, CSS, and JavaScript code following industry coding standards.
"use strict";

(() => {
  const toggle = document.querySelector(".nav-toggle");
  const navigation = document.querySelector("#primary-nav");
  if (toggle && navigation) {
    toggle.addEventListener("click", () => {
      const expanded = toggle.getAttribute("aria-expanded") === "true";
      toggle.setAttribute("aria-expanded", String(!expanded));
      navigation.classList.toggle("is-open", !expanded);
    });
    navigation.addEventListener("click", (event) => {
      if (event.target instanceof HTMLAnchorElement) {
        toggle.setAttribute("aria-expanded", "false");
        navigation.classList.remove("is-open");
      }
    });
  }

  const share = document.querySelector("[data-share]");
  const shareStatus = document.querySelector("[data-share-status]");
  share?.addEventListener("click", async () => {
    const shareData = {
      title: document.title,
      text: "A little neighborhood bakery design demo.",
      url: window.location.href,
    };
    try {
      if (navigator.share) {
        await navigator.share(shareData);
        if (shareStatus) shareStatus.textContent = "Thanks for sharing.";
      } else if (navigator.clipboard) {
        await navigator.clipboard.writeText(shareData.url);
        if (shareStatus) shareStatus.textContent = "Page link copied.";
      } else {
        if (shareStatus) shareStatus.textContent = "Copy the page address from your browser to share it.";
      }
    } catch (error) {
      if (error instanceof DOMException && error.name === "AbortError") return;
      if (shareStatus) shareStatus.textContent = "Sharing is unavailable in this browser.";
    }
  });
})();

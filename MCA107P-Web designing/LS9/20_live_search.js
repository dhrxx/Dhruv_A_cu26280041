// Use JavaScript fetch() (AJAX) to perform live search suggestions without reloading the page.
"use strict";

(() => {
  const form = document.querySelector("[data-live-search]");
  const input = form?.querySelector("[data-search-input]");
  const list = form?.querySelector("[data-suggestions]");
  const status = document.querySelector("[data-live-status]");
  if (!form || !input || !list) return;

  let controller = null;
  let debounceTimer = null;

  function closeSuggestions() {
    list.replaceChildren();
    list.hidden = true;
  }

  input.addEventListener("input", () => {
    window.clearTimeout(debounceTimer);
    if (controller) controller.abort();
    const query = input.value.trim();
    if (query.length < 2) {
      closeSuggestions();
      if (status) status.textContent = "";
      return;
    }

    debounceTimer = window.setTimeout(async () => {
      controller = new AbortController();
      try {
        const response = await fetch(`search_suggestions.php?q=${encodeURIComponent(query)}`, {
          headers: { Accept: "application/json" },
          signal: controller.signal,
        });
        if (!response.ok) throw new Error(`Search request failed (${response.status}).`);
        const products = await response.json();
        list.replaceChildren();
        products.forEach((product) => {
          const item = document.createElement("li");
          const link = document.createElement("a");
          link.href = `08_products.php?name=${encodeURIComponent(product.name)}`;
          link.textContent = product.name;
          item.append(link);
          list.append(item);
        });
        list.hidden = products.length === 0;
        if (status) status.textContent = products.length ? `${products.length} suggestions found.` : "No suggestions found.";
      } catch (error) {
        if (error instanceof DOMException && error.name === "AbortError") return;
        closeSuggestions();
        if (status) status.textContent = "Suggestions are temporarily unavailable.";
      }
    }, 180);
  });

  document.addEventListener("click", (event) => {
    if (!form.contains(event.target)) closeSuggestions();
  });
  input.addEventListener("keydown", (event) => {
    if (event.key === "Escape") closeSuggestions();
  });
})();

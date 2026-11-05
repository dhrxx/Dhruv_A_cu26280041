// Ask for confirmation before submitting the secure server-side product delete form.
"use strict";
document.querySelectorAll(".delete-form").forEach((form) => {
  form.addEventListener("submit", (event) => {
    if (!window.confirm("Delete this product? This cannot be undone.")) {
      event.preventDefault();
    }
  });
});

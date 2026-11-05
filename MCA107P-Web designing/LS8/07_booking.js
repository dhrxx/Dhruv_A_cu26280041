// Design and integrate an interactive booking or reservation form with client-side JavaScript validation.
"use strict";

(() => {
  const form = document.querySelector("[data-booking-form]");
  if (!form) return;

  const status = form.querySelector("[data-booking-status]");
  const fields = {
    name: form.elements.namedItem("name"),
    email: form.elements.namedItem("email"),
    date: form.elements.namedItem("date"),
    guests: form.elements.namedItem("guests"),
  };
  const today = new Date();
  const localToday = new Date(today.getTime() - today.getTimezoneOffset() * 60000)
    .toISOString()
    .slice(0, 10);
  fields.date.min = localToday;

  function showError(name, message) {
    const field = fields[name];
    const output = form.querySelector(`[data-error-for="${name}"]`);
    field.setAttribute("aria-invalid", message ? "true" : "false");
    field.setCustomValidity(message);
    if (output) output.textContent = message;
  }

  function validate() {
    let firstInvalid = null;
    const checks = [
      ["name", fields.name.value.trim().length >= 2 && fields.name.value.trim().length <= 80, "Enter a name between 2 and 80 characters."],
      ["email", fields.email.validity.valid && fields.email.value.trim().length <= 254, "Enter a valid email address."],
      ["date", Boolean(fields.date.value) && fields.date.value >= localToday, "Choose today or a future date."],
      ["guests", /^[1-6]$/.test(fields.guests.value), "Choose a party size from 1 to 6."],
    ];

    checks.forEach(([name, valid, message]) => {
      showError(name, valid ? "" : message);
      if (!valid && firstInvalid === null) firstInvalid = fields[name];
    });
    if (status) status.textContent = "";
    if (firstInvalid) firstInvalid.focus();
    return firstInvalid === null;
  }

  Object.entries(fields).forEach(([name, field]) => {
    field.addEventListener("input", () => showError(name, ""));
    field.addEventListener("change", () => showError(name, ""));
  });

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    if (!validate()) {
      if (status) status.textContent = "Please correct the highlighted fields.";
      return;
    }
    if (status) {
      status.textContent = `Thanks, ${fields.name.value.trim()}! Your demo reservation request is ready. This sample site does not send or store bookings.`;
    }
    form.reset();
    fields.date.min = localToday;
    Object.keys(fields).forEach((name) => showError(name, ""));
  });
})();

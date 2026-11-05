// Add client-side form validation in JavaScript for registration, login, and checkout forms.
"use strict";

document.querySelectorAll("form[data-validate]").forEach((form) => {
    form.addEventListener("submit", (event) => {
        const password = form.querySelector('input[name="password"]');
        const email = form.querySelector('input[type="email"]');
        if (password && password.value.length < 10) {
            password.setCustomValidity("Use at least 10 characters for your password.");
        } else if (password) {
            password.setCustomValidity("");
        }
        if (email && email.value.length > 254) {
            email.setCustomValidity("Email addresses must be 254 characters or fewer.");
        } else if (email) {
            email.setCustomValidity("");
        }

        const invalidFields = form.checkValidity()
            ? []
            : Array.from(form.querySelectorAll(":invalid"));
        if (invalidFields.length > 0) {
            event.preventDefault();
            form.reportValidity();
        }
    });
});

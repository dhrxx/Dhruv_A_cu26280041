# Code audit and accessibility evaluation

> Conduct a final code audit and accessibility evaluation using automated tools (e.g., Lighthouse).

## Run locally

- Launch `17_start_local_server.ps1`; inspect the page through `http://127.0.0.1:8000/02_semantic_structure.html`.
- Use `12_responsive_test.js` in the browser developer console at 375px, 768px, and 1280px viewport widths.
- Run Chrome DevTools Lighthouse against the local page in **mobile** and **desktop** modes. Check Accessibility, Best Practices, SEO, and Performance. Review individual findings; do not treat a score as conformance certification.
- Run the keyboard/screen-reader/contrast checklist in `05_accessibility_audit.md`.
- Test current Chrome/Edge/Firefox and Safari/iOS Safari separately using `11_browser_compatibility.md`.

## Audit notes

- Source is plain HTML, CSS, and vanilla JavaScript; no application packages or trackers.
- Local SVGs include titles. Visible page images also have appropriate `alt` text or are explicitly decorative.
- Navigation and booking feedback announce state; reduced-motion support is available.
- The map is third-party content and is the only major external embed.
- The demo reservation intentionally has no network submission or persistence. Sample testimonials and contact data are fictional.

## Test evidence and remaining manual checks

The page and assets were served successfully over a local HTTP server; manual browser smoke tests should cover the home page, navigation, carousel controls, booking errors and valid demo result, review toggles, share action, and map link. Run Lighthouse and perform cross-browser and assistive-technology tests during the presentation environment; this Windows workspace cannot certify Safari or substitute for a human accessibility audit.

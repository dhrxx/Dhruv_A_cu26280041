// Verify mobile responsiveness across diverse device viewports using browser developer tools.
// Run in a browser's DevTools console on 02_semantic_structure.html at each target viewport.
"use strict";

(() => {
  const selectors = [".site-header", ".hero", ".bake-grid", ".booking-form", ".visit-section", ".site-footer"];
  const doc = document.documentElement;
  const results = selectors.map((selector) => {
    const element = document.querySelector(selector);
    if (!element) return { selector, present: false };
    const bounds = element.getBoundingClientRect();
    return {
      selector,
      present: true,
      withinViewport: bounds.left >= -1 && bounds.right <= window.innerWidth + 1,
      width: Math.round(bounds.width),
      scrollWidth: element.scrollWidth,
      clientWidth: element.clientWidth,
    };
  });
  const report = {
    viewport: `${window.innerWidth}x${window.innerHeight}`,
    devicePixelRatio: window.devicePixelRatio,
    documentHasHorizontalOverflow: doc.scrollWidth > doc.clientWidth,
    sections: results,
  };
  console.table(results);
  console.log("Responsive check:", report);
  return report;
})();

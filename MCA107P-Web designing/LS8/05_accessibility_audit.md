# Accessibility implementation and audit

> Implement web accessibility standards (WCAG) including ARIA landmarks, alt text for all images, and sufficient color contrast ratios.

## Implemented

- `02_semantic_structure.html` has a skip link, header, labeled navigation, main landmark, section headings, forms, and footer.
- Meaningful illustrations have descriptive alternative text. Brand marks and repeated team portraits next to a name use empty alternative text.
- Carousel buttons have names; slide position is announced, the pause button exposes its state, and users can operate it without a pointer.
- The mobile navigation button reports `aria-expanded` and the controlled navigation ID.
- Inputs have explicit labels, errors are placed next to fields, invalid inputs set `aria-invalid`, and results use live status regions.
- Review stars have a textual accessible rating. Helpful buttons expose their toggle state.
- Visible keyboard focus, a skip link, native controls, and a reduced-motion mode are included.
- The map iframe has a descriptive title.

## Verification checklist

1. Tab from the browser chrome through the page. Confirm the skip link, visible focus, logical order, and operable carousel, form, share, and navigation controls.
2. Test the menu and booking form with a keyboard and with JavaScript disabled.
3. Test at 200% zoom and narrow mobile width; verify there is no horizontal page overflow.
4. Check every non-decorative image for useful alt text and each form control for a programmatic label.
5. Run Lighthouse Accessibility and inspect each reported issue; automated scores are not certification.
6. Measure foreground/background text pairs with a contrast analyzer. Aim for WCAG AA: 4.5:1 for ordinary text and 3:1 for large text and meaningful controls.

## Prototype limitations

Third-party map content may have its own accessibility and privacy behavior. The address, reviews, brand, and contacts are illustrative. A production booking service needs an accessible server-side form, consent/privacy text, and confirmation workflow.

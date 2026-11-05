# Browser compatibility test record

> Test website cross-browser compatibility across Chrome, Firefox, Edge, and Safari.

## Support targets

| Browser | Test status | Notes |
|---|---|---|
| Chrome / Chromium | Local smoke checks available | Test the actual supported desktop and mobile versions before release. |
| Microsoft Edge | Not independently available in this test session | Verify navigation, carousel, form validation, and map iframe in current Edge. |
| Firefox | Not independently available in this test session | Verify date input, native validation, reduced motion, and layout breakpoints. |
| Safari / iOS Safari | Not available on this Windows test host | Verify Web Share, date picker, sticky header, and dynamic viewport sizing on Apple devices. |

The site uses semantic HTML, native form controls, widely supported CSS Grid/Flexbox, SVG, and a feature-tested Web Share API. The web share action falls back to copying the URL when the Clipboard API is available. The map is third-party content.

**Release checklist:** run the exact same keyboard, viewport, carousel, reservation-form, sharing, and map checks in each target browser; record browser/OS versions and any differences. A Chromium-only smoke test does not constitute cross-browser certification.

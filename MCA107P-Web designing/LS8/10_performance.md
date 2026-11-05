# Asset optimization and performance

> Optimize website assets (compressing images, minifying CSS/JS) for high performance and fast loading speeds.

## Choices in this prototype

- Original artwork is clean SVG: scalable, crisp on high-density screens, and much smaller than equivalent photographic assets. SVGs are local, so the hero artwork avoids third-party image requests.
- Below-the-fold product and team images use native `loading="lazy"`; intrinsic width and height reduce layout shift.
- System font stacks avoid blocking font downloads.
- CSS and JavaScript are external files with no framework or runtime dependency.
- The hero loads its first local image eagerly; the map iframe is lazy-loaded.
- Responsive layouts reuse a few images rather than downloading a separate image for each viewport.

## Production optimization

Keep the readable source files for this lab. For a production release, use a maintained CSS/JS minifier (for example Lightning CSS and Terser), minify SVGs with an SVG-aware optimizer, and serve precompressed Brotli or gzip assets with long-lived cache headers and content hashes. Do not remove SVG accessibility titles or image alternatives when optimizing.

Compare network transfer size and Lighthouse before and after each build; never minify by stripping arbitrary whitespace with regular expressions. Web performance is also affected by the third-party Google map, which should be deferred or consent-gated where required.

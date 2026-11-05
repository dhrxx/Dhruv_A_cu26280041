# Visual system and design principles

> Incorporate modern web design principles including consistent color palettes, typography scales, and white space management.

## Palette

| Token | Hex | Role |
|---|---|---|
| Forest | `#244338` | Brand, buttons, and the review section |
| Deep forest | `#17352b` | Emphasis and dark surfaces |
| Warm cream | `#fbf8ef` | Spacious hero and page accents |
| Soft sage | `#dfe8de` | Illustration and story backgrounds |
| Terracotta | `#b9573f` | Warm emphasis and reservation section |
| Golden butter | `#f0c978` | Small, high-contrast accent details |
| Ink | `#25352e` | Main reading text |

Use the deeper brand colors for text on light surfaces; do not use gold for small text on white. Contrast should be measured on each final foreground/background pairing rather than inferred from a palette.

## Type and spacing

- Georgia is the display face; the system sans-serif stack is the readable body face.
- Fluid `clamp()` heading sizes avoid abrupt jumps across viewports.
- `--space-1` to `--space-7` are a consistent 0.5rem-to-6rem rhythm.
- Large margins, generous line-height, restrained card chrome, and clear grouping create calm reading space.

## Layout rules

- Start with a single-column mobile layout; add two- and three-column CSS Grid tracks at 680px and 1050px.
- Use Flexbox for header navigation, buttons, card metadata, and small control groups.
- Keep paragraphs measure-limited; allow content to grow vertically rather than clipping.
- Motion is brief and optional; `prefers-reduced-motion` disables non-essential transitions.
- Keep local artwork small, meaningful, and self-hosted.

# Project specification

> Draft a project specification and wireframe layout for a fictional client (e.g., local artisan bakery, eco-friendly cafe, or boutique fitness studio).

## Client

**Juniper & Grain**, a fictional Portland neighborhood bakery. This exercise uses invented people, testimonials, address, contact details, menu prices, and opening hours. It is a visual prototype, not a real shop or reservation service.

## Audience and goals

- **Audience:** nearby residents, weekend visitors, bread enthusiasts, and people looking for a welcoming place to meet.
- **Primary goal:** help visitors explore the daily menu and decide to visit.
- **Secondary goals:** tell the bakery's story, introduce its team, let a visitor try the sample reservation form, and make its location easy to find.
- **Success criteria:** the page is usable on a phone, all navigation works, the carousel and form respond to input, and primary content is available without external accounts.

## Content and components

1. Sticky brand header and in-page navigation.
2. Hero headline, primary menu link, and keyboard-accessible three-slide product carousel.
3. Three daily menu cards with original, locally hosted SVG illustrations.
4. Bakery story and fictional team.
5. Clearly labeled sample testimonials with interactive helpful buttons.
6. Accessible, client-validated reservation demo (no booking is transmitted or stored).
7. Contact details and a responsive, city-level Google Maps embed.
8. Share action, social service links, and site footer.

## Wireframe

```text
+----------------------------------------------------+
| MARK  Juniper & Grain       Menu Story Reviews Visit|
+----------------------------------------------------+
| Slow mornings.       | [ Featured bread artwork ]  |
| Good bread.          | [previous] 01/03 [next]     |
| Intro + menu link    |                              |
+----------------------------------------------------+
| A few daily favorites   | Intro                     |
| [Sourdough] [Morning bun] [Coffee]                  |
+----------------------------------------------------+
| [ Baker artwork ]      | Our story                  |
+----------------------------------------------------+
| The hands behind the bread: [Team] [Team] [Team]    |
+----------------------------------------------------+
| Kind words:             [Review] [Review] [Review]  |
+----------------------------------------------------+
| Reservation intro       | Name / Email / Date / CTA |
+----------------------------------------------------+
| Visit details           | Responsive map            |
+----------------------------------------------------+
| Brand / navigation / sharing / social links         |
+----------------------------------------------------+
```

## Technical scope and exclusions

Static HTML, CSS, SVG and browser JavaScript; no build step or dependencies. The reservation form is a client-side demonstration only. The map is a generic Portland, Oregon map rather than a precise, verified storefront pin. Use the local server instructions in `17_start_local_server.ps1`.

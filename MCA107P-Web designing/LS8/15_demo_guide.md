# Live demonstration guide

> Deliver a live demonstration of the website functionality, interactive features, and responsive design during the lab presentation.

## Start the sample

1. Open a PowerShell terminal in the `labsheet 8` folder.
2. Run `.\17_start_local_server.ps1`.
3. Open `http://127.0.0.1:8000/02_semantic_structure.html`.
4. Keep a second tab open at `http://127.0.0.1:8000/14_presentation.html`.

## Five-minute walkthrough

1. **Brand and menu (45 sec):** introduce the fictional brief, skip link, and featured loaf. Scroll through menu, story, and team.
2. **Carousel (45 sec):** use Previous and Next; pause/resume; turn on OS reduced-motion and reload.
3. **Reservation (60 sec):** submit empty; show focus and field errors. Submit a future date, valid email, and party size. Point out that the confirmation is deliberately demo-only and no booking is sent.
4. **Reviews and sharing (45 sec):** toggle Helpful and note its accessible pressed state; try the share action in a secure context.
5. **Responsive design (60 sec):** use browser responsive tools at 375px, 768px, and 1280px. Open navigation, run `12_responsive_test.js` in DevTools, and tab through focus states.
6. **Access and testing (45 sec):** visit the map, use the presentation deck, then explain the browser-compatibility and accessibility checklists.

Before presenting, make sure the network is available for the external Google map; all custom illustrations and core website styles are local.

# Phase 3 — GSAP Replacement Record

Date: 2026-08-10  
Scope: Instantio Free and WooInstant Pro compatibility  
Status: Complete

## Objective

Remove the rejected GreenSock dependency without changing Instantio's saved option contracts, cart behavior, checkout step behavior, or Free/Pro ownership.

## Animation inventory and replacement

| Surface | Existing behavior retained | Native replacement |
| --- | --- | --- |
| Free cart header | Fade and 100 px upward entrance, 200 ms after a 200 ms delay | Web Animations API |
| Free checkout steps | Fade and 100 px left entrance, 200 ms after a 200 ms delay | Web Animations API |
| Free cart contents and upsells | Fade and 100 px upward entrance, 200 ms after a 400 ms delay | Web Animations API |
| Free cart footer and buttons | Fade and 100 px right entrance, 200 ms after a 600 ms delay | Web Animations API |
| Free cart-item removal | Fade and 100 px left exit, preserving the existing 400 ms refresh boundary | Web Animations API |
| Pro shipping columns | Staggered upward entrances at 200 ms and 400 ms | Shared Free native helper |
| Pro cart content/footer | Existing 400 ms and 600 ms stagger | Shared Free native helper |
| Pro payment columns | Existing 200 ms, 350 ms, and 500 ms stagger | Shared Free native helper |

The implementation uses only opacity and compositor-friendly `translate3d()` transforms. The existing selectors, direction, distance, duration, and normal-motion delays remain unchanged.

## Reduced-motion behavior

When `prefers-reduced-motion: reduce` is active, the shared helper:

- removes all translation;
- removes stagger delays;
- keeps a 150 ms opacity transition as an orientation cue;
- reads the media query for each animation, so a live operating-system preference change is honored without reloading.

## Dependency removal

- Removed the frontend script enqueue from `includes/controller/Assets.php`.
- Deleted `assets/app/js/gsap.min.js`.
- Removed all live calls and obsolete comments from Free and Pro source.
- Rebuilt both readable-source production counterparts with Terser.
- Confirmed `.distignore` already excludes internal `docs`; no obsolete package exclusion was required.

Build commands:

```bash
npx --yes terser instantio/assets/app/js/instantio-script.js --compress --mangle --comments '/^!/' --output instantio/assets/app/js/instantio-script.min.js
npx --yes terser wooinstant/assets/app/js/instantio-script-pro.js --compress --mangle --comments '/^!/' --output wooinstant/assets/app/js/instantio-script-pro.min.js
```

## Verification evidence

Static checks:

- PHP syntax passed for the modified enqueue controller.
- Node syntax checks passed for readable and minified Free and Pro scripts.
- Case-insensitive runtime scan found zero `gsap` or `GreenSock` references in PHP, JavaScript, and CSS.
- File scan found no GSAP-named file in either plugin.

Real browser checks were run on `http://dev.local` with Instantio Free and the licensed WooInstant Pro active:

- readable scripts, normal motion: passed;
- readable scripts, reduced motion: passed;
- optimized scripts, normal motion: passed;
- optimized scripts, reduced motion: passed;
- the Instantio panel opened and displayed a real cart item in every run;
- normal mode retained the 100 px translation, 200 ms duration, and configured stagger;
- reduced mode used zero translation, zero delay, and a 150 ms fade;
- no page or console errors were recorded.

The optimized-mode test temporarily changed only `wiopt['js-min']`, restored it to its original empty-string value, and confirmed the complete `wiopt` hash returned to `c38360b2337083ac6174e500dbebedae9873f58bcdcf162e6626d645b49f9056`.

The broader cart, fly-to-cart, popup/side-panel, recommendation, keyboard-triggerable control, repeated interaction, and mobile regression evidence remains recorded in `phase-3-browser-regression-record.md`; none of those implementations or option values were changed by this dependency replacement.

## Gate 3 result

Gate 3 passes for the working repository and distribution-candidate tree. The final exact WordPress.org archive will be scanned again during the artifact phase before submission.

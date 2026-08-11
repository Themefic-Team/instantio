# Phase 7 — Full Regression and Compatibility Record

Date started: 2026-08-10  
Scope: Instantio Free 3.3.34 with WooInstant Pro 3.2.11  
Status: Complete — local Gate 7 passed

## Test environment

- WordPress 7.0.3
- WooCommerce 11.0.0
- PHP 8.2.29
- HPOS enabled
- Twenty Twenty-Five 1.5 active
- Twenty Twenty-Four 1.5 available for the second-theme pass
- WooCommerce store visibility is `Coming soon`; automated storefront batches temporarily set it live and restore it after each batch

## Fixture inventory

The baseline store contains simple and variable products but no grouped, virtual, or sold-individually products. Temporary published products were created for the first cart batch, identified by these SKUs:

- `instantio-phase7-virtual`
- `instantio-phase7-sold`
- `instantio-phase7-grouped`

They were test-only fixtures and their IDs were never assumed by production code. All three were permanently deleted by exact SKU/identity verification at the end of the matrix.

## Completed batch: products, cart, mobile, accessibility, and motion

The same isolated-browser matrix passed with licensed Pro active and with Pro deactivated:

- simple product added and Instantio updated immediately;
- virtual product added;
- sold-individually product remained one line/item after a second add attempt;
- variable product selection and add succeeded;
- grouped product add succeeded;
- item removal reduced the live cart badge;
- empty cart reduced the badge to zero;
- 390 px viewport produced zero horizontal document overflow;
- no page or browser-console errors were captured;
- reduced-motion emulation removed the 120/80 px translation and retained a 150 ms opacity transition.

## Accessibility remediation from the first batch

The initial browser audit found three critical name/keyboard failures. Minimal semantic fixes were applied using the selected accessibility guidance:

- the cart trigger changed from a click-only `div` to a named native `button` with `aria-controls` and `aria-expanded`;
- open/close JavaScript keeps `aria-expanded` synchronized;
- quantity increase/decrease buttons now include product-specific accessible names;
- the coupon input now has a screen-reader label.

The repeated browser scan returned zero unnamed visible controls in the Instantio cart, and the trigger passed Enter-key activation on Side Cart and Popup Cart desktop/mobile checks.

## Layout progress

- Direct Checkout: passed as the plugin's intended named checkout anchor; this layout does not automatically redirect after add-to-cart.
- Side Cart: passed desktop/mobile opening, keyboard activation, expanded state, responsive overflow, and console checks.
- Popup Cart: passed desktop/mobile opening, keyboard activation, expanded state, responsive overflow, and console checks.
- Side Cart and Popup Cart both move initial focus to the close button, close on Escape, and restore focus to the cart trigger.

## Coupon and quantity batch

- quantity update from one to two passed;
- invalid coupon returned HTTP 200 but was not applied;
- a temporary valid coupon applied and recalculated totals;
- coupon removal restored the uncoupled total;
- the temporary coupon was deleted after the run;
- no page or browser-console errors were captured.

## Pro checkout and HPOS order batch

Real guest checkout passed in both Pro cart-and-checkout layouts:

- Side Cart (`ins-layout-options = '2'`);
- Popup Cart (`ins-layout-options = '3'`).

For each layout the guarded browser run:

- added the simple product and opened Instantio;
- advanced Cart to Shipping and Shipping to Payment;
- populated all required billing address fields;
- rendered the temporarily enabled Cash on Delivery gateway;
- rendered and activated the Place Order button;
- received HTTP 200 from WooCommerce's `wc-ajax=checkout` endpoint;
- reached WooCommerce's order-received page under HPOS;
- captured no page or browser-console errors.

WooCommerce visually hides the gateway radio input when COD is the sole available gateway. The regression assertion therefore checks gateway availability and checked state independently from CSS visibility.

Each checkout run snapshotted and restored the complete raw `wiopt`, `woocommerce_coming_soon`, and `woocommerce_cod_settings` values. Test orders belonging to `phase7@example.test` were permanently removed after verification. The post-run state was confirmed as:

- `ins-layout-options = '2'`;
- `woocommerce_coming_soon = 'yes'`;
- original COD option value restored to the empty string;
- zero matching test orders;
- `wiopt` SHA-256 unchanged at `c38360b2337083ac6174e500dbebedae9873f58bcdcf162e6626d645b49f9056`.

## Checkout validation, alternate shipping, notes, and account batch

The next guarded checkout matrix covered both virtual and physical products.

### Required-field validation

- submitting an empty Shipping step marked five visible required fields invalid;
- the checkout remained on Shipping;
- Payment remained unavailable until the required fields were completed;
- completing the required fields cleared all invalid markers and allowed progression.

### Alternate shipping address and order notes

A physical product was used because WooCommerce correctly omits alternate-shipping controls for virtual-only carts. The store did not have a shipping method for the US fixture address, so the test temporarily created a uniquely named US flat-rate zone.

- the alternate-shipping checkbox and fields rendered for the physical cart;
- the Payment summary displayed `987 Alternate Avenue` instead of the billing street;
- COD checkout completed after selecting the temporary shipping method;
- the HPOS order stored shipping recipient `Alternate Receiver`, address `987 Alternate Avenue`, and city `Los Angeles`;
- the HPOS order stored customer note `Phase 7 preserved order note`;
- the temporary order, shipping-method instance option, and uniquely named shipping zone were removed after inspection.

### Checkout account creation

WooCommerce checkout signup was temporarily enabled while preserving generated usernames and passwords.

- the Create an account control rendered and was selected;
- virtual-product COD checkout returned HTTP 200 and reached order-received;
- WordPress created a user with the `customer` role;
- the HPOS order referenced that customer's user ID;
- the order note and COD payment method persisted;
- the temporary HPOS order and test customer were deleted afterward.

Post-batch restoration confirmed:

- layout restored to `'2'`;
- Coming Soon restored to `'yes'`;
- COD restored to its original empty-string value;
- checkout signup restored to `'no'`;
- no temporary US shipping zone remained;
- no test users or matching test orders remained;
- `wiopt` SHA-256 remained `c38360b2337083ac6174e500dbebedae9873f58bcdcf162e6626d645b49f9056`.

## Settings save/reload and legacy-option batch

An authenticated browser session used the real Instantio settings form and AJAX Save endpoint. No administrator password was changed or stored.

### Combined Free and Pro schema

The test began with an old integer layout value (`2`) and temporary unknown top-level and nested keys. It then changed representative controls across the live schema:

- Layout image choice to `'3'`;
- mode image choice to `'light'`;
- progress image choice to `'4'`;
- cart-icon radio choice to `'cart-style-3'`;
- animation select to `'ins_animate_default'`;
- toggle-position select to `'left-bottom'`;
- header background color to `rgb(18,52,86)` through the WordPress color picker;
- dedicated-mobile toggle to enabled.

The first AJAX save returned HTTP 200 and `Options saved successfully!`. A full page reload rehydrated every selected value. A second unchanged save also returned HTTP 200, the button left its loading state, and no page or browser-console errors occurred.

Database inspection confirmed:

- the saved layout value was the string `'3'`, preserving the established scalar storage contract;
- the enabled toggle was stored as `'1'`;
- the WordPress color picker stored its normalized RGB value;
- both temporary unknown/Pro-owned sentinel values survived the save.

### Free-only preservation

WooInstant Pro was temporarily deactivated while its original active-plugin position and the complete `wiopt` value were snapshotted. A real no-change Free settings save returned HTTP 200 and reloaded successfully with no errors.

Database inspection confirmed that Free preserved both a top-level and a nested Pro-owned sentinel. The existing layout remained a string. The exact active-plugin list and `wiopt` snapshot were then restored, leaving Pro active.

### Sparse legacy fixture

The complete option was temporarily replaced with only `array( 'ins-layout-options' => 2 )` to represent an old installation missing modern nested groups. With licensed Pro active, Side Cart passed on desktop and mobile:

- native named cart trigger rendered and opened the panel;
- expanded state, initial focus, Escape close, and focus restoration passed;
- no horizontal overflow occurred;
- no page or browser-console errors occurred.

Final restoration confirmed Coming Soon at `'yes'`, Pro active, no temporary backup options, and `wiopt` SHA-256 unchanged at `c38360b2337083ac6174e500dbebedae9873f58bcdcf162e6626d645b49f9056`.

## Recommendations, dedicated mobile, assets, and theme batch

The compatibility matrix temporarily assigned product `26` as the upsell and product `27` as the cross-sell for product `28`. It enabled Side Cart, cart-and-checkout, upsells, cross-sells, dedicated mobile, and the dedicated mobile cart panel.

### Twenty Twenty-Five readable assets

- the page loaded `instantio-script.js` and `instantio-script-pro.js`;
- the Polo upsell rendered and its AJAX Add to Cart increased the Instantio count from one to two;
- the checkout advanced to Payment and rendered the Album cross-sell;
- the dedicated mobile bar remained hidden at 1440 px;
- at 390 px the mobile bar and dedicated-panel class rendered;
- mobile quantity increased from one to two;
- the mobile Checkout control opened the Instantio checkout panel;
- document overflow remained zero;
- no page or browser-console errors occurred.

### Twenty Twenty-Five minified assets

The same complete matrix passed while the page loaded `instantio-script.min.js` and `instantio-script-pro.min.js`. This verifies current readable/minified behavior parity for recommendations, cart refresh, checkout transition, and dedicated mobile interaction.

### Twenty Twenty-Four minified compatibility

The active theme was temporarily switched to Twenty Twenty-Four. The body theme class and both minified script URLs were verified, then the same desktop/mobile matrix passed without overflow or console errors.

Restoration confirmed:

- Twenty Twenty-Five active again;
- original empty upsell and cross-sell relationships restored on product `28`;
- Coming Soon restored to `'yes'`;
- licensed Pro remained active;
- the compatibility backup option was removed;
- `wiopt` SHA-256 restored to `c38360b2337083ac6174e500dbebedae9873f58bcdcf162e6626d645b49f9056`.

## Final static and Plugin Check gate

- all 96 PHP files across Instantio Free and WooInstant Pro passed PHP 8.2 syntax lint;
- readable and minified Free/Pro frontend JavaScript files passed `node --check`;
- `git diff --check` passed in both repositories;
- the first final Plugin Check run found four missing translator comments on the newly added quantity-button labels;
- translator comments were added without changing output or storage behavior;
- the repeated source-tree check reported only `.distignore` and `.gitignore`, both repository-only files excluded by `.distignore`;
- the distribution-equivalent Plugin Check run excluding those two non-shipped files completed with `Success: Checks complete. No errors found.`

## Final cleanup and restoration audit

- the virtual, sold-individually, and grouped Phase 7 products were permanently deleted;
- all Phase 7 test order searches returned empty;
- the temporary checkout customer does not exist;
- no temporary shipping zone remains;
- no Phase 7 backup option remains;
- Twenty Twenty-Five is active;
- Coming Soon is restored to `'yes'`;
- Instantio Free, WooCommerce, Plugin Check, and licensed WooInstant Pro are active;
- the Pro license check returns valid;
- HPOS remains enabled;
- `wiopt` SHA-256 remains `c38360b2337083ac6174e500dbebedae9873f58bcdcf162e6626d645b49f9056`.

## Gate 7 decision

Gate 7 passes for the tested local environment. The planned Free/Pro browser, checkout, settings, legacy-option, accessibility, responsive, asset, theme, static, and cleanup matrices are complete with the original persistent state restored.

This is a local release-readiness result. It does not by itself prove WordPress.org reviewer approval, production hosting behavior, or compatibility with every third-party theme, gateway, and extension.

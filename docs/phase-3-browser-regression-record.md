# Phase 3 — Browser Regression Record

Started: 2026-08-09  
Status: Settings round-trip drift resolved; extended checkout matrix remains

## Test safety

- Used a short-lived WordPress authentication cookie for the existing administrator; no password was read or changed.
- Browser tooling was installed only under `/tmp`.
- The license key and email were not written to source, test output, or documentation.
- Before the Free-only save test, the complete `wiopt` value was snapshotted. A shell exit trap restored it and reactivated WooInstant even if the browser failed.
- No order was placed while settings round-trip stability remained unresolved.

## Licensed Free + Pro browser result

Passed:

- Authenticated Instantio settings page loaded.
- Checkout Editor was present.
- No “Active Pro Plugin to use this features” lock copy appeared.
- Pro single-step, progress-bar, and dedicated-mobile controls existed in the page source.
- A published WooCommerce product completed the add-to-cart browser interaction.
- Chrome reported no serious JavaScript console errors.
- Pro remained active and the stored license remained valid.

The first Pro settings Save completed without leaving the button loading, but it changed the `wiopt` serialized hash from the Phase 0 value `80736d97ae2a67521c40bf76e1528fbda1b82bbf2ef2a88e19186d747400fd1f` to `baf8c537527ea56a619525b4a470676ae78e4ccdd4e8e652eeb33f0e92363b3e`. No field was intentionally changed.

Observed normalization included nested design groups, serialized checkout repeater values, `order_note_editor`, and `dedicated_mobile`. Because no complete pre-click value snapshot was written before that first click, this test does not claim byte-for-byte restoration to the earlier Phase 0 state. The current value is retained for diagnosis rather than replaced from the older SQL fixture, which has a different known hash and may be stale.

## Free-only browser result

WooInstant was temporarily deactivated with automatic cleanup/restoration.

Passed:

- Authenticated Free settings page loaded.
- Checkout Editor was visible and activation-lock copy was absent.
- Pro single-step, progress-bar, and dedicated-mobile controls were absent.
- Settings Save completed and the button left its loading state.
- A published product completed the add-to-cart browser interaction.
- Chrome reported no serious JavaScript console errors.

The Free save also normalized the option hash during the test. The full pre-test option snapshot was then restored successfully, and WooInstant was reactivated. This confirms the test harness cleanup but also reproduces the no-change save drift in both active schemas.

## Current environment after cleanup

- Instantio Free: active.
- Instantio Pro: active.
- Pro license: valid.
- `wiopt` hash: `baf8c537527ea56a619525b4a470676ae78e4ccdd4e8e652eeb33f0e92363b3e` (the state after the first Pro save).
- No test order was created.

## Stop gate and next action

Before testing every layout or placing an order, fix no-change settings round-trip drift. The save engine must distinguish fields omitted because they are unchecked from fields absent because their tab/control was not rendered or submitted, and it must avoid changing serialized/nested values when the browser submitted an equivalent value.

After that fix:

1. Snapshot `wiopt` completely.
2. Repeat no-change Save in Free and Pro and compare semantic values plus stored types.
3. Test every layout and cart mutation.
4. Exercise Checkout Editor add/clone/reorder/disable/delete/reset.
5. Place Free and Pro checkout orders and verify custom metadata in the HPOS order screen.

## Settings round-trip fix

Completed on 2026-08-09 after a minimized before/after browser diff.

Root causes:

- A Free save replaced complete numeric Checkout Editor row lists. This correctly kept deleted rows deleted, but also removed Pro-only metadata stored inside retained rows.
- The Free `ins-page-selected` Select2 declaration omitted the Pro schema's `multiple` contract. With no saved selection, the browser submitted the first page ID.

Implementation:

- Checkout repeater rows now merge with stored rows by their stable billing/shipping origin keys.
- Only submitted rows are returned, preserving deletion and reorder behavior.
- Matching rows retain unknown Pro-owned keys; new custom rows do not inherit metadata from another row.
- `ins-page-selected` is now a multiple Select2 in Free, matching the established runtime and Pro array contract.

Regression evidence:

- Focused row-merge test passed for reorder, deletion, submitted edits, new rows, and unknown Pro metadata preservation.
- Authenticated Free no-change Save produced **zero changed option keys**.
- Authenticated licensed Pro no-change Save produced **zero changed option keys**.
- Both browser runs completed settings submission without a stuck loading state.
- WooInstant was restored active and its license remained valid.
- All 61 Free PHP files and project JavaScript passed syntax checks.
- Live frontend returned HTTP 200 with no PHP warning/fatal markers.

The current `wiopt` hash remains `baf8c537527ea56a619525b4a470676ae78e4ccdd4e8e652eeb33f0e92363b3e`. This is the post-normalization state retained during diagnosis; the fix proves subsequent equivalent Free and Pro saves are stable.

The former round-trip stop gate is resolved. Remaining work is the interaction-heavy layout, cart mutation, Checkout Editor CRUD/reset, checkout submission, and HPOS order verification matrix.

## Extended cart and layout matrix — 2026-08-10

The Playwright CLI testing guidance was used to structure isolated browser sessions, explicit assertions, temporary fixtures, console capture, and cleanup.

### Cart mutations

The same real Instantio cart-panel workflow passed with licensed WooInstant active and with WooInstant temporarily deactivated:

- add published simple product;
- increase quantity from 1 to 2;
- decrease quantity from 2 to 1;
- apply a temporary fixed-cart WooCommerce coupon;
- verify coupon row and discounted total;
- remove coupon;
- remove cart item;
- add again and empty the complete cart;
- no serious browser console errors.

Each temporary coupon used a clearly marked `instantio-*-e2e-*` code and was deleted by cleanup. WooInstant was restored active afterward.

The first coupon assertion failed because quantity controls legitimately generated asynchronous cart refreshes that replaced the coupon form. Waiting for those refreshes before interacting made the deterministic matrix pass; the plugin coupon path itself did not require a code change.

### Layout rendering

With the licensed Pro schema active, HTTP render probes confirmed:

- `ins-layout-options = 1`: Direct Checkout; no side/popup cart shell is emitted on the homepage.
- `ins-layout-options = 2`: side/slide Instantio cart shell renders.
- `ins-layout-options = 3`: popup Instantio cart shell renders.

The selected value was restored to `2`; WooInstant is active and its license is valid.

### Snapshot handling correction

The first extended tests used JSON for a full option snapshot. JSON restoration preserved the semantic layout/mode and option data, but reserialization changed the byte-level `wiopt` hash to `630c39e0eb3ccba6af48d9f3b9613ca67c8dc3adef190b2c25ac81922ee954f9`. Future reversible tests must snapshot and restore PHP serialization directly rather than JSON when byte identity matters.

No test order exists yet. Remaining matrix: Checkout Editor CRUD/reset, checkout submission, custom metadata/HPOS verification, upsell/cross-sell fixtures, and dedicated-mobile behavior.

## AJAX add-to-cart immediate refresh regression — 2026-08-10

### Reported behavior

When a product was added through AJAX, the Instantio cart did not display the new item until the page was reloaded.

### Reproduction and root cause

The issue was reproduced only while the `js-min` optimization option was enabled:

- the Instantio single-product request returned `{"error":true}`;
- the cart remained empty until reload;
- the browser reported that `ins_cart_count` was being read from an undefined value.

The readable `instantio-script.js` already sent the required `ins_ajax_nonce` and consumed the current WordPress JSON response envelope. The checked-in `instantio-script.min.js` was a stale copy that did not contain the repaired single-product request contract.

### Fix

- Regenerated `assets/app/js/instantio-script.min.js` from the current readable source with Terser.
- Added file-modification cache versioning to the selected Instantio frontend script in `includes/controller/Assets.php`.
- Kept the existing `js-min` option and its saved value format unchanged.
- Kept the public `ins_script_min_status_checked` URL filter in place for WooInstant compatibility.

### Browser regression evidence

The focused Playwright regression passed with `js-min = 1` and again with the readable-script setting restored:

- Instantio single-product handler: cart items changed from 0 to 1 immediately.
- WooCommerce archive `added_to_cart` event: cart items changed from 0 to 1 immediately.
- Cart badge displayed `1` without a page reload.
- AJAX responses returned HTTP 200 with `success: true` and the expected `data` envelope.
- No browser console or page errors were captured.
- Optimized asset URL included a file-based cache suffix, for example `instantio-script.min.js?ver=3.3.34-1786340392`.
- Readable asset URL also included a file-based cache suffix.

The complete PHP-serialized `wiopt` value was restored byte-for-byte after the optimized-mode test. Its restored SHA-256 was `8c9eb9d08f0fa11290853c367f01080bbfff7225d6157262ecbcf72978ae99ff`.

### Static validation

- Readable JavaScript syntax passed `node --check`.
- Regenerated minified JavaScript syntax passed `node --check`.
- `includes/controller/Assets.php` passed PHP syntax validation with the Local PHP 8.2 runtime.

## WooCommerce Blocks add-to-cart synchronization — 2026-08-10

### Corrected reproduction

The earlier archive regression manually dispatched the legacy jQuery `added_to_cart` event and therefore did not exercise the shop template's real add-to-cart implementation.

The real rendered product button on the Twenty Twenty-Five block shop produced the reported failure:

- WooCommerce's Mini-Cart changed from 0 to 1.
- Instantio's badge and cart remained at 0.
- The product was present in Instantio only after page reload.
- No JavaScript exception occurred.

### Root cause

The block product button adds products through `/wp-json/wc/store/v1/batch` and emits the native `wc-blocks_added_to_cart` browser event. Instantio listened only for WooCommerce's legacy jQuery `added_to_cart` event, so its PHP-rendered cart was not refreshed.

The native event also occurs immediately around the Store API response. A short delay is required before requesting classic PHP cart markup so the WooCommerce session cookie from the Store API request is available to `admin-ajax.php`.

### Compatibility fix

- Moved the existing post-add cart refresh into one shared function.
- Listened for both legacy jQuery `added_to_cart` and native `wc-blocks_added_to_cart`.
- Debounced both event paths into a single refresh after 100 milliseconds. This prevents duplicate refreshes when WooCommerce bridges the legacy event to its native event.
- Added a defensive JSON-envelope check before reading response data.
- Rebuilt the optimized script from the corrected readable source.
- Did not change product, variation, cart, license, or Instantio option storage.

### Exact browser evidence

The Playwright regression clicked the real rendered AJAX button for published product `28`; it did not synthesize the cart event.

Before the fix:

- WooCommerce Mini-Cart count: 1.
- Instantio item count: 0.
- Instantio badge: 0.
- Native Blocks event observed; legacy event absent.

After the fix, in both readable and optimized modes:

- WooCommerce Mini-Cart count: 1.
- Instantio item count: 1 without reload.
- Instantio badge: 1 without reload.
- Store API batch returned HTTP 207 with the added cart item.
- Instantio cart reload returned HTTP 200 with `success: true` and the same item.
- No console or page errors occurred.
- The optimized asset URL contained the new file-based cache version.

The WooCommerce coming-soon setting was temporarily disabled only to expose the shop to the isolated guest browser and was restored to `yes` after each run. The `js-min` setting was restored after optimized testing, and the complete serialized `wiopt` hash matched its pre-test value.

## Instantio variation-popup theme synchronization — 2026-08-10

### Reported behavior and cause

After selecting a variation inside Instantio's product popup, the private `ins_ajax_cart_single` request updated Instantio successfully. However:

- the theme Mini-Cart did not update;
- the variation fly-cart animation did not run.

The private response did not contain standard WooCommerce cart fragments and its success callback did not publish `added_to_cart`. Therefore theme cart integrations and WooCommerce Blocks did not know that the cart changed. Separately, the variation animation handler referenced `variation_id` without defining it in that handler.

### Fix

- `ins_ajax_cart_single` responses now include filtered WooCommerce Mini-Cart fragments and ensure cart cookies are set.
- The popup success path publishes the standard jQuery `added_to_cart` event with fragments, cart hash, and button.
- WooCommerce's existing jQuery-to-native bridge consequently publishes `wc-blocks_added_to_cart` for block themes.
- Instantio marks its local update before publishing the event, preventing its dual-event compatibility listener from performing a redundant cart reload.
- The fly-animation handler now reads the selected `variation_id` from its own cart form.
- The optimized script was rebuilt from the corrected readable source.

No variation identifiers, selection behavior, product data, or option storage formats were changed.

### Browser evidence

The focused Playwright test used the real Instantio popup for variable product `16`, selected `blue` and `No`, confirmed variation `34`, and clicked the popup's real Add to Cart button.

In readable and optimized modes:

- Instantio cart item count: 1.
- Instantio badge: 1.
- Theme WooCommerce Blocks Mini-Cart count: 1.
- `#ins-cart-fly` was present during the animation.
- Both `added_to_cart` and `wc-blocks_added_to_cart` were observed.
- The private AJAX response returned HTTP 200, `success: true`, the selected variation, cart hash, and fragments.
- No console or page errors occurred.

The WooCommerce coming-soon setting and `js-min` test value were restored afterward. The serialized `wiopt` hash matched its pre-test value.

## Checkout Editor CRUD and HPOS order regression — 2026-08-10

### Checkout Editor browser matrix

An authenticated Playwright session exercised the real Checkout Editor controls with a serialized `wiopt` snapshot:

- Billing rows increased from 11 to 12 after Add.
- Rows increased from 12 to 13 after Clone.
- The cloned custom field received a distinct stable origin.
- Rows returned from 13 to 12 after Delete confirmation.
- The retained custom field was moved to index 0 and saved successfully.
- Its label, placeholder, stable origin, enabled status, and added-row marker persisted.
- Disabling the field persisted as an empty status value.
- Reset Billing Fields removed the stored billing repeater key so defaults can be rebuilt.
- The complete serialized settings snapshot was restored afterward.

The disable test initially failed because the origin-aware merge preserved the prior checkbox value when an unchecked control was absent from POST data. `Ins_TF_Settings.php` now explicitly clears only the known billing/shipping checkbox keys for a submitted row while continuing to preserve unknown Pro-owned metadata. The same browser test then passed.

### Real checkout and HPOS verification

A temporary published virtual product and a clearly marked custom billing field were created for an isolated guest checkout. Cash on Delivery was temporarily enabled because the development store otherwise had no available payment method.

The real Instantio checkout request passed:

- custom billing field rendered in Instantio;
- WooCommerce checkout returned HTTP 200 and `result: success`;
- an HPOS-backed order was created;
- billing first name saved as `Instantio`;
- billing email saved as `instantio-test@example.com`;
- custom metadata saved as `E2E-REFERENCE-20260810`;
- customer order note saved as `Instantio checkout E2E note`;
- no console or page errors occurred.

Cleanup was verified:

- the test order was permanently deleted;
- the temporary virtual product was permanently deleted;
- Cash on Delivery settings were restored;
- WooCommerce coming-soon mode was restored to `yes`;
- the complete serialized `wiopt` hash matched its pre-test value.

No persistent test order, product, field, payment configuration, or credential remains.

## Upsell, cross-sell, and dedicated-mobile matrix — 2026-08-10

Temporary relationships were assigned to product `28`: product `26` as its upsell and product `27` as its cross-sell. The corresponding Pro options were enabled only during testing.

### Recommendation results

Layouts `2` (side cart) and `3` (popup cart) both passed:

- upsell product rendered in the cart;
- cross-sell product rendered in the checkout response;
- upsell Add to Cart increased the Instantio cart to two items;
- Instantio and WooCommerce cart synchronization remained functional;
- no console or page errors occurred.

The first run exposed a fly-animation error for recommendation cards because their container is `.ins-single-product-sell`, not `.product`. The readable script now resolves either container and safely falls back to the cart-icon animation when image or toggle geometry is unavailable. The optimized script was rebuilt, and the rerun passed without errors.

### Dedicated-mobile results

At a 390 by 844 viewport:

- the dedicated mobile bar was visible;
- the dedicated mobile panel class rendered;
- quantity increased from 1 to 2;
- the mobile Checkout control opened the Instantio checkout panel;
- removing the item emptied the cart and updated the count.

At a 1280 by 900 viewport, the mobile bar remained hidden.

### Restoration

- the original upsell and cross-sell relationships were restored;
- the complete serialized `wiopt` value was restored exactly;
- WooCommerce coming-soon mode was restored;
- no temporary products or orders were required.

This completes the planned Phase 3 browser regression matrix.

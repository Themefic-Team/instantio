# Phase 2 — Free/Pro Separation Record

Started: 2026-08-09  
Status: Steps 2.1–2.5 and licensed automated regression complete; manual browser regression remains

## Purpose

Remove Guideline 5 trialware architecture from Instantio Free without breaking Free functionality, Pro functionality, or existing `wiopt` settings.

The UI Skills `distill` guidance was applied to the settings inventory: remove nonfunctional locked controls from the task flow, retain only controls users can operate, and keep any separate-add-on explanation concise rather than duplicating disabled controls.

## Safety boundary

No runtime code was changed during Step 2.1. The local site has no Pro license key/email, so valid-license Pro behavior cannot currently be baselined. The field inventory and ownership recommendation may proceed; migration edits must respect the stop gates below.

## Inventory result

- Raw `is_pro => true` markers in the Free schema: 39
- Commented/inactive legacy markers: 6
- Active locked declarations: 33
- Active unique option IDs: 32 (`custom_billing_field_add` occurs twice)

## Recommended ownership groups

### Group A — Fully enable in Free

These settings control implementation already owned and executed by Free. Keeping their working controls disabled is the clearest trialware violation; moving their runtime into Pro would add risk with little architectural benefit.

| Option ID | Free consumer | Pro schema | Recommendation |
| --- | --- | --- | --- |
| `ins-page-selected` | Free `App` hides Instantio on selected pages | Present | Remove `is_pro`; fully enable in Free. |
| `woins-quickview-disable` | Free `Assets`/setup wizard controls Free quick view | Present | Remove `is_pro`; fully enable in Free. |
| `wi-disable-ajax-add-cart` | Free `Assets`/setup wizard controls Free AJAX behavior | Present | Remove `is_pro`; fully enable in Free. |
| `wi-icon-choice-uploder` | Free `App` and `Assets` render uploaded toggle image | Present | Remove `is_pro`; fully enable in Free. |
| `js-min` | Free setup wizard stores it; Pro also uses it | Present | Remove `is_pro`; fully enable in Free, then verify Free actually selects its minified asset as intended. |

Enabling these fields retains the same IDs and stored types. No data migration is required.

### Group B — Remove disabled controls from Free; retain in Pro

These controls configure behavior whose meaningful implementation lives in Pro. Free should retain only stable actions/filters and should not render disabled copies of the actual settings.

| Feature/options | Free dependency | Pro implementation | Recommendation |
| --- | --- | --- | --- |
| Single-step checkout: `ins-layout-step` | Free reads it to adjust shell/header/content flow | Pro replaces layout/template and checkout steps | Remove Free control; preserve Free hook/compatibility reads; keep control in Pro schema. |
| Checkout progress: `ins-layout-progressbar` | Free setup wizard displays/stores it | Pro `templates/progress.php` consumes it | Remove Free settings/wizard control; keep Pro control/template. |
| Upsells: `ins-upsell`, `upsell-heading` | Free has output insertion hook/dashboard marketing only | Pro templates/rendering | Remove Free controls; retain `ins_show_items_upsells` hook and Pro controls. |
| Cross-sells: `crosssell`, `crosssell-heading` | Free has no rendering implementation | Pro controller/templates | Remove Free controls; retain `ins_show_items_cross_sell` hook and Pro controls. |
| Checkout billing design: `ins-bill-*` | No Free runtime consumers outside schema | Pro `Assets` generates CSS | Remove Free controls; retain identical IDs in Pro schema. |
| Checkout payment/button design with Pro equivalents | No Free runtime consumers outside schema | Pro `Assets` generates CSS | Remove Free controls; retain identical IDs in Pro schema. |

Removing a control must not unset its `wiopt` value. Pro reactivation must restore the saved value.

### Group C — Remove dead/obsolete controls

| Option IDs | Evidence | Recommendation |
| --- | --- | --- |
| `ins-pay-item-des-bg`, `ins-pay-item-des-txt` | No runtime consumer and no Pro-schema equivalent found | Remove from Free schema; do not delete stored keys during this release. |
| `ins-place-order-bg`, `ins-place-order-txt` | No runtime consumer and no Pro-schema equivalent found; newer Pro uses `ins-place-order-button-*` | Remove from Free schema; preserve old values for compatibility until a later migration policy exists. |
| `mobile` | Free control saves `mobile`, but Free and Pro runtime read `dedicated_mobile` | Remove the dead Free control. Do not map values automatically without product approval because legacy intent is ambiguous. Pro retains `dedicated_mobile`. |

The `mobile`/`dedicated_mobile` mismatch is a pre-existing bug. Merely unlocking `mobile` would display a control that does not drive the runtime feature.

### Group D — Checkout Editor decision

Current Free architecture bundles and loads the complete checkout editor runtime:

- billing/shipping field filters;
- field reorder, label, placeholder, required/status behavior;
- custom-field order metadata behavior;
- reset callbacks/AJAX;
- order-note customization;
- repeater JavaScript including new-field behavior.

The Free schema currently exposes the base repeaters but locks:

- both “Create new fields” controls/notices;
- billing reset;
- shipping reset;
- order-note customization;
- JavaScript imposes checkout-editor field limits.

The Pro schema supplies expanded repeater fields and the same reset callback names, but still depends on the runtime code loaded by Free.

Two compliant choices exist:

#### Recommended for this reopening release: fully enable Checkout Editor in Free

- Remove the locked “activate Pro” notices.
- Enable reset callbacks and order-note controls.
- Remove artificial field limits from Free JavaScript.
- Keep current runtime ownership and option keys unchanged.
- Verify that adding custom fields is complete and secure in Free.

This is the lowest functionality risk and the fastest defensible Guideline 5 resolution because the implementation is already distributed in Free.

#### Alternative commercial choice: move the complete Checkout Editor to Pro

- Move schema, runtime filters, AJAX/reset handlers, order metadata, and JavaScript to Pro.
- Remove the Checkout Editor section and implementation from Free.
- Preserve compatibility hooks and stored values.
- Requires valid-license Pro baseline and a much larger regression matrix.

This preserves the feature as paid but has significantly higher regression risk. It must not be attempted without a working Pro license fixture.

## Settings engine change required before removing fields

Source inspection confirms the Free save engine currently starts with an empty `$tf_option_value`, sanitizes only registered schema fields, and replaces the complete option with `update_option( $this->option_id, $tf_option_value )`. Therefore, removing Pro controls from the Free schema now would discard those Pro keys on the next Free save.

The first implementation slice must change this behavior so sanitized submitted/registered values are merged into the existing `wiopt` array. It must still allow a visible field to be intentionally cleared, and it must not turn scalar values into arrays.

Acceptance assertion:

1. Capture `wiopt` hash and a sentinel unknown key.
2. Save Free settings.
3. Confirm the sentinel and all Pro keys remain unchanged.
4. Confirm changed Free keys update with the same scalar/array types.

No production sentinel may be added; this test belongs on the local fixture only.

## Generic locked-control mechanism

After all active Free fields are classified and migrated:

- remove active `is_pro` use from the Free schema;
- remove Free renderer behavior adding `tf-field-disable tf-field-pro` for feature gating;
- remove JavaScript that disables `.tf-field-disable` and intercepts `.tf-field-pro` clicks;
- keep `badge_up`/upcoming behavior only if it does not disable bundled functional code and is acceptable under the final review policy;
- replace duplicate disabled controls with at most one concise, contextual link explaining that a separate add-on provides additional features.

The settings interface must prioritize completing Free configuration, not navigating an obstacle course of disabled upsells.

## Step 2.1 verification

- Every active `is_pro` declaration was identified.
- Each unique locked ID was checked against Free consumers and the Pro schema/runtime.
- The `mobile`/`dedicated_mobile` mismatch was identified before changes.
- Dead fields and missing Pro equivalents were identified.
- Checkout Editor was isolated as the major product decision.
- The current replacement-style save behavior was confirmed as unsafe for schema separation.
- No option key, stored value, hook, template, PHP, JavaScript, or CSS behavior was changed.

## Step 2.2 — Shared option preservation implementation

Completed in `admin/tf-options/classes/Ins_TF_Settings.php`:

- The save handler no longer replaces `wiopt` with only fields registered by the active schema.
- Sanitized submitted values are merged into the previously stored option.
- Associative option groups merge recursively, preserving unsubmitted Pro-owned nested keys.
- Numeric lists such as checkout repeater rows replace the previous list as a unit, so intentionally deleted rows do not reappear.
- Submitted empty scalar values still replace stored values, preserving the ability to clear a visible field.
- An empty/invalid field result no longer deletes the entire shared option.
- The implementation remains compatible with PHP 7.4 and does not use `array_is_list()`.

Validation:

- PHP syntax: passed.
- Merge helper assertions passed for scalar clearing, top-level unknown preservation, nested unknown preservation, nested update, and numeric-list replacement.
- Real `save_options()` integration test passed against temporary option `instantio_phase2_merge_test`.
- Temporary option cleanup passed.
- The real `wiopt` serialized hash remained `80736d97ae2a67521c40bf76e1528fbda1b82bbf2ef2a88e19186d747400fd1f`, identical to the Phase 0 baseline.

## Checkout Editor implementation constraint discovered

The custom-field limit is not merely a disabled UI choice. Current PHP explicitly handles only billing custom IDs 11–16 and shipping custom IDs 10–14, while JavaScript generates IDs from the repeater row count. Removing the UI maximum alone would produce saved fields that the checkout runtime and order metadata code do not process.

The Checkout Editor Free-enablement slice must therefore replace hard-coded custom ID branches with validated prefix-based generic handling before removing limits. This refactor requires focused billing, shipping, validation, order-save, admin-display, and deletion tests.

## Step 2.3 — Free-native controls, first slice

The following controls are now fully enabled in the Free schema by removing only their `is_pro` flags:

- `ins-page-selected`
- `woins-quickview-disable`
- `wi-disable-ajax-add-cart`
- `wi-icon-choice-uploder`

These controls already had active Free runtime consumers. Their IDs, defaults, field types, saved types, consumers, and Pro-schema equivalents were not changed.

`js-min` is now also fully enabled. Free's asset controller independently selects `instantio-script.js` or `instantio-script.min.js` from the existing scalar option, then applies the established `ins_script_min_status_checked` filter for backward compatibility with Pro and integrations.

Validation:

- Free settings schema PHP syntax: passed.
- Source assertions confirm all four controls no longer carry active `is_pro` markers.
- Active Free schema lock declarations decreased from 33 to 28.
- The real `wiopt` serialized hash remains identical to Phase 0; this schema edit performed no migration or database write.
- `git diff --check`: passed.
- Filter-based runtime registration tests confirmed `js-min` off selects `instantio-script.js` and `js-min` on selects `instantio-script.min.js` without changing the database.

## Step 2.4 — Checkout Editor fully enabled in Free

Completed without changing any existing option ID or stored value shape:

- Billing and shipping repeaters now expose Add, Clone, Delete, Edit, Enable/Disable, Required, and drag-to-reorder controls.
- The disabled “activate Pro” notices were removed; billing/shipping reset and Order Notes controls are enabled.
- Checkout-specific quota copy was removed and these repeaters have no Free-only maximum.
- New and cloned fields receive collision-resistant numeric suffixes while retaining the established custom-key prefixes.
- PHP registration now accepts any correctly formed configured field key instead of only the former hard-coded ranges.
- Order creation derives allowed custom meta keys from saved editor rows, validates prefixes, sanitizes values, and uses `WC_Order::update_meta_data()` for HPOS compatibility.
- Admin order display uses the same validated configuration and `WC_Order::get_meta()`.

Security and compatibility boundaries:

- Only configured keys matching the billing/shipping custom-key patterns are accepted; arbitrary order-meta keys are rejected.
- Existing legacy custom IDs remain valid, `wiopt` is not migrated, and Pro can read the same arrays and keys.

Automated validation:

- Generic billing and shipping field registration: passed with IDs outside the old ranges.
- Configured custom order meta was sanitized and stored on an in-memory `WC_Order`; forged/unconfigured `admin_email` meta was rejected.
- All 61 Free PHP files and all project JavaScript files passed syntax checks.
- `git diff --check`: passed.
- Local homepage smoke: HTTP 200 with no PHP warning/fatal markers.
- The real `wiopt` hash remained `80736d97ae2a67521c40bf76e1528fbda1b82bbf2ef2a88e19186d747400fd1f`.

Manual release gate: add, clone, edit, reorder, disable, delete, and reset fields in the authenticated UI; place a checkout order; verify custom values in the HPOS order screen.

## Step 2.5 — Remove Free trialware controls and obsolete settings

The remaining disabled controls were removed from the Free settings schema as complete functional groups:

- Pro layout: `ins-layout-step`, `ins-layout-progressbar`.
- Pro merchandising: `ins-upsell`, `upsell-heading`, `crosssell`, `crosssell-heading`.
- Pro checkout design: all `ins-bill-*` controls.
- Pro payment design: `ins-pay-item-bg`, `ins-pay-item-txt`, and the three `ins-place-order-button-*` controls.
- Obsolete design controls: `ins-pay-item-des-bg`, `ins-pay-item-des-txt`, `ins-place-order-bg`, `ins-place-order-txt`.
- Dead Free mobile section: `mobile`, its heading, and `mobile-cart-panel`; the runtime uses Pro's `dedicated_mobile` contract instead.

The generic `is_pro` settings renderer and the JavaScript behavior that disabled `.tf-field-disable` elements and redirected `.tf-field-pro` clicks were removed. Upcoming-field support remains separate. The licensed WooInstant schema-loading path in `Ins_TF_Options` was not changed.

Compatibility and preservation evidence:

- Active `is_pro => true` declarations in the Free schema: **0**.
- All retained Pro-owned option IDs were found in WooInstant's schema/runtime.
- Free runtime compatibility reads and the upsell/cross-sell extension hooks were not removed.
- A merge test populated representative removed Pro/dead values, submitted only a visible Free value, and confirmed every removed value survived unchanged.
- No database migration or deletion was added.
- The live `wiopt` serialized hash remains `80736d97ae2a67521c40bf76e1528fbda1b82bbf2ef2a88e19186d747400fd1f`.
- All 61 Free PHP files passed syntax checks; all project JavaScript files passed `node --check`; `git diff --check` passed.

This completes the source-level Free/Pro field separation. Browser regression and a licensed Free + Pro fixture remain release gates.

## Licensed Free + Pro automated regression

Run on 2026-08-09 after the site owner activated WooInstant. No license key or license email is recorded in this repository.

Environment confirmed:

- Instantio Free 3.3.34: active.
- Instantio Pro 3.2.11: active.
- Stored license key/email: present.
- `ins_checked_license_status`: valid.
- `WOOINS` and the Pro controller classes: loaded.

Integration evidence:

- Exactly one `Ins_TF_Settings` instance was registered.
- Its active sections were loaded from WooInstant's Pro schema and contained representative Pro controls for layout, progress, upsells, cross-sells, billing design, payment design, place-order design, and `dedicated_mobile`.
- Shared hooks registered successfully: `ins_layout_slug`, `ins_get_svg_icon_pro`, `ins_layout_class`, `ins_cart_template`, `ins_show_items_cross_sell`, `ins_show_items_upsells`, `ins_script_min_status_checked`, and `ins_style_min_status_checked`.
- The live homepage returned HTTP 200 and loaded both Free assets and WooInstant Pro 3.2.11 CSS/JavaScript.
- Combined Free + Pro syntax validation passed for 98 PHP files and all project JavaScript outside bundled vendor/library directories.
- The `wiopt` hash remained identical to the Phase 0 baseline.

The legacy WooInstant `is_ins_pro()` function returns `false`, but the active Free settings framework correctly detects WooInstant through `is_plugin_active()` plus the `WOOINS` class and loads the valid-license Pro schema. The removed generic Free lock renderer therefore no longer incorrectly disables fields in this active Pro schema.

Still manual: authenticated Pro settings save/reload, each layout, cart operations, checkout submission, custom-field display, upsell/cross-sell output, and mobile behavior.

## Remaining stop gates before field separation

1. **Checkout Editor ownership:** approved direction is fully enabled in Free for the reopening release.
2. **Authenticated Free browser baseline:** settings save/reload, all layouts, cart operations, checkout field behavior.
3. **Valid-license Pro fixture:** available; automated schema/hook/assets regression passed, manual workflows remain.
4. **Unknown-key preservation:** completed in Step 2.2.

## Next implementation slices after gates clear

1. Run the authenticated Free browser regression matrix.
2. Run the authenticated valid-license Free + Pro browser regression matrix.
3. Complete the release-readiness evidence after both manual gates.

## Current phase status

Step 2.1 inventory: **complete**.  
Step 2.2 option preservation: **complete**.  
Step 2.3 Free-native controls: **complete; five controls enabled**.  
Step 2.4 Checkout Editor: **implementation complete; authenticated browser/order regression remains a release gate**.  
Step 2.5 Pro/dead control removal: **complete**.  
Phase 2 source implementation: **complete; manual regression gates remain**.  
Licensed Free + Pro automated regression: **passed**.  
Runtime files changed in this phase so far: `admin/tf-options/classes/Ins_TF_Settings.php`, `admin/tf-options/Ins_TF_Options.php`, `admin/tf-options/options/tf-settings.php`, `admin/tf-options/assets/js/ins-options.js`, `admin/tf-options/assets/js/admin.js`, `includes/controller/Assets.php`, `includes/controller/checkout_editor.php`.

Phase 3 browser continuation is tracked separately in `docs/phase-3-browser-regression-record.md`. It passed schema visibility, activation switching, add-to-cart, and console checks, but found no-change settings round-trip drift that must be fixed before the remaining checkout/order matrix.

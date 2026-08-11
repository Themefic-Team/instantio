# Instantio complete changed-file record

## Scope

- Change period: 2026-08-08, from the initial clean Git worktree through the completed Plugin Check remediation.
- Source directory: `wp-content/plugins/instantio/`.
- This inventory was generated from the current Git diff and untracked-file list.
- No commit was created, so every remediation change is still visible in the working tree.
- The release ZIP is outside the plugin directory and is not part of this source-file inventory.

## Functional code files modified

These files can directly affect plugin behavior and should be reviewed first when investigating lost functionality.

### Plugin bootstrap and shared functions

- `instantio.php` — metadata, quick-view request handling, compatibility annotations, initialization-related edits, and complete removal of the Appsero bootstrap.
- `functions.php` — API replacements, promotion callbacks, sanitization, output escaping, and compatibility annotations.
- `composer.json` — license identifier changed to `GPL-3.0-or-later`.
- `readme.txt` — minimum WordPress version, license metadata, and short description.

### Public JavaScript

- `assets/app/js/instantio-script.js` — nonce payloads added to cart update, removal, empty-cart, and coupon actions.
- `assets/app/js/instantio-script.min.js` — distributed copy synchronized with the readable JavaScript file.

### Public controllers and templates

- `includes/controller/App.php` — cart AJAX validation, nonce verification, sanitization, rendering escaping, compatibility annotations, and removal of layout debug output that corrupted AJAX JSON.
- `includes/controller/Assets.php` — compatibility annotation for the established namespace and hooks.
- `includes/controller/checkout_editor.php` — checkout field persistence, input handling, output escaping, notices, and compatibility annotations.
- `includes/controller/ins-checkout-editor.php` — direct-access protection and compatibility annotation.
- `includes/controller/icon-svg.php` — direct-access protection, SVG output handling, and compatibility annotation.
- `includes/layouts/layout-1.php` — output handling and compatibility annotation.
- `includes/layouts/layout-2.php` — output handling and compatibility annotation.
- `includes/layouts/layout-3.php` — output handling and compatibility annotation.
- `includes/templates/cart-modern.php` — cart item, price, metadata, quantity, and upsell output handling.
- `includes/templates/ins_single_step_cart.php` — cart item, price, metadata, quantity, and upsell output handling.

### Administrator controllers

- `includes/controller/Admin.php` — review notice, plugin URL, AJAX data, escaping, and WooCommerce activation link changes.
- `includes/controller/class-dashboard-widget.php` — output escaping changes.
- `includes/controller/class-promo-notice.php` — date/time API, translation-domain, and output changes.
- `includes/controller/class-setup-wizard.php` — nonce/capability validation, request sanitization, JSON responses, redirects, and output escaping.
- `includes/controller/dashboard-promo-notice.php` — asset and output escaping changes.

### Removed Appsero SDK integration

- Removed `includes/app/` in full, including `Client.php`, `Insights.php`, `License.php`, `Updater.php`, its nested Composer manifest, and development configuration.
- Removed `appsero.json`.
- Removed the Appsero telemetry disclosure from `readme.txt`.

## Settings framework files modified

### Settings framework core

- `admin/tf-options/Ins_TF_Options.php` — local dependency enqueues, explicit footer loading, upload handling, request validation, URLs, and compatibility annotations.
- `admin/tf-options/assets/js/ins-options.js` — switched the code editor from dynamic CDN loading to WordPress's native Code Editor assets; settings saves now request native JSON and clear the loading state on both success and failure.
- `admin/tf-options/classes/INS_Metabox.php` — nonce input handling and sanitization.
- `admin/tf-options/classes/Ins_ChangeLog.php` — compatibility annotation for included data.
- `admin/tf-options/classes/Ins_TF_Settings.php` — extensive administrator output escaping and compatibility annotations.
- `admin/tf-options/classes/TF_Taxonomy_Metabox.php` — capability, nonce, and input handling.
- `admin/tf-options/options/tf-menu-icon.php` — included-variable compatibility annotation.
- `admin/tf-options/options/tf-settings.php` — missing translation domains and compatibility annotations.

### Individual field components

- `admin/tf-options/fields/checkbox/INS_checkbox.php`
- `admin/tf-options/fields/code_editor/INS_codeeditor.php`
- `admin/tf-options/fields/color/INS_color.php`
- `admin/tf-options/fields/date/INS_date.php`
- `admin/tf-options/fields/editor/INS_editor.php`
- `admin/tf-options/fields/file/INS_file.php`
- `admin/tf-options/fields/gallery/INS_gallery.php`
- `admin/tf-options/fields/icon/INS_icon.php`
- `admin/tf-options/fields/image/INS_image.php`
- `admin/tf-options/fields/imageselect/INS_imageselect.php` — output escaping plus backward-compatible scalar persistence for Layout and Design image selectors; unexpected array requests are normalized to one scalar.
- `admin/tf-options/fields/notice/INS_notice.php`
- `admin/tf-options/fields/number/INS_number.php`
- `admin/tf-options/fields/radio/INS_radio.php`
- `admin/tf-options/fields/repeater/INS_Repeater.php`
- `admin/tf-options/fields/select/INS_select.php`
- `admin/tf-options/fields/select2/INS_select2.php`
- `admin/tf-options/fields/switch/INS_switch.php`
- `admin/tf-options/fields/tab/INS_tab.php`
- `admin/tf-options/fields/text/INS_text.php`
- `admin/tf-options/fields/textarea/INS_textarea.php`
- `admin/tf-options/fields/time/INS_time.php`

All field-component changes concern contextual escaping of names, identifiers, values, labels, URLs, state, dependencies, or generated attribute fragments. The code-editor component additionally changed from CDN CodeMirror to `wp_enqueue_code_editor()`.

## New release and documentation files

- `.distignore` — WordPress.org artifact exclusion rules; obsolete Appsero-specific exclusions were removed after deleting the SDK.
- `docs/plugin-check-remediation.md` — plan, chronological work log, validation record, and remaining manual tests.
- `docs/release-packaging.md` — artifact composition and WordPress.org packaging rules.
- `docs/changed-files-record.md` — this complete inventory.

## New locally bundled dependency files

### Flatpickr 4.6.13

- `admin/tf-options/assets/libs/flatpickr/LICENSE.md`
- `admin/tf-options/assets/libs/flatpickr/flatpickr.min.css`
- `admin/tf-options/assets/libs/flatpickr/flatpickr.min.js`

### Font Awesome 4.7.0

- `admin/tf-options/assets/libs/font-awesome-4/LICENSE-AND-README.md`
- `admin/tf-options/assets/libs/font-awesome-4/css/font-awesome.min.css`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/FontAwesome.otf`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/fontawesome-webfont.eot`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/fontawesome-webfont.svg`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/fontawesome-webfont.ttf`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/fontawesome-webfont.woff`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/fontawesome-webfont.woff2`

### Font Awesome Free 5.15.4

- `admin/tf-options/assets/libs/font-awesome-5/LICENSE.txt`
- `admin/tf-options/assets/libs/font-awesome-5/css/all.min.css`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-brands-400.eot`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-brands-400.svg`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-brands-400.ttf`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-brands-400.woff`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-brands-400.woff2`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-regular-400.eot`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-regular-400.svg`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-regular-400.ttf`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-regular-400.woff`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-regular-400.woff2`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-solid-900.eot`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-solid-900.svg`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-solid-900.ttf`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-solid-900.woff`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-solid-900.woff2`

### Font Awesome Free 6.4.2

- `admin/tf-options/assets/libs/font-awesome-6/LICENSE.txt`
- `admin/tf-options/assets/libs/font-awesome-6/css/all.min.css`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-brands-400.ttf`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-brands-400.woff2`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-regular-400.ttf`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-regular-400.woff2`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-solid-900.ttf`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-solid-900.woff2`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-v4compatibility.ttf`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-v4compatibility.woff2`

### Remix Icon 2.5.0

- `admin/tf-options/assets/libs/remixicon/License`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.css`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.eot`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.less`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.svg`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.symbol.svg`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.ttf`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.woff`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.woff2`

### Select2 4.1.0-rc.0

- `admin/tf-options/assets/libs/select2/LICENSE.md`
- `admin/tf-options/assets/libs/select2/css/select2.min.css`
- `admin/tf-options/assets/libs/select2/js/select2.min.js`

### WP Color Picker Alpha

- `admin/tf-options/assets/libs/wp-color-picker-alpha/LICENSE`
- `admin/tf-options/assets/libs/wp-color-picker-alpha/SOURCE-COMMIT`
- `admin/tf-options/assets/libs/wp-color-picker-alpha/wp-color-picker-alpha.js`

## Highest-risk areas for the reported functionality loss

Review these areas first because their changes affect runtime behavior rather than only output formatting:

### WordPress.org reopening work

- `admin/tf-options/classes/Ins_TF_Settings.php` — now merges sanitized settings into the existing shared `wiopt` value. Associative groups preserve unsubmitted nested Pro keys, while numeric repeater lists replace as complete lists so deleted rows stay deleted.
- `admin/tf-options/classes/Ins_TF_Settings.php` — Checkout Editor lists now merge retained rows by stable origin, preserving Pro-only row metadata while still honoring Free reorder, deletion, and new rows.
- `admin/tf-options/options/tf-settings.php` — Free `ins-page-selected` now uses the same multiple-value array contract expected by the runtime and Pro schema, preventing an empty selector from saving the first page automatically.
- `admin/tf-options/options/tf-settings.php` — enabled five controls whose runtime belongs to Free: page exclusion, quick-view conflict handling, AJAX add-to-cart conflict handling, custom toggle-image upload, and JavaScript minification. IDs and storage shapes remain unchanged.
- `includes/controller/Assets.php` — Free now independently honors `js-min` when choosing its readable/minified frontend script while retaining the established URL filter for Pro compatibility.
- `admin/tf-options/assets/js/ins-options.js` — Checkout Editor additions and clones now receive unique legacy-compatible field keys; checkout-specific quota copy was removed.
- `includes/controller/checkout_editor.php` — custom billing/shipping fields are handled from validated configured keys, saved with the WooCommerce order object for HPOS compatibility, and displayed from order-object metadata.
- `admin/tf-options/options/tf-settings.php` — fully enabled Checkout Editor add/clone/delete, reset, and Order Notes controls in Free and removed its disabled activation notices.
- `admin/tf-options/options/tf-settings.php` — removed the remaining Pro-owned and obsolete controls from the Free schema without deleting their stored values; the corresponding WooInstant controls remain in Pro.
- `admin/tf-options/Ins_TF_Options.php` — removed the generic `is_pro` field-lock renderer while retaining licensed Pro schema loading and separate upcoming-field support.
- `admin/tf-options/assets/js/admin.js` and `admin/tf-options/assets/js/ins-options.js` — removed generic disabled-field and Pro-click redirect behavior after the Free schema reached zero active Pro locks.
- `docs/wordpress-org-reopening-plan.md` — complete reopening phases and regression gates.
- `docs/phase-0-baseline-record.md` — pre-remediation source, environment, option types/hash, static checks, and live Free smoke evidence.
- `docs/phase-1-reviewer-issue-ledger.md` — current status, ownership, resolution, and evidence requirement for every Plugins Team email category.
- `docs/phase-2-free-pro-separation-record.md` — locked-field ownership, approved Checkout Editor direction, preservation validation, and remaining phase gates.
- `docs/phase-3-browser-regression-record.md` — authenticated Free and licensed Pro browser evidence, reversible activation testing, and the settings round-trip drift stop gate.

The Phase 2 record now also contains the licensed Instantio Free + WooInstant Pro automated regression results. Credentials are intentionally excluded.

## Phase 5 technical and security remediation — 2026-08-10

- `instantio.php` — aligns the plugin header's `Tested up to` value with the valid WordPress 7.0 readme declaration.
- `includes/controller/Admin.php` — removes obsolete review-notice AJAX registrations whose callback is no longer active.
- `includes/controller/App.php` — verifies the existing public nonce on direct cart-reload AJAX requests while preserving internal fragment generation.
- `assets/app/js/instantio-script.js` — sends the established Instantio nonce with direct cart reloads.
- `assets/app/js/instantio-script.min.js` — rebuilt from the readable source so the distributed optimized path has the same security behavior.
- `admin/tf-options/classes/Ins_TF_Settings.php` — enforces `manage_options` and the settings nonce at the settings AJAX boundary while preserving the existing JSON envelope and `wiopt` merge/storage contract.
- `includes/controller/ins-checkout-editor.php` — removes an unused, unbalanced global output buffer.
- `docs/phase-5-technical-security-record.md` — records the audit, changes, negative tests, compatibility checks, exact option restoration, and Gate 5 evidence.

## Phase 6 Free/Pro integration hardening — 2026-08-10

### Instantio Free

- `includes/controller/Admin.php` — replaces dotted-version floating-point comparison with `version_compare()` and keeps normal Free admin initialization running for compatible Pro versions.
- `admin/tf-options/Ins_TF_Options.php` — uses a strict false license contract when deciding between the Free and licensed Pro settings schemas.
- `docs/phase-6-free-pro-integration-record.md` — records the compatibility matrix, ownership changes, activation-state tests, option/license restoration evidence, and Gate 6 result.

### WooInstant Pro

- `wooinstant.php` — adds explicit minimum Free compatibility, dependency-safe and WooCommerce-safe early returns, administrator dependency messaging, and one consistent license-controlled controller bootstrap.
- `includes/controller/Assets.php` — assigns unique Pro admin handles and resolves Free's Notyf asset through `INS_ADMIN_URL`.
- `includes/controller/App.php` — changes the fallback license state from permissive to unlicensed and uses strict comparison.
- `includes/controller/Functions.php` — removes the duplicate runtime `insopt()` declaration so Free owns the shared helper.
- `includes/license/license.php` — secures license-notice dismissal and corrects the activation-hook bootstrap path.
- `uninstall.php` — preserves shared `wiopt` configuration and removes only Pro-owned license/dismissal metadata.

## AJAX add-to-cart immediate refresh fix — 2026-08-10

- `assets/app/js/instantio-script.min.js` — regenerated from the current readable frontend source so optimized mode sends the established nonce and handles the current JSON response envelope. This fixes cart content and badge refresh immediately after AJAX add-to-cart.
- `includes/controller/Assets.php` — now adds the selected frontend script's modification time to its version, preventing a browser or proxy from retaining a stale optimized bundle. The existing option value and WooInstant URL filter contract remain unchanged.
- `docs/phase-3-browser-regression-record.md` — records the failing optimized-only reproduction, root cause, implementation, browser evidence, settings restoration, and syntax checks.
- `docs/changed-files-record.md` — records this fix and its affected files.

### Follow-up: WooCommerce Blocks event support

- `assets/app/js/instantio-script.js` — now refreshes Instantio after both the legacy WooCommerce jQuery cart event and the native WooCommerce Blocks cart event. A shared 100 ms debounce avoids duplicate requests and waits for Store API session synchronization.
- `assets/app/js/instantio-script.min.js` — rebuilt again from the corrected readable source so optimized mode includes WooCommerce Blocks compatibility.
- `docs/phase-3-browser-regression-record.md` — adds the exact real-button failing reproduction and passing readable/optimized browser evidence.

### Follow-up: variation-popup theme cart and animation

- `includes/controller/App.php` — Instantio's private add-to-cart response can now include standard filtered WooCommerce Mini-Cart fragments and ensures the cart cookie is available to theme and Blocks consumers.
- `assets/app/js/instantio-script.js` — publishes the standard WooCommerce post-add event after a successful Instantio popup add, avoids a redundant Instantio refresh, validates the JSON envelope, and obtains the selected variation locally for fly animation.
- `assets/app/js/instantio-script.min.js` — rebuilt from the corrected readable source.
- `docs/phase-3-browser-regression-record.md` — records the popup-specific cause, implementation, and real variation-selection browser evidence.

### Checkout Editor CRUD and HPOS checkout phase

- `admin/tf-options/classes/Ins_TF_Settings.php` — submitted Checkout Editor rows now clear known unchecked status/required checkbox keys instead of restoring their prior values. Unknown Free/Pro metadata remains origin-merged and preserved.
- `docs/phase-3-browser-regression-record.md` — records authenticated add/clone/delete/reorder/disable/reset evidence plus real checkout, custom metadata, order note, HPOS, restoration, and cleanup evidence.
- `docs/changed-files-record.md` — records the focused checkbox-merge correction and phase evidence.

### Final Phase 3 recommendations and mobile matrix

- `assets/app/js/instantio-script.js` — fly-cart animation now supports normal product cards and Instantio recommendation cards, with a safe geometry fallback.
- `assets/app/js/instantio-script.min.js` — rebuilt from the corrected readable source.
- `docs/phase-3-browser-regression-record.md` — records passing side-cart, popup-cart, upsell, cross-sell, recommendation add-to-cart, dedicated-mobile, quantity, checkout opening, removal, responsive visibility, and restoration evidence.

## Official Phase 3 GSAP replacement — 2026-08-10

- `includes/controller/Assets.php` — removed the GSAP frontend enqueue; the established `ins-script` handle and Free/Pro filter contract remain unchanged.
- `assets/app/js/gsap.min.js` — deleted the rejected third-party runtime.
- `assets/app/js/instantio-script.js` — replaced Free entrance and removal calls with one native Web Animations helper. Normal timing and displacement are preserved; reduced motion becomes a 150 ms opacity-only cue with no stagger.
- `assets/app/js/instantio-script.min.js` — rebuilt from the readable Free source.
- `../wooinstant/assets/app/js/instantio-script-pro.js` — moved Pro shipping, cart, and payment step entrances to the shared Free native helper without changing selectors or normal-motion timing.
- `../wooinstant/assets/app/js/instantio-script-pro.min.js` — rebuilt from the readable Pro source.
- `docs/phase-3-gsap-replacement-record.md` — records inventory, implementation, build commands, readable/optimized browser evidence, reduced-motion evidence, scans, and settings restoration.
- `docs/changed-files-record.md` — records the official Phase 3 files and purpose.

## Official Phase 4 external-services cleanup — 2026-08-10

- `instantio.php` — removed the remote promotion service bootstrap while retaining the local dashboard widget.
- `functions.php` — removed obsolete remote-image promotions, made clicked-link UTM source site-agnostic, and added cleanup for Instantio-specific legacy promotion data and cron state.
- `includes/controller/class-promo-notice.php` — deleted the daily Themefic promotion API client and remote-controlled promotion renderer.
- `includes/controller/dashboard-promo-notice.php` — replaced remote dynamic pricing with the existing local fallback; the dashboard UI and clicked pricing link remain.
- `admin/tf-options/assets/css/tf-options.css`, `admin/tf-options/assets/css/tf-options.min.css`, `admin/tf-options/assets/sass/tf-options.css`, and `admin/tf-options/assets/sass/tf-options.min.css` — removed Google Fonts loading and synchronized production CSS.
- `assets/admin/css/instantio-admin-style.css` — removed the obsolete remote-font import comment.
- `admin/tf-options/classes/Ins_TF_Settings.php` — stopped rendering remote product-card images and removed the related automatic plugin-management action from the active settings UI.
- `admin/tf-options/assets/js/ins-options.js` and `admin/tf-options/fields/map/INS_map.php` — removed the unused generic map field's OpenStreetMap/Nominatim service dependency.
- `docs/phase-4-external-services-record.md` — records classifications, removals, retained clicked links, Pro-owned services, and live/static evidence.
- `docs/changed-files-record.md` — records the official Phase 4 changes.

## Phase 7 full regression and compatibility — 2026-08-10

### Instantio Free

- `includes/controller/App.php` — cart triggers and panel controls now use named native semantics with synchronized expanded state and dialog metadata.
- `assets/app/js/instantio-script.js` — adds cart-dialog focus entry, Escape close, focus restoration, and keyboard focus containment while preserving existing cart events.
- `assets/app/js/instantio-script.min.js` — rebuilt from the readable source for optimized-mode parity.
- `assets/app/css/instantio-style.css` and `assets/app/css/instantio-style.min.css` — reset native close-button presentation and add visible keyboard focus indicators.
- `includes/templates/cart-modern.php` and `includes/templates/ins_single_step_cart.php` — add product-specific quantity-button names, coupon labels, and required translator comments.
- `docs/phase-7-regression-record.md` — records the complete product, cart, layout, checkout, HPOS, settings, legacy, accessibility, mobile, asset, theme, Plugin Check, cleanup, and restoration evidence.

### WooInstant Pro

- `../wooinstant/includes/templates/cart.php` — adds product-specific quantity-button names and coupon labels to the Pro cart template.
- `../wooinstant/includes/templates/form-checkout.php` — compares the shared layout option using its established string-compatible contract.
- `../wooinstant/includes/templates/content/ins-payment-popup.php` — allows the filtered WooCommerce order-button HTML through the appropriate safe HTML policy.
- `../wooinstant/assets/app/js/instantio-script-pro.js` and `../wooinstant/assets/app/js/instantio-script-pro.min.js` — retain readable/optimized parity for the tested checkout-step behavior.

## Phase 8 release packaging — 2026-08-10

- `instantio.php` and `readme.txt` — align WooCommerce tested-up-to metadata with the completed WooCommerce 11.0 regression.
- `readme.txt` — clarifies the fully functional Free scope, separates Pro add-on messaging, removes outdated field-editor claims, and records the compliance release work.
- `docs/phase-8-release-packaging-record.md` — records the distribution build, artifact inventory, hash, scans, Plugin Check result, and remaining clean-install gate.
- `docs/phase-8-clean-install-checklist.md` — provides the exact disposable-site installation and smoke sequence.
- `docs/wordpress-org-reviewer-response.md` — provides the copy-ready Plugins Team response and reviewer steps.

### WooInstant P3 integration follow-up

- `assets/app/js/instantio-script.js` and `assets/app/js/instantio-script.min.js` — Free's Pro order-review refresh now sends the existing `ins_ajax_nonce`, matching the secured WooInstant P3 endpoint without changing the action or response contract.
- The prior Phase 8 Free ZIP predates this integration change and is superseded; rebuild the matching Free/Pro artifact pair before clean-install testing.

### Settings-page warning cleanup

- `admin/tf-options/Ins_TF_Options.php` — removes the stale Free Pro-badge conditional left after Free-side Pro locks were removed, eliminating the repeated undefined `$is_pro` warning without changing `wiopt` storage or settings behavior.
- `docs/phase-8-release-packaging-record.md` — records the reproduction, root cause, scope, and regression evidence.

1. `admin/tf-options/Ins_TF_Options.php` and the newly bundled libraries — administrator dependency loading changed from CDNs to local files.
2. `admin/tf-options/fields/code_editor/INS_codeeditor.php` and `admin/tf-options/assets/js/ins-options.js` — CodeMirror loading changed to WordPress's native editor API.
3. `includes/controller/App.php` plus both `instantio-script` files — cart requests now require and send nonces.
4. `includes/controller/class-setup-wizard.php` — saving now requires a valid nonce and `manage_options` capability.
5. `includes/controller/checkout_editor.php` — checkout custom-field persistence and output paths changed.
6. `admin/tf-options/Ins_TF_Options.php` — font uploads now use the WordPress filesystem API.
7. Appsero no longer participates in Instantio runtime behavior because its bootstrap and complete SDK directory were removed.

## Important distinction

Plugin Check passing with zero findings confirms static policy compliance for the release artifact. It does not prove functional equivalence. Because functionality loss has now been observed, the next task should be a regression investigation comparing each high-risk diff against the original Git version and testing the affected feature in the browser before retaining or revising that change.

## Setup Wizard Finish response fix — 2026-08-11

- `admin/tf-options/assets/js/setup-wizard.js` — declares the WordPress AJAX response as JSON and consumes the object returned by jQuery directly. The previous callback passed an already-decoded object into `JSON.parse()`, causing `"[object Object]" is not valid JSON` and leaving the Step 3 Finish button loading even though PHP returned `wp_send_json()`.
- `admin/tf-options/Ins_TF_Options.php` — versions the setup-wizard script with its modification time so browsers immediately load this fix instead of retaining the previous `ver=2.3.0` response parser.
- No setup-wizard field name, `wiopt` key, value type, nonce, capability check, or PHP save behavior changed.
- PHP syntax and `git diff --check` pass. Browser verification remains pending because the Local site is currently stopped.

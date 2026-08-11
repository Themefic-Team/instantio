# Instantio WordPress.org Reopening Plan

Created: 2026-08-09  
Source review: WordPress.org Plugins Team email dated 2026-08-05  
Review ID: `GUIDELINES LIC-GPL-OTH instantio/themefic/5Aug26/T1 5Aug26/4.2A2`

## Objective

Produce a new Instantio Free release that:

1. satisfies the complete WordPress.org review request;
2. remains fully functional as a standalone Free plugin;
3. remains compatible with the separately distributed Instantio Pro (`wooinstant`) add-on;
4. preserves existing customer settings and database value shapes;
5. is verified from the exact release artifact before anything is uploaded to SVN.

This plan is intentionally phased. A phase may not proceed when its regression gate fails. Fix the regression inside the current phase or revert that phase's isolated change before continuing.

## Non-negotiable compatibility contracts

The following contracts must be preserved throughout remediation:

- Free remains the required base plugin and continues to expose global class `INSTANTIO`.
- Pro remains detectable through `WOOINS` and `INSTANTIO_PRO_VERSION`.
- The shared WordPress option remains `wiopt`.
- Existing option IDs are not renamed or deleted during normal upgrades.
- Scalar values remain scalar, especially:
  - `ins-layout-options`: `'1'`, `'2'`, or `'3'`
  - `ins-layout-mode`: values such as `'light'`, `'dark'`, `'glass-morphism'`, or `'gradient'`
  - `ins-layout`: values such as `'cart'`
- Nested values such as `ins-toggle-tab`, `ins-toggle-panel-tab`, and `empty-cart-content` remain arrays.
- The settings save endpoint returns clean native JSON and always clears the browser loading state.
- Free-to-Pro hooks, their argument counts, priorities, and output-buffer placement remain stable unless both plugins are updated and tested together.
- Existing stored Pro keys inside `wiopt` must survive saving the Free settings screen.
- Removing a control from Free's screen must not delete its stored value.

## Working rules

1. Do not edit the WordPress.org SVN copy directly. Work in the Git development repository and create a release artifact only after all gates pass.
2. Keep each phase in a separate, reviewable change set. Do not mix trialware separation, animation replacement, remote-call cleanup, and security fixes in one batch.
3. Before editing a setting or feature, search its option ID, hooks, CSS classes, AJAX actions, and JavaScript selectors in both plugins.
4. Never use broad automated escaping or renaming across templates. WooCommerce HTML filters and public hooks require context-sensitive handling.
5. Never debug with output-producing functions in a request path. Use the PHP/WordPress debug log.
6. Never test only the source directory. Final validation must run against the exact packaged ZIP contents.
7. Update `docs/changed-files-record.md` after each completed phase.
8. Do not increment the public version until the release-candidate phase.

## Required test fixtures

Prepare these before functional changes begin:

- A clean WordPress installation with `WP_DEBUG`, `WP_DEBUG_LOG`, and `SCRIPT_DEBUG` enabled.
- Current supported WooCommerce and at least one previous supported WooCommerce version.
- Simple, variable, grouped, downloadable/virtual, upsell, and cross-sell products.
- Coupons: fixed-cart, percentage, valid, expired, and usage-limited.
- Shipping zones with at least two methods and a product that requires shipping.
- Tax enabled and disabled scenarios.
- Guest checkout and logged-in customer accounts.
- At least one offline payment gateway and one real/test-mode JavaScript gateway.
- Desktop and mobile viewport coverage.
- Free-only installation.
- Free + Pro with valid license.
- Free + Pro with invalid/expired license.
- Pro active while Free is inactive.
- A copy of an existing site's `wiopt` value containing both Free and Pro keys.

## Phase 0 — Freeze and evidence baseline

### Purpose

Establish what currently works before changing compliance architecture. Without a baseline, a later functional loss cannot be attributed to a phase.

### Actions

1. Capture current Git status and preserve all existing user changes.
2. Record checksums or an archive of both plugin directories outside the release artifact.
3. Export the current `wiopt` option from the test site in PHP-serialized or JSON-safe form.
4. Record active plugin versions, WordPress version, WooCommerce version, PHP version, theme, gateway, and shipping configuration.
5. Capture screenshots/video of every current Free settings section and each Pro settings section.
6. Capture browser network responses for settings save and principal cart/checkout AJAX operations.
7. Record current frontend DOM classes for the three Free layouts and all Pro checkout layouts.
8. Run PHP syntax, JavaScript syntax, Plugin Check, and focused PHPCS/WPCS scans to create a new baseline.
9. Execute the Phase 0 smoke matrix below and record pass/fail results before edits.

### Phase 0 smoke matrix

Free only:

- Plugin activates with WooCommerce active.
- Graceful notice with WooCommerce inactive.
- Settings save and persist after reload.
- Layout options `1`, `2`, and `3` render correctly.
- Light, dark, glass-morphism, and gradient modes persist and render.
- Toggle position, icon, custom image, colors, sizes, and panel widths persist.
- Add simple/variable/grouped products.
- Update quantity, remove item, empty cart, apply/remove coupon.
- Cart and checkout buttons navigate correctly.
- Mini cart, quick view, mobile behavior, and checkout-field behavior work as currently advertised for Free.
- Shortcode output works without console errors.

Free + Pro:

- Pro schema loads when the license is valid.
- All Pro values save and survive reload.
- Cart-only, multistep, and single-step checkout layouts work.
- Billing/shipping fields, shipping method refresh, payment refresh, and order placement work.
- Cross-sells, upsells, dedicated mobile layout, minified assets, and custom styling work.
- Deactivating Pro returns to Free without corrupting `wiopt`.
- Reactivating Pro restores previously saved Pro settings.

### Gate 0

- Baseline evidence is stored under `docs` or a non-distributed test-evidence location.
- Known pre-existing failures are explicitly recorded.
- No remediation begins until critical baseline flows are reproducible.

## Phase 1 — Build the reviewer issue ledger

### Purpose

Translate every email item into a verifiable source and artifact task. The review team will inspect the entire plugin, not only cited lines.

### Actions

Create a ledger with one row per issue and these columns:

- Email category
- Cited file/line from submitted version
- Current source location
- Present in development tree?
- Present in release artifact?
- Required resolution
- Functional owner: Free, Pro, shared, or packaging
- Test case
- Evidence
- Status

Required categories:

1. Trialware and locked features.
2. GPL-incompatible GSAP distribution.
3. Appsero tracking/phoning home.
4. Composer manifest packaging.
5. Outdated/conflicting Appsero library.
6. Remote JavaScript/CSS/images.
7. Undocumented external services.
8. Direct core-file loading.
9. Incorrect path determination.
10. File upload API.
11. Shortcode/output escaping.
12. Unclosed output buffering.
13. Text-domain mismatches.
14. Dynamic translation strings.
15. Direct file access.
16. Short readme description.
17. Any new full-scan findings.

### Gate 1

- Every email finding is resolved, demonstrably already resolved, or documented as a possible false positive requiring a concise reviewer clarification.
- No finding may be closed only because Plugin Check is silent.

## Phase 2 — Separate Free and Pro functionality (Guideline 5)

### Purpose

Remove trialware architecture while preserving the functionality of both products.

### Required product rule

Every feature must be assigned to exactly one of these models:

- **Free feature:** all implementation and controls remain in Free and are fully enabled without Pro or a license.
- **Pro add-on feature:** implementation and controls live in `wooinstant`; Free contains only stable extension hooks and optionally a modest informational upsell.

Free may not bundle a disabled functional control waiting for Pro activation.

### Step 2.1 — Inventory every locked field

1. Extract every Free schema field with `is_pro`, `tf-field-pro`, disabled classes, upgrade-click handlers, feature limits, time/quantity limits, or license checks.
2. Map each field to its PHP consumer, JS consumer, CSS selector, template, AJAX endpoint, and Pro equivalent.
3. Classify it as Free or Pro add-on.
4. Record whether existing users may already have a stored value for it.

Known priority areas include:

- Dedicated Mobile Version.
- Checkout Editor and custom billing/shipping fields.
- Pro checkout layouts and progress modes.
- Upsells and cross-sells.
- Pro design controls.
- Any checkout-editor field count limit.

### Step 2.2 — Preserve the settings framework contract

1. Keep the active settings engine in Free.
2. Keep the save endpoint and `wiopt` option stable.
3. Ensure the Free save operation merges/preserves unknown Pro keys rather than rebuilding `wiopt` only from visible Free fields.
4. Keep scalar image-select normalization.
5. Remove generic Free behavior that disables bundled controls based on `is_pro` after the schema no longer needs it, but only after all field ownership has been migrated.

### Step 2.3 — Move Pro-only controls and implementation

For every Pro-classified feature:

1. Ensure its active settings definition exists in the Pro schema.
2. Move missing runtime PHP/JS/CSS/templates into Pro when the implementation currently exists only in Free.
3. Leave the minimal compatible action/filter in Free where Pro needs an insertion point.
4. Remove the disabled control and its implementation from the WordPress.org Free package.
5. Do not delete legacy option values on upgrade or Pro deactivation.
6. When Pro is activated again, read the same legacy option keys so customers regain their settings.

For Checkout Editor, move in small slices:

1. Field-definition/admin UI ownership.
2. Save/reset AJAX ownership.
3. Billing-field runtime filters.
4. Shipping-field runtime filters.
5. Order/admin metadata display.
6. JavaScript repeater behavior.

Run regression tests after every slice. Do not move the entire editor in one unverified edit.

### Step 2.4 — Keep compliant upgrade messaging

- A simple comparison table or link explaining that a separate add-on supplies additional features is acceptable.
- Do not render disabled copies of the real settings controls.
- Keep upgrade notices contextual, dismissible, and limited to Instantio screens.
- Remove code suggesting a payment or license unlocks functionality already present in Free.

### Gate 2A — Free-only regression

- Every advertised Free feature works without Pro.
- No disabled Pro settings controls remain.
- No local Free functionality depends on a license filter.
- Saving Free settings preserves hidden Pro keys.
- Settings types match the Phase 0 export.

### Gate 2B — Free + Pro regression

- Pro exposes all assigned Pro controls.
- Previously saved Pro values reappear.
- Checkout Editor, dedicated mobile, checkout layouts, upsells/cross-sells, and Pro design controls work.
- Pro deactivation cleanly falls back to Free.
- No duplicate function/class, AJAX action, script handle, or settings-section registration occurs.

## Phase 3 — Replace GSAP without visual regressions

### Purpose

Remove the explicitly rejected library while preserving visible interaction behavior.

### Actions

1. Inventory every `gsap` call in readable and minified Free JavaScript.
2. Record each animation's trigger, initial/final state, duration, easing, interruption behavior, and reduced-motion behavior.
3. Replace simple transitions with CSS transitions/keyframes.
4. Use the Web Animations API only where sequencing or cancellation requires JavaScript.
5. Add `prefers-reduced-motion` handling.
6. Remove the GSAP enqueue, file, source references, comments, and package exclusions.
7. Regenerate/synchronize the readable and minified production scripts using a documented build command.

### Gate 3

- Repository and artifact contain no GSAP file or GreenSock reference.
- Toggle/cart open-close, fly-to-cart, popup/side panel, and mobile animations match baseline behavior.
- Repeated rapid clicks do not leave panels stuck.
- Keyboard interaction and reduced-motion mode work.
- No new console errors or layout shifts appear.

## Phase 4 — Eliminate phoning home and unnecessary external dependencies

### Purpose

Make the Free plugin privacy-safe and self-contained.

### Actions

1. Confirm Appsero code, bootstrap, cron hooks, options, notices, and release files are absent.
2. Inventory every `wp_remote_get`, `wp_remote_post`, cURL/socket call, iframe, remote image, remote script/style/font, and dynamically constructed endpoint.
3. Remove Themefic promotional pricing API requests from Free unless they are part of an allowed, documented, opt-in service. Promotional content is not a necessary service and should normally be local or removed.
4. Replace remote promotional images with local assets or remove the promotion.
5. Keep ordinary clicked links to documentation/support/pricing separate from automatic remote calls.
6. Verify all runtime JS/CSS dependencies are local and GPL-compatible.
7. If any legitimate service remains, add an `External services` readme section containing purpose, exact data sent, timing/conditions, provider, Terms link, and Privacy link.
8. Ensure consent is explicit and off by default for any nonessential data transmission.

### Gate 4

- A clean activation and ordinary admin/frontend navigation sends no unsolicited request to Appsero, Themefic APIs, IP lookup services, or asset CDNs.
- Browser network evidence and a source scan agree.
- External hyperlinks remain functional but do not transmit data until clicked.
- Readme disclosures exactly match any remaining automatic service calls.

## Phase 5 — Complete technical and security remediation

### Purpose

Resolve the remaining email findings and full-review risks without changing business behavior.

### Work packages

### 5.1 Request security

- Inventory every public/admin AJAX action, REST route, form handler, and query-parameter action.
- Require nonce verification for state changes.
- Require the narrowest appropriate capability for administrator changes.
- Sanitize after `wp_unslash()` and validate against expected values.
- Keep public cart AJAX compatible with guest WooCommerce sessions.

### 5.2 Output escaping

- Trace shortcode output and escape every option-derived class, URL, attribute, and text value in its exact context.
- Preserve trusted WooCommerce HTML through narrow `wp_kses()` allowlists or documented trusted boundaries.
- Do not apply `esc_html()` to prices, quantity inputs, gateway markup, SVG, or hook-generated HTML that must remain structured.

### 5.3 Output buffers

- Pair every `ob_start()` with `ob_get_clean()`, `ob_end_clean()`, or `ob_end_flush()` in the same logical path.
- Remove the global buffer from `ins-checkout-editor.php` if it is not required.
- Test early returns and exceptions for balanced buffer levels.

### 5.4 Internationalization

- Use literal `instantio` translation strings.
- Remove incorrect `ultimate-addons-cf7` and `bafg` domains.
- Treat administrator-configured text as data: sanitize on input and escape on output; do not pass it into translation functions.
- Use placeholders and translator comments for dynamic literal messages.
- Do not translate WooCommerce-owned dynamic field labels with variable gettext calls.

### 5.5 Files and paths

- Add direct-access guards to executable PHP files.
- Use `require_once`/`include_once` for allowed WordPress admin files and call the required API immediately.
- Use plugin and upload directory APIs rather than hard-coded content paths.
- Use WordPress upload/filesystem APIs with MIME, extension, capability, and nonce validation.
- Verify behavior with a custom `WP_CONTENT_DIR`/plugin directory where practical.

### 5.6 Dependencies and licensing

- Produce a dependency/license inventory for every third-party PHP, JS, CSS, font, image, and SVG asset in the artifact.
- Include license texts and source/build information where required.
- Remove unused libraries and source artifacts from distribution.
- Confirm `composer.json` is present in the root of the exact release artifact.

### Gate 5

- All first-party PHP passes syntax validation.
- Readable and minified JS pass syntax validation.
- Focused WPCS/PHPCS security, escaping, and internationalization scans have no unresolved actionable errors.
- Plugin Check has zero errors and all warnings are reviewed, not ignored blindly.
- `WP_DEBUG` log remains clean through the complete functional matrix.

## Phase 6 — Free/Pro integration hardening

### Purpose

Repair fragile boundaries exposed during separation while avoiding unrelated redesign.

### Actions

1. Replace `(double)` version checks with `version_compare()` and define an explicit compatibility matrix.
2. Ensure Pro does not initialize functional frontend controllers without Free; it should show only a dependency notice in admin.
3. Make license gating intentional and consistent within Pro.
4. Give Pro-only admin assets unique handles or make Free the documented sole owner.
5. Replace Pro's hard-coded `/wp-content/plugins/instantio/...` URL with a Free-provided constant/API.
6. Fix Pro uninstall so it does not delete shared Free configuration.
7. Remove or quarantine the stale Pro settings framework only after confirming it is not loaded anywhere.
8. Keep the Free extension hooks stable and add documented deprecation paths for any unavoidable future change.

### Gate 6

Test these activation states independently:

| State | Expected result |
| --- | --- |
| Free only | Complete Free functionality, no Pro errors |
| Free + valid Pro | Complete Free and Pro functionality |
| Free + invalid/expired Pro | Defined product behavior, no partial/fatal initialization |
| Pro without Free | Admin dependency notice, no frontend fatal/error |
| Pro deactivated after use | Free works; Pro keys remain stored |
| Pro reactivated | Previous Pro settings return |
| WooCommerce inactive | Graceful admin notices; no frontend fatal |

All states must pass PHP log, browser console, settings-save, and relevant frontend checks.

## Phase 7 — Full regression and compatibility testing

### Functional matrix

### Settings

- Every visible field type saves and reloads: checkbox, switch, radio, image select, select/select2, number, text, textarea, color, file/image, repeater, code editor, date/time.
- Save response is valid JSON with no warning/debug prefix.
- Save button exits loading state on success and server error.
- Unknown Pro keys remain unchanged after a Free save.
- Old `wiopt` fixtures upgrade without data loss.

### Cart

- Simple, variable, grouped, virtual, and sold-individually products.
- AJAX and non-AJAX add to cart.
- Quantity update, remove item, empty cart.
- Valid/invalid coupon apply and removal.
- Taxes, shipping, subtotal, total, and fragments.
- Empty-cart content and return button.
- Quick view and cart shortcode.

### Layouts and design

- Direct Checkout, Side Cart, and Popup Cart.
- Every Free mode and animation replacement.
- Toggle positions, icons, uploaded icons, counters, fly animation, custom CSS, z-index, and responsive widths.
- Keyboard navigation, focus behavior, screen reader labels, and reduced motion.

### Checkout and Pro

- Multistep and single-step layouts.
- Billing/shipping field editor and custom fields.
- Shipping recalculation and address changes.
- Guest/login flow, account creation, order notes.
- Offline and JavaScript payment gateway.
- Validation errors and successful order placement.
- Cross-sells, upsells, dedicated mobile version, and minified/unminified asset modes.

### Compatibility

- Default Storefront theme plus one common block theme.
- Current and previous supported WordPress/WooCommerce versions.
- PHP 7.4 minimum and current supported PHP version.
- HPOS enabled and disabled where supported.
- Cart/Checkout blocks are either explicitly supported and tested or accurately documented as unsupported.

### Gate 7

- Every release-blocking test passes.
- Any unsupported configuration is truthfully documented rather than silently failing.
- No new functionality loss compared with Phase 0 except intentional removal of noncompliant locked Free UI or promotional/telemetry behavior.
- Pro retains all paid functionality assigned in Phase 2.

## Phase 8 — Exact artifact verification

### Purpose

Prove the uploaded package, not merely the development tree, is compliant.

### Actions

1. Increment plugin `Version` and readme `Stable tag` to the same new version.
2. Build a clean release directory using `.distignore`/documented packaging rules.
3. Confirm the ZIP contains root `composer.json`.
4. Confirm it excludes `.git`, internal docs, test configuration, source maps, Appsero, GSAP, obsolete SDKs, and development-only files.
5. Extract the ZIP into a clean WordPress site's plugin directory.
6. Activate and run Plugin Check against the extracted artifact.
7. Run PHP/JS syntax and prohibited-pattern scans against the extracted artifact.
8. Run the critical Free-only regression matrix against the extracted artifact.
9. Install Pro alongside that extracted Free artifact and run the critical Pro regression matrix.
10. Record the ZIP filename, size, SHA-256, file list, test environment, and results.

### Artifact prohibited-pattern scan

At minimum verify absence of:

- `appsero`
- `icanhazip`
- GSAP/GreenSock
- remote executable CDN URLs
- `var_dump`, `print_r`, and debug `die`/`exit` in request paths
- `move_uploaded_file`
- direct `wp-load.php`, `wp-config.php`, or `wp-blog-header.php` loading
- wrong text domains
- remaining Free `is_pro`/locked-control architecture

### Gate 8

- Exact extracted artifact passes every release gate.
- File inventory and checksum are recorded.
- No SVN upload occurs if source and artifact differ unexpectedly.

## Phase 9 — SVN release and reviewer response

### Actions

1. Update SVN `trunk` from the verified artifact only.
2. Create the matching version under `tags/`.
3. Verify trunk/readme Stable tag and plugin header version agree.
4. Review `svn diff` before committing.
5. Make one release-quality commit with a descriptive message.
6. Download or inspect the WordPress.org-generated package after processing and compare its contents/version.
7. Reply in the existing email thread, retaining the Review ID.
8. Keep the reply concise: confirm full review, trialware separation, GSAP removal, Appsero/remote-call removal, security/standards review, clean-site testing, and the new version/tag.
9. Do not send an enormous file-by-file changelog unless the reviewer requests it.

### Gate 9

- SVN release is visible and internally consistent.
- Reply has been sent to the Plugins Team.
- Reopening remains pending until the team confirms approval; local success is not WordPress.org approval.

## Recommended execution order and checkpoints

| Phase | Main outcome | Runtime risk | Mandatory checkpoint |
| --- | --- | --- | --- |
| 0 | Reproducible functional baseline | None | Evidence captured |
| 1 | Complete issue ledger | None | Every email item mapped |
| 2 | Guideline 5-compliant Free/Pro split | Very high | Free and Pro regression gates |
| 3 | GSAP removed | Medium/high | Visual and interaction parity |
| 4 | No unsolicited external calls | Medium | Network/source audit |
| 5 | Security and standards compliance | High | Static + functional tests |
| 6 | Hardened Free/Pro lifecycle | High | All activation states |
| 7 | Full system regression | None except test fixes | Complete matrix passes |
| 8 | Verified release ZIP | Low | Artifact-only validation |
| 9 | SVN and reviewer response | External release | Team approval pending |

## Definition of done

Development remediation is complete only when:

- every closure-email issue has evidence in the ledger;
- Free contains no bundled locked functionality;
- all advertised Free features work without payment, Pro, license, quota, or time restriction;
- all assigned Pro features work through the separately distributed Pro add-on;
- shared settings survive Free/Pro activation changes without type or value loss;
- GSAP and Appsero are absent from the artifact;
- ordinary use makes no prohibited external requests;
- the exact artifact passes Plugin Check, focused WPCS checks, syntax checks, clean `WP_DEBUG` testing, Free regression, and Free + Pro regression;
- versions, Stable tag, SVN trunk, and SVN tag agree;
- the Plugins Team has been emailed with the new release details.

The plugin is not considered reopened until WordPress.org completes its review and confirms restoration.

# Plugin Check Remediation Record

## Purpose

Track the review, remediation, and verification of findings exported from Plugin Check for Instantio.

## Source and baseline

- Source report: `instantio-instantio-php-20260808-093729.json`
- Report generated: 2026-08-08 09:37:29
- Plugin version at review start: 3.3.34
- Findings: 895 across 53 files
- Errors: 568
- Warnings: 327
- Worktree state at review start: clean

The exported report is evidence of the starting state. It will not be edited.

## Remediation principles

1. Preserve existing plugin behavior and public hooks wherever possible.
2. Prioritize exploitable security issues and WordPress.org blockers over style-only findings.
3. Sanitize input as it enters the plugin, validate expected values, and escape output for its exact HTML context.
4. Add nonce and capability checks to state-changing requests without breaking legitimate WooCommerce or WordPress flows.
5. Review naming warnings individually because renaming public hooks, functions, classes, or stored keys can be backward-incompatible.
6. Treat bundled third-party Appsero code and intentional external resources separately; do not blindly modify vendor-derived behavior.
7. Record false positives, deferred compatibility changes, and remaining external review gates explicitly.

## Planned phases

### Phase 1 - Inventory and triage

- Map every finding by rule, file, severity, and affected feature.
- Inspect WordPress/plugin metadata and detected distribution blockers.
- Classify findings as fix, compatibility-sensitive, third-party/bundled, or likely false positive.
- Establish available syntax, coding-standard, and Plugin Check validation commands.

### Phase 2 - Security and blocking errors

- Correct unsafe or unescaped output using context-appropriate escaping.
- Correct request validation, unslashing, and sanitization.
- Add or repair nonce and authorization checks where required.
- Add direct-file-access protection where safe.
- Address prohibited/development functions, unsafe redirects, and direct database access where applicable.

### Phase 3 - Internationalization and compatibility

- Correct text-domain mismatches and missing domains.
- Replace dynamic translation strings with translatable literal patterns where practical.
- Resolve WordPress-version compatibility findings or adjust declared requirements based on actual API usage.
- Add missing plugin/readme metadata where accurate.

### Phase 4 - Naming, resources, and distribution review

- Prefix private globals and internal hooks where compatibility permits.
- Document public or compatibility-sensitive identifiers that cannot safely be renamed in this pass.
- Review externally hosted resources, updater detection, hidden/application files, and update-modification findings.
- Separate code defects from WordPress.org policy/product decisions requiring owner approval.

### Phase 5 - Verification and final record

- Run PHP syntax checks on every changed PHP file.
- Run relevant coding-standard checks if the repository provides them.
- Re-run Plugin Check from WP-CLI when the local environment supports it.
- Compare final counts with the 895-finding baseline.
- Record changed files, checks run, results, deferrals, and remaining manual smoke tests below.

## Work log

### 2026-08-08 - Review initialized

- Located Instantio as a standalone Git repository inside the WordPress plugins directory.
- Confirmed the repository was clean before changes.
- Parsed the JSON export and established the baseline above.
- Created this plan and tracking record before changing plugin runtime code.

## Change record

### 2026-08-08 - Initial blocking-finding cleanup

- Added the existing GPLv3 license declaration to the main plugin header.
- Raised the declared minimum WordPress version from 4.0 to 4.8 in the plugin header and readme. This matches the oldest WordPress API requirement reported by Plugin Check and remains conservative relative to the plugin's PHP 7.4 requirement.
- Added direct-access guards to the four runtime PHP files reported without them.
- Removed leftover debug output and termination from `App::ins_options_init()` while retaining the public method for compatibility.
- Replaced the setup-wizard activation redirect with `wp_safe_redirect()`; the existing immediate `exit` remains in place.
- Hardened the public variable-product quick-view AJAX handler by validating, unslashing, and converting `product_id` to an integer; invalid requests now receive a JSON 400 response.
- Escaped the WooCommerce variation-script URL, removed unnecessary output buffering, and restored global post data after the query.
- Added nonce verification to cart-item removal, cart updates, empty-cart, and coupon-removal endpoints and added the already-localized nonce to their JavaScript requests.
- Unslashed, validated, and sanitized product IDs, variation IDs, grouped quantities, cart-item keys, quantities, and coupon codes used by public cart AJAX handlers.
- Kept the readable and distributed `instantio-script` JavaScript copies synchronized.
- Secured the setup-wizard save endpoint with WordPress AJAX nonce verification and a `manage_options` capability check, sanitized the complete request before updating options, and switched the response to `wp_send_json()`.
- Unslashed and sanitized the setup wizard's read-only `step` and `page` routing parameters; nonce warnings on these non-mutating reads are documented inline.
- Consolidated checkout custom-field persistence into an allowlisted field-key loop. Each value is unslashed and sanitized; the inline nonce annotation records that WooCommerce verifies its checkout nonce before firing the order-creation hook.
- Replaced PHP `parse_url()` with WordPress's consistent `wp_parse_url()` API.
- Replaced the settings font upload's forbidden direct `move_uploaded_file()` call with the WordPress filesystem API while retaining extension, MIME, and uploaded-file validation.
- Replaced the remaining settings URL parser with `wp_parse_url()` and replaced timezone-sensitive promotion `date()` calls with WordPress site-time calls.
- Unslashed nonce and field payloads for post metabox and taxonomy saves, retained field-class-specific sanitization, and added an `edit_term` capability check to taxonomy persistence.
- Hardened plugin-management and settings requests with key/text sanitization, normalized option nonces, validated uploaded-file array shapes, and sanitized file metadata before filesystem operations.
- Sanitized the settings page URL's server-derived host and request components.
- Removed the discouraged manual text-domain loader; WordPress automatically loads translations for WordPress.org-hosted plugins since WordPress 4.6.
- Documented the staged file-upload validation inline: raw arrays are shape-checked first, then each filename, temporary path, and MIME value is sanitized before use.
- Removed invalid translation wrappers from administrator-configured cart and checkout labels. Dynamic settings remain escaped at output, while literal fallback labels remain translatable.
- Replaced translated whitespace-only metabox titles with intentionally empty strings.
- Added translator context to formatted admin notices, changed unordered placeholders to numbered placeholders, and moved HTML markup outside a translatable version-warning string.
- Hardened public cart icon markup with escaped classes, URLs, and alternative text; escaped cart counts as integers and filtered generated button markup through `wp_kses_post()`.
- Documented trusted output-buffer boundaries where applying generic text escaping would break local templates or registered extension output.
- Removed an unnecessary `echo` around `do_action()` and documented the final local layout-template buffer boundary.
- Escaped checkbox/radio field names, option keys, dependency IDs, state fragments, and labels; strict comparison is now used for checkbox option selection. The shared pre-escaped attribute fragment is documented inline.
- Applied contextual attribute, URL, HTML, and textarea escaping across the small reusable input components, including text, number, file, textarea, date/time, switch, select, color, image, code editor, editor, notice, and tab fields.
- Escaped compound repeater, gallery, Select2, image-select, and icon component output, including stored labels, attachment URLs, generated classes, field identifiers, selection state, and pre-escaped attribute fragments.
- Escaped all setup-wizard visible translations, local asset URLs, and admin destination URLs; documented the three static inline SVG step indicators as trusted method-local markup.
- Escaped configured checkout-field labels and stored order metadata in WooCommerce admin output. Rebuilt billing/shipping reset notices as context-escaped templates with HTML outside translation strings.
- Escaped review-notice user data and labels, removed a leftover console log, escaped the AJAX endpoint, generated the WooCommerce activation URL with `wp_nonce_url()`, and allowlisted formatted admin notice markup.
- Escaped cart-template data attributes, formatted metadata/prices/subtotals and upsell HTML with `wp_kses_post()`, and documented WooCommerce-filtered thumbnail and quantity markup boundaries where generic KSES would remove required form/SVG elements.
- Converted settings-screen literal printing to escaped translation functions, retained the installation FAQ link through `wp_kses_post()`, corrected its `target` attribute typo, and removed redundant `echo` calls around printing translation functions.
- Escaped settings asset URLs, version/changelog data, section labels, helper/sidebar markup, customization copy, and the authorization failure message; removed an unnecessary echo around the top-header renderer.
- Cleared remaining first-party output findings in generic field wrappers, legacy promotion notices, layout 1, dashboard promotion assets, and active-order counts. Dependency attribute fragments now escape combined controller/parent values before their documented output boundary.
- Cleared the final bundled Appsero output findings in `License.php` and `Insights.php` by escaping URLs, attributes, JavaScript values, translated text, notices, license data, and deactivation-reason markup at their rendering boundaries. Licensing, telemetry, API, and updater behavior was not changed.
- Replaced all CDN-enqueued Font Awesome, Remix Icon, Select2, Flatpickr, and WP Color Picker Alpha files with versioned local runtime assets and their upstream license records.
- Replaced the dynamically CDN-loaded CodeMirror implementation with WordPress's native Code Editor API. The declared minimum WordPress version is now 4.9, matching the API requirement, and duplicate CodeMirror core files are not shipped.
- Added explicit footer placement to the affected administrator scripts.
- Replaced Appsero's direct posts-table count query with the cached `wp_count_posts()` API.
- Preserved established `ins_*`, `INS`, WooCommerce-template, and Appsero SDK public symbols through documented PHPCS compatibility boundaries instead of renaming extension APIs.
- Added `.distignore` and a separate release-packaging guide. The WordPress.org artifact excludes the Appsero updater, development configuration, source styles/maps, Git metadata, and internal docs without deleting them from the development repository.
- Shortened the readme short description to the WordPress.org 150-character limit and aligned the root Composer license declaration with GPLv3.
- Fixed a settings regression without changing the established option schema. General → Layout image cards again submit and store scalar values such as `"1"`; defensive sanitizer normalization collapses any unexpected array-shaped request to one sanitized scalar. Design → Cart Icon Style retains the same scalar behavior.
- Removed a runtime `var_dump()` from layout initialization that prefixed settings AJAX responses with PHP debug output. The options handler now uses `wp_send_json()`, and the JavaScript requests native JSON while always clearing the loading state on success or failure.
- Removed Appsero completely: deleted the administrator bootstrap, project identifier, telemetry notice and hooks, bundled Insights/License/Updater/Client SDK, nested tooling files, `appsero.json`, readme telemetry disclosure, and obsolete release exclusions.
- Post-removal verification found zero runtime Appsero references and no legacy Appsero options or cron event in the Local database. All 52 remaining first-party PHP files, focused WordPress checks, JavaScript syntax, and diff validation pass; the final live rerun is pending because Local MySQL stopped afterward.

## Validation record

### 2026-08-08 - Initial batch

- PHP 8.2.29 syntax checks passed for all seven changed PHP files using Local's bundled PHP runtime.
- `git diff --check` passed with no whitespace errors.
- Local WP-CLI 2.12.0 was found and works with the bundled PHP runtime.
- A live `wp plugin check instantio --format=json` rerun was attempted but could not start because the Local site's database was not running or reachable (`Error establishing a database connection`). This is an environment gate, not a passing or failing Plugin Check result.
- Plugin Check's bundled PHPCS ruleset reports 0 errors and 0 warnings for `includes/controller/App.php` after the public-cart security batch.
- The same ruleset reports 0 errors and 0 warnings for `functions.php` and `includes/controller/checkout_editor.php` after their security/API cleanup.
- A full static scan of the plugin (excluding `vendor`) now reports 0 errors and 38 warnings, down from 6 errors and 38 warnings immediately before the filesystem/date fixes. Seven warnings are tokenizer notices from minified assets; the remaining warnings are queued for focused review.
- After the admin-input batch, the bundled Plugin Check ruleset reports 0 errors and only 7 tokenizer warnings, all from minified CSS/JavaScript assets.
- The targeted WordPress internationalization ruleset now reports 0 errors and 0 warnings, down from 46 errors and 1 warning at the start of the phase.
- The targeted output-escaping baseline is now 412 errors (264 unescaped-output and 148 unsafe-printing findings), down from 426 before the public cart-controller batch. `includes/controller/App.php` itself now reports 0 escaping findings.
- After remediating all reusable admin field components, the targeted output-escaping count is 316 errors, down from 412. The complete changed PHP set passes syntax checks, both distributed JavaScript copies pass `node --check`, and `git diff --check` passes.
- After the setup wizard, checkout editor, admin controller, cart templates, and settings renderer batches, the combined escaping/internationalization count is 47 errors and 0 warnings. This is down from 464 combined findings at the start of those targeted audits; 34 of the remaining 47 are in bundled Appsero source files.
- After the Appsero rendering-boundary batch, the full targeted WordPress output-escaping and internationalization audit reports **0 errors and 0 warnings**, down from the 464-finding targeted baseline.
- PHP 8.2.29 syntax validation passes for every PHP file in the plugin (excluding `vendor`). Both distributed `instantio-script` JavaScript files pass `node --check`, and `git diff --check` passes.
- Plugin Check's complete bundled PHPCS ruleset reports **0 errors and 7 warnings**. All seven warnings are tokenizer limitations on already-minified third-party or generated CSS/JavaScript assets; no actionable PHP finding remains in that static ruleset.
- The final WordPress.org release artifact passed the live `wp plugin check` command with **0 errors and 0 warnings** using the `instantio` slug override.
- The final artifact is `instantio-wordpress-org-20260808.zip`, is 5.8 MB on disk, and has SHA-256 `3f1ced49982982905ba2f6ad9038495cffad02e7822475b43697b265560e0af4`.
- Artifact inspection confirmed the Appsero updater, hidden development files, PHPCS configuration, SCSS/Sass sources, and source maps are absent. Bundled runtime dependencies and the root Composer manifest are present.
- The active Local site returned HTTP 200, WooCommerce and Instantio loaded, seven Instantio frontend CSS/JavaScript references were present, cart/coupon AJAX hooks and the checkout filter were registered, and a cart-empty request without a nonce was rejected with HTTP 403 and body `-1`.
- Final PHP 8.2.29 syntax checks passed for all 57 first-party PHP files, the changed JavaScript files pass `node --check`, local font references resolve, and `git diff --check` passes.

## Remaining manual verification

- Complete authenticated browser smoke tests for settings save and font upload, setup wizard navigation, icon/date/color/code editor fields, license activation/deactivation, Appsero opt-in/deactivation UI, real product quick view, add/update/remove/empty cart, coupon removal, and checkout submission.
- These flows require interactive administrator/customer sessions and suitable product/license fixtures. The non-mutating CLI and HTTP smoke checks above confirm plugin loading, hook registration, asset rendering, and nonce rejection but do not substitute for those UI workflows.

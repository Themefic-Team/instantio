# Instantio WordPress.org T3 Remediation Plan

Created: 2026-09-02  
Review ID: `SVN instantio/themefic/5Aug26/T3 2Sep26/4.3A2 (P0TDX135539HGN)`  
Source: WordPress Plugins Team email received 2026-09-02  
Target release: `3.3.36`  
Status: Phases 1-3 implemented; automated and runtime verification in progress

## Objective

Prepare a new Instantio Free release that resolves every finding in the T3 review, fixes all equivalent occurrences across the package, preserves existing Free and Pro settings, passes clean-install and upgrade regression, and is uploaded to WordPress.org SVN only after the exact release artifact passes the final gate.

The T3 email is authoritative for this remediation. Previous phase records were removed because they describe superseded artifacts and review findings. Their Git history remains recoverable.

## Rollback and recovery policy

All remediation work must remain reversible until the final release is approved.

- Record the pre-remediation Git branch and commit before changing runtime code.
- Keep each remediation phase in a separate, reviewable commit. Do not mix metadata, sanitization, prefix migration, runtime regression, and packaging changes in one commit.
- Tag or branch the verified pre-remediation state before the first runtime-code commit when repository permissions allow it.
- Never use destructive recovery commands such as `git reset --hard` or overwrite the working tree while unrelated user changes exist.
- If a phase causes a regression, revert that phase's commit or restore only its explicitly listed files from the recorded baseline after confirming the target diff.
- The deleted historical documents remain recoverable from the pre-remediation commit; they must not be reconstructed from memory.
- Database tests must export and hash `wiopt` before mutation. Every test that changes `wiopt`, active plugins, WooCommerce settings, products, coupons, users, orders, or themes must snapshot the original value and restore it in a cleanup step.
- Test credentials, SMTP data, license keys, cookies, nonces, and customer information must never be written to repository evidence.
- Before any rollback, capture `git status --short`, the current diff, and the exact failing test so valid later work is not accidentally discarded.

Safe recovery references (inspection first; do not run a destructive reset):

- Free baseline tree: `backup/instantio-pre-t3-2026-09-02` at `24d53b1f915a6c9eec0e6d0ee5e74e261d44f7f5`.
- Pro baseline tree: `backup/wooinstant-pre-t3-2026-09-02` at `11f64ec2080f26676e73f670093dc7e9a33204eb`.
- Review the old version with `git diff backup/instantio-pre-t3-2026-09-02 -- <file>` before restoring any file.
- Restore through a reviewed revert commit or a narrowly scoped patch. Preserve the pre-existing Pro change in `includes/controller/Admin.php`.

Pre-remediation baseline record fields:

- Git branch: `staging`
- Git commit: `24d53b1f915a6c9eec0e6d0ee5e74e261d44f7f5`
- Recovery branch: `backup/instantio-pre-t3-2026-09-02`
- Pro Git branch/commit: `staging` / `11f64ec2080f26676e73f670093dc7e9a33204eb`
- Pro recovery branch: `backup/wooinstant-pre-t3-2026-09-02`
- Working-tree status: documentation replacement in progress; Pro had a pre-existing modification in `includes/controller/Admin.php`
- Free version: `3.3.35`
- Pro version: `3.2.11`
- WordPress/WooCommerce/PHP versions: `7.0.3` / `11.0.1` / `8.2.29`
- Theme/HPOS: Twenty Twenty-Five / enabled
- `wiopt` hash: `e1fda0c845c17e11de41f0610e4a2557853d46fa8c0994964379c5edca5a4589`
- Baseline Plugin Check: completed against the development directory; only `.distignore` and `.gitignore` hidden-file warnings were reported
- Baseline runtime note: Local was halted; its MySQL 8.4.0 service was started from the generated Local configuration before collecting database-backed evidence
- Baseline validation still pending: authenticated browser save/reload capture for Free-only and Free + Pro

## Findings from the Plugins Team

### T3-01 — Declare `Tested up to` only in `readme.txt`

Reviewer evidence:

- `instantio.php` declares `Tested up to: 7.1`.
- `readme.txt` declares `Tested up to: 7.1`.

Required result:

- Remove `Tested up to` from the main plugin PHP header.
- Keep the value only in `readme.txt`.
- Keep `Version:` in `instantio.php` and `Stable tag:` in `readme.txt` synchronized at `3.3.36` for the new SVN release.

### T3-02 — Sanitize, validate, and escape all request data

Reviewer examples:

- `admin/tf-options/classes/Ins_TF_Settings.php`: raw option request values reach a dynamically selected field class before persistence.
- `admin/tf-options/classes/INS_Metabox.php`: raw metabox request values reach a dynamically selected field class before persistence.
- `admin/tf-options/classes/TF_Taxonomy_Metabox.php`: raw taxonomy request values reach a dynamically selected field class before persistence.

Current source assessment:

- Nonce and capability checks exist in all three paths.
- The active settings path delegates sanitization to field classes, but `INS_Repeater`, `INS_tab`, `INS_color`, and `INS_fieldset` currently return their values unchanged.
- Field class names are constructed dynamically from schema data without an explicit field-type allowlist.
- The metabox configuration contains only an empty `tf_post_opt` definition for a non-used `tf_post-meta` post type.
- No Instantio taxonomy registers or calls `TF_Taxonomy_Metabox::taxonomy()`.
- The metabox save path constructs `TF_*` field classes while the included field implementations are `INS_*`, confirming the subsystem is stale and nonfunctional.

Required result:

1. Remove the unused metabox and taxonomy subsystems rather than retain unnecessary request handlers:
   - remove their loader calls;
   - remove `INS_Metabox.php`;
   - remove `TF_Taxonomy_Metabox.php`;
   - remove the empty `metaboxes/tf-metabox.php` definition;
   - confirm no Free or Pro runtime reference remains.
2. Harden the active `wiopt` settings save path:
   - require the submitted option payload to be an array;
   - derive accepted keys only from the registered settings schema;
   - validate field types against an explicit allowlist before constructing a class name;
   - sanitize every submitted scalar or nested value before serialization and before field object construction;
   - use context-appropriate sanitizers for URLs, HTML/editor content, CSS/textareas, numeric values, booleans, select choices, colors, and nested compound fields;
   - reject invalid shapes and values instead of coercing arbitrary input;
   - replace no-op field sanitizers with type-aware implementations;
   - keep escaping at output sites rather than storing escaped values.
3. Preserve compatibility:
   - retain option ID `wiopt`;
   - retain all existing option keys;
   - preserve established scalar, associative-array, numeric-list, and serialized compound value shapes;
   - preserve unknown Pro-owned keys during Free saves;
   - preserve checkout repeater origin merging and checkbox clearing behavior.
4. Audit every remaining `$_POST`, `$_GET`, `$_REQUEST`, and `$_FILES` occurrence, not only the three cited examples.

Security acceptance tests:

- forged option keys are ignored;
- unknown field types are rejected and never instantiated;
- scalar input cannot be stored where an array is required, and arrays cannot reach scalar sanitizers;
- script tags, event handlers, malformed URLs, invalid select values, invalid numbers, and unexpected nested keys are rejected or sanitized appropriately;
- valid custom CSS, editor content, colors, image URLs, checkout repeaters, tabs, and fieldsets retain their intended formats;
- nonce and capability failures do not write options or metadata;
- saved values render without stored-XSS or PHP warnings.

### T3-03 — Replace insufficiently prefixed identifiers

Reviewer examples:

- `TF_OPTION_ID`
- `TF_Setup_Wizard`
- `TF_Taxonomy_Metabox`
- AJAX action `tf_setup_wizard_submit`

Required result:

- Rename `TF_OPTION_ID` to `INSTANTIO_OPTION_ID` in Free and every active Pro integration reference.
- Rename `TF_Setup_Wizard` to `Instantio_Setup_Wizard`.
- Rename the setup wizard callback methods from `tf_*` to `instantio_*` for consistency.
- Rename AJAX action `tf_setup_wizard_submit` to `instantio_setup_wizard_submit` in PHP and JavaScript.
- Remove `TF_Taxonomy_Metabox` with the unused taxonomy subsystem described in T3-02.
- Scan all global functions, classes, constants, hooks, AJAX actions, shortcodes, globals, and option names for prefixes shorter than four characters or unrelated prefixes.
- Preserve established public `ins_*`/`instantio_*` hooks and `wiopt` storage where changing them would break installed Free/Pro integrations; document any compatibility exception precisely instead of using a blanket PHPCS suppression.

Compatibility acceptance tests:

- the setup wizard loads and submits with the renamed AJAX action;
- no request still targets `tf_setup_wizard_submit`;
- no runtime reference to `TF_OPTION_ID`, `TF_Setup_Wizard`, or `TF_Taxonomy_Metabox` remains in the Free artifact;
- licensed Free + Pro settings pages load without undefined constants or missing hooks;
- Free and Pro save/reload the same existing `wiopt` fixture without losing hidden keys.

## Implementation sequence

### Phase 0 — Freeze and baseline

1. Confirm clean Git state or record every pre-existing user change.
2. Export the current `wiopt` value and hash it without storing credentials or license values in the repository.
3. Record active WordPress, WooCommerce, PHP, Free, and Pro versions.
4. Capture a Free-only and Free + Pro settings save/reload baseline.
5. Run Plugin Check and focused PHPCS/WPCS to establish the pre-fix finding set.

Gate: baseline evidence exists and current settings behavior is reproducible.

### Phase 1 — Metadata and dead-code removal

Status: implemented and syntax/runtime checked.

1. Remove `Tested up to` from `instantio.php`.
2. Remove the unused metabox/taxonomy loaders, handlers, and empty definition.
3. Confirm the package has no runtime references to the removed files or classes.

Gate: plugin activates and settings load in Free-only and Free + Pro modes.

### Phase 2 — Settings input boundary

Status: implemented; focused malicious-input and isolated save tests passed. Full browser matrix remains pending.

1. Add a single schema-aware input sanitizer/validator used before dynamic field construction.
2. Add an explicit supported field-type-to-class map; do not construct arbitrary class names from unchecked values.
3. Implement type-appropriate scalar and recursive compound sanitization.
4. Replace no-op `sanitize()` implementations.
5. Preserve existing `wiopt` merge and repeater behavior.
6. Add focused malicious-input and value-shape regression tests.

Gate: all T3-02 acceptance tests pass and the baseline `wiopt` fixture round-trips without shape changes or Pro-key loss.

### Phase 3 — Prefix migration

Status: implemented in Free and the active Pro constant consumer; runtime identifier checks and Free/Pro settings renders passed.

1. Rename the cited constant, setup wizard class, methods, and AJAX action.
2. Update setup wizard JavaScript and localized action references.
3. Update the separate Pro plugin wherever it consumes the renamed constant or wizard integration.
4. Run a package-wide identifier inventory and fix equivalent unprefixed definitions.
5. Remove or narrow naming-standard suppressions that could hide new collisions.

Gate: no cited generic identifier remains, wizard submission passes, and Free + Pro integration passes.

### Phase 4 — Full code review and automated checks

1. Audit all request superglobals, dynamic class calls, serialization, database writes, and output sites.
2. Run PHP syntax checks on every packaged PHP file.
3. Run JavaScript syntax checks on readable and minified project scripts.
4. Run `git diff --check`.
5. Run Plugin Check against an isolated staged package.
6. Run focused PHPCS with WordPress, WordPress-Extra, WordPress-Docs, and relevant security rules.
7. Manually classify false positives; do not silence actionable findings.

Gate: zero actionable Plugin Check or focused PHPCS findings.

### Phase 5 — Runtime regression

Free only:

- clean install with no existing `wiopt`;
- activation with WooCommerce active and graceful behavior without WooCommerce;
- setup wizard rendering and AJAX submission;
- settings save/reload across Layout, Design, Cart Icon, Optimization, and Checkout Editor;
- checkout fields add, clone, reorder, enable/disable, delete, reset, order-save, and HPOS display;
- Direct Checkout, Side Cart, Popup Cart, quick view, AJAX add-to-cart, quantities, coupons, and item removal;
- custom checkout slug, checkout endpoints, and cart page do not render the Instantio overlay;
- `WP_DEBUG` and browser console remain clean.

Free + Pro:

- valid-license Pro schema and wizard controls load;
- Pro settings save and reload;
- Free saves preserve Pro-owned keys;
- cart-and-checkout, progress styles, upsells, cross-sells, and dedicated mobile behavior work;
- Pro deactivation returns to Free without data loss; reactivation restores values.

Gate: clean-install, upgrade, Free-only, and Free + Pro matrices pass with no warnings, fatals, console errors, or unexpected outbound requests.

### Phase 6 — Release artifact and SVN

1. Set `Version:` and `Stable tag:` to `3.3.36` only after all prior gates pass.
2. Build from an isolated `.distignore`-filtered stage.
3. Confirm one top-level `instantio/` directory and no Git metadata, internal docs, Pro code, source maps, Sass sources, or lock files.
4. Compare staged and extracted ZIP manifests byte-for-byte.
5. Re-run Plugin Check, PHP syntax, JavaScript syntax, prohibited-pattern scans, and clean-install smoke against the exact ZIP.
6. Record ZIP path, SHA-256, size, file count, and validation results in a new release evidence file created at that time.
7. Commit the corrected code to SVN `trunk/`, create `tags/3.3.36/`, and confirm the public Stable Tag resolves to the new tag.
8. Reply concisely to the Plugins Team with the new version and any necessary clarification; do not claim checks that were not run.

Gate: exact artifact passes and SVN trunk/tag contents match it.

## Stop conditions

- Do not upload or reply while any cited identifier or unsafe request path remains.
- Do not apply blanket `sanitize_text_field()` to all settings if it damages valid HTML, CSS, arrays, or serialized value shapes.
- Do not rename `wiopt` or established public integration hooks without an explicit migration and synchronized Pro release.
- Do not treat Plugin Check silence as proof of security or runtime correctness.
- Do not call the release ready based only on source lint or a development-site test.

## Definition of done

- All T3 findings and equivalent occurrences are resolved.
- Free and Pro compatibility contracts are preserved and verified.
- Clean-install and upgrade tests pass with `WP_DEBUG` enabled.
- The exact `3.3.36` ZIP passes Plugin Check and focused PHPCS/WPCS.
- SVN `trunk/` and `tags/3.3.36/` contain the verified artifact.
- The Plugins Team response is sent only after the upload and final evidence review.

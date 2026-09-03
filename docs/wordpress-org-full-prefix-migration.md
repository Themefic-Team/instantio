# Instantio Full Prefix Migration

Updated: 2026-09-03  
Target version: `3.3.37`
Status: compatibility follow-up implementation and packaged validation complete; the previous 3.3.36 package is superseded

## Purpose

Resolve the Plugins Team requirement that globally accessible plugin identifiers use a unique prefix of at least four characters. Instantio now uses `instantio_`, `INSTANTIO_`, `Instantio_`, or `Themefic\Instantio` for its public PHP and JavaScript identifiers.

Class method names that still start with `ins_` or `tf_` are intentionally unchanged. They are scoped inside uniquely prefixed or namespaced classes and the Plugins Team explicitly permits short names inside classes. Renaming those methods would add callback risk without improving global collision safety.

## Recovery points

- Free branch: `backup/instantio-pre-full-prefix-2026-09-03`
- Free commit: `57066d6f4dd20981bec425f6f005e535e93fb80c`
- Pro branch: `backup/wooinstant-pre-full-prefix-2026-09-03`
- Pro commit: `3f8c93ccbedc63ba8057245b1288e9161371f391`
- Pre-compatibility Free branch: `backup/instantio-pre-pro-compat-2026-09-03`
- Pre-compatibility Free commit: `7c2994e32f50b7fc534e12d62fb7f4d888d18715`
- Pre-compatibility Pro branch: `backup/wooinstant-pre-pro-compat-2026-09-03`
- Pre-compatibility Pro commit: `0d4b32702e60ec9c46b3481838d2ea2400afdb71`

Before any rollback, inspect `git status --short` and the relevant diff. Restore through a reviewed revert commit or a narrow patch. Do not use a destructive reset.

## Migrated contracts

- Free constants use `INSTANTIO_` instead of `INS_`.
- Free controller namespace uses `Themefic\Instantio\Controller` instead of `INS\Controller`; Composer runtime maps were updated with the source definition.
- Settings classes use `Instantio_Options` and `Instantio_Settings`.
- Global helper and checkout-editor functions use `instantio_`.
- Free and Pro shared hooks use `instantio_`.
- AJAX actions, nonces, localized JavaScript objects, script handles, style handles, and the shared animation global use `instantio` names.
- The dashboard plugin-management request now has a matching authenticated PHP AJAX registration.
- Dead remote-promotion JavaScript, localization, and cleanup code was removed.

## Compatibility boundaries

The migration does not rename the `wiopt` WordPress option or any saved keys inside it, including legacy keys beginning with `ins-`, `wi-`, or `woins-`. Those strings are stored-data contracts, not globally declared PHP identifiers. Renaming them would discard existing merchant configuration.

CSS classes, element IDs, template filenames, response payload keys, and class-scoped method names are also retained where changing them would create needless storefront or extension breakage.

Instantio Pro was updated in the same workspace for every shared Free/Pro hook and JavaScript contract. Free-only and Free-plus-Pro boot checks must both pass before release.

### Legacy Pro upgrade safety

Instantio Pro 3.2.11 and earlier call the removed `insopt()` global helper. Restoring that helper in the WordPress.org package would restore the exact short-prefix violation this migration resolves. Instead:

- Instantio Free 3.3.37 detects Pro versions older than 3.2.12 during its priority-0 `init` callback.
- It removes only the legacy Pro `WOOINS::wooinstant()` priority-10 bootstrap callback before Pro can load code that calls `insopt()`.
- The add-on remains installed and active, but its runtime is paused and administrators see an update notice; no settings are deleted.
- Instantio Pro 3.2.12 requires Free 3.3.37 and explicitly checks for `instantio_get_option()` before loading its runtime.

Release Pro 3.2.12 through the private updater before publishing Free 3.3.37 on WordPress.org. The Free guard remains necessary for sites that receive the Free update first or have disabled private Pro updates.

## Validation record

- Free PHP syntax: passed on PHP 8.2.29.
- Pro PHP syntax: passed on PHP 8.2.29.
- Free and Pro JavaScript syntax: passed with Node.js.
- Composer JSON: valid.
- Composer autoload smoke test: all three Free controller classes resolved.
- Plugin Review PHPCS ruleset: passed with no findings.
- Plugin Check against the development directory: only `.distignore` and `.gitignore` package-hygiene warnings remain; no prefix warning remains. These files are excluded from the distributable package.
- Plugin Check against the exact staged package under the canonical `instantio` slug: `Success: Checks complete. No errors found.`
- Free runtime boot: required functions and classes resolved.
- Free plus Pro runtime boot: license, layout, wizard-injection, and checkout AJAX hooks resolved under their new names. Pro was restored to its pre-test inactive state.
- Free 3.3.37 plus a legacy Pro 3.2.11 fixture: WordPress booted without a fatal error and the legacy `WOOINS::wooinstant()` callback did not run.
- An older Free version signal plus Pro 3.2.12: Pro remained installed but did not load its controllers.
- Free 3.3.37 plus Pro 3.2.12: both booted and the Pro Assets controller loaded.
- Free settings rendered `159,989` bytes with no missing-field fallback.
- Free plus Pro settings rendered `203,667` bytes with no missing-field fallback. The CLI-only render emitted the existing WordPress admin-menu-context warning from `wp-admin/includes/plugin.php`; no Instantio file emitted a warning.

## Current packages

- Free ZIP: `/tmp/instantio-3.3.37-xoJrMT/instantio-3.3.37.zip`
- Free SHA-256: `0654d9d332d0970d4d54634d5d24828ad280cfdb30f723ed1fd258301d3c4702`
- Free size: `6,002,659` bytes
- Free file count: `204`
- Exact staged Free package Plugin Check: `Success: Checks complete. No errors found.`
- Pro ZIP: `/tmp/wooinstant-3.2.12-Qyrk0r/wooinstant-3.2.12.zip`
- Pro SHA-256: `df7000be9b0a80eafc7bcaac1bbf6c50f3a22314a84b7556beb3c40f96dbe01b`
- Pro size: `783,706` bytes
- Pro file count: `108`

## Superseded package

- ZIP: `/tmp/instantio-full-prefix-GRK682/instantio-3.3.36-full-prefix.zip`
- SHA-256: `092d4fe6b95dcca150fb8ad17ca31e843bdbbf55e8e0a93678c4633743f59c4a`
- Size: `6,001,697` bytes
- File count: `204`

Any subsequent source change included in the distributable package invalidates this result and requires a rebuild.

The compatibility changes and version bumps described above invalidate this 3.3.36 ZIP. Use the validated 3.3.37 package recorded above.

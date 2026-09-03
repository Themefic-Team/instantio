# Instantio Full Prefix Migration

Updated: 2026-09-03  
Target version: `3.3.36`  
Status: implementation and packaged validation complete

## Purpose

Resolve the Plugins Team requirement that globally accessible plugin identifiers use a unique prefix of at least four characters. Instantio now uses `instantio_`, `INSTANTIO_`, `Instantio_`, or `Themefic\Instantio` for its public PHP and JavaScript identifiers.

Class method names that still start with `ins_` or `tf_` are intentionally unchanged. They are scoped inside uniquely prefixed or namespaced classes and the Plugins Team explicitly permits short names inside classes. Renaming those methods would add callback risk without improving global collision safety.

## Recovery points

- Free branch: `backup/instantio-pre-full-prefix-2026-09-03`
- Free commit: `57066d6f4dd20981bec425f6f005e535e93fb80c`
- Pro branch: `backup/wooinstant-pre-full-prefix-2026-09-03`
- Pro commit: `3f8c93ccbedc63ba8057245b1288e9161371f391`

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
- Free settings rendered `159,989` bytes with no missing-field fallback.
- Free plus Pro settings rendered `203,667` bytes with no missing-field fallback. The CLI-only render emitted the existing WordPress admin-menu-context warning from `wp-admin/includes/plugin.php`; no Instantio file emitted a warning.

## Current package

- ZIP: `/tmp/instantio-full-prefix-GRK682/instantio-3.3.36-full-prefix.zip`
- SHA-256: `092d4fe6b95dcca150fb8ad17ca31e843bdbbf55e8e0a93678c4633743f59c4a`
- Size: `6,001,697` bytes
- File count: `204`

Any subsequent source change included in the distributable package invalidates this result and requires a rebuild.

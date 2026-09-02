# Instantio WordPress.org T3 Validation Record

Updated: 2026-09-02  
Target version: `3.3.36`  
Review ID: `SVN instantio/themefic/5Aug26/T3 2Sep26/4.3A2 (P0TDX135539HGN)`  
Release status: candidate validated locally; SVN upload and authenticated browser matrix are not complete

## Recovery baseline

- Free recovery branch: `backup/instantio-pre-t3-2026-09-02`
- Free recovery commit: `24d53b1f915a6c9eec0e6d0ee5e74e261d44f7f5`
- Pro recovery branch: `backup/wooinstant-pre-t3-2026-09-02`
- Pro recovery commit: `11f64ec2080f26676e73f670093dc7e9a33204eb`
- Existing development `wiopt` SHA-256 before and after non-mutating tests: `e1fda0c845c17e11de41f0610e4a2557853d46fa8c0994964379c5edca5a4589`
- The pre-existing Pro change in `includes/controller/Admin.php` remains preserved.

Before restoring anything, inspect the exact difference against the appropriate recovery branch. Use a reviewed revert commit or a narrow patch; do not use a destructive working-tree reset.

## Reviewer findings

- `Tested up to` exists only in `readme.txt`.
- The unused metabox and taxonomy request handlers and their empty configuration were removed.
- Active settings submissions are rebuilt from the registered schema, validated by type, sanitized recursively, and never passed raw into a dynamically chosen field class.
- Unknown top-level and nested keys are discarded; unsupported field types are skipped.
- The cited generic constant, setup-wizard class, and AJAX action were replaced with Instantio-prefixed identifiers.
- The active Pro consumer uses `INSTANTIO_OPTION_ID`.
- The legacy unused font-upload request path was removed.
- Internal settings field classes were migrated from the three-character `INS_` prefix to `Instantio_Field_`.
- The generic checkout-save callback and dashboard widget class were renamed.

## Focused security results

An isolated settings schema and option were used; the test option was deleted in cleanup.

- script markup removed from text, repeater, fieldset, and tab values;
- unsafe image URL rejected;
- invalid numeric value rejected;
- invalid select choice rejected;
- invalid color rejected and valid RGBA retained;
- forged top-level and nested keys discarded;
- repeater serialized shape retained;
- no test wrote to the development `wiopt` option.

## Runtime results

Development site, WordPress `7.0.3`, WooCommerce `11.0.1`, PHP `8.2.29`, HPOS enabled:

- Free + Pro settings render: 12 sections, 202,926 output bytes, no `Field not found!` fallback.
- Free-only settings render: 10 sections, 159,977 output bytes, no `Field not found!` fallback.
- Pro was restored to active immediately after the Free-only check.
- New setup-wizard class, AJAX action, and option constant are registered; cited old runtime identifiers are absent.

Isolated clean install with `WP_DEBUG` and `WP_DEBUG_LOG` enabled:

- exact packaged Instantio `3.3.36` activated with WooCommerce;
- no pre-existing `wiopt` was present;
- Free settings rendered with no missing field control;
- a clean-install query-string warning was found, fixed, and the render was repeated with a zero-byte debug log;
- renamed setup-wizard AJAX submission returned success and persisted the expected Free values;
- setup-wizard submission left a zero-byte debug log.

The clean test used its own temporary WordPress directory and database. It did not share the development database. The disposable test database was dropped after validation; the test files remain at `/tmp/instantio-t3-clean-wp-BnA6FP-completed` for inspection during this session.

## Automated and package results

- PHP syntax: 45 packaged project PHP files passed (vendored code excluded from the focused lint count).
- JavaScript syntax: passed for packaged project scripts.
- `git diff --check`: passed.
- Composer JSON: valid after removing a pre-existing trailing comma.
- Plugin Check against the exact staged package: `Success: Checks complete. No errors found.`
- Prohibited identifier scan: no cited generic runtime identifier remains in the package.
- Package hygiene: no hidden files, source maps, Sass/SCSS files, internal docs, or Git metadata.
- Staged and extracted archive file hashes match.

Current candidate (must be rebuilt if packaged source changes):

- ZIP: `/tmp/instantio-t3-package-final-xJOCq6/instantio-3.3.36.zip`
- SHA-256: `d1e1aadbec0ecf02578b24b3d125b78ffcd3c6d1023c24d538cd5bee3125d442`
- Size: `6,003,171` bytes
- File count: `204`

## Remaining release gates

- authenticated browser save/reload coverage for all primary Free settings surfaces;
- browser setup-wizard navigation and submission;
- storefront cart, quick-view, AJAX add-to-cart, coupon, checkout, and console regression;
- licensed Pro browser coverage for Pro-injected settings and wizard controls;
- final ZIP rebuild only if source changes after those tests;
- SVN `trunk/` commit and `tags/3.3.36/` creation;
- concise reply to the Plugins Team after SVN verification.

Do not describe this candidate as published or fully release-ready until these gates are completed.

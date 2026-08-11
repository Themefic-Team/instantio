# Phase 5 — Technical and Security Remediation Record

Date: 2026-08-10  
Scope: Instantio Free release candidate, preserving the shared Free/Pro `wiopt` contract  
Status: Complete

## Objective

Close the technical and security work packages from the WordPress.org reopening plan without changing cart behavior, administrator option shapes, or WooInstant Pro-owned values.

## Request-security audit

The Free plugin registers 19 active AJAX hooks across cart, quick-view, Checkout Editor reset, setup wizard, notice dismissal, WooCommerce installation, and settings save paths. Public cart and quick-view actions remain available to guest WooCommerce sessions, while administrator state changes require administrator capabilities.

Changes made in this phase:

- `ins_ajax_cart_reload` now verifies the existing `ins_ajax_nonce` for direct AJAX requests. Internal fragment composition remains callable without a redundant nonce check.
- the readable and distributed frontend scripts now send that established nonce with cart-reload requests;
- `tf_ajax_save_options` now enforces `manage_options` and the existing settings nonce at the AJAX boundary before entering the established option-save routine;
- obsolete authenticated and unauthenticated review-notice AJAX registrations were removed because their callback no longer exists;
- the settings response remains a WordPress JSON response, avoiding the debug-output corruption that previously left the Save button loading.

No option key, scalar type, selected layout value, or Free/Pro merge rule was changed by these request-security updates.

## Database, input, and output review

- No direct `$wpdb` query or direct database write exists in Instantio Free.
- State-changing AJAX handlers have nonce checks; administrator-only actions also have capability checks.
- Direct request reads use `wp_unslash()` followed by field-appropriate sanitization or numeric normalization.
- Plugin Check's security, escaping, internationalization, file, and plugin-repository checks report no release-candidate error.
- Trusted WooCommerce structured markup remains at its documented rendering boundaries rather than being flattened with `esc_html()`.

## Output-buffer correction

`includes/controller/ins-checkout-editor.php` contained an orphan global `ob_start()` whose matching output call had been commented out. The buffer was not required because the file only registers hooks and functions, so the start and dead comment were removed. All other active buffer starts have a corresponding clean/flush operation in their rendering path.

## Compatibility and dependency review

- all 50 first-party PHP files pass PHP 8.2 syntax validation;
- the changes use APIs compatible with the declared PHP 7.4 minimum;
- readable and minified frontend JavaScript pass `node --check`;
- incorrect legacy translation domains are absent from first-party PHP;
- the distribution candidate is built through `.distignore`, which excludes development metadata and documentation;
- bundled third-party assets and their license/source records remain documented in `docs/release-packaging.md` and `docs/changed-files-record.md`;
- the release candidate retains the root `composer.json`.

## Live and negative-path evidence

Authenticated browser regression on `http://dev.local` with Instantio Free and WooInstant Pro active:

- the visible settings Save button returned HTTP 200 with JSON `status: success`;
- a real WooCommerce add-to-cart request updated the cart;
- the secured Instantio cart reload returned HTTP 200 with `success: true`;
- no browser console error was captured.

Security boundary checks:

- a public cart-reload request without its nonce returned HTTP 403 and body `-1`;
- an unauthenticated settings-save request did not reach the authenticated WordPress AJAX callback and returned the expected WordPress denial response.

The browser settings test temporarily serialized the same visible settings form differently. A second identical save was stable, but its full option hash differed from the locked baseline, so it was treated as a stop gate. The exact pre-test row was recovered from the local MySQL row binlog and was written back only after its raw serialized value matched the known SHA-256 guard. The final stored `wiopt` hash is:

`c38360b2337083ac6174e500dbebedae9873f58bcdcf162e6626d645b49f9056`

No stale backup or guessed option value was used.

## Final verification

- PHP syntax: 50 files checked, zero failures.
- JavaScript syntax: readable and distributed frontend files passed.
- Direct database-query scan: zero matches.
- Wrong translation-domain scan: zero matches.
- `git diff --check`: passed.
- fresh `.distignore` distribution candidate: `Success: Checks complete. No errors found.`
- WordPress debug log: no log file was produced during the regression run.

## Gate 5 result

Gate 5 passes for the Instantio Free release candidate. Phase 6 may now harden the remaining Free/Pro integration boundaries; deployment, WordPress.org reviewer acceptance, and production-site verification remain separate later gates.

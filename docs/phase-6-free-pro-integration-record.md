# Phase 6 — Free/Pro Integration Hardening Record

Date: 2026-08-10  
Scope: Instantio Free 3.3.34 and WooInstant/Instantio Pro 3.2.11  
Status: Complete

## Objective

Make Pro an intentional extension of Instantio Free, keep invalid dependency/license states inert, and preserve every shared `wiopt` value and public extension hook.

## Compatibility matrix

| Component | Compatibility rule |
| --- | --- |
| Instantio Free | supports Instantio Pro 3.0.0 or newer |
| Instantio Pro 3.2.11 | requires Instantio Free 3.3.34 or newer |
| WooCommerce | must be active before Pro functional controllers initialize |
| PHP | both plugins declare PHP 7.4 or newer |

Free now uses `version_compare()` instead of casting dotted versions to floating-point numbers. A compatible Pro installation no longer exits the Free administrator controller constructor early, so Free action links and lifecycle hooks remain registered.

## Pro bootstrap ownership

WooInstant Pro now evaluates its dependencies before loading its license runtime or any functional controller:

- without a compatible Instantio Free class/version, Pro registers only an administrator dependency notice and returns;
- without WooCommerce, Pro returns without frontend/admin functional initialization; Free retains ownership of the WooCommerce dependency notice;
- with both dependencies, Pro loads its autoloader and license runtime;
- Pro assets and frontend `App`/`Functions` controllers initialize only for a valid license;
- the Pro admin controller remains available with dependencies present so an invalid license can display activation guidance.

The dependency gate is implemented at runtime instead of with the WordPress `Requires Plugins` header. The header prevented the required “Pro active without Free” recovery-notice state by refusing/deactivating that combination.

## License gate normalization

- all active settings/frontend bootstrap decisions now use boolean `false` as the unlicensed value and strict `false !== $status` checks for a valid response object;
- the Pro `App` no longer defaults to licensed behavior when its filter is absent;
- the duplicate Pro-side `insopt()` declaration was removed; the compatible Free global helper is the sole owner;
- the license-notice dismissal now requires its own nonce and the `activate_plugins` capability;
- the license activation hook now points to the actual Pro plugin bootstrap file.

No license key or email is recorded in this document.

## Assets and paths

- Free keeps its established `ins-admin` and `ins-admin-script` handles;
- Pro admin assets now use `ins-pro-admin` and `ins-pro-admin-script`, eliminating first-registration URL collisions;
- Pro obtains Notyf through Free's `INS_ADMIN_URL` and established `notyf-js` handle rather than `/wp-content/plugins/instantio/...`;
- no hard-coded Free asset URL remains in Pro.

The remaining `WP_PLUGIN_DIR/instantio/instantio.php` references are dependency file-presence checks, not generated URLs.

## Uninstall and shared data

Pro uninstall no longer attempts to delete data derived from the shared `wiopt` value. It removes only Pro-owned license/dismissal metadata. Deactivating or uninstalling Pro therefore leaves Free configuration and dormant Pro keys in the shared option so they return when Pro is reactivated.

## Legacy Pro settings framework

`wooinstant/admin/tf-options/TF_Options.php` and its old framework assets have no active loader outside their own legacy directory. The live settings engine is `instantio/admin/tf-options/Ins_TF_Options.php`, which selects the licensed Pro schema from `wooinstant/admin/tf-options/options/`.

The duplicate framework is quarantined as inactive source rather than deleted in this phase. Removing its old images/framework files without a dedicated Pro packaging comparison would add unnecessary functionality risk.

## Activation-state evidence

| Tested state | Result |
| --- | --- |
| Free only | storefront HTTP 200; complete `wiopt` hash unchanged |
| Free + valid Pro | license valid; Pro assets loaded; settings save HTTP 200 with native success JSON; cart reload HTTP 200/success; zero browser console errors |
| Free + invalid Pro | storefront HTTP 200; Pro Assets and App controllers not loaded |
| Pro without Free | storefront HTTP 200; Pro asset controller not loaded; administrator dependency hook registered |
| Pro deactivated, then reactivated | Free remained operational; `wiopt` hash unchanged; valid Pro state returned |
| WooCommerce inactive | storefront HTTP 200; Pro asset controller not loaded; `wiopt` hash unchanged |

The invalid-license test temporarily blanked the two local license rows. Its initial restore harness passed the socket variable incorrectly and captured empty values. Work stopped immediately; the exact pre-test key/email rows were identified from the local MySQL row binlog, restored, and the valid license state was reverified. The secret values were not printed or written to documentation. `wiopt` was never changed by that test.

The licensed browser settings test did serialize the visible settings form, so it used a correctly captured raw database snapshot with a guaranteed restoration trap. Its final complete `wiopt` SHA-256 is the locked baseline:

`c38360b2337083ac6174e500dbebedae9873f58bcdcf162e6626d645b49f9056`

## Static verification

- 78 first-party PHP files across Free and Pro passed PHP 8.2 syntax validation;
- zero dotted-version float casts remain;
- zero hard-coded Free asset URLs remain in Pro;
- zero active loaders reference the stale Pro `TF_Options.php` framework;
- Free and Pro administrator asset handles are distinct;
- `git diff --check` passed;
- the final Instantio Free distribution candidate passed Plugin Check with zero errors.

## Gate 6 result

Gate 6 passes locally. Shared option values, public Free extension hooks, valid Pro functionality, and license credentials are restored. Deployment and production-license behavior remain separate release gates.

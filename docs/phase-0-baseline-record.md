# Phase 0 — Baseline and Freeze Record

Started: 2026-08-09  
Plan: `docs/wordpress-org-reopening-plan.md`  
Completed: 2026-08-09  
Status: Automated baseline complete; two manual stop gates remain before Phase 2 runtime changes

## Purpose

Capture the current Instantio Free and Instantio Pro source, environment, option data, static validation, and observable behavior before WordPress.org compliance architecture changes begin.

No runtime plugin code may be changed during this phase.

## Source freeze

### Instantio Free

- Directory: `instantio`
- Branch: `staging`
- Git revision: `ef24e2ef0c55b78391a6a927cee6871f3dd71d6b`
- Plugin version: `3.3.34`
- File count excluding `.git`: 268 at capture time
- Disk usage: 52 MB
- Deterministic source-manifest SHA-256, excluding `.git` and this evolving record: `c7a4f931f77c2e9cca0e6be9642848a2477028ed40c459279c162028c85ce938`
- Tracked working tree: clean
- Untracked files at capture time:
  - `docs/wordpress-org-reopening-plan.md`
  - `docs/phase-0-baseline-record.md`

### Instantio Pro

- Directory: `wooinstant`
- Branch: `staging`
- Git revision: `8890b2b1362fb62bb7de581a0a1921aabc477d9f`
- Plugin version: `3.2.11`
- File count excluding `.git`: 122
- Disk usage: 19 MB
- Deterministic source-manifest SHA-256 excluding `.git`: `6be46e07c52d5440ebc64e87697172aca3890bc5f526b6c068cc5669ed993193`
- Working tree: clean

No source archive was moved into the WordPress plugin directory. The Git revisions, status, and manifests provide the source identity without changing runtime plugin contents.

## Test environment

- Site URL: `http://dev.local`
- WordPress: 7.0.3
- WooCommerce: 11.0.0
- PHP CLI: 8.2.29
- MySQL client/server family: 8.4.0
- WP-CLI: 2.12.0
- Active theme: Twenty Twenty-Five 1.5
- Published products: 15
- HPOS/custom order tables: enabled
- Guest checkout: enabled
- Available offline gateways: BACS, cheque, and cash on delivery; none was reported enabled in the captured table
- `WP_DEBUG`: false
- `WP_DEBUG_LOG`: false
- `SCRIPT_DEBUG`: false

Plugin activation state at capture:

| Plugin | Status | Version |
| --- | --- | --- |
| Instantio Free | Active | 3.3.34 |
| Instantio Pro | Inactive | 3.2.11 |
| WooCommerce | Active | 11.0.0 |
| Plugin Check | Active | 2.0.0 |

## Shared option baseline

The `wiopt` value was inspected without printing customer-entered values.

- Stored type: array
- Top-level keys: 36
- Serialized-value SHA-256: `80736d97ae2a67521c40bf76e1528fbda1b82bbf2ef2a88e19186d747400fd1f`

Critical compatibility assertions:

| Key | Current type |
| --- | --- |
| `ins-layout-options` | string |
| `ins-layout-mode` | string |
| `ins-layout` | string |
| `ins-toggle-tab` | array, 11 keys |
| `ins-toggle-panel-tab` | array, 30 keys |
| `empty-cart-content` | array, 4 keys |

Other stored keys include cart/checkout button groups, checkout-editor keys, mobile flags, upsell/cross-sell keys, minification settings, quick-view settings, and custom CSS. Later Free settings saves must preserve unknown or Pro-owned keys.

## Static validation baseline

### PHP

- Instantio Free: 61 PHP files checked; 0 syntax failures.
- Instantio Pro: 37 PHP files checked; 0 syntax failures.

### JavaScript

- Node.js: 24.17.0
- Free and Pro combined: 22 JavaScript files checked; 0 syntax failures.

### Git validation

- Free `git diff --check`: passed.
- Pro `git diff --check`: passed.

### Plugin Check

Live Plugin Check was run against the active Free source directory.

- Errors: 0 reported
- Warnings: 2
- Warnings were limited to `.distignore` and `.gitignore` being hidden files.
- These files are development/package-control files and must be excluded from the exact WordPress.org artifact.

Plugin Check success does not resolve the manual-review trialware, licensing, external-service, or full-security requirements.

## Live Free-only smoke evidence

### HTTP

- Homepage: HTTP 200
- Shop: HTTP 200
- Instantio Free asset references found across the responses: 14
- Instantio Pro asset references: 0, as expected while Pro is inactive
- PHP warning/fatal/deprecated/notice markers in returned markup: 0

### Security behavior

- An unauthenticated `ins_ajax_empty_cart` request without its nonce returned HTTP 403 with body `-1`.

### Runtime registration

The following were registered in a live WordPress load:

- authenticated and guest `ins_ajax_cart_reload` AJAX hooks;
- authenticated and guest `ins_ajax_update_cart` AJAX hooks;
- `ins_options_save` settings AJAX hook;
- WooCommerce checkout-fields filter;
- `before_woocommerce_init` compatibility hook;
- `instantio-cart-icon` shortcode.

## Functionality preservation baseline

The automated evidence confirms that the current Free plugin loads, registers its principal runtime surfaces, serves its assets, preserves expected option types, and rejects a protected mutation without its nonce.

This evidence does not prove all interactive behaviors. The following tests require an authenticated browser session or controlled state changes and remain mandatory before Phase 2 changes functionality:

- Settings field changes, save response inspection, reload persistence, and Save-button loading state.
- Visual operation of all Free layouts, modes, toggle/icon controls, cart mutations, coupons, quick view, and mobile behavior.
- Real checkout/order placement with shipping, tax, and payment fixtures.
- Free + Pro behavior with a valid license, including all Pro schema fields and checkout layouts.
- Pro invalid/expired license behavior and Pro-without-Free behavior.

The Pro plugin was intentionally not activated during this evidence pass because its bootstrap can contact the license service and modify license state. Valid-license testing must use an approved test license and an authenticated browser fixture.

## Known pre-existing constraints

1. Debug constants are currently disabled. The clean-release matrix must be repeated with `WP_DEBUG`, `WP_DEBUG_LOG`, and `SCRIPT_DEBUG` enabled.
2. Pro was inactive at baseline; licensed Pro functionality is not claimed as passing.
3. No payment gateway was shown as enabled by the captured WooCommerce CLI table.
4. Plugin Check reports only artifact-hidden-file warnings, but the WordPress.org email identifies manual policy issues outside Plugin Check's coverage.

## Phase gate result

Phase 0 automated baseline: **complete**.

Runtime remediation remains blocked until these two evidence gates are satisfied:

1. Authenticated Free browser baseline.
2. Valid-license Free + Pro browser baseline.

Phase 1 is documentation/inventory work and may proceed without changing runtime behavior. Phase 2 must not begin implementation until both browser baselines have evidence or the missing fixture is explicitly accepted as a release risk by the product owner.

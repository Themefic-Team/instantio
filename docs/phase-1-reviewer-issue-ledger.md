# Phase 1 — WordPress.org Reviewer Issue Ledger

Started: 2026-08-09  
Completed: 2026-08-09  
Source: Plugins Team closure email dated 2026-08-05  
Review ID: `GUIDELINES LIC-GPL-OTH instantio/themefic/5Aug26/T1 5Aug26/4.2A2`  
Status: Complete

## Purpose

Map every issue category in the closure email to the current Instantio Free source, identify what prior remediation already fixed, and define the remaining resolution and evidence required before release.

This phase changed documentation only. It did not alter plugin runtime code.

## Status definitions

- **Resolved in source:** cited implementation is absent or corrected in the current development tree.
- **Open:** the prohibited or defective behavior remains in current source.
- **Verify artifact:** current source appears corrected, but the exact packaged ZIP must prove it.
- **Manual verification:** static inspection is insufficient; browser, network, or functional evidence is required.

## Executive result

| Classification | Count |
| --- | ---: |
| Open release blockers | 7 |
| Resolved in current source, artifact proof required | 7 |
| Requires focused/manual verification | 3 |
| Total tracked categories | 17 |

The main WordPress.org closure reason remains open: Free still bundles and disables many `is_pro` fields. GSAP and unnecessary Themefic promotional requests also remain. The previous Plugin Check remediation correctly addressed several cited technical findings, but those fixes must be proven in the exact release artifact.

## Detailed ledger

| ID | Email issue | Current status | Current evidence | Required resolution | Owner | Acceptance evidence |
| --- | --- | --- | --- | --- | --- | --- |
| WORG-01 | Guideline 5 trialware / locked built-in features | **Open — primary blocker** | Free schema contains many `is_pro => true` fields; settings framework adds `tf-field-disable tf-field-pro`; JS disables/captures those controls; checkout-editor field-count limit messages remain. | Assign every feature to fully enabled Free or separately hosted Pro. Remove disabled Pro controls and license-based local feature gating from Free. Preserve extension hooks and stored `wiopt` keys. | Shared architecture | Source/artifact scans contain no Free locked-control mechanism; Free-only and Free+Pro regression gates pass. |
| WORG-02 | Guideline 6 serviceware clarification | **Open policy cleanup** | Local feature availability is influenced by `ins_checked_license_status`; Free loads the Pro schema only when Pro/license state is truthy. | Ensure the WordPress.org Free package does not use a license to unlock locally bundled Free code. License behavior may remain in separately distributed Pro. | Shared architecture | Free works completely without Pro/license; Pro functionality resides in Pro; reviewer-ready architecture explanation. |
| WORG-03 | GPL-incompatible GSAP library | **Open — release blocker** | `assets/app/js/gsap.min.js` 3.11.5 remains, is enqueued as `ins-gsap-script`, and readable Free JS calls `gsap.to()`/`gsap.from()`. | Replace used animations with CSS/Web Animations API, add reduced-motion behavior, remove GSAP file/enqueue/references. | Free frontend | Zero GSAP/GreenSock references in source and artifact; animation parity tests pass. |
| WORG-04 | Appsero tracking/phoning home, including opt-out request | **Resolved in source; verify artifact** | `includes/app/` and `appsero.json` are absent; runtime scan outside historical docs finds no Appsero or `icanhazip` reference. | Keep Appsero excluded permanently. Ensure no generated/autoload reference returns. | Free/package | Artifact scan zero; activation/network test sends no Appsero/IP request. |
| WORG-05 | Readme short description over 150 characters | **Resolved in source; verify artifact** | Current short description is 96 characters. | Preserve current concise description. | Free readme | Readme validator/artifact inspection confirms <=150 characters. |
| WORG-06 | Composer used but root `composer.json` missing | **Resolved in source; verify artifact** | Root `composer.json` exists and `.distignore` does not exclude it. | Keep it in package; verify autoload metadata matches shipped classes. | Free/package | Exact ZIP contains `instantio/composer.json`. |
| WORG-07 | Out-of-date Appsero library | **Resolved by removal; verify artifact** | Appsero SDK no longer exists. | No upgrade needed because dependency was removed completely. | Free/package | Artifact contains no `includes/app`, Appsero client, or Appsero version marker. |
| WORG-08 | PHP library collision from bundled Appsero | **Resolved by removal; verify artifact** | Conflicting Appsero class files are absent. | Keep removed. Review remaining Composer classes for namespace safety. | Free/package | Artifact inventory and activation with common plugins show no collision. |
| WORG-09 | Calling JavaScript/CSS remotely | **Previously remediated; focused verification** | Active settings framework now enqueues local Font Awesome, Remix Icon, Select2, Flatpickr, WP Color Picker Alpha and WordPress Code Editor. No direct remote enqueue call was found. Map/geocoder URLs remain in generic settings JS and must be checked for reachability/use. | Remove unused map/geocoder code if no Instantio field needs it; otherwise document/consent as applicable. Confirm every runtime dependency is local or WordPress core. | Free admin/package | Network test on every settings tab; source/artifact enqueue scan; no unnecessary CDN request. |
| WORG-10 | Undocumented external services | **Open — release blocker** | `class-promo-notice.php` posts to `https://api.themefic.com/`; `dashboard-promo-notice.php` gets Themefic dynamic pricing over HTTP; generic settings JS references OpenStreetMap/Nominatim. | Remove promotional API calls and unused external integrations from Free. If a legitimate functional service remains, document purpose/data/timing/terms/privacy and require appropriate consent. | Free admin/readme | Source/network inventory agrees with readme; no undisclosed automatic service call. |
| WORG-11 | Remote promotional images/assets | **Open** | Current code still contains Themefic-hosted promotional image URLs and WordPress.org-hosted product-card images. Ordinary clicked links are separate and acceptable. | Bundle necessary display assets locally or remove promotional UI. Avoid automatic remote image loads in admin. | Free admin | Browser network log shows no remote image/script/style request during ordinary admin use. |
| WORG-12 | Calling WordPress core loading files directly | **Resolved cited Appsero instance; verify remaining includes** | Appsero `Insights.php` is absent. Current code loads allowed admin include files with `include_once`/`require_once`; no `wp-load.php`, `wp-config.php`, or `wp-blog-header.php` reference found. | Confirm each remaining admin include is necessary, hook-scoped, and followed by its needed API. Prefer `require_once` consistently. | Free core/admin | Focused manual review and prohibited-pattern artifact scan. |
| WORG-13 | Incorrect file/directory determination | **Resolved cited Appsero instances; additional shared risk outside Free review** | Appsero theme-path logic is absent. Free uses plugin constants and `wp_upload_dir()` in the reviewed areas. Pro separately has a hard-coded `/wp-content/plugins/instantio` asset URL. | Verify Free contains no hard-coded plugin/content paths. Fix Pro hard-coded URL during integration hardening without changing Free artifact behavior. | Free verification / Pro integration | Custom-content-path test or focused source proof. |
| WORG-14 | Use WordPress file uploader instead of `move_uploaded_file()` | **Partially resolved; needs decision** | Direct `move_uploaded_file()` is gone. Current settings code validates metadata, initializes `WP_Filesystem`, and calls `$wp_filesystem->move()` into an uploads subdirectory. | Review whether this upload is necessary. Prefer `wp_handle_upload()` for browser uploads, then perform any controlled relocation using WordPress filesystem APIs. Preserve accepted font types and settings behavior. | Free settings | Authenticated upload test, MIME/extension rejection tests, nonce/capability proof, Plugin Check/WPCS pass. |
| WORG-15 | Shortcode callback returns unescaped HTML | **Previously remediated; manual regression required** | Prior remediation added contextual escaping and `wp_kses_post()` around cart-icon markup; current static Plugin Check reports no error. | Perform focused tainted-data review of icon class/image URL/cart count and test custom icon output. Do not escape required HTML as plain text. | Free frontend | Focused PHPCS result, malicious-value test, rendered shortcode test. |
| WORG-16 | Unclosed `ob_start()` | **Open — technical blocker** | `includes/controller/ins-checkout-editor.php` opens a global buffer at line 13; the only `ob_get_clean()` is commented out. | Remove the unused global buffer or close it within the same logical scope. Confirm buffer level remains stable on admin/frontend/AJAX requests. | Free checkout editor | Source scan balanced; runtime `ob_get_level()` before/after relevant loads; checkout editor regression passes. |
| WORG-17 | Internationalization, direct access, and full security follow-up | **Previously remediated in cited areas; full verification required** | Wrong `ultimate-addons-cf7`/`bafg` domains are absent. Approximate scan found no clear dynamic gettext calls except a multiline literal. `functions.php` and `ins-checkout-editor.php` have ABSPATH guards. Some namespaced class-definition files lack guards but contain definitions rather than direct execution. | Run focused WordPress I18n/escaping/security rules across the artifact; manually classify remaining direct-access candidates; add guards to executable files; keep dynamic option text out of gettext calls. | Free all | Zero actionable focused findings, direct-access request tests where relevant, clean `WP_DEBUG` log. |

## Open blocker detail

### Trialware inventory boundary

The following mechanisms make WORG-01 demonstrably present:

- Free fields marked `is_pro` in `admin/tf-options/options/tf-settings.php`.
- `tf-field-disable` and `tf-field-pro` classes added by the Free settings renderer.
- Free JavaScript disabling `.tf-field-disable` inputs and handling `.tf-field-pro` clicks.
- Field-count limitation messages in the checkout editor JavaScript.
- Pro/license-dependent option-schema selection in the Free settings framework.

Phase 2 must inventory each individual field and its consumers before moving or enabling it. Removing only the CSS class would not be a safe or sufficient fix.

### External-call boundary

Automatic runtime calls currently requiring removal or justification include:

- Themefic promotional API POST in `includes/controller/class-promo-notice.php`.
- Themefic dynamic-pricing GET in `includes/controller/dashboard-promo-notice.php` using insecure HTTP.
- OpenStreetMap tile and Nominatim search code in the generic settings JavaScript if reachable by Instantio fields.

Ordinary hyperlinks opened only after a user clicks Documentation, Support, Demo, or Pricing are not classified as automatic phoning home. They still must avoid prohibited referral tracking behavior and excessive admin promotion.

### Packaging boundary

The development tree intentionally contains `.gitignore`, `.distignore`, and internal `docs`. Plugin Check warns about hidden files when scanning the source tree. The `.distignore` rules exclude these from distribution. Phase 8 must prove the exact ZIP rather than relying on development-tree results.

## Work ownership by future phase

| Future phase | Ledger items |
| --- | --- |
| Phase 2 — Free/Pro separation | WORG-01, WORG-02 |
| Phase 3 — GSAP replacement | WORG-03 |
| Phase 4 — external calls/assets | WORG-04, WORG-09, WORG-10, WORG-11 |
| Phase 5 — technical/security | WORG-12 through WORG-17 |
| Phase 8 — exact artifact | WORG-04 through WORG-09 plus all prohibited-pattern proof |

## Phase gate result

Phase 1: **complete**.

- Every category from the closure email has a current status.
- Every category has an owner, required resolution, and acceptance evidence.
- Existing fixes are not treated as released until the exact artifact proves them.
- No runtime code was changed in this phase.

The next planned phase is Phase 2, the highest-risk Free/Pro separation. Its runtime work remains blocked by the two browser baselines identified in Phase 0 unless the product owner explicitly accepts that risk. A read-only field/consumer inventory can begin without crossing that gate.

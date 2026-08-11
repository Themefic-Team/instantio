# Phase 4 — External Services and Privacy Record

Date: 2026-08-10  
Scope: Instantio Free release candidate, with WooInstant Pro ownership review  
Status: Complete

## Objective

Remove unsolicited Free-plugin network traffic and remotely hosted runtime assets without changing Instantio cart, checkout, settings, or Free/Pro option contracts.

## Removed Free-plugin network behavior

| Previous behavior | Trigger | Resolution |
| --- | --- | --- |
| Promotion configuration POST to `api.themefic.com` | Daily WordPress cron created by the promotion class | Promotion class, bootstrap, callback, and cron creation removed |
| Dynamic pricing GET to Themefic API | Rendering the Instantio dashboard promotion | Replaced with the existing local fallback offer |
| Remote Black Friday images | Legacy admin notice/product markup | Obsolete 2023 promotion code removed |
| Google Fonts stylesheet | Loading the Instantio settings UI | Import removed; readable and minified CSS rebuilt |
| WordPress.org product-card icons | Loading the Instantio settings sidebar | Remote card rendering removed and dormant data localized |
| OpenStreetMap tiles and Nominatim search | Dormant generic map field | Unused field removed and remote client calls neutralized |

The legacy promotion cleanup removes the Instantio-specific scheduled event, cached API response, start timestamp, and dynamic-pricing transient. It does not delete shared Themefic options that may belong to another plugin.

## Appsero result

- No Appsero SDK directory or runtime filename exists in the Free plugin.
- No Appsero bootstrap, telemetry, tracking, opt-in, or deactivation-survey source reference remains outside internal documentation.
- Appsero does not register a cron event, option, notice, updater, or HTTP request.

## Links retained

Documentation, support, community, review, pricing, and upgrade links remain ordinary anchors. They do not transmit data until an administrator clicks them. UTM generation no longer includes the site's hostname; it uses the static source value `instantio`.

## Pro-owned services intentionally unchanged

WooInstant Pro license activation/update requests and payment-provider scripts are functional Pro services and were not removed from Pro. They are not part of the WordPress.org Free artifact. Phase 4 changes were limited to unsolicited Free-plugin promotion and asset traffic.

## Verification

Static:

- zero Free PHP outbound HTTP/cURL/socket API calls;
- zero Free Appsero runtime references or files;
- zero Themefic API, remote promotional-image, Google Fonts, CDN, OpenStreetMap tile, or Nominatim runtime references;
- PHP syntax passed for every modified PHP file;
- JavaScript syntax passed for the modified settings script;
- `git diff --check` passed.

Live browser network test on `http://dev.local` with Free and licensed Pro active:

- homepage: HTTP 200, zero external requests, zero console errors;
- real add-to-cart page: HTTP 200, zero external requests, zero console errors;
- Instantio settings (`page=wiopt`): HTTP 200, correct settings route, zero console errors;
- the settings page emitted only WooCommerce Admin-owned `stats.wp.com`/`pixel.wp.com` requests. Source scanning confirms Instantio contains no matching endpoint or request path.

The complete `wiopt` hash remains `c38360b2337083ac6174e500dbebedae9873f58bcdcf162e6626d645b49f9056`; no functional setting value or type changed.

## Gate 4 result

Gate 4 passes for Instantio Free. The final WordPress.org distribution archive will receive the same static and live request scan during the artifact phase.

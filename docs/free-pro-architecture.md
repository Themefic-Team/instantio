# Instantio Free and Pro Architecture

Last reviewed: 2026-08-09

## Purpose

This document records how the current `instantio` Free plugin (3.3.34) and the `wooinstant` Instantio Pro add-on (3.2.11) work together. It is the compatibility reference to consult before changing settings, templates, hooks, AJAX, assets, or plugin bootstrapping.

This was a read-only architecture pass. No runtime behavior in either plugin was changed while producing this document.

## Executive summary

- `instantio` is the required base plugin. It owns the main bootstrap, public helper functions, settings engine, common option storage, core cart rendering, core AJAX endpoints, and base assets.
- `wooinstant` is an add-on. It detects the Free plugin through the global `INSTANTIO` class and extends it through filters/actions, a licensed Pro settings schema, Pro templates, Pro AJAX, and additional assets.
- Both plugins use the `instantio` text domain and the same `wiopt` WordPress option.
- The saved shape of every `wiopt` field is a compatibility contract. Scalar selectors such as `ins-layout-options`, `ins-layout-mode`, and `ins-layout` must remain scalar strings even though their schema currently contains `multiple => true`.
- A Free-only test is not enough for a shared setting, hook, template, script handle, CSS class, or AJAX response. Those changes must be tested with Free only and Free + Pro.

## Plugin ownership map

| Concern | Free (`instantio`) | Pro (`wooinstant`) |
| --- | --- | --- |
| Required base | Yes | No; requires Free |
| Main class | `INSTANTIO` | `WOOINS` |
| Namespace | `INS\Controller` | `INS_PRO\Controller` |
| Version constant | `INSTANTIO_VERSION` | `INSTANTIO_PRO_VERSION` |
| Settings framework | Owns `Ins_TF_Options`, fields, rendering, and AJAX save | Supplies a replacement option schema when licensed |
| Settings storage | Owns/shared `wiopt` option | Reads and writes the same `wiopt` option |
| Cart shell and common AJAX | Owns | Extends/filters |
| Checkout steps and summaries | Provides extension points | Supplies Pro implementations |
| Base frontend assets | Owns `ins-style`, `ins-script`, `ins_params` | Adds Pro assets and can select Free minified asset URLs |
| Licensing/updater | None after Appsero removal | Custom Themefic license server and updater |
| WooCommerce dependency messaging | Owns | Relies on Free and separately warns when Free is absent |

## Boot sequence

### Free

1. `instantio.php` constructs global `INSTANTIO` immediately.
2. It defines all `INS_*` URL/path constants and registers the WooCommerce installer AJAX action.
3. It loads Composer, WordPress plugin helpers, and `functions.php`.
4. When WooCommerce is active it loads the setup wizard and checkout editor; promotional controllers are also loaded.
5. On `init` priority `0`, it creates the shared asset controller and then either the admin controller or frontend `App` controller.
6. It loads the Free settings framework/schema through a later `init` callback.

### Pro

1. `wooinstant.php` constructs global `WOOINS` immediately and defines all `INS_PRO_*` constants.
2. On `init` at the default priority, it checks whether the Free `INSTANTIO` class exists.
3. When Free exists, it loads the custom license/updater layer and creates the Pro asset controller.
4. It creates either the Pro admin controller or the Pro frontend `App` and `Functions` controllers.
5. On the frontend, `INS_PRO\Controller\Functions` loads integration files and declares the namespaced `INS_PRO\Controller\insopt()` helper. This is distinct from Free's global `insopt()`.

The current load order depends on Free registering its main `init` callback at priority `0`, before Pro's default-priority `init` callback. Do not casually change these priorities or move the `INSTANTIO` class creation behind a hook.

## Free-to-Pro detection and licensing

Free considers Pro active when both conditions are true:

```php
is_plugin_active( 'wooinstant/wooinstant.php' ) && class_exists( 'WOOINS' )
```

Pro considers Free available primarily through `class_exists( 'INSTANTIO' )`.

Pro registers `ins_checked_license_status`. The returned value is either `false` or a license response object. Free uses this filter when selecting the settings schema:

- Pro active and license truthy: Free loads `wooinstant/admin/tf-options/options/*.php`.
- Otherwise: Free loads `instantio/admin/tf-options/options/*.php`.

Pro's frontend `App` registers some checkout-step hooks before its license early-return, but registers its other Pro hooks only after the license check. This split is important and should be normalized carefully in future work rather than assumed to be a single feature gate.

Pro uses `includes/license/InstantioProBase.php` for activation, update checks, plugin-information responses, and server commands against `https://license.themefic.com/wp-json/licensor/`. Its license options include `InstantioPro_lic_Key` and `InstantioPro_lic_email`.

## Shared option contract

The canonical option is:

```php
get_option( 'wiopt' )
```

Free's global helper and Pro's namespaced helper both return an entry from this array:

```php
insopt( $option, $default )
```

The Free settings engine owns `wp_ajax_ins_options_save`, sanitizes registered fields, updates the complete `wiopt` array, and returns a native JSON response with `wp_send_json()`.

### Rules for changing settings

1. Never change a stored field from scalar to array (or array to scalar) without tracing every consumer in both plugins and providing a migration/compatibility reader.
2. Preserve existing field IDs. Pro consumes many Free IDs directly.
3. Preserve unrelated keys when saving a subset of settings; Pro-only keys can be present in the shared array.
4. Do not print debugging output in plugin load, hooks, or AJAX callbacks. Any `var_dump`, notice, or stray output corrupts JSON and can leave the Save button loading forever.
5. AJAX clients and callbacks should agree on one response format. The current save endpoint sends native JSON.
6. Treat strings such as `'1'`, `'2'`, `'3'`, `'light'`, and `'cart'` as intentional values, not accidental serialization.

The following high-impact selectors are scalar contracts:

- `ins-layout-options`: `'1'`, `'2'`, or `'3'`
- `ins-layout-mode`: for example `'light'`, `'dark'`, `'glass-morphism'`, or `'gradient'`
- `ins-layout`: for example `'cart'` or the Pro checkout layout choice

Nested groups such as `ins-toggle-tab`, `ins-toggle-panel-tab`, and `empty-cart-content` are arrays and must retain their nested keys.

## Admin settings architecture

`instantio/admin/tf-options/Ins_TF_Options.php` is the active framework in both Free-only and Free + Pro operation. It loads field classes from Free, enqueues the admin settings assets, and decides which schema to include.

The Pro copy at `wooinstant/admin/tf-options/TF_Options.php` appears to be a legacy duplicate and is not required by the current Pro bootstrap. It references old constants/files that are not defined in the current Pro tree. Do not edit it expecting the live settings screen to change.

The active schemas are:

- Free: `instantio/admin/tf-options/options/tf-settings.php`
- Licensed Pro: `wooinstant/admin/tf-options/options/tf-settings.php`

The schemas overlap heavily. Pro adds or changes fields such as dedicated mobile behavior, checkout refinements, and extra design controls. Schema drift is therefore a major regression risk.

## Frontend rendering flow

Free's `INS\Controller\App` owns the cart shell and primary interaction endpoints. It:

- determines layout data from `wiopt`;
- renders the toggle, header, cart content, and layout wrapper;
- handles cart reload, single-cart rendering, removal, quantity updates, empty-cart, and coupon removal AJAX;
- exposes actions and filters where Pro inserts checkout behavior.

Pro's `INS_PRO\Controller\App` extends that shell. It:

- replaces the selected layout slug for single-step checkout;
- adds checkout progress and checkout form content;
- changes the cart button to advance into checkout;
- provides login, contact, shipping, order-summary, cross-sell, and upsell markup;
- adds a dedicated mobile cart bar;
- provides `ins_update_order_review_callback` for Pro checkout summaries.

Important Free extension points consumed by Pro include:

- `ins_layout_slug`
- `ins_layout_class`
- `ins_cart_template`
- `ins_cart_buttons_pro`
- `ins_template_steps`
- `ins_template_step_content`
- `ins_login_form`
- `ins_show_items_cross_sell`
- `ins_show_items_upsells`
- `dedicated_mobile_version`

Renaming one of these hooks, changing its arguments, moving it relative to output buffering, or escaping its returned HTML differently can break Pro without producing a PHP error.

## Assets and browser contracts

Free registers:

- `ins-style`
- `ins-gsap-script`
- `ins-script`
- localized object `ins_params`
- admin handle `ins-admin-script`

Pro registers:

- `ins-style-pro`
- `ins-script-pro`
- `ins-owl-carousel` / `ins-owl-carousel-js`
- `ins_loadScript_pl`
- localized object `ins_params_pro`

Pro filters `ins_style_min_status_checked` and `ins_script_min_status_checked` to select Free's minified files based on the shared `css-min` and `js-min` options.

Both plugins enqueue the same admin handles (`ins-admin` and `ins-admin-script`). WordPress handle collisions mean the first registered URL can win. This is a fragile ownership boundary; future cleanup should give the Pro admin additions unique handles or make Free the explicit sole owner.

Pro currently references the Free Notyf asset through the hard-coded URL `/wp-content/plugins/instantio/...`. This is unsafe for renamed plugin directories, custom content directories, subdirectory installations, and some multisite configurations. It should eventually use a URL supplied by Free or `plugins_url()`.

## WooCommerce integration

Both plugins declare HPOS compatibility via `before_woocommerce_init`.

Free is responsible for the WooCommerce requirement notice and loads most runtime controllers only around WooCommerce availability. Pro relies on Free but still instantiates its admin/frontend controllers even when Free is absent; individual methods then guard some, but not all, behavior.

The templates reproduce or call many standard WooCommerce cart/checkout hooks. When updating templates, compare against the supported WooCommerce versions and preserve third-party gateway, shipping, tax, coupon, and checkout-field hooks.

Pro integrations currently include:

- a general support integration file;
- a Dokan Pro / Stripe Connect integration loaded when `Dokan_Pro` exists.

## Known risks found during architecture review

These are observations for future work, not changes made in this pass.

1. **Version comparison is unsafe.** Free casts Pro versions to `double`; for example semantic versions with more than one dot do not compare reliably. Use `version_compare()` in a dedicated compatibility gate.
2. **Pro initializes without Free.** The Pro bootstrap creates Admin/App controllers even when `INSTANTIO` is absent. The dependency gate should prevent functional initialization and only show an admin dependency notice.
3. **License gating is split.** Some Pro checkout hooks are registered before the license failure return, while others are gated. Define one intentional policy and apply it consistently.
4. **Duplicate admin asset handles.** Free and Pro register identical handles with different URLs.
5. **Hard-coded Free asset URL in Pro.** The Notyf path assumes `/wp-content/plugins/instantio`.
6. **Pro uninstall is incorrect.** It reads the value of `wiopt` and passes that value to `delete_option()` rather than deleting the option name. More importantly, a Pro uninstall should not delete the shared Free configuration unless product policy explicitly requires full data removal.
7. **Legacy Pro settings framework is stale.** `wooinstant/admin/tf-options/TF_Options.php` references constants and files not present in the current tree and should not be treated as active architecture.
8. **Translation and escaping debt.** Several Pro templates/controllers contain untranslated literal strings, variable translation calls, direct output, and incorrect escaping for HTML-returning WooCommerce filters. These require careful output-context fixes, not blanket escaping.
9. **Remote license handler has high authority.** The custom updater contains server-triggered license cleanup and plugin deletion paths. Security review should verify authentication, authorization, replay resistance, and request signing before release.
10. **Schema duplication invites drift.** Free and Pro maintain large near-duplicate setting schemas. A long-term design should define one base schema and let Pro add/replace specific fields through filters.

## Safe change workflow

Before changing shared behavior:

1. Identify whether Free owns the implementation and Pro extends it, or whether licensed Pro replaces the schema/template.
2. Search the exact option ID, hook name, CSS class, script handle, AJAX action, and localized JS key in both repositories.
3. Record the current stored PHP type and browser response shape.
4. Make the smallest compatible change in the owning layer.
5. Run PHP syntax/static checks for both plugins.
6. Test Free only: activate, settings save/reload, cart add/update/remove/coupon, every Free layout, guest and logged-in behavior.
7. Test Free + Pro with a valid license: Pro schema save/reload, cart-only and checkout layouts, multistep/single-step, payment gateways, shipping refresh, cross-sells/upsells, mobile mode, minified/unminified assets.
8. Test Pro active while Free is inactive and an invalid/expired license to ensure graceful admin behavior and no frontend fatal errors.
9. Check browser console, AJAX response bodies, PHP error log, WooCommerce logs, and page source for warnings or debug output.
10. Update the change record in `docs/changed-files-record.md` and add a focused note for any new compatibility decision.

## Current boundary for future work

The architecture is now mapped sufficiently to begin targeted tasks. Before implementation, each task still needs a focused trace of the exact option/hook/template involved and a test matrix covering both product modes. This document describes the current local source and should be updated whenever boot order, licensing, option ownership, or extension hooks change.

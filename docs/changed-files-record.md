# Instantio complete changed-file record

## Scope

- Change period: 2026-08-08, from the initial clean Git worktree through the completed Plugin Check remediation.
- Source directory: `wp-content/plugins/instantio/`.
- This inventory was generated from the current Git diff and untracked-file list.
- No commit was created, so every remediation change is still visible in the working tree.
- The release ZIP is outside the plugin directory and is not part of this source-file inventory.

## Functional code files modified

These files can directly affect plugin behavior and should be reviewed first when investigating lost functionality.

### Plugin bootstrap and shared functions

- `instantio.php` — metadata, quick-view request handling, compatibility annotations, initialization-related edits, and complete removal of the Appsero bootstrap.
- `functions.php` — API replacements, promotion callbacks, sanitization, output escaping, and compatibility annotations.
- `composer.json` — license identifier changed to `GPL-3.0-or-later`.
- `readme.txt` — minimum WordPress version, license metadata, and short description.

### Public JavaScript

- `assets/app/js/instantio-script.js` — nonce payloads added to cart update, removal, empty-cart, and coupon actions.
- `assets/app/js/instantio-script.min.js` — distributed copy synchronized with the readable JavaScript file.

### Public controllers and templates

- `includes/controller/App.php` — cart AJAX validation, nonce verification, sanitization, rendering escaping, compatibility annotations, and removal of layout debug output that corrupted AJAX JSON.
- `includes/controller/Assets.php` — compatibility annotation for the established namespace and hooks.
- `includes/controller/checkout_editor.php` — checkout field persistence, input handling, output escaping, notices, and compatibility annotations.
- `includes/controller/ins-checkout-editor.php` — direct-access protection and compatibility annotation.
- `includes/controller/icon-svg.php` — direct-access protection, SVG output handling, and compatibility annotation.
- `includes/layouts/layout-1.php` — output handling and compatibility annotation.
- `includes/layouts/layout-2.php` — output handling and compatibility annotation.
- `includes/layouts/layout-3.php` — output handling and compatibility annotation.
- `includes/templates/cart-modern.php` — cart item, price, metadata, quantity, and upsell output handling.
- `includes/templates/ins_single_step_cart.php` — cart item, price, metadata, quantity, and upsell output handling.

### Administrator controllers

- `includes/controller/Admin.php` — review notice, plugin URL, AJAX data, escaping, and WooCommerce activation link changes.
- `includes/controller/class-dashboard-widget.php` — output escaping changes.
- `includes/controller/class-promo-notice.php` — date/time API, translation-domain, and output changes.
- `includes/controller/class-setup-wizard.php` — nonce/capability validation, request sanitization, JSON responses, redirects, and output escaping.
- `includes/controller/dashboard-promo-notice.php` — asset and output escaping changes.

### Removed Appsero SDK integration

- Removed `includes/app/` in full, including `Client.php`, `Insights.php`, `License.php`, `Updater.php`, its nested Composer manifest, and development configuration.
- Removed `appsero.json`.
- Removed the Appsero telemetry disclosure from `readme.txt`.

## Settings framework files modified

### Settings framework core

- `admin/tf-options/Ins_TF_Options.php` — local dependency enqueues, explicit footer loading, upload handling, request validation, URLs, and compatibility annotations.
- `admin/tf-options/assets/js/ins-options.js` — switched the code editor from dynamic CDN loading to WordPress's native Code Editor assets; settings saves now request native JSON and clear the loading state on both success and failure.
- `admin/tf-options/classes/INS_Metabox.php` — nonce input handling and sanitization.
- `admin/tf-options/classes/Ins_ChangeLog.php` — compatibility annotation for included data.
- `admin/tf-options/classes/Ins_TF_Settings.php` — extensive administrator output escaping and compatibility annotations.
- `admin/tf-options/classes/TF_Taxonomy_Metabox.php` — capability, nonce, and input handling.
- `admin/tf-options/options/tf-menu-icon.php` — included-variable compatibility annotation.
- `admin/tf-options/options/tf-settings.php` — missing translation domains and compatibility annotations.

### Individual field components

- `admin/tf-options/fields/checkbox/INS_checkbox.php`
- `admin/tf-options/fields/code_editor/INS_codeeditor.php`
- `admin/tf-options/fields/color/INS_color.php`
- `admin/tf-options/fields/date/INS_date.php`
- `admin/tf-options/fields/editor/INS_editor.php`
- `admin/tf-options/fields/file/INS_file.php`
- `admin/tf-options/fields/gallery/INS_gallery.php`
- `admin/tf-options/fields/icon/INS_icon.php`
- `admin/tf-options/fields/image/INS_image.php`
- `admin/tf-options/fields/imageselect/INS_imageselect.php` — output escaping plus backward-compatible scalar persistence for Layout and Design image selectors; unexpected array requests are normalized to one scalar.
- `admin/tf-options/fields/notice/INS_notice.php`
- `admin/tf-options/fields/number/INS_number.php`
- `admin/tf-options/fields/radio/INS_radio.php`
- `admin/tf-options/fields/repeater/INS_Repeater.php`
- `admin/tf-options/fields/select/INS_select.php`
- `admin/tf-options/fields/select2/INS_select2.php`
- `admin/tf-options/fields/switch/INS_switch.php`
- `admin/tf-options/fields/tab/INS_tab.php`
- `admin/tf-options/fields/text/INS_text.php`
- `admin/tf-options/fields/textarea/INS_textarea.php`
- `admin/tf-options/fields/time/INS_time.php`

All field-component changes concern contextual escaping of names, identifiers, values, labels, URLs, state, dependencies, or generated attribute fragments. The code-editor component additionally changed from CDN CodeMirror to `wp_enqueue_code_editor()`.

## New release and documentation files

- `.distignore` — WordPress.org artifact exclusion rules; obsolete Appsero-specific exclusions were removed after deleting the SDK.
- `docs/plugin-check-remediation.md` — plan, chronological work log, validation record, and remaining manual tests.
- `docs/release-packaging.md` — artifact composition and WordPress.org packaging rules.
- `docs/changed-files-record.md` — this complete inventory.

## New locally bundled dependency files

### Flatpickr 4.6.13

- `admin/tf-options/assets/libs/flatpickr/LICENSE.md`
- `admin/tf-options/assets/libs/flatpickr/flatpickr.min.css`
- `admin/tf-options/assets/libs/flatpickr/flatpickr.min.js`

### Font Awesome 4.7.0

- `admin/tf-options/assets/libs/font-awesome-4/LICENSE-AND-README.md`
- `admin/tf-options/assets/libs/font-awesome-4/css/font-awesome.min.css`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/FontAwesome.otf`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/fontawesome-webfont.eot`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/fontawesome-webfont.svg`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/fontawesome-webfont.ttf`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/fontawesome-webfont.woff`
- `admin/tf-options/assets/libs/font-awesome-4/fonts/fontawesome-webfont.woff2`

### Font Awesome Free 5.15.4

- `admin/tf-options/assets/libs/font-awesome-5/LICENSE.txt`
- `admin/tf-options/assets/libs/font-awesome-5/css/all.min.css`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-brands-400.eot`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-brands-400.svg`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-brands-400.ttf`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-brands-400.woff`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-brands-400.woff2`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-regular-400.eot`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-regular-400.svg`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-regular-400.ttf`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-regular-400.woff`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-regular-400.woff2`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-solid-900.eot`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-solid-900.svg`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-solid-900.ttf`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-solid-900.woff`
- `admin/tf-options/assets/libs/font-awesome-5/webfonts/fa-solid-900.woff2`

### Font Awesome Free 6.4.2

- `admin/tf-options/assets/libs/font-awesome-6/LICENSE.txt`
- `admin/tf-options/assets/libs/font-awesome-6/css/all.min.css`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-brands-400.ttf`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-brands-400.woff2`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-regular-400.ttf`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-regular-400.woff2`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-solid-900.ttf`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-solid-900.woff2`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-v4compatibility.ttf`
- `admin/tf-options/assets/libs/font-awesome-6/webfonts/fa-v4compatibility.woff2`

### Remix Icon 2.5.0

- `admin/tf-options/assets/libs/remixicon/License`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.css`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.eot`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.less`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.svg`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.symbol.svg`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.ttf`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.woff`
- `admin/tf-options/assets/libs/remixicon/fonts/remixicon.woff2`

### Select2 4.1.0-rc.0

- `admin/tf-options/assets/libs/select2/LICENSE.md`
- `admin/tf-options/assets/libs/select2/css/select2.min.css`
- `admin/tf-options/assets/libs/select2/js/select2.min.js`

### WP Color Picker Alpha

- `admin/tf-options/assets/libs/wp-color-picker-alpha/LICENSE`
- `admin/tf-options/assets/libs/wp-color-picker-alpha/SOURCE-COMMIT`
- `admin/tf-options/assets/libs/wp-color-picker-alpha/wp-color-picker-alpha.js`

## Highest-risk areas for the reported functionality loss

Review these areas first because their changes affect runtime behavior rather than only output formatting:

1. `admin/tf-options/Ins_TF_Options.php` and the newly bundled libraries — administrator dependency loading changed from CDNs to local files.
2. `admin/tf-options/fields/code_editor/INS_codeeditor.php` and `admin/tf-options/assets/js/ins-options.js` — CodeMirror loading changed to WordPress's native editor API.
3. `includes/controller/App.php` plus both `instantio-script` files — cart requests now require and send nonces.
4. `includes/controller/class-setup-wizard.php` — saving now requires a valid nonce and `manage_options` capability.
5. `includes/controller/checkout_editor.php` — checkout custom-field persistence and output paths changed.
6. `admin/tf-options/Ins_TF_Options.php` — font uploads now use the WordPress filesystem API.
7. Appsero no longer participates in Instantio runtime behavior because its bootstrap and complete SDK directory were removed.

## Important distinction

Plugin Check passing with zero findings confirms static policy compliance for the release artifact. It does not prove functional equivalence. Because functionality loss has now been observed, the next task should be a regression investigation comparing each high-risk diff against the original Git version and testing the affected feature in the browser before retaining or revising that change.

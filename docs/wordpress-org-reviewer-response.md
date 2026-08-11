# Copy-ready WordPress.org Plugins Team response

Subject: Instantio guideline remediation and request for re-review

Hello Plugins Team,

Thank you for the detailed review of Instantio. We completed a full source, packaging, privacy, licensing, and functionality remediation and prepared an updated `3.3.34` release artifact.

The reported issues were addressed as follows:

1. **Trialware and locked built-in functionality**
   - Removed the generic locked/Pro-field mechanism from the WordPress.org plugin.
   - Fully enabled the Checkout Editor and the other features that remain in Instantio Free.
   - Removed Pro-owned controls from the Free schema instead of shipping them disabled.
   - WooInstant Pro remains a separately distributed add-on and is not included in the WordPress.org ZIP.
   - Free settings saves preserve shared add-on option data without requiring a license or changing the existing `wiopt` storage contract.

2. **GPL-incompatible GSAP code**
   - Removed `assets/app/js/gsap.min.js`, its enqueue, and all GSAP/GreenSock usage.
   - Replaced the required effects with the browser Web Animations API and added reduced-motion behavior.

3. **Telemetry, phoning home, and external services**
   - Removed the complete Appsero SDK and bootstrap, including telemetry, opt-out requests, IP lookup, updater, and deactivation-feedback behavior.
   - Removed automatic Themefic promotion and dynamic-pricing API requests.
   - Removed unused map/geocoding integration.
   - The release artifact contains no automatic `wp_remote_get()`, `wp_remote_post()`, or `wp_remote_request()` calls.

4. **Remote scripts, styles, fonts, and images**
   - Replaced remote admin dependencies with local GPL-compatible copies or WordPress core APIs.
   - Included the distributed license files for bundled libraries.
   - Removed automatic remote promotional images.

5. **Composer and library findings**
   - The root `composer.json` is included in the release artifact.
   - The obsolete Appsero library and its global collision risk were removed completely.

6. **Technical and security findings**
   - Removed the unused unclosed output buffer.
   - Removed direct upload handling and retained validated WordPress filesystem handling.
   - Added capability and nonce enforcement to settings and cart AJAX boundaries.
   - Corrected contextual escaping, translation comments, file loading, and HPOS-compatible checkout-field persistence.

7. **Readme and packaging**
   - The short description is 96 characters.
   - The readme now clearly distinguishes fully functional Free features from the separately distributed Pro add-on.
   - The ZIP excludes Git files, internal remediation docs, lock files, source maps, Sass sources, and Pro code.
   - The artifact contains one `instantio/` root and includes all required runtime files and licenses.

Validation completed:

- Plugin Check against the isolated release artifact: no errors found.
- All 59 packaged PHP files pass PHP 8.2 syntax checks.
- Readable and minified JavaScript paths pass syntax and browser parity tests.
- Free-only and licensed Free+Pro settings preservation tests pass.
- Cart, variable-product popup, coupons, checkout validation, guest checkout, account creation, order notes, alternate shipping, HPOS orders, accessibility, responsive behavior, and two default themes pass in the local regression environment.
- The artifact contains no Appsero/IP-service/GSAP code, remote enqueues, automatic HTTP calls, or bundled Pro plugin files.

Suggested review steps:

1. Install and activate Instantio Free with WooCommerce.
2. Open Instantio settings and save Layout, Design, Cart Icon, Optimization, and Checkout Editor changes.
3. Add, clone, reorder, disable, and remove a Checkout Editor field.
4. Test Direct Checkout, Side Cart, Popup Cart, variable-product quick view, quantity changes, coupons, and item removal.
5. Enable optimized assets and repeat an add-to-cart/cart-update smoke test.
6. Run Plugin Check against the installed plugin.

No license key or paid service is required to use any functionality included in the WordPress.org plugin.

Please re-review the updated Instantio plugin. We appreciate your time and guidance.

Kind regards,  
Themefic

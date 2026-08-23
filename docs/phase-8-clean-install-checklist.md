# Phase 8 — Clean-Install Smoke Checklist

Artifact: `instantio-3.3.34.zip` — superseded by the WooInstant P3 nonce integration; rebuild required  
Previous SHA-256: `1bddca6902e3852bca89df06815e5bda62185bbdd0741f0d730546425a912033`

## Completed artifact checks

- [x] ZIP contains one top-level `instantio/` directory.
- [x] ZIP integrity test passes.
- [x] Staged tree and extracted ZIP manifests match byte-for-byte.
- [x] Plugin header version and readme stable tag both equal `3.3.34`.
- [x] WooCommerce tested-up-to metadata reflects the completed 11.0 regression.
- [x] Short description is 96 characters.
- [x] Root `composer.json` is included.
- [x] Git metadata, internal docs, lock file, maps, Sass/SCSS, node modules, and WooInstant Pro are excluded.
- [x] No Appsero, IP lookup, GSAP/GreenSock, remote enqueue, or automatic HTTP-call code exists in the artifact.
- [x] Bundled third-party assets include their distributed license files.
- [x] All 59 staged PHP files pass PHP 8.2 syntax lint.
- [x] Readable and minified Instantio JavaScript files pass syntax checks.
- [x] Plugin Check against the isolated staged artifact reports no errors.

## Separate clean-site installation

These steps must be run on a separate disposable WordPress installation so the existing development site's plugin directory and settings are not reused.

- [x] Install WordPress with WooCommerce but without an existing Instantio directory or `wiopt` option.
- [x] Upload and activate `instantio-3.3.34.zip` through Plugins > Add New > Upload Plugin.
- [x] Confirm activation produces no PHP warning, fatal error, or unexpected outbound network request.
- [x] Complete the Instantio setup wizard.
- [x] Save Layout, Design, Cart Icon, Mobile, Optimization, and Checkout Editor settings; reload and verify persistence.
- [ ] Add, clone, reorder, disable, delete, and reset a Checkout Editor field.
- [ ] Test Direct Checkout, Side Cart, and Popup Cart.
- [ ] Test simple, variable, grouped, virtual, and sold-individually products.
- [ ] Test quantity, remove, empty-cart, valid coupon, invalid coupon, and variable-product popup paths.
- [ ] Repeat core cart smoke tests with optimized assets enabled.
- [ ] Verify desktop keyboard behavior, Escape close, focus restoration, reduced motion, and 390 px responsive layout.
- [ ] Run Plugin Check against the installed ZIP.
- [ ] Confirm uninstall behavior matches the documented data-retention policy.

## Release gate

The artifact is statically ready. WordPress.org upload should wait until the separate clean-site installation section is completed or an equivalent disposable-site test is documented.

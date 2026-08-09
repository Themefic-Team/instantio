# Instantio WordPress.org release packaging

## Purpose

The development repository contains documentation, source styles, and build metadata that should not be included in the WordPress.org release ZIP. The root `.distignore` is the authoritative exclusion list for that artifact.

## WordPress.org-specific exclusions

- Git metadata and ignore files.
- Composer lock files and nested development manifests that are not required at runtime. The root `composer.json` remains beside the runtime `vendor` directory and declares the plugin's GPLv3-compatible license.
- Internal remediation documentation.
- PHP_CodeSniffer and PHP CS Fixer configuration.
- SCSS/Sass sources and source maps.
- Appsero is no longer part of Instantio; no telemetry, Appsero licensing, deactivation feedback, or custom updater files are shipped.

## Bundled runtime dependencies

The following dependencies are stored under `admin/tf-options/assets/libs/` so the plugin does not load executable code, styles, icon fonts, or editor modes from a CDN:

- Font Awesome 4.7.0
- Font Awesome Free 5.15.4
- Font Awesome Free 6.4.2
- Remix Icon 2.5.0
- Select2 4.1.0-rc.0
- Flatpickr 4.6.13
- The WordPress 4.9+ Code Editor API supplies CodeMirror; no duplicate CodeMirror core files are shipped.
- WP Color Picker Alpha, pinned to the commit recorded in its `SOURCE-COMMIT` file

Each dependency directory includes its distributed license or upstream licensing documentation.

## Required release verification

1. Build the ZIP using `.distignore`.
2. Confirm none of the excluded paths are present.
3. Search the extracted artifact for remote `wp_enqueue_script()` and `wp_enqueue_style()` sources.
4. Run Plugin Check against the extracted artifact in a working WordPress installation.
5. Smoke-test the settings editor, icon selectors, date fields, color fields, cart actions, and checkout.

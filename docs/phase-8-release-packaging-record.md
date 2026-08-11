# Phase 8 — Release Packaging and WordPress.org Handoff

Date started: 2026-08-10  
Source: Instantio Free 3.3.34  
Status: In progress

> Superseded artifact notice (2026-08-10): WooInstant P3 required Free's order-review AJAX caller to send the existing Instantio nonce. The ZIP/hash recorded below predates that synchronized integration change and must not be used for the final clean-install gate. A matching Free/Pro pair will be rebuilt during the final artifact phase.

## Objectives

1. Build the release from the current Instantio Free source without modifying the live plugin directory structure.
2. Apply `.distignore` so repository metadata, internal documentation, source maps, Sass sources, and development-only lock files are not shipped.
3. Audit plugin metadata, licensing, bundled dependencies, forbidden files, and Free/Pro separation.
4. Validate the staged tree and ZIP independently from the working source tree.
5. Produce clean-install smoke instructions and a copy-ready WordPress.org reviewer response.

## Locked source metadata

- Plugin version: `3.3.34`
- Readme stable tag: `3.3.34`
- WordPress requires at least: `4.9`
- WordPress tested up to: `7.0`
- PHP requires: `7.4`
- WooCommerce requires at least: `7.0`
- WooCommerce tested up to before Phase 8: `10.7`; aligned to the completed local regression target `11.0` during Phase 8
- Phase 7 local WooCommerce regression version: `11.0.0`
- License: `GPLv3`

## Distribution exclusions

The repository `.distignore` excludes:

- `.git`, `.gitignore`, and `.distignore`;
- `composer.lock`;
- the internal `docs` directory;
- source maps;
- Sass and SCSS source files.

## Final artifact

- ZIP: `/home/mhemelhasan/Local Sites/dev/app/public/wp-content/instantio-3.3.34.zip`
- Isolated stage: `/tmp/instantio-release-approved.o06YpL/instantio`
- File count: `209`
- PHP file count: `59`
- ZIP size: approximately `5.8 MB`
- SHA-256: `1bddca6902e3852bca89df06815e5bda62185bbdd0741f0d730546425a912033`

The live plugin directory was not replaced or moved. The ZIP was built from an isolated `.distignore`-filtered copy.

## Artifact audit

- one top-level `instantio/` directory;
- ZIP integrity passed;
- staged and extracted ZIP manifests matched byte-for-byte;
- zero forbidden development or Pro entries;
- zero Appsero, IP lookup, GSAP/GreenSock, remote enqueue, or automatic HTTP-call matches;
- root `composer.json` present;
- eight bundled dependency license files present;
- short description length: 96 characters;
- plugin version and stable tag: `3.3.34`;
- WooCommerce tested-up-to metadata aligned to `11.0` after the completed local 11.0 regression.

## Validation

- all 59 staged PHP files passed PHP 8.2 syntax lint;
- readable and minified Free JavaScript passed syntax checks;
- Plugin Check accepted the isolated staged path and returned `Success: Checks complete. No errors found.`;
- the final ZIP and final stage contain identical file bytes.

## Readme release cleanup

- replaced outdated “unlock” wording with a clear link to explore the separately distributed Pro add-on;
- clarified that the Free cart, layout, design, optimization, and Checkout Editor functionality is fully functional;
- removed the incorrect statement that adding checkout fields is Pro-only;
- expanded the 3.3.34 changelog with compliance, privacy, licensing, security, and accessibility work.

## Settings-page warning cleanup

- Reproduced the warning flood from Local's PHP error log: `Undefined variable $is_pro` occurred once per rendered Free settings field in `admin/tf-options/Ins_TF_Options.php`.
- Root cause: Phase 2 intentionally removed Free-side Pro locks, but the old Pro-badge conditional still read the deleted `$is_pro` variable.
- Removed only the stale Free Pro-badge branch. This does not read, normalize, migrate, or write `wiopt`, and it does not change any saved option value format.
- A WordPress CLI renderer check promoted PHP warnings to exceptions and passed while rendering a real Free text field.
- PHP syntax and `git diff --check` passed after the correction.

## Handoff documents

- `phase-8-clean-install-checklist.md` separates completed artifact validation from the pending separate clean-site installation test.
- `wordpress-org-reviewer-response.md` contains a copy-ready issue-by-issue response and concise reviewer steps.

## Current gate

Artifact build and static validation: **passed**.

Separate clean-site ZIP installation: **pending**. The existing development site cannot prove a clean install because it already contains Instantio configuration and the development source directory.

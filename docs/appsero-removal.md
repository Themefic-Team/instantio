# Appsero removal record

## Date

2026-08-09

## Removed runtime behavior

- Appsero client initialization from `instantio.php`.
- Administrator telemetry opt-in notice.
- Diagnostic and usage tracking requests.
- Weekly tracking cron callback.
- Plugin deactivation feedback dialog and AJAX endpoint.
- Bundled Appsero license client.
- Bundled custom updater.

## Removed files

- Complete `includes/app/` directory.
- `appsero.json` packaging configuration.
- Appsero telemetry disclosure in `readme.txt`.
- Appsero-specific `.distignore` entries.

## Legacy database cleanup targets

The following records are checked on the Local development site and removed when present:

- Option `instantio_allow_tracking`.
- Option `instantio_tracking_notice`.
- Option `instantio_tracking_last_send`.
- Cron hook `instantio_tracker_send_event`.

No customer, order, product, cart, checkout, or Instantio settings data is part of this cleanup.

## Verification

- Repository scan reports zero Appsero/project-ID references outside historical documentation.
- `includes/app/` and `appsero.json` are absent.
- The Local database contained none of the exact legacy Appsero options or cron events listed above, so no database rows were deleted.
- PHP syntax checks passed for all 52 remaining first-party PHP files.
- Focused WordPress escaping and internationalization checks passed with zero findings.
- JavaScript syntax and `git diff --check` passed.
- A final live WordPress runtime/Plugin Check rerun was attempted after removal, but the Local MySQL service had stopped. This is an environment verification gate, not a source failure.

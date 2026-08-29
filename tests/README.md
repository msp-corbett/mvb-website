# Unit tests

Fast, isolated PHPUnit tests for mvb-core's and the theme's pure business
logic — date conversion, the list-books repeater's save-time sanitization,
Shop Details sanitization/merging, the REST reshuffle payload shape, and
the per-page title/meta-description rules. They use
[Brain Monkey](https://brain-wp.github.io/BrainMonkey/) to stub the handful
of WordPress core functions this logic calls (`sanitize_text_field` and
similar) — no WordPress install, database, or Docker required.

```
composer install
composer test        # or: vendor/bin/phpunit
```

## What this suite covers, and what it doesn't

Only logic extracted into `inc/pure-functions.php` (in both the plugin and
the theme) is covered here — functions with no top-level side effects, so
they can be `require`d directly with no WordPress loaded. Hook
registration (`add_action`, `register_post_type`, `add_meta_box`, …),
template rendering, and anything that genuinely needs a database (the REST
endpoint's `WP_Query`, actually saving/reading post meta) is wired in
separate files that call this tested logic, and isn't unit-tested here —
that's integration-test territory.

If Docker becomes available (this repo's `.wp-env.json` already has a
`tests` environment on port 8889 for it), the natural next step is a
`tests/Integration` suite using `WP_UnitTestCase` via `wp-env run
tests-phpunit phpunit`, for the parts that actually need WordPress running
— e.g. confirming `/wp-json/mvb/v1/picks/random` really excludes/tops-up
correctly against real posts, or that a past-dated event really drops out
of `page-events.php`'s query. Until then, those are covered by the manual
QA pass in the project plan.

## Writing new tests

Follow the pattern already here: write the test against a function that
doesn't exist yet (or doesn't yet behave the way the test expects), run
`vendor/bin/phpunit` to see it fail, then add/adjust the function in the
relevant `pure-functions.php` until it passes. Keep business rules —
anything with a branch, an edge case, or a "when X, do Y" — in a pure
function there rather than buried inline in a hook callback or template,
so the rule stays testable.

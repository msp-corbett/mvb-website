# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this repo is

A custom WordPress theme (`wp-content/themes/mvb`) plus a companion plugin
(`wp-content/plugins/mvb-core`) for Matakana Village Books. WordPress core,
uploads, and third-party plugins are deliberately **not** tracked here — the
repo root just happens to look like a WP install so the two tracked folders
sit at their real paths. There is no build step: PHP/CSS/JS are served as
written.

## Commands

```bash
composer install
composer test                       # or: vendor/bin/phpunit
vendor/bin/phpunit --filter testName          # a single test
vendor/bin/phpunit tests/Unit/SeoMetaTest.php # a single file

npx @wordpress/env start            # WP + MySQL at localhost:8888 (needs Docker)
npx @wordpress/env stop
```

The unit suite needs **no** Docker, database, or WordPress — Brain Monkey
stubs the handful of core functions the tested code calls.

wp-env live-mounts the theme and plugin, so edits show on refresh. After a
first start, activate MVB Core + the Matakana Village Books theme and set
permalinks to anything other than "Plain" (the Bookshelf/Events pages and the
REST route need it). `.wp-env.json` has `testsEnvironment: false`, so there is
no `tests-wordpress` container — a future integration suite would need that
re-enabled.

## The pure-functions convention (most important rule here)

Both the plugin and the theme have an `inc/pure-functions.php`. These files
are `require`d directly by `tests/bootstrap.php` with no WordPress loaded, so:

- Nothing at file scope beyond function declarations — no `add_action`, no
  `register_post_type`, no `define`. Those files also intentionally have **no
  `ABSPATH` guard** (every other PHP file does).
- They may *call* WordPress functions (`sanitize_text_field`, `esc_url_raw`);
  Brain Monkey stubs them in tests.

Any business rule — a branch, an edge case, a "when X do Y" — belongs in one
of those two files, with the hook callback / template that uses it staying a
thin wrapper. Existing examples of the split:

| Pure (tested) | Wired in (untested) |
|---|---|
| `mvb_ymd_to_input_date`, `mvb_input_date_to_ymd` | `inc/meta-boxes.php` event save |
| `mvb_sanitize_list_books_rows` | `mvb_save_list_meta` |
| `mvb_default_shop_details`, `mvb_merge_shop_details`, `mvb_sanitize_shop_details`, `mvb_shop_hours_rows` | `inc/shop-details-page.php`; header, footer, front page |
| `mvb_build_pick_payload`, `mvb_build_preorder_payload` | `mvb_format_pick_for_rest`, `mvb_format_preorder_for_rest` (read `WP_Post`/meta) |
| `mvb_ym_to_input_month`, `mvb_input_month_to_ym`, `mvb_arrival_label` | `inc/meta-boxes.php` preorder save, `template-parts/pick-card.php` |
| `mvb_document_title`, `mvb_meta_description` | `inc/seo-meta.php` (real query conditionals) |

Tests extend `MVB\Tests\Unit\TestCase`, which handles Brain Monkey setup/teardown.

## Content model

Four CPTs registered in `mvb-core`, all edited from wp-admin with hand-built
native meta boxes (no ACF):

- `mvb_pick` — a shelf-talker. Meta: `book_author`, `picked_by`, `pick_note`,
  `buy_url`; cover = featured image; categorised by the `pick_kind` taxonomy.
- `mvb_preorder` — a "Coming soon" book. Same meta keys as `mvb_pick` (the
  meta box reuses `mvb_render_pick_field_rows` / `mvb_save_pick_fields`)
  plus `arrival_month`; shares the `pick_kind` taxonomy. Rendered with the
  same `pick-card` template, which adds a diagonal corner banner ("Mar 2027")
  for this post type. Doesn't expire on its own — staff unpublish it.
- `mvb_list` — a top-ten list. Meta: `list_subtitle`, plus `list_books`, a
  serialized array of `{title, author, url}` rows saved by a hand-rolled
  repeater (`assets/admin-repeater.js` clones a `<template>` row, renumbering
  `__INDEX__`).
- `mvb_event` — meta: `event_date`, `event_time`, `event_cost`.

Shop hours/address/phone/email/map live in one option, `mvb_shop_details`
(Settings → Shop Details), read anywhere via `mvb_get_shop_details()`.

### Two conventions that are easy to break

- **`event_date` is stored as `Ymd`** (e.g. `20260908`) so it string-sorts and
  compares correctly in `WP_Query` meta queries. HTML date inputs need
  `Y-m-d`, hence the two conversion functions. Past events are excluded by the
  query in `mvb_get_upcoming_events()`, not filtered downstream.
- **The "top up" rule for random picks**: when excluding already-shown picks
  would return fewer than requested (small catalog), re-query the full set
  rather than showing fewer. This rule is implemented *twice* —
  `mvb_get_random_picks()` in the theme and `mvb_rest_random_posts()` in the
  plugin (both take a post type, serving picks and preorders). Change one,
  change the other.
- **`arrival_month` is stored as `Ym`** (e.g. `202703`), the month-only
  sibling of `event_date`; `<input type="month">` needs `Y-m`.
- **Because `pick_kind` is shared**, `mvb_get_used_pick_kinds( $posts )` is
  scoped to the posts on the page, so a kind used only by preorders doesn't
  show as an empty filter on the Bookshelf (and vice versa).

## Front end

Everything is server-rendered; the two JS files are progressive enhancements
that guard-clause out on pages lacking their markup.

- `assets/js/reshuffle.js` → `GET /wp-json/mvb/v1/{picks,preorders}/random?count=&exclude=`
  (the only REST routes), the homepage "show me three more" buttons. Each
  `[data-reshuffle]` button drives the shelf it `aria-controls`; the shelf's
  `data-reshuffle-endpoint` picks the route. Its `cardMarkup()` mirrors
  `template-parts/pick-card.php` — keep them in step. REST root and nonce
  reach it via `wp_localize_script` as `MVB_REST`.
- `assets/js/filter.js` → Bookshelf filter bar; the full grid is already
  rendered with `data-kind` per card, so it only toggles `hidden`.

Page templates are assigned in wp-admin via Page Attributes:
`templates/page-bookshelf.php` ("Bookshelf"), `templates/page-coming-soon.php`
("Coming Soon", slug `coming-soon`) and `templates/page-events.php` ("Events"). `inc/seo-meta.php` detects those pages with `is_page_template()`,
so renaming a template file changes the title/description behaviour — update
`mvb_seo_context()` too.

Styles are all in `assets/css/main.css`; `style.css` holds only the required
theme header. Assets are cache-busted with `filemtime()`.

Title tags and meta descriptions are handled in-house (`inc/seo-meta.php`) —
no SEO plugin, by preference.

## Deployment

GoDaddy Managed WordPress. Only the two tracked folders reach production, via
SFTP/File Manager upload to staging then a staging → production push. No CI
deploy exists yet.

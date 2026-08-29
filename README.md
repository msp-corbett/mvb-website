# Matakana Village Books — WordPress site

Custom WordPress theme + companion plugin for Matakana Village Books, a
boutique independent bookshop in Matakana Village, NZ. Replaces the shop's
previous default WordPress site.

Only the theme and plugin are tracked here — WordPress core, uploads, and
third-party plugins are never committed (see `.gitignore`).

```
wp-content/
├── themes/mvb/          the site's theme: templates, styles, JS, fonts
└── plugins/mvb-core/    custom post types, taxonomy, meta boxes, REST route
```

## Content model

- **Shelf-talker picks** (`mvb_pick`) — a staff-recommended book: title,
  author, cover image (featured image), who picked it, their note, a link to
  buy it on the shop's external online store, and a "kind" (Fiction,
  Aotearoa, Cooking, etc.) used for the Bookshelf filter.
- **Top ten lists** (`mvb_list`) — a curated list (e.g. "New Zealand", "For
  the kids") with a subtitle and a repeatable set of book rows
  (title/author/link). One list is shown at random on the homepage.
- **Events** (`mvb_event`) — date, time, cost, and a blurb. Past events drop
  out of every listing automatically.
- **Shop Details** — a single options page (Settings → Shop Details) for
  hours, address, phone, email, and the map link, used across the site.

All of the above are edited entirely from `wp-admin` — no plugins beyond
`mvb-core` are required.

## Local development

This project uses [`@wordpress/env`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/)
(wp-env), which needs Docker. From the repo root:

```
npx @wordpress/env start
```

This boots WordPress + MySQL with `wp-content/themes/mvb` and
`wp-content/plugins/mvb-core` live-mounted from this repo, at
http://localhost:8888 (wp-admin: http://localhost:8888/wp-admin, default
`admin` / `password`). Edits to theme/plugin files are reflected on refresh
— no build step. Stop with `npx @wordpress/env stop`.

After first start, activate the **MVB Core** plugin and the **Matakana
Village Books** theme from wp-admin if they aren't already active, and set
a permalink structure other than "Plain" (Settings → Permalinks → "Post
name") — the Bookshelf/Events pages and REST route need it.

## Pages to create in wp-admin

Create three pages and assign templates from the page attributes panel:

| Page slug   | Template                          |
|-------------|------------------------------------|
| (homepage)  | Set as the site's static front page (`templates/front-page.php` is used automatically) |
| `bookshelf` | "Bookshelf" (`templates/page-bookshelf.php`) |
| `events`    | "Events" (`templates/page-events.php`) |

Then set them under Settings → Reading (front page) and in the primary
navigation menu (Appearance → Menus).

## Deployment

Hosting is GoDaddy Managed WordPress. Only `wp-content/themes/mvb` and
`wp-content/plugins/mvb-core` need to reach production:

1. Initial launch: upload both folders to the site's staging environment
   (SFTP or GoDaddy's File Manager), QA there, then use GoDaddy's
   staging → production push.
2. Ongoing: either repeat the manual upload for future code changes, or add
   a GitHub Actions SFTP/rsync deploy step once hosting credentials are
   available — day-to-day content edits (picks, lists, events, hours) don't
   need a deploy at all, they happen directly in wp-admin.

See `/root/.claude/plans/root-claude-uploads-73c69daf-5bce-5ee1-virtual-newt.md`
for the full planning document this build follows.

# ConvergX WordPress theme

A WordPress theme port of the ConvergX Connect launch site
(<https://chipmartens.github.io/convergx-connect-launch/>), built by mode40.

Version 0.1.0. Status: **works end to end on a clean local install. Not deployed anywhere,
and not ready to be pointed at convergx.co. Read "Before this goes near convergx.co" first.**

---

## What is in here

| Path | What it is |
|---|---|
| `convergx/` | The theme. This is the deliverable. Zip it or symlink it into `wp-content/themes/`. |
| `seeders/` | WP-CLI scripts that create all the content: pages, speakers, team, agenda, hotels, sponsors. |
| `seeders/data/` | The content itself, extracted from the static site as JSON. |
| `seeders/media/` | Images the seeders import: speakers, team, hotels, sponsor marks, industry heroes. |
| `convergx.zip` | The theme, pre-zipped for Appearance → Themes → Add New. |
| [Releases](https://github.com/chipmartens/convergx-wordpress-theme/releases) | `convergx-complete-site.zip` — the ENTIRE working install: core, theme, plugins, uploads and the database. See its DEPLOY.md. Also `convergx-content.xml` (standard WordPress import file). |

**WordPress core and the plugins are deliberately NOT in this repo.** They are downloadable and
versioned by their own projects; committing them makes the repo enormous and instantly stale.
The setup below fetches them.

---

## Setup from scratch (about ten minutes)

Needs PHP 8.0+ and [WP-CLI](https://wp-cli.org/). No MySQL required: the steps below use SQLite.

```bash
# 1. WordPress core
mkdir convergx-wp && cd convergx-wp
wp core download

# 2. SQLite, so there is no database server to install
wp plugin install sqlite-database-integration
cp wp-content/plugins/sqlite-database-integration/db.copy wp-content/db.php
# edit wp-content/db.php: replace {SQLITE_IMPLEMENTATION_FOLDER_PATH} with the absolute path to
# wp-content/plugins/sqlite-database-integration, and {SQLITE_PLUGIN} with
# sqlite-database-integration/load.php

# 3. Install
wp core install --url=http://localhost:8944 --title="ConvergX" \
  --admin_user=admin --admin_password=admin --admin_email=you@example.com --skip-email

# 4. Plugins
wp plugin install secure-custom-fields --activate
wp plugin install woocommerce --activate

# 5. The theme
git clone <this repo> ../convergx-wp-theme
ln -s ../../../convergx-wp-theme/convergx wp-content/themes/convergx
wp theme activate convergx

# 6. Permalinks and store settings
wp rewrite structure '/%postname%/' && wp rewrite flush
wp option update woocommerce_coming_soon 'no'
wp option update woocommerce_store_pages_only 'no'
# Figure first, currency word after ("2,000 USD"), on classic pages AND the
# cart/checkout blocks: the blocks read this option, not the price-format filter.
wp option update woocommerce_currency_pos 'right_space'

# 7. Content
cd ../convergx-wp-theme/seeders
cp -R media/* ../../convergx-wp/wp-content/uploads/
cd ../../convergx-wp
for s in cx-seed-tree cx-seed-speakers cx-portraits \
         cx-seed-team cx-seed-congress cx-exact-rows \
         cx-products cx-woo-settings cx-cart-page; do
  wp eval-file ../convergx-wp-theme/seeders/$s.php
done

# 8. Serve
PHP_CLI_SERVER_WORKERS=8 php -S localhost:8944 -t .
```

### Use Secure Custom Fields, not ACF

The theme needs **Repeater** and **Flexible Content**. Those are ACF **Pro** (paid) features, but
they ship free in [Secure Custom Fields](https://wordpress.org/plugins/secure-custom-fields/),
WordPress.org's fork, which has the identical API. The theme works with either. SCF means no
licence to buy.

With plain free ACF, two field groups register unknown field types and render broken in wp-admin.

---

## How the theme is put together

- **Design system**: `convergx/assets/tokens.css` (130 custom properties) then
  `convergx/assets/styles.css`. Order matters: styles.css resolves everything through tokens.
  **tokens.css is byte-identical to the live static site**, so colours and type are exact.
  It is also the swap file: change it and the whole site re-skins.
- **Header, footer, mega menus, notice bar**: rendered by `assets/js/shell.js`, ported from the
  static site. When a menu is assigned under Appearance → Menus, WordPress feeds it to shell.js
  and that wins; with no menu assigned, shell.js uses its built-in definition, so the site is
  never navless mid-setup.
- **Content**: ACF/SCF fields, registered in PHP (`inc/acf.php`), one-way. Field groups are
  read-only in wp-admin on purpose, so there is no database-versus-file drift to reconcile.
- **Speakers and Team**: their own post types with their own admin screens. Add a speaker the way
  you would add a post: title is the name, editor is the bio, featured image is the portrait.
- **Forms**: five of them, defined in `inc/forms.php`. Submissions are **stored as posts before
  any email is sent**, so a mail failure never loses an enquiry. See *Form submissions* in the
  sidebar.

### Two body attributes do the colour work

`data-surface` (dark / light / muted) scopes every colour. `data-hero` (veil / photo) opts a page
into one of the two photographic hero components, and **half of each component's CSS lives behind
that attribute**. Emit the band class without the attribute and the page renders with the
photograph stacked above the copy instead of behind it, with no error.

### Things that are hardcoded, and must stay that way

The globe hero, the flow band, the proof bar and the launcher rows are PHP partials, not editable
fields. They are machine-generated SVG with `data-*` attributes rewritten at runtime by
`globe.js` and `flow.js`. Behind a rich-text field, the first editor save strips the SVG and the
data attributes: nothing errors, the globe just stops animating. The flow band additionally
registers a `cx-flow-limb` clipPath id, so two instances on one page collide.

---

## WooCommerce

The theme supports WooCommerce and restyles it through the design tokens: shop archive, product
page, cart, checkout, my-account, notices, buttons and form rows.

**There is deliberately no `woocommerce/` template directory, and adding one is how you break
checkout.** In WooCommerce 11 the cart and checkout are React blocks. Hand-authored replacements
drop the hook stack that Stripe's Apple Pay and Google Pay buttons mount on, silently, with no
error and no visible gap. Restyle with CSS and hooks; never copy a Woo template.

Prices render as `2,000 USD` rather than `$2,000.00`, to match the registration page. That runs
through Woo's own formatting pipeline, so it reaches emails and the Store API too. The trailing
`.00` is trimmed with Woo's trim-zeros filter, **not** by setting decimals to zero, because that
would round: a 10.50 fee would silently become 11. There is a check for it:

```bash
wp eval-file wp-content/themes/convergx/tests/price-format-check.php
```

### The products in this repo are TEST products

The seeders create three placeholder products (Standard 2,000 / Military 400 / Government 1,000
USD) so the registration page has something real to read prices from. **They are not ConvergX's
products and this is not ConvergX's store.** The registration page reads prices live from
WooCommerce by product ID; on a real install those IDs have to be repointed to the real products.

ConvergX's live product IDs, verified 2026-08-14: Standard `230`, Military `11306`,
Government `12764`. Note the Government product sits at a `-2` slug and there are superseded
duplicates in the catalogue, which is why the theme targets IDs and not slugs.

---

## Before this goes near convergx.co

Please read this section before installing anywhere that takes money.

**1. convergx.co's checkout is rendered by Divi Theme Builder, not by WooCommerce shortcodes or
blocks.** Verified live 2026-08-14: the checkout page's content contains only Divi shortcodes,
with no `[woocommerce_checkout]` and no checkout block. The product page renders 52 Divi module
instances.

Activating any non-Divi theme there leaves the site **with no payment form at all**. This theme
cannot fix that and neither could Woo template overrides, because an override only runs when the
shortcode or block runs. The four Woo pages need real Woo blocks in their content first, verified
with a test-mode transaction on a staging clone.

**2. The 5% admin fee has no identifiable source.** The products are flat 2,000 / 400 / 1,000 with
no fee on the object, and no known fee plugin is installed, so it is custom code. If it lives in
Divi's `functions.php`, switching themes deletes it from all 21 paid products, invisibly, until
reconciliation. Find it and move it into a standalone plugin before any theme change.

**3. `order-barcode-for-woocommerce` and a WooCommerce POS namespace are live** on that site and
may be the on-site check-in for the Congress. If so, product and checkout surfaces are event-day
operations, not just web pages.

**4. There is no staging environment** on that domain, and the Congress is Sep 22-24.

---

## Known gaps

- Body copy is at parity with the static site as of 2026-08-17: every substantive line inside
  `<main>` matches in both directions on all 23 pages, including duplicate counts. The one
  reported difference is `/about/`, where the static site renders the team bios in overlay
  panels after `</main>` while the theme renders them inline, so a `<main>`-scoped diff counts
  them as extra. The bio text itself is word for word identical.
- Sponsor marks default to **not cleared**. An uncleared mark does not render. That is the system
  working: showing another organisation's logo implies a relationship.

## Local admin

`http://localhost:8944/wp-admin` — `admin` / `admin` on a fresh local install. Change it if the
install is ever reachable by anyone else.

## Checking it against the live site

`seeders/compare-with-live.py` diffs every page against
<https://chipmartens.github.io/convergx-connect-launch/> and reports missing headings and
paragraphs per page.

```bash
python3 seeders/compare-with-live.py
```

As of 2026-08-17: **0 missing headings** everywhere. The remaining paragraph misses it reports
are pages where the deployed site lags the working static tree (the tree is the source the
seeders were matched against), plus the `/about/` overlay-versus-inline bio difference noted
under Known gaps.

It ignores two things on purpose. The `<noscript>` navigation fallback differs by design (the
static tree hand-maintains one per page; the theme has a single one), and WordPress texturizes
straight quotes into curly ones, which is a typographic improvement rather than a content
difference. Both were counted as ~75 false misses before they were excluded.

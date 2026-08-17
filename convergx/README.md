# ConvergX WordPress theme

Ports the static ConvergX Connect launch site (`Website/site-launch/`) to a classic
WordPress theme. Version 0.1.0, built 2026-08-14.

## What is in it

| Layer | Where | Notes |
|---|---|---|
| Design tokens | `assets/tokens.css` | 130 custom properties. **The brand swap file.** Change this, the whole theme re-skins. |
| Stylesheet | `assets/styles.css` | 6,646 lines, ported unchanged except asset paths |
| Shell | `assets/js/shell.js` | Ported **unchanged**. Injects header, mega panels, notice bar, footer |
| Templates | `templates/`, `page.php`, `single.php`, `index.php`, `404.php` | |
| Sections | `template-parts/sections/` | 6 ACF flexible-content layouts |
| Fields | `inc/acf.php` | Registered in PHP, one-way, read-only in wp-admin |
| Commerce | `inc/woo.php` | Theme support only. **No template overrides.** |

## Verified on 2026-08-14

Against WordPress 7.0.4 + WooCommerce 11.0.1 (the same Woo version production runs):

- All 19 PHP files lint clean on PHP 8.5
- Theme activates with no fatal, no notice
- Register template renders 3 cards with **live prices read from WooCommerce** (2,000 / 400 / 1,000)
- A pass pointing at a non-existent product renders **no card** rather than a dead button
- A total with no verification date **does not render** (see "The total gate" below)
- `/shop/`, `/cart/`, `/checkout/` all 200 with the theme active, no fatals
- Store API confirms a real priced cart: 1 item, 2,000.00 USD, `needs_payment: true`
- Woo's own stylesheets remain enqueued (`woocommerce.css`, `wc-blocks.css`, et al)
- No hardcoded robots meta reaches the head
- Industry template renders the veiled photographic hero, exactly one `.say`, the sector
  label, and both hero standfirst paragraphs, with `data-hero="veil"` on `<body>`

## Prerequisites

1. **ACF Pro.** Repeater and Flexible Content are Pro-only. Not currently installed on
   convergx.co. Without it the theme still activates and renders; the ACF-driven sections
   are simply empty.
2. **WooCommerce.** Already on convergx.co at 11.0.1.

## THE BLOCKER, before this theme goes anywhere near convergx.co

**convergx.co's cart, checkout, my-account and product buy UI are rendered by Divi Theme
Builder.** Measured 2026-08-14: the checkout page's `post_content` contains only Divi
shortcodes, with no `[woocommerce_checkout]` and no checkout block. The standard-registration
product page renders 52 `et_pb_` instances.

Activating any non-Divi theme on that site leaves it **with no payment form at all.**

This theme cannot fix that, and neither could Woo template overrides: an override only runs
when the shortcode or block runs, and neither is on the page. The fix is on the site, before
the theme lands. The four Woo pages need their `post_content` replaced with the Woo blocks or
shortcodes, and that has to be verified with a real test-mode transaction on a staging clone.

Two further unknowns that must be answered in wp-admin first:

- **Where the 5% admin fee is implemented.** Products are flat 2,000 / 400 / 1,000 with no fee
  on the object. No known fee plugin is installed, so it is custom code. If it lives in Divi's
  `functions.php`, switching themes silently deletes the fee from all 21 paid products. If it
  does, move it into a standalone plugin before the swap, so no future theme change can kill
  revenue again.
- **Whether `wc/pos/v1` (WooCommerce POS) and `order-barcode-for-woocommerce` are the Sep 22
  door check-in.** If so, product and checkout surfaces are event-day operations.

Full findings: `companies/convergx/deliverables/wp-theme-redteam-2026-08-14.md` in the brain.

## The total gate

The register template reads each pass's **base price live from its WooCommerce product**, so
that figure can never drift from the shop.

It does **not** compute a checkout total, and no WooCommerce API can give it one: the 5% admin
fee and 5% tax that turn 2,000 into 2,200 are cart-level, from code that is not identifiable
from outside the site.

So the total is an ACF field paired with a **Total verified on** date, and the line renders
only when **both** are present. Clear the date and the total disappears. An absent total is
honest. A stale total on the money path is the worst failure this page can have.

Re-verify by adding each product to a cart and reading the totals block.

## Design decisions worth knowing

- **No `woocommerce/` template directory, deliberately.** See the header comment in
  `inc/woo.php`. Overriding `cart.php` or `form-checkout.php` drops Stripe's express-checkout
  buttons (they mount on hooks inside the stock templates) and goes stale on the next Woo
  update. Restyle with CSS, use hooks, never copy a template.
- **shell.js is not ported to PHP.** Its hrefs are already root-relative, so it works at a
  domain root unchanged, and it carries per-row descriptors and CTA flags a WP menu cannot
  express. Its notice bar also self-removes on the visitor's clock, which server rendering
  under a page cache would break. `header.php` carries one no-JS fallback for the whole site,
  replacing the 24 hand-maintained `<noscript>` blocks in the static tree.
- **ACF fields are registered in PHP, not acf-json.** One-way, so there is no DB-vs-file drift
  to reconcile on a site this theme may need rolling back on.
- **The globe, the flow band and the agenda are not fields and must never become fields.** They
  are generated or JS-coupled; a WYSIWYG save would strip their inline SVG and `data-*`
  attributes. The rule: fields hold text, URLs, images and repeater rows. Nothing else.
- **Section order on the register page is fixed in the template, not draggable.** The static
  page's own instruction is "never reorder so a price appears above the standard." Flexible
  Content's headline feature is drag-to-reorder, which would make the one forbidden edit the
  easiest one in the interface.

## Two body attributes, not one

`data-surface` (dark / light / muted) scopes every colour. `data-hero` (veil / photo) is the
opt-in for the two photographic hero components, and **half of each component's CSS lives
behind that attribute selector**. Emit the band class without the attribute and everything
renders, but the photograph stacks above the copy as a plain block instead of sitting behind
it. Nothing errors. This was a real bug during the build and it is why `convergx_hero()`
exists.

`veil` and `photo` are siblings, not variants. The sector heroes take no filter and no tint;
`/congress/` is a duotone. Do not merge them.

## Still to build

Templates for `/congress/` (the 2,468-line page), the homepage globe hero, xpand, about, and
the flow band. `page-flex.php` covers editorial pages, `page-industry.php` covers all eight
sector pages. The static tree has ~25 distinct section shapes; seven are implemented.

Also not built: a content seeder, a redirect map for the 118 legacy pages, and the five forms
(they currently POST to formsubmit.co and should be rebuilt on WPForms, which convergx.co
already runs).

## Local test harness

`~/Sites/convergx-wp-test` is a working WordPress + SQLite + WooCommerce install with the
theme symlinked in and three products mirroring ConvergX's. Start it with:

```
PHP_CLI_SERVER_WORKERS=8 php -S localhost:8944 -t ~/Sites/convergx-wp-test
```

`wp-content/mu-plugins/cx-test-fixture.php` supplies the register page's pass data through the
theme's `convergx_field` filter, standing in for the ACF Pro repeater. It is test scaffolding
and is not part of the theme.

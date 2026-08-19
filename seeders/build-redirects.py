#!/usr/bin/env python3
"""Generate the legacy-URL redirect table: convergx/inc/redirects.php.

Input: every URL in convergx.co's live sitemaps (the old Divi site). Each old
path gets a 301 target on the new structure, so ten years of inbound links,
Google results and printed QR codes don't die the day the theme flips.

Run:  python3 seeders/build-redirects.py            (uses cached /tmp/cx-old-urls.txt
                                                     or refetches the live sitemaps)

The four legal pages (privacy-policy, cookie-policy, terms-conditions,
trademark) are ported as real pages by cx-legal-pages.php and therefore not
redirected. /copyright/ (a link hub) and /security/ (an old speaker bio)
redirect instead.
"""
import re, subprocess, os, sys

OUT = os.path.join(os.path.dirname(__file__), "..", "convergx", "inc", "redirects.php")
CACHE = "/tmp/cx-old-urls.txt"

# The sixteen current speakers' overlay ids on the new congress page.
SPEAKERS = {
    "kimberley-van-vliet", "tracy-latourette", "david-perry", "susan-skone",
    "chris-lohmann", "joy-romero", "jonathan-morris", "emma-spanswick",
    "joshua-potter", "rob-huebert", "christine-hanson", "mark-norton",
    "christopher-coates", "stephane-masson", "anton-sestritsyn", "john-sheehan",
}

# Old bio slugs whose spelling differs from the new overlay id.
BIO_ALIASES = {
    "jonathan-m-morris": "jonathan-morris",
    "joshua_j_potter": "joshua-potter",
    "lgen-coates": "christopher-coates",
}

# Ported as real pages by cx-legal-pages.php, so no redirect ever fires for
# them (the handler only runs on 404s):
KEEP = ["privacy-policy", "cookie-policy", "terms-conditions", "trademark"]

EXACT = {
    "2026-congress-speakers": "/congress/#speakers",
    "2026-congress-agenda": "/congress/#agenda",
    "2026-congress-accommodations-2": "/congress/#accommodations",
    "2026-congress-sponsors": "/congress/#sponsors",
    "2026-congress-who-attends": "/congress/#who-attends",
    "x-hotels-accommodations": "/congress/#accommodations",
    "conference-location": "/congress/#accommodations",
    "why-attend-new": "/congress/#who-attends",
    "xchange-partnerships": "/congress/partnerships/",
    "convergx-xpand": "/xpand/",
    "where-we-excel": "/xpand/#what-xpand-does",
    "contact-convergx-xpand": "/xpand/",
    "our-team": "/about/#leadership",
    "who-we-are": "/about/#who-we-are",
    "contact-us": "/about/#get-in-touch",
    "x-contact": "/about/#get-in-touch",
    "contact-convergx-xchange": "/about/#get-in-touch",
    "x-sectors": "/industries/",
    "chatham-house-rule": "/congress/",
    # A link hub with 15 words, and an old speaker-bio page respectively.
    "copyright": "/terms-conditions/",
    "security": "/congress/#speakers",
    "global-congress-2026": "/congress/",
    "product/standard-registration": "/product/standard-registration/",
    "product/government-registration": "/product/government-registration/",
    "product/government-registration-2": "/product/government-registration/",
    "product/military-government-registration": "/product/military-registration/",
    "product/active-military-registration": "/product/military-registration/",
}

# First matching rule wins; broadest buckets last.
PREFIX_RULES = [
    # Old speaker bios -> the same person's overlay, or the grid.
    (r"^(?:convergx-bio|x-bio)-(.+)$", "BIO"),
    # Sponsorship products and every sponsor/segment request form.
    (r"^product/.*sponsor|^product/sponsorship|^product/presenting-partner|^product/new-cross-sector|^product/reconciliation",
     "/congress/#sponsor-contact"),
    (r"^(explore-sponsorship|sponsorship|sponsor-3.*|x-2026-spotlight-opportunities)$",
     "/congress/#sponsor-contact"),
    (r"^x-.*(sponsor-form|sponsor-gold|sponsor-platinum|sponsor-silver|segment-request|roundtable-member|roundtable-panelist|reconciliation-champion)",
     "/congress/#sponsor-contact"),
    # Registration and the store's front doors.
    (r"^(conference-registration|registration-old.*|ces-registration|checkoutold|speaker-registration|single-package|shop.*|x_test_purchase)$",
     "/congress/register/"),
    # Agenda and programme archives.
    (r"^(conference-agenda|keynotes|summit-day-\d|x-convergx-2025-agenda|x-2023-convergx-congress-agenda|x-congress-2024-convergx-topics-of-discussion|convergx_2024_topics_of_discussion|x-convergx-at-ces-2026-agenda)$",
     "/congress/#agenda"),
    # Speaker archives.
    (r"^(speakers|x-convergx-speakers|x-convergx-2024-speakers|x-convergx-congress-linup|convergx-global-congress-2023-speaker-lineup)$",
     "/congress/#speakers"),
    # Regional and partner events, past summits.
    (r"^(conferences|custom-regional-xchanges|executive-roundtable|uk-executive_roundtable|beyond-borders|canada-summit.*|canada-summit-panels.*|farnborough-2|x-convergx-at-ces-2024)$",
     "/congress/xplore/"),
    # Xpand's TRL and commercialization pages.
    (r"^(xpand-trl.*|xpands-trl-description|xpand-commercialization.*)$",
     "/xpand/#the-last-mile"),
    # People pages outside the congress.
    (r"^project(/.*)?$", "/about/#leadership"),
    (r"^(testimonials|convergx-message|convergx-host-2019)$", "/about/"),
    (r"^(speakerapplication|x-request-to-be-a-speaker|awardapplication|convergx-awards)$",
     "/about/#get-in-touch"),
    # Old homepage drafts, campaign pages, blog debris: the front door.
    (r"^(welcome|x-home.*|x-connex.*|connect-2|landing-page-2-2|x-factor|show-up|news|admin|category/.*|industry-4-0|power|tech-innovation|rise-of-the-robots|x-ploit|convergx-message)$",
     "/"),
]

def fetch_urls():
    if os.path.exists(CACHE):
        return [l.strip() for l in open(CACHE) if l.strip()]
    urls = set()
    for s in ["post", "page", "project", "product", "post-archive", "category", "product-cat"]:
        xml = subprocess.run(["curl", "-sL", "--max-time", "30",
                              f"https://convergx.co/{s}-sitemap.xml"],
                             capture_output=True, text=True).stdout
        urls.update(re.findall(r"<loc><!\[CDATA\[([^\]]+)", xml))
    return sorted(urls)

def target_for(path):
    p = path.strip("/")
    if p == "" or p in KEEP:
        return None
    if p in EXACT:
        return EXACT[p]
    for pat, tgt in PREFIX_RULES:
        m = re.match(pat, p)
        if not m:
            continue
        if tgt != "BIO":
            return tgt
        slug = m.group(1).strip("/").replace("_", "-")
        slug = BIO_ALIASES.get(m.group(1), BIO_ALIASES.get(slug, slug))
        if slug in SPEAKERS:
            return f"/congress/#bio-{slug}"
        return "/congress/#speakers"
    # An old URL nothing above recognises still deserves a door, not a 404.
    return "/"

rows, kept = {}, []
for url in fetch_urls():
    path = re.sub(r"^https?://[^/]+", "", url)
    p = path.strip("/")
    if p in KEEP:
        kept.append(path)
        continue
    t = target_for(path)
    if t and t != path:
        rows["/" + p + "/"] = t

lines = [f"\t'{k}' => '{v}'," for k, v in sorted(rows.items())]
php = """<?php
/**
 * Legacy-URL redirects, generated by seeders/build-redirects.py from the live
 * convergx.co sitemaps (%d old URLs mapped). Regenerate rather than hand-edit.
 *
 * The handler runs ONLY on requests WordPress has already decided are 404s,
 * so a table entry can never shadow a real page: if content is later created
 * at an old address, the page wins and the redirect goes dormant.
 *
 * KEPT, NOT REDIRECTED (recreate these on the new install before launch --
 * checkout links to them): %s
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

function convergx_legacy_redirects() {
	return array(
%s
	);
}

add_action( 'template_redirect', 'convergx_route_legacy_url' );
function convergx_route_legacy_url() {
	if ( ! is_404() ) {
		return;
	}

	$path = strtolower( (string) wp_parse_url( add_query_arg( array() ), PHP_URL_PATH ) );
	$path = '/' . trim( $path, '/' ) . '/';

	$map = convergx_legacy_redirects();

	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
}
""" % (len(rows), ", ".join(kept), "\n".join(lines))

open(OUT, "w").write(php)
print(f"wrote {OUT}: {len(rows)} redirects, {len(kept)} kept (legal pages)")
for k in kept:
    print("  KEEP:", k)

#!/usr/bin/env python3
"""Extract every non-component section of the static site VERBATIM.

The field-by-field extraction (headings, ledes, claims, link rows...) lost, one
by one: section ids the CSS keys on ([aria-labelledby="whatcx-h"], #about,
#our-process), bare-section classes, the no-rail say sections, link-index
placement (inside .body vs beside it), inline mid-body p.say, and the subnavs.
Each loss was a separate bug report from Chip. Storing the section's outer HTML
verbatim removes the entire class: what the static page ships is what WordPress
stores.

A section is still a flexible-content row, so an editor can edit the HTML and
reorder sections. Dynamic zones stay dynamic:
  - forms are swapped for {{FORM:key}} and rendered by convergx_render_form()
  - the speakers/agenda/hotels/sponsors sections and the About team become
    marker rows rendered from their CPTs/fields
  - heroes, the globe, both flow bands, launchers, impact and app bands stay
    template-owned (generated SVG / shell.js content does not survive an editor
    field)

Output: data/cx-exact-rows.json  {page_path: [row, ...]}
  row := {layout:"exact", surface:"", html:"<section ...>...</section>"}
       | {layout:"part",  surface:"", part:"speakers|agenda|hotels|sponsors|team|flow"}
"""
import re, json, subprocess, sys
from urllib.parse import urljoin

BASE = "https://chipmartens.github.io/convergx-connect-launch"
OUT  = __file__.rsplit("/", 1)[0] + "/data/cx-exact-rows.json"

# /congress/register/ is wholly template-owned (hero, Woo pricing, who-for).
PAGES = ["", "congress", "congress/xplore", "congress/the-app",
         "congress/partnerships", "congress", "xpand", "about", "access",
         "access/apply", "access/request", "requirement", "thanks",
         "industries"] + [f"industries/{s}" for s in
         ["aerospace-defence", "agriculture", "construction", "energy",
          "manufacturing", "military", "mining-natural-resources", "technology"]]
PAGES = list(dict.fromkeys(PAGES))

FORM_KEY = {"about": "contact", "congress": "sponsor", "access/apply": "apply",
            "access/request": "request", "requirement": "requirement"}

# Congress sections that render from CPTs/fields, in the band they sit in.
PART_IDS = {"speakers": "speakers", "agenda": "agenda",
            "accommodations": "hotels", "sponsors": "sponsors"}

VOID = {"img", "br", "hr", "input", "meta", "link", "source", "track", "wbr",
        "area", "base", "col", "embed", "param"}
TAG = re.compile(r'<(/?)([a-zA-Z0-9-]+)([^>]*)>')

def get(url):
    return subprocess.run(["curl", "-sSL", "--max-time", "60", url],
                          capture_output=True, text=True).stdout

def chunks(h):
    """Top-level balanced elements of an HTML fragment (void-tag aware)."""
    out, pos = [], 0
    while True:
        m = TAG.search(h, pos)
        if not m:
            break
        if m.group(1) or m.group(2).lower() in VOID or m.group(3).rstrip().endswith("/"):
            pos = m.end()
            continue
        depth, p2 = 0, m.start()
        while True:
            m2 = TAG.search(h, p2)
            if not m2:
                p2 = len(h)
                break
            t2 = m2.group(2).lower()
            if t2 in VOID or (not m2.group(1) and m2.group(3).rstrip().endswith("/")):
                p2 = m2.end()
                continue
            depth += -1 if m2.group(1) else 1
            p2 = m2.end()
            if depth == 0:
                break
        attrs = dict(re.findall(r'([a-zA-Z-]+)="([^"]*)"', m.group(3)))
        out.append((m.group(2).lower(), attrs, h[m.start():p2]))
        pos = p2
    return out

def inner(chunk):
    m = TAG.match(chunk)
    return chunk[m.end():chunk.rfind("<")]

def rewrite_urls(html, page):
    """Relative hrefs -> root-relative; asset paths -> {{ASSETS}} token."""
    base = "/" + (page + "/" if page else "")

    def fix(m):
        attr, q, val = m.group(1), m.group(2), m.group(3)
        if re.match(r'^(https?:|mailto:|tel:|data:|#|\{\{)', val):
            return m.group(0)
        resolved = urljoin(base, val)
        if re.match(r'^/?assets/', resolved.lstrip("/")):
            resolved = "{{ASSETS}}/" + re.sub(r'^/?assets/', '', resolved)
        return f'{attr}={q}{resolved}{q}'

    return re.sub(r'\b(href|src|poster)=(")([^"]+)"',
                  lambda m: fix(m) + '', html)

def swap_form(html, key):
    """Replace the balanced <form>...</form> with a render placeholder."""
    m = re.search(r'<form\b', html)
    if not m:
        return html, False
    depth, pos = 0, m.start()
    while True:
        m2 = re.compile(r'<(/?)form\b[^>]*>').search(html, pos)
        if not m2:
            return html, False
        depth += -1 if m2.group(1) else 1
        pos = m2.end()
        if depth == 0:
            break
    return html[:m.start()] + "{{FORM:" + key + "}}" + html[pos:], True

def classify(page, tag, attrs, chunk, surface):
    cls = attrs.get("class", "")
    cid = attrs.get("id", "")
    if re.search(r'\bbio-overlay\b|\bhero-[a-z-]*band\b|\blaunchers-band\b'
                 r'|\bimpact\b|\bapp-band\b', cls):
        return None
    if "flow-band" in cls:
        # The homepage flow band is a positioned row; the congress one is
        # template-owned (it carries its own globe instance).
        return {"layout": "part", "part": "flow", "surface": surface} if page == "" else None
    # Plain heroes (an <h1> in an ordinary section) are captured verbatim like
    # everything else: several carry paragraphs beyond the title and lede that
    # the templates' two-field hero cannot represent. Only the generated hero
    # bands above (globe/veil/photo) stay template-owned.
    if cid == "leadership":
        return {"layout": "part", "part": "team", "surface": surface}
    if cid in PART_IDS:
        return {"layout": "part", "part": PART_IDS[cid], "surface": surface}
    html = rewrite_urls(chunk, page)
    if "<form" in html:
        key = FORM_KEY.get(page)
        if not key:
            print(f"!! form on /{page}/ with no key mapping", file=sys.stderr)
        else:
            html, ok = swap_form(html, key)
            if not ok:
                print(f"!! form swap failed on /{page}/", file=sys.stderr)
    return {"layout": "exact", "surface": surface, "html": html}

def extract(page):
    url = f"{BASE}/{page}/" if page else f"{BASE}/"
    h = re.sub(r'<!--.*?-->', '', get(url), flags=re.S)
    m = re.search(r'<main\b[^>]*>(.*)</main>', h, re.S)
    rows = []
    for tag, attrs, chunk in chunks(m.group(1)):
        band = attrs.get("data-surface", "")
        # The speaker bio overlays carry data-surface="dark" but are dialogs
        # rendered by the speakers CPT, not band wrappers. Skip before recursing.
        if "bio-overlay" in attrs.get("class", ""):
            continue
        if tag == "div" and (band or "band--navy" in attrs.get("class", "")):
            surface = "navy" if "band--navy" in attrs.get("class", "") else band
            for t2, a2, c2 in chunks(inner(chunk)):
                row = classify(page, t2, a2, c2, surface)
                if row:
                    rows.append(row)
        else:
            row = classify(page, tag, attrs, chunk, "")
            if row:
                rows.append(row)
    return rows

data = {}
for page in PAGES:
    data[page] = extract(page)
    kinds = [r["layout"] + (":" + r.get("part", "") if r["layout"] == "part" else "")
             for r in data[page]]
    print(f"/{page or ''}: {len(data[page])} rows  {kinds}")

json.dump(data, open(OUT, "w"), indent=1)
print(f"\nwrote {OUT}")

import re, subprocess, html, sys, json
BASE="https://chipmartens.github.io/convergx-connect-launch"
WP="http://localhost:8945"

PAGES=[("","/"),("congress/","/congress/"),("congress/register/","/congress/register/"),
("congress/xplore/","/congress/xplore/"),("congress/the-app/","/congress/the-app/"),
("congress/partnerships/","/congress/partnerships/"),
("xpand/","/xpand/"),("xpand/streams/","/xpand/streams/"),
("about/","/about/"),("access/","/access/"),("access/apply/","/access/apply/"),
("access/request/","/access/request/"),("requirement/","/requirement/"),("thanks/","/thanks/"),
("industries/","/industries/")]
for s in ["aerospace-defence","agriculture","construction","energy","manufacturing","military",
          "mining-natural-resources","technology"]:
    PAGES.append((f"industries/{s}/", f"/industries/{s}/"))

def get(u):
    return subprocess.run(["curl","-sSL","--max-time","45",u],capture_output=True,text=True).stdout

def strip(h):
    # <noscript> holds the no-JS nav fallback on both sides. It is navigation,
    # not page content, and the two implementations differ by design.
    for p in (r'<script.*?</script>',r'<style.*?</style>',r'<svg.*?</svg>',
              r'<noscript.*?</noscript>',r'<!--.*?-->'):
        h=re.sub(p,'',h,flags=re.S|re.I)
    return h

TYPO = {'\u2019':"'", '\u2018':"'", '\u201c':'"', '\u201d':'"',
        '\u2013':'-', '\u2014':'-', '\u2026':'...', '\u00a0':' ', '\u2011':'-'}
def txt(x):
    x=re.sub(r'<[^>]+>','',x)
    x=html.unescape(x)
    # WordPress texturizes straight quotes into curly ones (wptexturize). That is
    # a typographic improvement, not a content difference, so normalise both
    # sides before comparing or every apostrophe reads as a missing paragraph.
    for a,b in TYPO.items(): x=x.replace(a,b)
    return re.sub(r'\s+',' ',x).strip()

def headings(h):
    h=strip(h)
    return [txt(m.group(2)) for m in re.finditer(r'<(h1|h2|h3)[^>]*>(.*?)</\1>',h,re.S) if txt(m.group(2))]

def paras(h):
    h=strip(h)
    out=[txt(m.group(1)) for m in re.finditer(r'<(?:p|li)[^>]*>(.*?)</(?:p|li)>',h,re.S)]
    return [x for x in out if len(x)>20]

rows=[]
for rel,wp in PAGES:
    L=get(f"{BASE}/{rel}"); W=get(WP+wp)
    if not L.strip(): continue
    lh,wh=headings(L),headings(W)
    lp,wp_=paras(L),paras(W)
    miss_h=[x for x in lh if x not in wh]
    extra_h=[x for x in wh if x not in lh]
    wjoin=" || ".join(wp_)
    miss_p=[x for x in lp if x[:70] not in wjoin]
    rows.append({"page":wp,"h_live":len(lh),"h_wp":len(wh),"miss_h":miss_h,"extra_h":extra_h,
                 "p_live":len(lp),"p_wp":len(wp_),"miss_p":miss_p})

json.dump(rows,open("/tmp/cx-compare.json","w"),indent=1)
print(f"{'PAGE':<34}{'H live/wp':>11}{'missH':>7}{'P live/wp':>11}{'missP':>7}")
print("-"*72)
for r in rows:
    print(f"{r['page']:<34}{str(r['h_live'])+'/'+str(r['h_wp']):>11}{len(r['miss_h']):>7}"
          f"{str(r['p_live'])+'/'+str(r['p_wp']):>11}{len(r['miss_p']):>7}")
print(f"\nTOTAL missing headings: {sum(len(r['miss_h']) for r in rows)}"
      f"   missing paragraphs: {sum(len(r['miss_p']) for r in rows)}")

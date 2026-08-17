# fig-globe-converging-network

The hero plate: a living deal cycle on a turning globe. Extended 2026-07-28.

**It is not a numbered figure.** Fig. 1 to Fig. 9 are the site's plates: each carries a number,
a caption, an aria-label and an argument, and site numbering is append-only. This is decorative
geometry for the top of a page. Giving it "Fig. 10" would enter it into that sequence as
something it is not, so the filename carries a slug and no number. The two-file convention
beside every other plate is unchanged.

## The three files

| File | What it is |
|---|---|
| `assets/fig/fig-globe-converging-network.svg` | The plate. Frame zero of the projection, rendered statically. Inline it; never `<img src>`. |
| `_system/globe.js` | The projector, the loop, and the embedded land geometry. Optional. |
| `site/index.html` | Carries an INLINED copy of the plate. Written by the build script, but **only under `--inject`**, and as of 2026-07-28 it is deliberately stale. |
| `_reference/globe-build/land.cache.json` | The simplified land, and the authoritative source for it. |
| `_reference/globe-build/test_inject.py` | The check on the injector's failure path. |
| this file | The notes. |

Both shipped files are written by one build script, `_reference/globe-build/globe_build.py`,
from one set of constants, one endpoint list and one set of projection functions. That is the
whole answer to how the static plate and the live version stay in sync, and it is measured
rather than asserted: see **Parity** below.

**Never hand edit any output, the inlined copy included.** Change a constant, the endpoint list or the projection,
re-run the build script, then re-run the parity diff. The preview pages come out of
`_reference/globe-build/gen_preview.py` the same way.

## The one sentence

Deals keep happening across the alliance: someone with a problem, a handful of companies that
could solve it, and a world that is real and turning underneath them.

## What is on the plate

| Mark | Means | Drawn as |
|---|---|---|
| Problem holder | The one with the requirement | A FILLED accent dot |
| Solution provider | A company that could take it | An UNFILLED accent circle |
| Route | The connection between them | A great circle arc, accent, dashed |

Filled against unfilled is the whole idea, and it is also how the accent stays inside its
ration: a ring of hairline is a fraction of the ink of a solid disc, so providers stay quieter
than holders by construction rather than by dimming. **A site that is not part of a live deal
is not drawn at all**, which is what stops the plate reading as confetti. At rest the plate
carries four visible holders, eleven routes and twenty six marks in total; through a full cycle
the count visible in the hero runs between 9 and 46, averaging 26, and never reaches zero.

## Strings

**None.** No caption, no aria-label, no micro-labels, no country names, no numbers. Zero
`<text>` elements in the plate and zero member names anywhere in either shipped file, including
comments. The plate is `aria-hidden`, so there is nothing for a screen reader to read and
nothing for it to read wrongly.

## The projection

Orthographic, which is what a globe seen from space is, and what the reference shows.

    x = cos(lat) sin(lon - lon0)
    y = cos(lat0) sin(lat) - sin(lat0) cos(lat) cos(lon - lon0)
    z = sin(lat0) sin(lat) + cos(lat0) cos(lat) cos(lon - lon0)     z > 0 is the near face

Rotation advances `lon0`. The axial tilt is applied afterwards as a fixed in-plane rotation of
`(x, y)`, so the pole leans off screen vertical the way a real globe's does while the rotation
still happens about the polar axis, and `z` is untouched by it. Folded together, one frame is
nine coefficients, so there is no trigonometry inside the per-point loop: every point is a unit
vector computed once at load, and a frame is nine multiplies and six adds per point.

### The deal cycle

A deal is one problem holder joined to two to five solution providers. It draws in, holds and
breathes once, then releases, while others are alive elsewhere.

| Timing | Value | Why |
|---|---|---|
| Draw in | 1.5 s | The arcs grow out from the holder toward each provider. A plain fade read as specks appearing; a draw-on reads as a connection being made. |
| Hold | 5.5 s | One breath across it, 20 percent deep. Shallower than it was: with five deals alive at once a deep breath on every one starts to read as flicker. |
| Release | 2 s | Fades out. Total life 9 s. |
| Between starts | 2 s | One turn divided by the number of deals: 105 of them. |
| Concurrent | 4 to 5, averaging 4.5 | Something is always happening, and several things usually are. |

**The rotation did not speed up and must not.** It is still one turn per 210 seconds. Only the
deal cycle is quick; a globe spinning fast reads as a loading spinner. Every timing above is a
constant in the build script and is shipped to the projector as data, so changing the pace is
a one line edit and a rebuild.

**The deal sequence is exactly one rotation long**, so the rotation and the lifecycle share a
single period and the whole figure loops as one thing rather than as two things drifting apart.
Slots are stable across the wrap because the number of deals is a multiple of the maximum
concurrency.

**Nothing is chosen at runtime.** Every deal, and every point in it, is decided by the build
script and shipped as a list that the projector plays. That is why a point can never land in
the Atlantic or on a non-member: there is no random coordinate anywhere in the system, only a
fixed geographic pool and a fixed sequence over it. It is also what keeps the static plate and
the live figure the same picture.

| Constant | Value | Why |
|---|---|---|
| Radius, centre | 265 at (1130, 350) in a 1600 x 700 viewBox | The family's frame. Right-weighted like Fig. 4, so the empty left half is where hero type goes. |
| View latitude | 40 | The dial that decides how much of the network travels round the back. A point at latitude p never leaves the face when 40 + p is over 90. |
| Axial tilt | 23.44 degrees | Earth's real obliquity. |
| **Resting longitude** | **31 W** | Frame zero. Mid Atlantic. Searched, not chosen: see below. Was 16 E, which rested on Europe. |
| **Resting phase** | **150 s** | Which moment of the deal cycle the plate stops on. Searched WITH the longitude. Not a speed. |
| One turn | 210 seconds | Slow enough to read as alive, not as animation. |
| Frame cap | 24 per second | A hero decoration does not need 60. |

**View latitude was the fix for a real defect.** It started at 52, where every endpoint stays on
the near face for the whole cycle, so nothing ever went behind and the figure lost the thing
that sells the sphere. Measured, not eyeballed: 360 route-frames sampled through a full turn,
zero partly behind. At 40 the four southern endpoints each spend roughly a quarter of the turn
hidden, staggered by longitude, while the three northern ones and the convergence node stay up
throughout, so the network is never absent from the plate.

## Geography

### The membership rule widened on 2026-07-28, and that was asked for

**It was NATO members only. It is now NATO members and NATO partners.** Chip asked for markers
in Japan and South Korea connecting to Calgary. Japan and South Korea are not NATO members, they
are Indo-Pacific partners, so the old rule and the request could not both stand and the rule is
what moved.

This is written here so that a future reader finds a **stated change, not a drift**. The five
Indo-Pacific entries in the build script's pool are correct and deliberate. Do not "fix" them
back out.

**Fifty six fixed sites**, several per country so the plate reads as companies rather than as
capitals. The pool is in the build script; it is fixed and geographic and nothing is ever spawned
at a random coordinate.

Coverage, for the record and for nowhere else: Canada 5, United States 5, one to three each
across Iceland, the United Kingdom, Norway, Sweden, Finland, Denmark, Estonia, Latvia,
Lithuania, Poland, Germany, the Netherlands, Belgium, Luxembourg, France, Czechia, Slovakia,
Hungary, Slovenia, Croatia, Italy, Spain, Portugal, Greece, Bulgaria, Romania, Albania,
Montenegro, North Macedonia and Turkiye, and three in Japan and two in South Korea. **No count
and no country name appears anywhere a reader can see**, and at this size no viewer can identify
a country from a dot, so the pool cannot be read as a claim about who is on the network.

### The trans-Pacific link

Added 2026-07-28. Calgary is a new named endpoint and the right anchor for it, because that is
where the Congress is held. It is the only deal in the whole system **written by hand rather than
drawn**: the holder is fixed and the legs are fixed, because "Japan and South Korea connecting to
Calgary" is a specific instruction and not something to leave to the pool.

| End | Latitude | Longitude | Separation from Calgary |
|---|---|---|---|
| Calgary, the holder | 51.0447 N | 114.0719 W | n/a |
| Sapporo | 43.0618 N | 141.3545 E | 65.5 degrees |
| Sendai | 38.2682 N | 140.8694 E | 69.3 degrees |
| Nagoya | 35.1815 N | 136.9066 E | 73.7 degrees |
| Incheon | 37.4563 N | 126.7052 E | 76.7 degrees |
| Busan | 35.1796 N | 129.0756 E | 77.5 degrees |

Every one is on its real coordinates, and all five separations sit inside the pool's existing
12 to 80 degree band, so the link is an ordinary deal in every respect except that it is pinned.

**Kobe was tried and dropped.** It is a real industrial site, but it sits 1.8 degrees from
Nagoya, which at hero size is about four pixels, and two marks of radius six that close together
merge into one. A five leg fan that renders as four legs is a lie about the geometry, so Sendai
took its place at 4.4 degrees from Nagoya. This is now asserted rather than eyeballed: the build
script measures every pair of marks inside a deal at the frame that deal is actually seen at, and
**fails if any pair involving a site this change added comes within 8 units.** Tightest such pair
is 10.0.

**Where it appears is measured, not chosen.** A Calgary to Asia great circle runs over the north
Pacific, so for most of the turn it is genuinely round the back. The build script computes the
band of view longitudes over which the arc is actually on the near face and inside the hero
window, and then places the link's slots across that band. Measured in the browser, sweeping the
whole cycle:

| Check | Result |
|---|---|
| Slots per turn | 6 |
| Alive | 0.224 of the turn |
| **Visible in the hero band** | **0.217 of the turn, about 46 seconds of 210** |
| Partly behind the sphere, of the time it is alive | 0.41 |

**It is absent for roughly four fifths of the turn and that is correct.** It is not faked into
persistence and it is not dimmed into a permanent ghost: it comes round, it is cut by the limb as
it arrives and as it leaves, and it goes behind again. A route that travels round the back and
reappears is the thing that proves the globe is a sphere, so **the slots are deliberately spread
across the whole visible window including its edges rather than concentrated in the clean middle
of it.** An earlier pass took the six phases with the most visible seconds, which put every
appearance in the middle of the window and left the link never once crossing the horizon. The
build script now asserts that it IS cut by the horizon; that assert is what caught it.

Half the deals are anchored above 50 N, where a point never leaves the face for the whole turn.
Without that rule the plate goes bare whenever the Pacific faces the reader, because the
alliance is genuinely on the far side. Measured: at the weakest moment in the cycle the
strongest visible holder still sits at 0.39 opacity.

Providers are drawn from a band 12 to 80 degrees from the holder, and every deal carries at
least one leg beyond 35 degrees, so there is always a long arc crossing real distance.
Consecutive deals start at least 25 degrees apart, so the cycle walks the globe. **All three of
those rules are fixes for a measured defect**: at 4 to 55 degrees every deal was a huddle of
European neighbours, because that is where the site density is, and the whole plate read as one
tangle in one place.

The previous seven-endpoint set is retired. For the record, it was:

| Member | Point | Latitude | Longitude | Frames hidden, of 36 sampled through a turn |
|---|---|---|---|---|
| Canada | Ottawa | 45.4215 N | 75.6972 W | 7 |
| United States | Washington | 38.9072 N | 77.0369 W | 9 |
| Iceland | Reykjavik | 64.1466 N | 21.9426 W | 0 |
| Portugal | Lisbon | 38.7223 N | 9.1393 W | 10 |
| Norway | Oslo | 59.9139 N | 10.7522 E | 0 |
| Poland | Warsaw | 52.2297 N | 21.0122 E | 0 |
| Turkiye | Ankara | 39.9334 N | 32.8597 E | 9 |

**The eighth dot is not a country.** It is the point the routes converge on, at 55 N 30 W: open
north Atlantic, west of Ireland and south of Iceland. Not a member, not a non-member, not land.
It is far enough north that it never rotates out of view, so the convergence is always on the
plate. It sits on the face, not on the limb.

## Land

Natural Earth 110m admin 0 countries. Public domain. Simplified with Douglas Peucker at a
1.1 degree tolerance, islands under 3 degrees of span dropped, Antarctica dropped because it is
never on the face at this view latitude. **135 features, 167 rings, 1478 points, embedded in
`globe.js`. Nothing is fetched at runtime, ever, and the figure works offline.**

**The simplified result IS the source, and it lives in `_reference/globe-build/land.cache.json`.**
The Natural Earth download is not kept in the repo, so a rebuild used to depend on a file that
was not there. Re-fetching and re-simplifying would make a second source of truth for the same
geometry, and the first silent difference between the two would land in the parity diff, so the
cache is authoritative instead. Drop `ne110m.geojson` beside the build script only when the land
itself has to change; it then rewrites the cache. The land came through this change byte for
byte, which is exactly what should happen when nothing about the land changed.

Countries are outlined in the cage tone and **filled with `var(--bg)`**. The fill does real work
beyond the look: it occludes the graticule behind the land, which is what makes the sphere read
as solid rather than as a wire ball with shapes floating inside it. Because the fill is a
surface token it is the page showing through, not a colour, so the plate still resolves on all
three surfaces with no variant class.

A ring that crosses the horizon is clipped at z equals zero and **closed along the limb itself,
not across a chord**, so a country cut by the horizon keeps the horizon's curve. Measured: land
is cut by the horizon in 12 of 12 frames sampled through a turn.

## Routes

Great circles, interpolated on the sphere and projected point by point, so they curve over the
surface. Each is lifted off the surface by up to 9 percent at its midpoint, so it arcs over the
land instead of lying on it.

**Twenty five route slots**, five deals of up to five legs, drawn from a fixed pool that is never
added to and never removed from: a slot holding no deal this frame carries an empty `d` and zero
opacity. Across the whole cycle the 105 deals carry 387 legs between them.

**Far-side segments are dropped, not dimmed.** A run of points is emitted only while z is
positive, and the crossing is interpolated on the sphere so the route ends exactly on the limb.
Endpoint dots fade across a narrow band at the limb rather than popping, and are fully hidden
behind it. Measured through a full turn:

| Check | Result |
|---|---|
| Route frames sampled | 360 |
| Partly behind the sphere | 51 |
| Entirely behind the sphere | 5 |
| **Drawn while entirely behind** | **0** |
| Route points emitted outside the lifted limb | 0 |
| Node samples on the far side | 35 |
| **Nodes visible while on the far side** | **0** |

## The no-JS path

The default markup **is** the finished plate. `globe.js` only ever rewrites `d`, `cx`, `cy` and
`opacity` on elements the markup already contains; it adds nothing and removes nothing, and it
bails out entirely if the element counts do not match what it expects. If the file fails to
load, fails to parse, or throws, a complete correct globe is still on screen.

**Verified with scripts genuinely off, not reasoned about.** `_dev/globe-preview-nojs.html`
carries no script tag at all, on any of the three surfaces.

**Parity, measured.** The static plate on disk was fetched, parsed, and diffed attribute by
attribute against what `globe.js` produces at the resting frame. Re-run 2026-07-28 after the
recentring and the new sites:

| Layer | Result |
|---|---|
| Graticule | 0 of 17 paths differ |
| Land | 0 of 135 paths differ |
| Routes | 0 of 25 differ, path and opacity |
| Provider circles | 0 of 25 differ, position and opacity |
| Holder dots | 0 of 5 differ, position and opacity |

That diff is not decoration; it caught two real bugs that nothing else would have found. The
JS clipper was not counting the crossing point that closes a run, so a one-vertex run at the
horizon was kept on one side and dropped on the other. And the static plate divided the fade
term by the radius as well as the fade band, which put every endpoint dot on the no-JS plate at
about two percent opacity. Both are fixed; re-run the diff after any change to either side.

## Motion and its budget

| Behaviour | How |
|---|---|
| Frame rate | Capped at 24. The other animation-frame ticks return immediately without drawing. |
| Off screen | `IntersectionObserver` per plate. With every plate off screen the loop is not scheduled at all. Verified: running false, no frame scheduled, resumes on return. |
| Tab hidden | `visibilitychange`. Verified: running false, no frame scheduled. |
| Reduced motion | Checked before the first frame, so nothing ever runs and the markup stands. Also listened for at runtime: on change the loop stops and the plate is redrawn at frame zero. Verified: loop stopped, and the redrawn plate is identical to the shipped markup. |
| Allocation | Scratch arrays are reused. Nothing per-point is allocated per frame. |

**Cost, one plate, measured on this machine in Chrome:** 0.3 ms median per frame, 1.4 ms at the
95th percentile, 3.1 ms worst. At a 24 fps cap that is roughly 0.7 percent of wall clock at the
median. Three plates on one page cost the same, because the projection runs once and only the
attribute writes repeat.

**On a phone.** This machine is not a mid-range phone and I could not test on one. The honest
extrapolation: the work is about 2600 point projections and roughly 24 KB of path string per
frame, and phone JS is commonly 4 to 6 times slower, which puts the median near 1.5 to 2 ms and
the worst case near 15 ms. That fits inside a 41 ms budget at 24 fps with room to spare, but the
part I cannot predict from here is the browser's own SVG re-rasterisation of 162 paths, which is
not in that number. **If it stutters on a real phone, the first dial is the frame cap and the
second is the simplification tolerance**, which is one constant in the build script: raising it
from 1.1 to 1.6 drops the land from 1478 points to 1078 and costs very little visually.

**Weight**, re-measured 2026-07-28: plate 42.3 KB, 9.7 KB gzipped. Projector 35.0 KB, 13.3 KB
gzipped. About 23 KB gzipped for the whole hero graphic, and no second request, no font, no
image. The projector grew by 2.4 KB gzipped for the six new sites and the longer deal list;
the land, which is most of the file, did not change at all.

## The loop closes

Rotation advances the view longitude and wraps modulo 360, and the projection is a continuous
periodic function of it, so there is no seam by construction. Measured anyway, on single points
so nothing can misalign:

- Resting longitude versus resting longitude plus 360: **0.000 units of drift**, exactly.
- Two adjacent frames across the wrap: 0.274 units.
- Two adjacent frames mid-cycle, for comparison: 0.222 units.

The wrap is no more discontinuous than any other pair of adjacent frames.

## Contrast

Hero type is `--fg-hi`. What sits behind it, and what that measures:

| Behind the type | Dark | Light and muted |
|---|---|---|
| The page ground, and the land fill, which is the same token | 17.23:1 | 18.69:1 |
| **The cage: land outline and graticule, `--rule-ink-load`** | **5.70:1** | **4.79:1** |
| A route, `--accent` | 2.99:1, fails AA | 5.44:1 |

**5.70:1 on dark and 4.79:1 on light and muted is the number**, and both clear AA for normal
text, not just for large text. That is as far as the tone can be pushed: the next token up
(`--fg-lo`) drops the dark case to 3.40:1, which passes only for large text and would fail
behind the 11px micro-label. The presence the plate gained instead came from separating the two
layers at the same tone: the graticule sits at half opacity behind land drawn at full, so the
land reads as the subject and the plate gains legibility without gaining contrast.

**Hero type still stays in the left half.** The plate occupies x 865 to 1395 of 1600. Type
running past about half the plate's width crosses the routes, where the dark case is 2.99:1.

## Responsive

Below 60rem the wrapper crops the plate's empty left half rather than scaling the whole 1600
unit frame down to a 124px globe. The globe comes back to roughly 273px across in a 375
viewport. No geometry changes; it is the same plate, cropped, which is what the frame edge
already does to Figs 4, 6, 8 and 9. **At 375 the plate should stack under the hero type rather
than sit behind it**, which is the move the industry header already makes at the same breakpoint
(POLISH-SPEC 3); the preview shows it that way.

## Where it sits on the homepage

Full bleed behind the hero band, type left, sphere right. It replaced the dot field and the
`.hero-aside` wrapper that used to hold it.

**The aside is gone, and that was a judgement call.** It held two things: a dot field and the
mechanism paragraph. A figure may never sit over a dot field (FIGURE-SYSTEM rule 12), so the
texture had to be one or the other, and the paragraph was a near-verbatim restatement of the
lede further down the same page. With both gone the wrapper held nothing, and its accent left
border, described in its own comment as "the edge the dot field terminates against",
terminated nothing. An edge that terminates nothing is an orphan rule, so the wrapper and the
edge went with the contents. The hero keeps its kicker, headline, subtext and CTA untouched.

The mechanism paragraph was checked against the verbatim-asset ledger in
`_copy/COPY-DIRECTION.md` first. **It is not a counted asset**, so no count changed. Both
statements sat on the same page, which the ledger's own rule treats as worse than a
cross-page repeat.

### Framing: the populated face, not the sphere's outline

This is the rule the placement is solved against, and it is the correction that mattered most.

Measured over a full cycle, the accent occupies **x 951 to 1389 of the globe's 865 to 1395, and
y 61 to 418 of its 85 to 615**: the upper right of the disc, and now taller than it was, because
the trans-Pacific arc rides high over the north Pacific and the recentring brought the northern
approaches up. So the frame is solved around that box, and what gets sacrificed to the bleed is
the empty part: the western ocean, which passes behind the type, and the southern hemisphere,
which runs off the foot of the band.

The plate is never empty. Measured in the real hero band at 1440, sweeping the whole cycle:
**between 9 and 46 marks are on screen at all times, averaging 26, and zero frames of 420 have
nothing visible at all.**

An earlier pass framed the sphere's outline instead. It kept the left limb and the empty
Atlantic in view and pushed the whole subject off the right edge, and it sat high enough that
the populated northern band was cropped off the top. Measured, that version left **24.5 percent
of the cycle with no deal visible at all and a 43.5 second unbroken blank**. The reframe takes
both to **zero**.

Two dials moved:

1. **The camera**, in styles.css 20b. Width 138.9 percent of the band, offset -11 percent,
   lifted 37 percent of the plate's own height. The globe is 0.46 of the band wide.
2. **The view longitude**, `LAM0`. This is the honest fix rather than a fudge: it moves the
   camera, not the geography. Every point stays on its real coordinates and still travels round
   the back. It is searched, never chosen by eye: see the section below.

### The resting frame, searched

    python3 _reference/globe-build/globe_build.py --search-lam0

**2026-07-28: 16 E to 31 W, and the resting phase from 44.5 s to 150 s.** Chip asked for the
plate to open on Canada and the United States while Europe stays visible and still carries live
deals. It rested on Europe.

**The search runs on both dials together, because they are not independent.** The longitude
decides where the populated face sits; the phase decides which deals are alive to sit on it.
Solving one and then the other gives a well placed camera pointed at an almost empty plate, which
is exactly what happened on the first attempt: at the best longitude on its own, only 38 percent
of the marks live at the resting moment were inside the hero window.

**What is scored, and what merely gates.** Sweeping every longitude first showed which is which,
and it inverted the obvious answer:

- **North America is inside the hero window at every longitude from 115 W to 20 E.** So "is North
  America in frame" discriminates nothing. What changes is how CENTRED it is, and centrality is
  just `z`: 1 at the middle of the face, 0 on the limb. That is the objective.
- **Europe is the opposite.** It is fully in frame down to about 25 W and then falls away fast:
  0.85 of its sites at 25 W, 0.68 at 31 W, 0.51 at 35 W, 0.24 at 45 W, 0.02 by 60 W. So Europe is
  the binding constraint, and it is a gate rather than a term, because a gate cannot be traded
  away by a big enough win elsewhere.

| Gate | Threshold | What it stops |
|---|---|---|
| Europe in the hero window at rest | a majority, 0.50 of its sites | the camera sailing west until Europe is a remnant |
| Europe still carrying live deals | 0.55 of the turn | Europe being technically visible but dead |
| Leftmost accent clear of the h1 box | see Contrast | hero type over an accent route, which is 2.99:1 |
| Resting frame representative | all 5 slots live, ages spread | a static plate showing five deals at the same instant of their life |

The objective is then `hero + North American centrality`, weighted equally and both reported, so
the trade is visible rather than buried in one number.

**The result, and why it beat the alternatives:**

| Longitude | hero | NA centred | Europe in frame | Europe live | Verdict |
|---|---|---|---|---|---|
| 16 E, the old value | 1.00 | **0.24** | 1.00 | ok | Europe is the subject. This is what Chip asked to change. |
| 25 W | 1.00 | 0.58 | 0.85 | 0.76 | passes, but 0.05 less centred on North America |
| **31 W, the winner** | **1.00** | **0.63** | **0.68** | **0.77** | **the westernmost longitude that still keeps every resting mark in frame** |
| 35 W | 0.81 | 0.66 | 0.51 | 0.75 | scores lower: a fifth of the resting marks fall out of frame |
| 45 W | 0.67 | 0.73 | **0.24** | n/a | Europe out of frame |
| 100 W | 0.56 | 0.95 | **0.00** | n/a | Europe gone entirely |

So North America goes from 0.24, which is riding the limb, to 0.63, while Europe keeps 68 percent
of its sites in the hero window and carries a live deal through 77 percent of the turn. Reported
for completeness: **the leftmost-accent gate is nearly flat across longitude**, landing at 945 to
948 units everywhere in the shortlist, because over a full turn every longitude gets sampled
anyway. It is a real gate and it is kept, but it did not decide this.

### Contrast, measured after wiring

Hero type over the cage is 5.70:1 on dark, which is why the globe's empty western limb is
allowed to pass behind the headline. Hero type over an **accent route is 2.99:1 and fails AA**,
so no accent mark may reach the type at any moment in the cycle.

That is enforced, not hoped for. The build script projects every arc sample and every mark of
every deal across the whole cycle, takes the leftmost, and asserts it lands clear of the h1's
eight-column box. It fails loudly if a change to the deal cycle or the framing breaks it.

Measured in the browser, sweeping the entire 210 second cycle and testing every accent element
against the boxes of the kicker, the h1, the sub and the CTA:

Re-measured 2026-07-28 after the recentring, on `_dev/globe-hero.html`, which reproduces the
hero band as index.html builds it. 420 frames swept per viewport, every accent element tested
against the boxes of the kicker, the h1, the sub and the CTA:

| Viewport | Type box ends | Leftmost accent | Clearance | Was | Accent over type, whole cycle |
|---|---|---|---|---|---|
| 975 | 630 | 693 | 63 px | 63 | **0** |
| 1180 | 735 | 838 | 103 px | 72 | **0** |
| 1280 | 816 | 909 | 93 px | 93 | **0** |
| 1440 | 896 | 1023 | 127 px | 127 | **0** |
| 1920 | 1113 | 1364 | 251 px | 198 | **0** |
| 375 | stacks above the plate | n/a | n/a | n/a | **0** |

**Recentring did not cost clearance anywhere and gained it at two widths.** That is not luck and
it is worth understanding: pulling the camera west moves the GEOGRAPHY across the face, but the
leftmost point any accent mark can reach is set by the globe's own left limb inside the frame,
and the frame did not move. The gain at 1180 and 1920 is the type box getting shorter, not the
accent retreating.

**Measure it on `_dev/globe-hero.html`, not on `globe-preview.html`.** The preview panels use
their own stage, which is not the hero's geometry, so a sweep run there is measuring the wrong
box. That page exists only because this measurement was nearly taken against the wrong one.

**So the worst case behind hero type is the cage, at 5.70:1 on dark and 4.79:1 on light and
muted.** Both clear AA for normal text, not just large. No scrim, no dimming, no accent moved:
AA is held by composition alone.

### At 375

The band becomes a flex column and the plate takes `order: 2`, so the type stacks above it and
the plate follows at 95 percent of the viewport width, a 356px globe. Whole sphere in frame,
so the framing goes back to the outline; nothing needs sacrificing at that width. No horizontal
overflow. The network is never absent: between 10 and 29 marks are in the plate at all times.

## For the integration agent

1. **Inline the SVG.** Never `<img src>`: the colour model is `currentColor` plus `var(--bg)`
   inherited from the surface, and an `<img>` breaks both on every surface. The build script owns
   the inlined copy; never paste one by hand. **The homepage's inlined copy is deliberately STALE
   as of 2026-07-28** and carries the old plate: the homepage is being rebuilt by other hands and
   writing it from here would either be thrown away or throw their work away. Re-inject it with
   the command below once that rebuild lands.
2. Wrap it in `<div class="fig-globe fig-globe--bleed">` for a full bleed band, or
   `fig-globe--crop` for a plate inside a column. There is no caption, so there is no
   `<figure>`, and `.fig` would ink it at `--fg-hi` and fight the headline.
3. Add `<script src="/_system/globe.js" defer></script>`. Leaving it out is a supported state,
   not a broken one: the plate is simply still.
4. **Do not add `data-fig`** and do not add `figures.js` for this plate's sake.
5. The plate carries `<clipPath id="cx-globe-limb">`. Inlining it more than once on a page
   duplicates that id. Every copy is geometrically identical so it resolves harmlessly, and the
   preview does exactly that, but a real page should carry one.
6. Preview: `/_dev/globe-preview.html`. Three surfaces turning, the same three held still, a
   link to the zero-script page, and the 375 companion in a real frame.
   `/_dev/globe-hero.html` is the hero band as index.html builds it, and is the page the
   accent-over-type sweep must be run against.

### Re-injecting the plate into the rebuilt homepage

    cd "_reference/globe-build"
    python3 globe_build.py --inject

**Without `--inject` it writes the plate and the projector and leaves every page alone.** That is
the default on purpose, so a routine rebuild can never touch a page someone else owns.

**What it expects to find, and what happens when it does not.** It looks in `site/index.html`
for exactly one line reading

    <div class="fig-globe fig-globe--bleed">

(or `fig-globe--crop`), and replaces everything between that line and its closing `</div>`.
Nothing else on the page is touched: the surrounding section, the type, the nav and every other
section come through byte for byte.

It **fails loudly and writes nothing at all** if the page is missing, if the container is missing
or was renamed, or if there is more than one container (which would duplicate the
`cx-globe-limb` clipPath id). The error names the file, says what it expected, and says that
nothing was written. The write itself goes to a temp file and lands with one atomic rename, so
the page is never left half rewritten. Re-running when the plate is already current is a no-op,
not an error.

Those are the two failure modes that would otherwise be silent, and both are covered by a test
that runs against a **deliberately rewritten homepage**, not the current one:

    python3 test_inject.py

If that test ever fails after the homepage rebuild, the container moved. Fix the page or update
`CONTAINER` in the build script; do not paste the plate in by hand.

## Collisions reported, not resolved (FIGURE-SYSTEM rule 14)

1. **The accent inside a figure.** FIGURE-SYSTEM 1.5 and rule 2 say the accent never appears
   inside a figure. This plate carries it on the ten routes and the eight endpoint dots. The
   brief requires it: convergence is what the plate is for. Reversible in two declarations in
   styles.css 20b; nothing in the SVG changes.
2. **A fill that is not a dot.** FIGURE-SYSTEM 1.5 and rule 4 ban fills except on dots. Land is
   filled with `var(--bg)`. It is what Chip asked for and it is load-bearing: without it the
   graticule shows through the land and the sphere stops reading as solid.
3. **The ink tone.** FIGURE-SYSTEM 1.5 inks a figure at `--fg-hi`. This one inks at
   `--rule-ink-load`, because at 17:1 behind a headline the plate stops being a ground and
   becomes a competitor. Still a surface token, so the works-on-any-ground law holds.
4. **A script, and an animation loop.** FIGURE-SYSTEM 3 permits no per-figure script, and the
   original brief for this plate banned an animation loop outright. Both were relaxed
   deliberately: country outlines that stay correct while the globe turns have to be
   re-projected every frame, and no CSS construction can do that. The dependency rule was not
   relaxed and is intact: zero external dependencies, nothing fetched at runtime, works offline.
   The polarity that matters is also intact, and is measured above rather than argued.
5. **The route weight.** Routes take the solid weight with the dash pattern. Still two weights,
   both from `--rule-hair`.
6. **Six pairs of European marks merge at the limb.** Added to this list 2026-07-28, when the
   check that protects the new Asia-Pacific sites was written and turned on the existing pool.
   Where two sites of one deal are near the limb and heavily foreshortened, their marks come
   within 12 units and read as one: Glasgow and Belfast at 7.3, Aarhus and Hamburg at 8.9,
   Belfast and Manchester at 10.7, Tallinn and Riga at 11.2, Aarhus and Gdansk at 11.3, Glasgow
   and Manchester at 11.7. All six are older than this change and all six are on a plate that is
   already signed off, so they are **reported, not resolved**. The assert covers the sites this
   change added, which is what it was written for; widening it to the whole pool would be a
   redesign, not a fix. Anyone doing that redesign later should move the tighter European pairs
   apart the way Kobe was moved, rather than loosening the threshold.
7. **The deal after a pinned one measures its spread against the wrong predecessor.** The
   trans-Pacific link is written by hand and overwrites six of the 105 drawn deals AFTER they are
   drawn, so the deal following one of those six had its `SPREAD_DEG` separation measured against
   the organic deal it replaced, not against Calgary. That split is deliberate and load bearing:
   without it the whole 105 deal sequence becomes a function of the view longitude and the joint
   search would have to redraw it tens of thousands of times. Six seams out of 105 do not change
   whether the cycle walks the globe.

## What I tried that did not work

1. **A CSS-only rotating sphere.** A meridian great circle projects to an ellipse of
   `rx = R |sin u|, ry = R`, so a meridian is a circle of radius R under `scaleX(|sin u|)`, and
   `scaleX` is a transform. That is a genuinely rotating wireframe sphere with correct limb
   compression, in about ten lines of CSS, zero JS. It is a real technique and worth remembering.
   It cannot carry country outlines or keep an endpoint on a country, so it died with the brief
   change.
2. **A tiling graticule translated inside a circular clip.** The instruction was to space the
   meridians by the sine of longitude so they compress toward the limb. That is geometrically
   incompatible with a rigid translate: for a strip to tile, x has to be monotonic in longitude
   over a full turn, and `R sin u` is not. Uniform spacing tiles but reads as a scrolling
   cylinder, and sine spacing reads correctly only at rest and then visibly slides. Reported
   rather than shipped.
3. **View latitude 52.** Chosen to keep the alliance on the face, and it did so completely:
   nothing ever went behind, so the round-the-back behaviour never happened once in 360 sampled
   route frames. The dial is now at 40.
4. **Douglas Peucker on closed rings.** A geojson ring repeats its first point last, so the
   first segment has zero length and every distance measured from it is zero. It silently kept
   two points per country. The closing point now comes off before simplification.
5. **A bare `data-globe` attribute.** Valid HTML, invalid XML, so the plate would not parse as a
   standalone SVG file. It also broke the parity check, which is how it surfaced. All hooks now
   carry an explicit empty value.
6. **`max-width: 100%`.** The global `img, svg, video` reset silently ate the 220 percent
   narrow-width crop until it was measured. `max-width: none` in that query is load-bearing.
7. **Searching the view longitude before the resting phase.** Two separate one dimensional
   searches gave 50 W, where only 38 percent of the marks live at the resting moment were in
   frame. The phase has to move with the camera. Searched jointly it is 31 W and 150 s, with
   every resting mark in frame.
8. **Taking the six best phases for the trans-Pacific link.** It maximised visible seconds and
   put every appearance in the clean middle of the visibility window, so the arc was never once
   cut by the horizon and the link read as a decal rather than as something on a sphere. The
   slots are now spread across the whole window, edges included, and the build script asserts the
   arc IS cut.
9. **Asserting no two marks of any deal come within 12 units.** True of the new sites, false of
   six pre-existing European pairs, on a plate already signed off. Scoped to the new sites and
   the rest reported. A check written for a new thing should not silently redesign an old one.

## Honesty check

- No endpoint is labelled. No country name, member name, company name or number appears in the
  plate, in `globe.js`, in the CSS, or in the preview.
- No point is a headquarters, a convergence node or a hub. A deal is one dot joined to a handful
  of circles and nothing in the plate says any of them is a centre.
- **The pool widened to include two non-members on 2026-07-28, on request, and it is written up
  as a stated change rather than left to be discovered.** Nothing rendered names them.
- Nothing here shows verifying, checking, catching, scoring or matching. Routes meeting at a
  point is the whole assertion.
- No arrowheads: an arrowhead is a claim about direction and force.
- Zero hex, rgb, hsl or named colours in the SVG, the JS, the CSS or the preview. Every value
  resolves through a token.
- No em dash, no en dash, no emoji.
- Decorative: `aria-hidden`, `focusable="false"`, zero focusable descendants, never a focus trap.

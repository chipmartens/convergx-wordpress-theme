# fig-8-record-persists

Built from `_reference/FIGURE-SYSTEM.md` section 2, the figure titled **The record persists**.
Filename and caption now agree: **Fig. 8** on disk and on the page. (Renamed 2026-07-28 from
`fig-5-record-persists` per POLISH-SPEC section 7.4; the old name carried the build-batch index.)

## The one sentence the reader must be able to say

The parties move on. The record of the introduction stays, and it is mine to defend a decision with.

## Strings, character for character

**Caption:**

```
Fig. 8. The parties part ways. The record of the introduction stays, and it is yours.
```

**aria-label** (already on the `<svg>`):

```
Diagram: two dashed paths meet at a dot, separate again, and end a short way later. From the meeting point a single solid line continues to the edge of the frame, labelled the record.
```

**Inner labels:** `The introduction`, `The parties`, `The record`. Sentence case in the DOM, CSS
uppercases. No fourth label.

## Reworked 2026-07-28, per POLISH-SPEC section 4

The shipped plate read as four faint scratches and one horizontal line, and "The introduction"
collided with the upper entering path. Geometry only; every string is untouched.

- Entering dashed paths now start at `(0, 80)` and `(0, 640)`, the frame's corners, and turn up
  late: `C 340 100, 560 140` and `C 340 620, 560 580`. The late turn does two jobs. It puts real
  angle at the convergence, and it keeps the lower path UNDER the moved label instead of crossing
  it. A smooth sweep through the same endpoints (control points near `460, 430`) runs a hairline
  straight through the word "introduction" between x 460 and x 500. Checked before changing it.
- Departing dashed paths extend to `(1150, 140)` and `(1150, 560)`, so parting reads as travel
  rather than a stub.
- `The introduction` moves below-left of the dot, anchored end at `(560, 430)`.
- `The parties` anchors start at `(1190, 120)`.
- The solid record line `M 600 350 L 1600 350` and its label at `(1560, 320)` are exactly as
  built. The dot is exactly as built.

## Facts

| | |
|---|---|
| viewBox | `0 0 1600 700`, with `width="1600" height="700"` |
| preserveAspectRatio | `xMidYMid meet` |
| Classes used | `fig-solid` (1), `fig-dash` (4), `fig-dot` (1), `fig-label` (3) |
| Dot | one, r 6, at (600, 350), on the node where all four dashed paths and the solid line meet |
| Size | 1.9 KB |
| Accent | none. Monochrome, `currentColor` from the surface, works on dark, light and muted |

## For the integration agent

1. **Inline it, never `<img src>`.** As an `<img>` the classes cannot reach the styles.css rules,
   so it renders black on black and the reveal never runs. Paste the `<svg>` element into the page.
   The `xmlns` attribute is harmless once inlined and can stay.
2. Wrap exactly as section 1.7 of the spec:

```html
<figure class="fig" data-fig>
  <!-- svg here -->
  <figcaption>Fig. 8. The parties part ways. The record of the introduction stays, and it is yours.</figcaption>
</figure>
```

3. **Placement:** `platform/trust-and-security/index.html`, section `03 / The record`, inside
   `.editorial`, after the `.claim` div. Use `grid-column: main`. **Not `bleed`.** Checked on
   2026-07-27: that page has no figure and no image, so its one grid break is unspent, but the
   spec assigns `bleed` only to Fig. 6 and this figure is not it.
4. **The page needs the script line added**, after the existing shell.js line:
   `<script src="/_system/figures.js" defer></script>`
5. **Dependencies I did not create and must not create** (spec assigns them to the styles.css
   owner): `--fig-dash`, `--fig-t`, `--fig-t-fade` in `tokens.css`; section 20 in `styles.css`;
   `_system/figures.js`. Verified 2026-07-27: none of the three existed yet. If they are still
   missing at integration, the figure renders as an unstyled black plate. **No new CSS rule and no
   new JS hook is required by this figure beyond what the spec already specifies.**
6. `--rule-hair` and `--fg-hi` both exist in `tokens.css` already, so the stroke weights and the
   ink resolve with no further work.

## Two collisions reported, not resolved (spec rule 14)

1. **`width`/`height` attributes.** The build brief for this figure says "no width/height
   attributes". The figure spec rule 7 requires them on every figure, and section 3.2 depends on
   them for its zero-layout-shift guarantee. I followed the spec, which the brief itself declares
   law, and set `preserveAspectRatio` deliberately as the brief asked. If the brief is meant to
   win, the two attributes come off and the CLS line in section 3.2 needs a different answer.
2. **The figure number.** Closed 2026-07-28: all six files were renamed to their public numbers,
   so filename and caption now agree on every figure.

## Honesty check on this figure

- Nothing here verifies, checks, catches or watches. Two parties arrive, an introduction happens,
  they part, the record continues.
- No count, no rate, no outcome. Two paths in and two out is the shape of one introduction, not a
  volume.
- The record drawn is the record that exists today: the requirement, the consent, the introduction.
  Nothing in the geometry, the labels or the caption suggests the written reason for a decision is
  on it, because that is in build.
- No arrowheads. The parties cross at the node and continue; direction is not claimed.

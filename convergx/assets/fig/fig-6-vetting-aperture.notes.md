# fig-6-vetting-aperture

**Spec source:** `_reference/FIGURE-SYSTEM.md` section 2, **Fig. 6. The decision, not the score**.
Filename and caption now agree: **Fig. 6** on disk and on the page. (Renamed 2026-07-28 from
`fig-3-vetting-aperture` per POLISH-SPEC section 7.4; the old name carried the build-batch index
and clashed with the real Fig. 3, which is a different, uncommissioned figure.)

## The one sentence the reader must be able to say

*The review ends in a decision, not a score, and ConvergX asks rather than verifies.*

The dot sits IN the aperture and marks where the questions are ASKED. Nothing in the drawing
catches, checks or verifies. One path continues, one path stops. Exactly one of each, so nothing
in the frame can be read as a rejection rate.

## Placement

- Page: `site/about/how-we-vet/index.html`, section **03 / Scope**, after that section's `.body` copy.
- **`grid-column: main` inside `.editorial`. Not `bleed`.** The spec permits `bleed` here only if
  the page has no other grid break. It has several already (`index.html:148`, `:196`, and the S4
  pull), so the budget is spent. Class the wrapper `fig`, nothing else.

## Strings, character for character

- **aria-label** (already on the `<svg>`, do not retype it):
  `Diagram: a dashed path passes through a narrow gate between two vertical lines, with a dot at the point where the questions are asked. Past the gate the path either continues as a solid line off the edge of the frame, labelled introduced, or ends a short way in, labelled not taken forward.`
- **figcaption:**
  `Fig. 6. The review ends in a decision, one way or the other. It does not end in a score.`

## Markup to wire in

Inline the `<svg>` element from the asset verbatim inside this wrapper. The `xmlns` attribute in
the standalone file is for the file to be valid on its own; it is harmless inline, keep or drop it.

```html
<figure class="fig" data-fig>
  <!-- svg goes here, unchanged -->
  <figcaption>Fig. 6. The review ends in a decision, one way or the other. It does not end in a score.</figcaption>
</figure>
```

Also add, after the existing shell.js line on this page:
`<script src="/_system/figures.js" defer></script>`

## What the asset needs from the system (I did not write any of it)

Nothing bespoke. Only the shared section 20 CSS and the tokens from FIGURE-SYSTEM section 1.6 and
1.8, owned by the styles.css agent:

- Classes used: `fig-solid`, `fig-dash`, `fig-dot`, `fig-label`. No others.
- Tokens consumed through those classes: `--rule-hair`, `--fig-dash`, `--font-mono`, `--fg-hi`,
  `--fig-t`, `--fig-t-fade`. All exist in `tokens.css` today except the three `--fig-*`, which
  section 1.6 adds.
- No new rule, no new class, no JS hook beyond the standard `data-fig` and `figures.js`.
- Opened standalone in a browser the file renders as nothing visible, because every stroke and
  fill resolves through the site CSS. That is correct, not a broken export.

## viewBox and the one collision I am reporting rather than resolving

- `viewBox="0 0 1600 700"`, `preserveAspectRatio="xMidYMid meet"` (uniform scale; with
  `width:100%; height:auto` from section 1.8 the meet behaviour never crops).
- **Collision, per rule 14:** my build brief said "no width/height attributes"; FIGURE-SYSTEM
  section 1.4 and rule 7 require `width="1600" height="700"` so the browser reserves the aspect
  ratio before paint (zero CLS). I followed the spec, which is named law and gives a reason. If
  the design director wants them gone, they come off all six figures at once, not just this one.

## Geometry, if anyone needs to verify

Reworked 2026-07-28 per POLISH-SPEC section 4. Geometry only; every string is untouched.

Gate: `(700,80)-(700,300)` and `(700,420)-(700,640)`; aperture y 300 to 420, unchanged. Entering
dashed path starts at `x 260` (not the frame edge: at `x 0` it was one hairline crossing 800 units
of empty plate, which read as unfinished) and lands at `(692,360)`. Dot at `(700,360) r6`. Both
branches leave from `x 708`, so the three meet at the aperture node without overlapping the dot.
Continuing branch is solid and still runs off the right edge at `(1600,320)`. Ending branch is
dashed and now travels to `(1120,540)`, so it reads as a path that went somewhere before it
stopped. No terminal mark, still exactly one continuing branch and one ending branch.

Labels re-anchored to the moved geometry, text unchanged: `(740,120)`, `(1330,280)`, `(1000,600)`.

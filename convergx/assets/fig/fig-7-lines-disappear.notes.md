# fig-7-lines-disappear

Spec source: `_reference/FIGURE-SYSTEM.md` section 2, **Fig. 7. Where the lines meet**.
Filename and caption now agree: **Fig. 7** on disk and on the page. (Renamed 2026-07-28 from
`fig-4-lines-disappear` per POLISH-SPEC section 7.4; the old name carried the build-batch index
and clashed with the real Fig. 4.) Site numbering is append-only.

## REBUILT 2026-07-27, and why

The original batch-slot-4 agent shipped a byte-near duplicate of `fig-4-unnamed-requirement.svg`
into this filename: the spec's **Fig. 4**, not **Fig. 7**. It appears to have read the site-wide
figure number as its own batch index. Fig. 7 was therefore never drawn, and
`industries/index.html` had no asset to receive.

The integration agent rebuilt the asset in place from the spec geometry, which is fully specified
in section 2 and required no invention. The superseded notes file claimed a Fig. 7 existed and
cited its `x="1530"` label as already verified. It did not exist. Nothing else in the batch is
affected: the other five assets are correct and correctly numbered.

## The one sentence the reader must be able to say

*The four industries are one network, joined at a single point, and the answer to a requirement in
one can come from any of the others.*

## Strings, character for character. Do not improve them.

**Caption:**

```
Fig. 7. Four industries, one shared point. A requirement raised in one can be answered from any of the others.
```

**aria-label** (already on the `<svg>`, copy the file rather than retyping):

```
Diagram: four circles, one for each industry, all touching at a single shared point marked by a dot. The labels read aerospace and defence, energy, mining and natural resources, and agriculture.
```

**Inner labels** (sentence case in the DOM, CSS uppercases): `Aerospace and defence`, `Energy`,
`Mining and natural resources`, `Agriculture`. Four labels, one per circle. No fifth.

## Facts

| Item | Value |
|---|---|
| viewBox | `0 0 1600 700`, with `width="1600" height="700"` so the box is reserved before paint |
| preserveAspectRatio | `xMidYMid meet`, set explicitly |
| Classes used | `.fig-solid` (2), `.fig-dash` (2), `.fig-dot` (1), `.fig-label` (4) |
| Dot | one, r 6, at (800, 390), the node all four circles are tangent to |
| pathLength | on both `.fig-solid` circles only. No `.fig-dash` element carries it |
| vector-effect | `non-scaling-stroke` on every stroked element |
| Colour | none in the file. `currentColor` from `.fig svg { color: var(--fg-hi) }` |

**Tangency, verified rather than asserted.** Every circle touches (800, 390) exactly:

- `(540, 390) r 260` at its rightmost point, `540 + 260 = 800`
- `(1060, 390) r 260` at its leftmost point, `1060 - 260 = 800`
- `(800, 170) r 220` at its bottom point, `170 + 220 = 390`
- `(800, 650) r 260` at its top point, `650 - 260 = 390`

The top circle runs to y -50 and the bottom to y 910 against a 700-unit frame. Both crops are the
spec's intent: the system is larger than the frame.

**Label widths, checked against the frame** (mono advance ~0.6em, `font-size: 22px`,
`letter-spacing: 0.18em`, so ~17.2 viewBox units per character):

- `Aerospace and defence`, 21 chars, anchored start at x 70, ends near x 431. Inside.
- `Energy`, anchored end at x 1530, starts near x 1427. Inside.
- `Mining and natural resources`, 28 chars, anchored middle at x 800, spans about 560 to 1040. Inside.
- `Agriculture`, anchored middle at x 800, spans about 705 to 895. Inside.

No label on this plate clips. This is the check that the Fig. 4 label at x 1230 fails; see the
integration report.

## Integration

1. **Inline the SVG.** Never `<img src>` or `background-image`: the ink comes from the page
   through `currentColor`, and an external reference cannot reach the `.fig-*` rules or animate.
2. Wrap in the section 1.7 skeleton:

```html
<figure class="fig" data-fig>
  <!-- svg from /assets/fig/fig-7-lines-disappear.svg -->
  <figcaption>Fig. 7. Four industries, one shared point. A requirement raised in one can be answered from any of the others.</figcaption>
</figure>
```

3. **Placement:** `industries/index.html`, between the H1 lede and the four-industry link index,
   at `grid-column: main` inside the first `.editorial`.
4. Add `<script src="/_system/figures.js" defer></script>` after the existing shell.js line.
5. `xmlns` is present so the file opens standalone. Harmless inline.
6. Do not add `aria` attributes to the inner `<text>` elements. `role="img"` plus the aria-label is
   the whole accessible surface.

## Honesty check

- The tagline belongs to `/about/` and appears once site-wide. Neither the caption nor any label
  here quotes its words, per the spec's honesty note on this figure.
- Nothing verifies, checks, catches or watches. Four circles share a point; that is a statement
  about the shape of the network, not about what ConvergX knows.
- The four industries are named neutrally and nothing is said about any of them, or about the
  reader's own network.
- No count, no rate, no outcome, no date, no module name, no accent, no fill outside the one dot,
  no arrowheads, no markers.
- No em dash, no en dash, no emoji, in the SVG, the caption, the aria-label or this file.

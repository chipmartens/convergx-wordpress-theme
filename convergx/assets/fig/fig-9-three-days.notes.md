# fig-9-three-days.svg

Built to `_reference/FIGURE-SYSTEM.md` section 2, the figure the spec calls **Fig. 9. Three days**.
Filename and caption now agree: **Fig. 9** on disk and on the page. (Renamed 2026-07-28 from
`fig-6-three-days` per POLISH-SPEC section 7.4; the old name carried the build-batch index and
clashed with the real Fig. 6.)

## The one sentence the reader must be able to say

*The Congress is three days of deliberate convergence, not a conference hall.*

## Strings, character for character from the spec. Do not improve them.

**aria-label** (already on the `<svg>`):

> Diagram: seven dashed paths converge from the left into a rectangular frame divided into three
> columns, one for each day of the Congress, September 22 to 24. Three dots inside the frame mark
> meetings.

**figcaption:**

> Fig. 9. Many paths, one window. Three days in Calgary where the right people are put in the same
> room.

**Inner label** (in the SVG, sentence case in the DOM, CSS uppercases it): `Sep 22 to 24, Calgary`

## Placement

- Page: `congress/index.html`, section "The Congress / 01" (the room-composition section), after
  its copy.
- `grid-column: main`. Not `bleed`: check the page's one-grid-break budget first, and if the break
  is already spent, `main` is the only option.
- Wrapper, verbatim per spec section 1.7:

```html
<figure class="fig" data-fig>
  <!-- contents of fig-9-three-days.svg, inlined -->
  <figcaption>Fig. 9. Many paths, one window. Three days in Calgary where the right people are put in the same room.</figcaption>
</figure>
```

- The SVG is inlined into the page, not referenced with `<img>`: it inherits its ink through
  `currentColor` and the draw-on needs the elements in the page DOM.
- The page needs `<script src="/_system/figures.js" defer></script>` after the existing shell.js
  line.

## Classes and tokens it depends on

Only what the spec already defines. Nothing new is needed.

- `.fig`, `.fig-solid`, `.fig-dash`, `.fig-dot`, `.fig-label` (styles.css section 20)
- `--fig-dash`, `--fig-t`, `--fig-t-fade` (tokens.css), plus the existing `--rule-hair`, `--fg-hi`,
  `--font-mono`
- `[data-fig]` on the `<figure>` for `figures.js`

## Facts

- `viewBox="0 0 1600 700"`, `width="1600" height="700"`, `preserveAspectRatio="xMidYMid meet"`.
- 3 solid elements (all `pathLength="1"`), 7 dashed paths (no `pathLength`), 3 dots at r 6, 1 label.
- Every stroke carries `vector-effect="non-scaling-stroke"`. No hex, no rgb(), no accent, no fill
  except the dots. No arrowheads, no markers, no numbers beyond the permitted Congress dates.
- 2.4 KB.

## Three things the integration agent should know

1. **The window is a `<path>`, not a `<rect>`; the two dividers are `<path>`, not `<line>`.**
   Coordinates are the spec's exactly (`980,110` to `1440,590`; dividers at x 1133 and x 1287,
   y 110 to 590), and the render is identical. Reason: the draw-on sets `stroke-dasharray: 1` and
   relies on `pathLength="1"` normalising the perimeter. `pathLength` on `<path>` is honoured
   everywhere; on basic shapes it is not, and where it is ignored the window would render as a
   1-unit dotted outline instead of a solid one. Revert to `<rect>`/`<line>` only if the design
   director wants the literal element names and has accepted that risk.
2. **The build brief says "no width/height attributes"; the figure spec section 1.4 and rule 7
   require them.** Kept them, matching the spec and the already-shipped fig-1: they reserve the
   aspect ratio before paint, which is the zero-layout-shift guarantee, and `.fig svg { width:100%;
   height:auto }` overrides them for layout anyway. Flagging rather than resolving it silently, per
   spec rule 14.
3. **Honesty:** the seven paths, three dots and three columns are structure, never a count. No
   caption, alt text, `data-spec` or adjacent copy may attach a number of attendees, introductions
   or deals to them, and the dates are the only permitted figure here (the tenth year is not in
   this figure). Nothing in the figure says ConvergX knows, verifies or checks anything: paths
   arrive, a room has three days, people meet.

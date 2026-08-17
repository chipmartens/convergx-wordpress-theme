# fig-5-two-sides-one-broker

This is **Fig. 5, "Two sides, one broker"** in `_reference/FIGURE-SYSTEM.md` section 2.
Filename and caption now agree. (Renamed 2026-07-28 from `fig-2-two-sides-one-broker` per
POLISH-SPEC section 7.4; the old name carried the build-batch index.) Site numbering is
append-only, and Fig. 1 to Fig. 3 are already placed.

## The one sentence a reader must be able to say

The two sides never touch. A person at ConvergX is the only thing that joins them.

## Strings, ship character for character

**Caption** (inside `<figcaption>`):

    Fig. 5. The two sides never meet on their own. A person carries the introduction across the gap.

**aria-label** (already on the `<svg>`):

    Diagram: two separate systems of circles that do not touch. A single dot between them, labelled a person at ConvergX, is joined to each side by a short solid line. Nothing else crosses the gap.

## Frame

- `viewBox="0 0 1600 700"`, with `width="1600" height="700"` attributes so the box is reserved
  before paint (spec section 1.4 and rule 7; zero layout shift depends on them).
- `preserveAspectRatio="xMidYMid meet"`, set explicitly rather than left to the default.

## Classes it needs

From styles.css section 20 as written in spec section 1.8. Nothing else, no new rule:
`.fig` (on the wrapping `<figure>`), `.fig-solid`, `.fig-dash`, `.fig-dot`, `.fig-label`,
plus the `.fig-armed` / `.fig-played` reveal states.

Tokens it needs, added once by the styles.css owner per spec section 1.6:
`--fig-dash`, `--fig-t`, `--fig-t-fade`. **None of the three exists in `tokens.css` yet.**
Everything else resolves through `--fg-hi`, `--rule-hair` and `--font-mono`, which all exist.

## For the integration agent

1. **Inline the SVG.** Do not reference it with `<img src>`. The whole colour model is
   `currentColor` inherited from the surface, and an `<img>` breaks it on every surface.
2. Wrap it in the section 1.7 skeleton verbatim:
   `<figure class="fig" data-fig>` ... `<figcaption>` with the caption string above.
3. **Home:** `platform/vetting-and-introductions/`, section "01 / Today", after that section's
   body copy.
4. **`grid-column: main`, not `bleed`.** That page's one grid break is already spent on Fig. 3.
5. Add `<script src="/_system/figures.js" defer></script>` after the existing `shell.js` line on
   that page. With JS off or reduced motion on, the CSS default is the finished plate, so nothing
   here can blank the figure.
6. Element order in the file is solid, dashed, dot, labels. Keep it: the four-step reveal reads
   correctly in DOM order and the dot must sit above the lines it terminates.

## One collision reported, not resolved (spec rule 14)

*(The caption-versus-filename collision is closed: the file was renamed to `fig-5` on 2026-07-28
so the two numbers agree.)*

1. **`width`/`height` attributes.** The build brief for this figure said no `width`/`height`
   attributes; FIGURE-SYSTEM section 1.4 and rule 7 require them and hang the zero-layout-shift
   guarantee on them. Built to FIGURE-SYSTEM, since that file is law for figures.
   `preserveAspectRatio` is set deliberately either way.

## Honesty check on this figure

- The joining element is a person, drawn as a dot. Not a machine, not a funnel, not an arrow.
  There are no arrowheads anywhere in the file.
- Nothing here shows software matching, scoring, deciding or verifying. Two short solid reaches
  and a person between them is the entire mechanism.
- Neither label, the caption, nor the aria-label says ConvergX knows, checks or confirms anything.
- No module is named. No number, rate, count or outcome appears. No em dash, en dash or emoji.
- No accent, no hex, no rgb(), no fill except the one dot.

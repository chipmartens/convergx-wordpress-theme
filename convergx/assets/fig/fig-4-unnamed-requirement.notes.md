# fig-4-unnamed-requirement

Spec source: `_reference/FIGURE-SYSTEM.md` section 2, **Fig. 4. The unnamed requirement**.
Filename and caption now agree: this is **Fig. 4** on disk and on the page. (Renamed 2026-07-28
from `fig-1-unnamed-requirement` per POLISH-SPEC section 7.4; the old name carried the build-batch
index and had already caused mis-edits.) Site-wide figure numbering is append-only.

## The one sentence

**My requirement moves through this system without my name on it.**

The dashed route enters from off-frame, arrives at a single point on the outer body, and the dot
sitting at that point is the only element with no label. Everything named belongs to the structure.

## Strings, character for character

**Caption:**

```
Fig. 4. The requirement travels without an author. The labels here belong to the structure. None belongs to you.
```

**aria-label:** already set on the `<svg>` in the asset. Do not retype it, copy the file.

```
Diagram: a dashed path enters from the left and meets a pair of concentric circles at a single point marked by a small unlabelled dot. The path is labelled posted with no name on it. The circles are labelled read by a person at ConvergX.
```

**Inner labels (sentence case in the DOM, CSS uppercases):** `Posted with no name on it`,
`Read by a person at ConvergX`. Two labels only. Adding a third is a defect.

## Technical

| Item | Value |
|---|---|
| viewBox | `0 0 1600 700`, with `width="1600" height="700"` attributes so the box is reserved before paint |
| preserveAspectRatio | `xMidYMid meet`, the default, set explicitly. The plate scales whole, never stretches |
| Classes used | `.fig-solid` (2), `.fig-dash` (2), `.fig-dot` (1), `.fig-label` (2) |
| Colour | none in the file. Every stroke and the dot are `currentColor` via styles.css section 20, which sets `.fig svg { color: var(--fg-hi) }`. Works on dark, light and muted with no variant class |
| pathLength | on both `.fig-solid` circles only. No `.fig-dash` element carries it |
| vector-effect | `non-scaling-stroke` on every stroked element |
| Size | ~1.7 KB |

## Integration

1. The asset is a **source file to be inlined**, not an `<img>`. Loaded through `<img>` or
   `<object>` it renders with no ink, because the ink comes from the page. Paste the `<svg>`
   element into the page inside the §1.7 skeleton:

```html
<figure class="fig" data-fig>
  <!-- svg from /assets/fig/fig-4-unnamed-requirement.svg -->
  <figcaption>Fig. 4. The requirement travels without an author. The labels here belong to the structure. None belongs to you.</figcaption>
</figure>
```

2. **Placement:** `index.html`, section `02 / The mechanism`, inside `.editorial`, after the
   `.body` paragraph ("Nothing anyone else sees carries an author..."), at `grid-column: main`.
   The homepage's one grid break is already spent on the photograph at `index.html:97`, so the
   figure never takes `bleed`.
3. **Add** `<script src="/_system/figures.js" defer></script>` after the existing shell.js line
   on `index.html`. No inline styles, no `<style>` block, no per-page script.
4. Depends on styles.css section 20 and the three `--fig-*` tokens existing. Owned by the
   styles.css agent, not by me. Nothing else is needed and I have requested no new CSS rule.
5. Nothing in this figure names a module. The homepage message map forbids it and the two labels
   comply.

## COLLISION, reported not resolved (spec rule 14)

**The second label runs off the right frame edge and will be clipped mid-word.**

- `Read by a person at ConvergX` is 28 characters. Uppercased at `font-size: 22px`,
  `letter-spacing: 0.18em`, in a mono face whose advance is the usual 0.6em, each character
  occupies about 17.2 viewBox units, so the string is about **481 units wide**.
- Anchored start at **x 1230**, it ends near **x 1711**. The viewBox is 1600 wide and the outer
  `<svg>` clips. About seven percent of the label disappears.
- The asset ships **exactly as specified**. I have not moved it. Two clean resolutions, both one
  attribute, for the design director to pick:
  1. **Recommended.** `text-anchor="end"` with `x="1560"`, which is the convention Fig. 8 already
     uses for its right-hand label. The label then runs from about x 1079 to x 1560 and sits
     under the circles as intended.
  2. Keep anchored start and move to `x="1100"`, ending near x 1581.
- Minor, same label, either way: at y 655 the dashed outer orbit crosses x 1268, so the label
  overlaps one hairline. Resolution 1 moves the overlap to the left of the string instead of the
  right. Flagging it, not fixing it.

## Honesty check

- Nothing here decides, matches, scores or verifies. A dashed route arrives, a person reads. The
  copy word is "read", never "checked".
- Gate 8 holds in the aria-label: it describes what the diagram **shows**, not what ConvergX knows.
- No numbers, no rates, no counts, no module names, no accent, no fills except the one dot, no
  arrowheads, no dashes of any kind, no emoji.

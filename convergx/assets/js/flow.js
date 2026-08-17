/* The flow band on /congress/: which industry is up, how many connections it
 * makes, and where those connections land on a globe that will not hold still.
 *
 * THE BRIEF, Chip 2026-07-31: "I think it would be worth just a single industry
 * feeding in to the ConvergX block, then it lights up and connects to 3-5
 * random parts on the globe in NATO allianced countries... What I essentially
 * want to show is aerospace and defense lighting up at the same time as
 * ConvergX and then from ConvergX, three nodes light up. And then military
 * lights up with ConvergX and connects to five different spots, and then energy
 * and then construction... What I want to show is one industry problem at a
 * time with ConvergX and that remains lit up while ConvergX makes the
 * connections in their network around the globe to those NATO-aligned
 * countries. So it should show that that's like an active relationship going
 * on at that time."
 *
 * WHY THIS NEEDS A SCRIPT, when the band deliberately ran on CSS alone before.
 * Three things a keyframe cannot express, and each one is in the brief:
 *   1. a count that changes, three to five connections chosen fresh per slot,
 *   2. an endpoint that follows a turning globe rather than sitting still,
 *   3. a feed line that starts at the end of a word, which only the DOM knows.
 * Everything else is still CSS. The split is written up in styles.css section
 * 41 and it is worth keeping: this file says WHAT is happening, the stylesheet
 * says what it LOOKS like, and neither reaches into the other's half.
 *
 * THE POLARITY, same as globe.js: this file only ever REWRITES attributes and
 * toggles one class on elements the markup already contains. It adds nothing
 * and removes nothing. The markup ships a complete figure with the first
 * industry and the hub already lit and all five connections drawn; with
 * scripts off, or if window.cxGlobeAPI never appears, this file returns and the
 * reader sees that figure. Do not make the markup depend on this file by
 * shipping empty paths for it to fill in.
 *
 * BUDGET: no clock of its own. Everything happens inside globe.js's onFrame,
 * which fires only when the globe actually draws, so this file inherits the
 * globe's frame cap, its off-screen pause, its tab-hidden pause and its
 * reduced-motion stop without reimplementing any of them. Do NOT add an
 * IntersectionObserver or a rAF loop here; it would run when the globe does
 * not and the two would fight.
 *
 * Zero dependencies. Nothing is fetched. No build step.
 */
(function () {
  "use strict";

  var band = document.querySelector(".flow-band");
  if (!band) return;

  /* No API means globe.js did not load, did not find a plate, or threw. The
   * static markup is a finished figure, so the correct move is to leave. */
  var api = window.cxGlobeAPI;
  if (!api) return;

  var art   = band.querySelector(".flow-art");
  var stage = band.querySelector(".flow-stage");
  var plate = band.querySelector(".flow-globe svg");
  if (!art || !stage || !plate) return;

  /* Four parallel pools, index aligned. .flow-fan is the resting line,
   * .flow-fan-pulse and .flow-return-pulse are the outbound and returning
   * comets that share its geometry, and .flow-node is the point it lands on.
   * All four are rewritten together or none are. If the counts ever disagree
   * with SLOTS the markup has been edited without this file, and a partial
   * rewrite would look worse than not running: leave.
   * SLOTS is the ceiling, not the count. Each turn uses three to five of them
   * and empties the rest. */
  var SLOTS = 5, MIN_LINKS = 3;
  var line = art.querySelectorAll(".flow-fan");
  var out  = art.querySelectorAll(".flow-fan-pulse");
  var back = art.querySelectorAll(".flow-return-pulse");
  var node = art.querySelectorAll(".flow-node");
  if (line.length !== SLOTS || out.length !== SLOTS ||
      back.length !== SLOTS || node.length !== SLOTS) return;

  /* The ten resting feed lines, and the ONE pulse that travels whichever of
   * them belongs to the industry currently up. One requirement is in flight at
   * a time because one industry is up at a time.
   * ROWS WENT 8 -> 10 on 2026-08-13 with the client's approved vertical list.
   * The guard below is deliberate and is the whole safety net: markup and
   * script must agree or this file leaves the static figure alone. If the band
   * ever goes dead, count the <li>s and the .flow-feed paths first. */
  var sector = band.querySelectorAll(".flow-sector");
  var dot    = band.querySelectorAll(".flow-dot");
  var feed   = art.querySelectorAll(".flow-feed");
  var feedPulse = art.querySelector(".flow-feed-pulse");
  var hub    = band.querySelector(".flow-hub");
  var ROWS = 10;
  if (sector.length !== ROWS || dot.length !== ROWS || feed.length !== ROWS ||
      !feedPulse || !hub) return;

  /* THE ROTATION, in DOM index order of the alphabetical list. Chip's run was
   * "aerospace and defense... and then military lights up... and then energy
   * and then construction and then technology and then agriculture and then
   * mining". REMAPPED 2026-08-13 onto the client's ten: military folded into
   * Aerospace, Defence, Security & Space, and technology into Digital
   * Infrastructure & Cybersecurity, so his run survives as
   *   aerospace, energy, construction, digital, agriculture, mining,
   * and the four he never named take the tail:
   *   advanced manufacturing, health & life sciences, supply chain, financial.
   * ORDER[0] is row 1, which is also the row the markup ships lit.
   * FIXED, NOT SHUFFLED. He asked for a legible rotation. Randomising the order
   * per loop would make it impossible to tell the sequence is a sequence. The
   * randomness in this component is the connection COUNT and the SITES, both of
   * which are about the network, not about the industries.
   * Every index 0-9 appears exactly once. Keep it that way. */
  var ORDER = [1, 5, 3, 4, 2, 8, 0, 7, 9, 6];

  /* Where the fan leaves, in the art viewBox: dead centre under the hub box,
   * whose own ground hides the join. HOLD is how much of a slot the pair stays
   * lit; the tail is a short dark beat so the next industry visibly lights
   * rather than the hub simply never going out. */
  var HUB_X = 800, HUB_Y = 270;
  var HOLD = 0.90;
  var NODE_R = "9";                       /* matches the markup's r */

  /* WHEN THE GLOBE CONNECTIONS EXIST AT ALL, as a fraction of the slot.
   * Chip, 2026-07-31: "I still see the lines from ConvergX to the globe after
   * the connection... I want those to fully disappear so it feels like a
   * totally fresh connection when the next industry lights up with 3 totally
   * different locations selected."
   * They used to persist for the whole slot on the resting .flow-line stroke,
   * which left a faint permanent line pointing at a specific place after the
   * pulse had gone. That is worse than untidy: a standing line to a named point
   * on a map is a claim about an ongoing link, and this site does not make it.
   * A connection here is one requirement being answered once.
   * The window is set against the keyframe percentages in styles.css and has to
   * stay outside them at both ends, or a pulse will animate along a path that
   * does not exist. Today: the outbound fan leaves at 26% (later with --d), the
   * return lands by 70%, the node goes dark at 84%. RE-CHECK THESE IF YOU
   * RE-TIME ANY OF THOSE KEYFRAMES. The gap that is left, 86% through to 22% of
   * the next slot, is the clean stage the next industry draws onto. */
  var LINK_IN = 0.22, LINK_OUT = 0.86;

  /* ---- which sites are eligible --------------------------------------- */

  /* NATO MEMBER SITES, derived from D.SITES by reading the coordinates, not
   * guessed. The pool is 56 [lat, lon] pairs and it is ordered: entries 0 to
   * 50 sit in member countries and entries 51 to 55 do not.
   *
   *   0-4    Canada       Vancouver, Calgary, Winnipeg, Toronto, Montreal
   *   5-9    United States Anchorage, Seattle, Denver, Houston, Boston
   *   10     Iceland      11-13 United Kingdom   14-15 Norway
   *   16     Sweden       17-18 Finland          19    Denmark
   *   20     Estonia      21    Latvia           22    Lithuania
   *   23-24  Poland       25-26 Germany          27    Netherlands
   *   28     Belgium      29    Luxembourg       30-31 France
   *   32     Czechia      33    Slovakia         34    Hungary
   *   35     Slovenia     36    Croatia          37-38 Italy
   *   39-40  Spain        41-42 Portugal         43    Greece
   *   44     Bulgaria     45    Romania          46    Albania
   *   47     Montenegro   48    North Macedonia  49-50 Turkiye
   *
   * EXCLUDED, and these are the only exclusions: 51 [43.06, 141.35],
   * 52 [38.27, 140.87] and 53 [35.18, 136.91] are in Japan, 54 [37.46, 126.71]
   * and 55 [35.18, 129.08] are in South Korea. Partners, not members.
   *
   * WHAT BREAKS IF SOMEONE EDITS D.SITES: this range. It is a range only
   * because the pool happens to be sorted that way today. Reorder or extend
   * D.SITES and every index below is wrong, silently, with no visible symptom
   * beyond a dot in the wrong place. Re-derive the list from the coordinates,
   * do not shuffle the numbers. */
  var SITES = [
    [49.2827, -123.1207], [51.0447, -114.0719], [49.8951, -97.1384], [43.6532, -79.3832],
    [45.5019, -73.5674], [61.2181, -149.9003], [47.6062, -122.3321], [39.7392, -104.9903],
    [29.7604, -95.3698], [42.3601, -71.0589], [64.1466, -21.9426], [55.8642, -4.2518],
    [54.5973, -5.9301], [53.4808, -2.2426], [69.6492, 18.9553], [58.97, 5.7331],
    [65.5842, 22.1547], [65.0121, 25.4651], [61.4978, 23.761], [56.1629, 10.2039],
    [59.437, 24.7536], [56.9496, 24.1052], [54.8985, 23.9036], [54.352, 18.6466],
    [52.4064, 16.9252], [53.5511, 9.9937], [48.7758, 9.1829], [51.9244, 4.4777],
    [51.2194, 4.4025], [49.6116, 6.1319], [45.764, 4.8357], [43.6047, 1.4442],
    [49.1951, 16.6068], [48.7164, 21.2611], [47.6875, 17.6504], [46.5547, 15.6459],
    [45.3271, 14.4422], [45.0703, 7.6869], [44.4949, 11.3426], [43.263, -2.935],
    [41.6488, -0.8891], [41.1579, -8.6291], [38.7223, -9.1393], [40.6401, 22.9444],
    [42.1354, 24.7453], [46.7712, 23.6236], [41.3275, 19.8187], [42.4304, 19.2594],
    [41.9981, 21.4254], [41.0082, 28.9784], [39.9334, 32.8597]
  ];

  /* ---- what makes a site usable as an endpoint ------------------------- */

  /* OFF THE LIMB, in two steps, because "prefer the near face" is a preference
   * and a hard rule at the same time and one number cannot be both.
   *
   * z is the component toward the reader: z = 1 is dead centre of the disc,
   * z = 0 is the horizon. A point's distance from the centre of the disc is
   * sqrt(1 - z*z) of the radius, which is what turns these into a look.
   *
   * Z_GOOD 0.44 keeps a pick inside 0.90 of the radius, which is the inboard,
   * clearly-on-the-globe placement the eight solved points had at 0.72. This
   * is what we want every time.
   * Z_MIN 0.25 allows out to 0.97 of the radius and is the floor we drop to
   * when the good pool is empty. It is NOT a taste setting: simulated across a
   * full turn, the good pool is empty for roughly seven percent of it (the
   * moment the near face is centred on the Pacific and the alliance is all
   * round the rim), and the floor pool never falls below four. Raise Z_MIN and
   * there are stretches with nothing to draw at all.
   * FIRST ATTEMPT WAS ONE NUMBER AT 0.15 and it looked wrong: picks landed at
   * 0.99 of the radius, reading as dots floating beside the globe rather than
   * on it. Do not collapse these back into one. */
  var Z_GOOD = 0.44;
  var Z_MIN  = 0.25;

  /* GONE ROUND THE BACK. Far lower than Z_MIN on purpose: a site is chosen with
   * headroom but allowed to travel all the way to the horizon before it is
   * dropped, which is the behaviour Chip asked for. Not 0 exactly, because at
   * z = 0 the point sits exactly on the limb stroke and pops rather than
   * leaves. */
  var Z_HIDE = 0.02;

  /* THE GLOBE IS MASKED, AND THIS NUMBER IS COUPLED TO THAT MASK.
   * .flow-globe fades the plate out to the right and down (styles.css section
   * 41) so it dissolves into the ground rather than ending on a line. The art
   * layer is NOT masked. Land a connector where the mask has taken the globe
   * to nothing and you get a line into empty space, which is the one thing
   * this component must never show.
   * The ramps below are that mask transcribed. IF YOU EDIT THE MASK, EDIT
   * THESE. There is a note on the CSS rule saying the same thing in reverse.
   * 0.05 is a floor, not a taste setting: it rejects the region where the
   * globe is effectively gone. The approved static ring had a node sitting at
   * 0.15, so anything above the floor is inside the look Chip signed off. */
  var MASK_MIN = 0.05;
  var ART_W = 1600, ART_H = 540;                       /* the art viewBox */
  var MASK_X0 = 0.61 * ART_W, MASK_X1 = 0.96 * ART_W;  /* to right, opaque 61%, gone 96% */
  var MASK_Y0 = 0.58 * ART_H, MASK_Y1 = ART_H;         /* to bottom, opaque 58%, gone 100% */

  function maskAlpha(x, y) {
    var rx = x <= MASK_X0 ? 1 : (MASK_X1 - x) / (MASK_X1 - MASK_X0);
    var ry = y <= MASK_Y0 ? 1 : (MASK_Y1 - y) / (MASK_Y1 - MASK_Y0);
    if (rx < 0) rx = 0; if (rx > 1) rx = 1;
    if (ry < 0) ry = 0; if (ry > 1) ry = 1;
    return rx * ry;              /* mask-composite: intersect multiplies */
  }

  /* Minimum gap between two endpoints, in art units. Europe is dense at this
   * scale and picks landing on top of each other read as one broken dot rather
   * than several connections. 80 is a little under a third of the globe's 260
   * unit radius; it was 90 of 260 when the stage was 1200 x 620 and the globe
   * was bigger, so this is the same fraction, not a new judgement. It is a
   * preference, not a rule: see the top-up in pick(). */
  var APART = 80;

  /* ---- plate coordinates into art coordinates -------------------------- */

  /* MEASURED, NOT ARITHMETIC. The placement is solved in CSS as percentages
   * (section 41) and it would be easy to retype those three numbers here, but
   * then the registration lives in two files and the next person to move the
   * globe fixes one of them. getScreenCTM asks the browser where the two
   * viewBoxes actually are, so the answer stays right through the percentages,
   * the stage's aspect-ratio, preserveAspectRatio on both layers, and any
   * future change to the stage width.
   * Cached, because it forces layout. Only a resize can change it: the two
   * CTMs share every scroll offset above them, so scrolling cancels out. */
  var xform = null, stale = true;
  var pt = art.createSVGPoint();

  function ctm() {
    if (!stale) return xform;
    /* ZERO SIZE MEANS NOT RENDERED, and it has to be checked separately from a
     * null CTM. Below 60rem the whole band is display:none, and in that state a
     * browser can still hand back a perfectly valid looking identity matrix
     * rather than null. Measured: it does. Acting on that would write plate
     * coordinates straight into the art viewBox. Stay stale, try again next
     * frame, and let the resize that reveals the band fix it. */
    var box = art.getBoundingClientRect();
    if (!box.width || !box.height) return null;
    var a = art.getScreenCTM(), b = plate.getScreenCTM();
    /* Null is the other way the same thing shows up, depending on the browser. */
    if (!a || !b) return null;
    xform = a.inverse().multiply(b);
    stale = false;
    return xform;
  }

  /* A resize moves both the plate registration AND every label, so both caches
   * go at once. Recomputed lazily on the next frame, never in the handler. */
  window.addEventListener("resize", function () { stale = true; feedOrigins = null; });

  function toArt(x, y, m) {
    pt.x = x; pt.y = y;
    return pt.matrixTransform(m);
  }

  /* ---- picking three ---------------------------------------------------- */

  /* A SLOT IS ONE INDUSTRY'S TURN, and it is exactly one --cycle: every CSS
   * beat in the band is written as a percentage of that, so reading the number
   * out of the stylesheet is what keeps the script and the keyframes describing
   * the same six seconds. Do not type 6000 here. */
  function slotMs() {
    var v = parseFloat(getComputedStyle(stage).getPropertyValue("--cycle"));
    return v > 0 ? v * 1000 : 6000;
  }

  /* WHEN THE CSS SEQUENCE ACTUALLY STARTED, taken off the running animation
   * rather than assumed to be zero. A CSS animation begins when its element is
   * first styled, which on a slow load is hundreds of milliseconds after the
   * document timeline's origin. Slicing the timeline at multiples of the slot
   * would then put the industry handover that far out of step with the beats
   * the handover is supposed to introduce. getAnimations gives the exact start
   * time on the same timeline, so the two cannot disagree.
   * Read lazily and cached: at parse time the animation may not exist yet. */
  var originMs = null;
  function origin() {
    if (originMs !== null) return originMs;
    var a = feedPulse.getAnimations ? feedPulse.getAnimations()[0] : null;
    if (a && a.startTime !== null && a.startTime !== undefined) {
      originMs = Number(a.startTime);
    }
    return originMs === null ? 0 : originMs;
  }

  /* The document timeline, which is the clock the CSS animations are on. */
  function clock() {
    return (window.document.timeline && document.timeline.currentTime) || performance.now();
  }

  var picks = [];
  var era = -1;
  var lit = -1;                            /* which sector currently carries .is-live */
  var reduce = window.matchMedia("(prefers-reduced-motion: reduce)");

  /* Every NATO site that could be drawn this frame, split into the two tiers.
   * good is the inboard placement we want; rest is everything else that is
   * still legitimately on the visible globe. */
  function candidates(m) {
    var good = [], rest = [], i, p, a, c;
    for (i = 0; i < SITES.length; i++) {
      p = api.project(SITES[i][0], SITES[i][1]);
      if (p.z < Z_MIN) continue;
      a = toArt(p.x, p.y, m);
      if (maskAlpha(a.x, a.y) < MASK_MIN) continue;
      c = { i: i, x: a.x, y: a.y };
      (p.z >= Z_GOOD ? good : rest).push(c);
    }
    return [good, rest];
  }

  /* Partial Fisher-Yates over one tier, taking candidates that are far enough
   * from everything already chosen. Shuffles only as far as it needs to. */
  function drawFrom(pool, chosen, want) {
    var i, j, k, t, dx, dy, far;
    for (i = 0; i < pool.length && chosen.length < want; i++) {
      j = i + Math.floor(Math.random() * (pool.length - i));
      t = pool[j]; pool[j] = pool[i]; pool[i] = t;
      far = true;
      for (k = 0; k < chosen.length; k++) {
        dx = pool[i].x - chosen[k].x; dy = pool[i].y - chosen[k].y;
        if (dx * dx + dy * dy < APART * APART) { far = false; break; }
      }
      if (far) chosen.push(pool[i]);
    }
  }

  /* Three to five, chosen fresh for this industry. Chip asked for the count to
   * vary, not just the places: a slot that always made the same number of
   * connections would read as a fixed diagram being re-pointed. */
  function pick(m) {
    var tiers = candidates(m), good = tiers[0], rest = tiers[1], i;
    if (good.length + rest.length < MIN_LINKS) return;  /* keep what is on screen rather than half a figure */

    var want = MIN_LINKS + Math.floor(Math.random() * (SLOTS - MIN_LINKS + 1));
    var chosen = [];
    drawFrom(good, chosen, want);                       /* the placement we want */
    if (chosen.length < want) drawFrom(rest, chosen, want);
    /* Last resort: spacing was too greedy for a tight pool. Connections close
     * together beat missing connections, every time. */
    for (i = 0; i < good.length && chosen.length < want; i++) {
      if (chosen.indexOf(good[i]) < 0) chosen.push(good[i]);
    }
    for (i = 0; i < rest.length && chosen.length < want; i++) {
      if (chosen.indexOf(rest[i]) < 0) chosen.push(rest[i]);
    }
    /* A thin pool can still come up short of what we asked for. Draw what we
     * got rather than nothing, so long as it clears the floor. */
    if (chosen.length < MIN_LINKS) return;

    picks = [];
    for (i = 0; i < chosen.length; i++) picks.push(chosen[i].i);
  }

  /* ---- drawing ---------------------------------------------------------- */

  var rnd = Math.round;

  /* Same shape family the eight solved paths used: out of the hub horizontally,
   * into the node horizontally, one cubic between. Their control points were
   * fixed at x 720 and 760, which only behaves while the endpoint is to the
   * right of both; a live endpoint can come as far back as the globe's left
   * limb, so the controls are a proportion of the run instead. At the old
   * endpoints this draws very nearly the old curve. */
  function fan(ex, ey) {
    var run = ex - HUB_X;
    return "M " + HUB_X + " " + HUB_Y +
           " C " + rnd(HUB_X + run * 0.35) + " " + HUB_Y +
           " " + rnd(ex - run * 0.25) + " " + rnd(ey) +
           " " + rnd(ex) + " " + rnd(ey);
  }

  /* A feed line: out of a row's dot horizontally, into the hub horizontally.
   * Controls at 0.45 of the run each side, which is what the eight typed paths
   * used before they were measured. */
  function feedPath(ox, oy) {
    var run = HUB_X - ox;
    return "M " + rnd(ox) + " " + rnd(oy) +
           " C " + rnd(ox + run * 0.45) + " " + rnd(oy) +
           " " + rnd(HUB_X - run * 0.45) + " " + HUB_Y +
           " " + HUB_X + " " + HUB_Y;
  }

  /* THE ORIGINS ARE MEASURED OFF THE DOTS, once per layout. Chip: the lines
   * should leave "at the end of the word", so the origin is wherever that row's
   * label ends, which moves with the label's length, the type scale and the
   * stage width. Nothing here can be typed and stay true.
   * The dot IS the origin, exactly: the row's own dot is what lights when the
   * requirement is submitted, and a line starting anywhere else would break the
   * cause and effect the dot is there to show. That is also why the per-row
   * jitter lives on --g in the markup, moving the dot itself, rather than being
   * added to the path here and pulling the line off the dot.
   * Cheap: ten rects on resize, not per frame. */
  var feedOrigins = null;
  function measureFeeds(m) {
    var box = art.getBoundingClientRect(), i, r, p, a;
    feedOrigins = [];
    for (i = 0; i < ROWS; i++) {
      r = dot[i].getBoundingClientRect();
      if (!r.width && !r.height) { feedOrigins = null; return; }   /* not laid out yet */
      /* Screen to art, straight through the art element's own box. The art
       * svg is preserveAspectRatio="none" over the stage, so this is a plain
       * proportional map and needs no CTM. */
      p = { x: (r.left + r.width / 2 - box.left) / box.width * ART_W,
            y: (r.top + r.height / 2 - box.top) / box.height * ART_H };
      feedOrigins.push(p);
      a = feedPath(p.x, p.y);
      feed[i].setAttribute("d", a);
    }
  }

  /* Hidden by geometry, not by opacity. The pulses' opacity is driven by CSS
   * keyframes and an attribute cannot win against a running animation, so an
   * empty d and a zero radius are the only honest way to take one off. Both
   * are attributes nothing else in the component touches. */
  function hide(i) {
    line[i].setAttribute("d", "");
    out[i].setAttribute("d", "");
    back[i].setAttribute("d", "");
    node[i].setAttribute("r", "0");
  }

  /* ONE INDUSTRY LIT, and the hub with it, for the same span. Both classes are
   * written from this one place, which is what guarantees they cannot get out
   * of step and that a second industry can never be up.
   * Guarded on a change, because paint runs every frame and the class list is
   * real DOM: setting it twenty-four times a second would be twenty-four style
   * recalculations for nothing, and it would also restart the transition. */
  function setLit(row) {
    if (row === lit) return;
    if (lit >= 0) sector[lit].classList.remove("is-live");
    if (lit >= 0) feed[lit].classList.remove("is-live");
    if (row >= 0) sector[row].classList.add("is-live");
    if (row >= 0) feed[row].classList.add("is-live");
    hub.classList.toggle("is-live", row >= 0);
    lit = row;
  }

  function paint() {
    var m = ctm();
    if (!m) return;
    if (!feedOrigins) measureFeeds(m);

    var slot = slotMs();
    var t = clock() - origin();
    var e = Math.floor(t / slot);
    var u = (t - e * slot) / slot;              /* 0 to 1 through this slot */
    var row = ORDER[((e % ORDER.length) + ORDER.length) % ORDER.length];

    /* Held open under reduced motion. The connections coming and going IS the
     * animation, so the frozen view is the moment they are all present: one
     * industry lit, the hub lit, its network drawn. A window applied literally
     * at u = 0 would freeze on the empty gap between two slots, which is a
     * blank right hand side and tells the reader nothing. */
    var show = reduce.matches || (u >= LINK_IN && u < LINK_OUT);

    if (reduce.matches) {
      /* Frozen. Advancing the rotation, reshuffling endpoints and re-counting
       * connections are all animation. Park on the industry the markup shipped
       * lit and never move: globe.js has stopped turning under the same query,
       * so the sites picked against its resting frame stay correct. */
      row = ORDER[0];
      u = 0;
      if (!picks.length) pick(m);
    } else if (e !== era) {
      era = e;
      pick(m);                                  /* new industry, new network */
    }
    if (!picks.length) return;

    /* Lit for most of the slot, then a short dark beat. Without the gap the hub
     * would simply never go out, since some industry is always up, and Chip
     * asked to see it light WITH each one. */
    setLit(u < HOLD ? row : -1);

    /* The single requirement in flight travels the live row's line. */
    if (feedOrigins) feedPulse.setAttribute("d", feed[row].getAttribute("d"));

    for (var i = 0; i < SLOTS; i++) {
      if (!show) { hide(i); continue; }               /* between slots: nothing */
      if (i >= picks.length) { hide(i); continue; }   /* unused this slot */
      var s = SITES[picks[i]];
      var p = api.project(s[0], s[1]);
      if (p.z < Z_HIDE) { hide(i); continue; }        /* gone round the back */
      var a = toArt(p.x, p.y, m);
      var d = fan(a.x, a.y);
      line[i].setAttribute("d", d);
      out[i].setAttribute("d", d);
      back[i].setAttribute("d", d);
      node[i].setAttribute("cx", rnd(a.x));
      node[i].setAttribute("cy", rnd(a.y));
      node[i].setAttribute("r", NODE_R);
    }
  }

  api.onFrame(paint);

  /* One paint now, for the case where globe.js never draws again: under
   * reduced motion it returns before starting its loop, so onFrame would
   * never fire and the connectors would sit on their static fallback geometry
   * while the globe sat at a different resting longitude. Load is a second
   * attempt for the case where layout is not settled yet and getScreenCTM
   * came back null. */
  paint();
  window.addEventListener("load", function () { feedOrigins = null; paint(); });

  /* RE-MEASURE WHEN THE WEBFONT LANDS. The origins are label positions, and a
   * font swap changes every label's width. Measured before this was added: the
   * eight feed lines started 5 to 8 art units off their dots, because the first
   * globe frame beat the font and the cache was never invalidated. A resize
   * cleared it by accident, which is why it looked correct while being wrong.
   * Guarded: document.fonts is absent in older engines, where there is no swap
   * to miss anyway. */
  if (document.fonts && document.fonts.ready && document.fonts.ready.then) {
    document.fonts.ready.then(function () { feedOrigins = null; paint(); });
  }

  /* Follow globe.js: it re-checks the query at runtime and so does this. Its
   * own listener was registered first (it loads first), so by the time this
   * runs it has already called rest() or start() and the globe is in its new
   * state. Repainting here covers the reduce direction, where rest() draws one
   * frame and then nothing ever again. era is reset so that coming back OUT of
   * reduced motion picks a fresh network rather than keeping the frozen one. */
  var onMq = function () { stale = true; feedOrigins = null; era = -1; paint(); };
  if (reduce.addEventListener) reduce.addEventListener("change", onMq);
  else if (reduce.addListener) reduce.addListener(onMq);
})();

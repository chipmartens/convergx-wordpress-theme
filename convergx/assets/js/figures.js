/* Figure reveal. Arms then plays each [data-fig] once. With JS off or
 * reduced motion requested, nothing here runs and the CSS default is
 * the finished figure. See _reference/FIGURE-SYSTEM.md section 3.
 *
 * The order matters and is the whole no-JS guarantee: styles.css
 * section 20 draws the FINISHED plate by default. This file adds
 * .fig-armed to hide it, then .fig-played to draw it back in. Nothing
 * here can leave a figure blank, because a script that never runs
 * never arms anything. Never invert this: a CSS default of "hidden"
 * plus a JS class of "shown" is the same animation and a broken page.
 *
 * The observer unobserves on first intersection, so each figure plays
 * exactly once per page load and nothing loops. */
(function () {
  "use strict";
  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
  if (!("IntersectionObserver" in window)) return;
  var figs = document.querySelectorAll("[data-fig]");
  if (!figs.length) return;
  figs.forEach(function (f) { f.classList.add("fig-armed"); });
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) {
        e.target.classList.add("fig-played");
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.35 });
  figs.forEach(function (f) { io.observe(f); });
})();

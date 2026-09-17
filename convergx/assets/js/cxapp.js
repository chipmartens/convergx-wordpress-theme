/* The Congress app carousel on /congress/#app.
 *
 * Progressive enhancement. The markup renders complete without this file:
 * every paragraph open, all four captures visible and captioned. Everything
 * below turns that into one panel at a time.
 *
 * THERE IS NO TIMER IN HERE. The progress line is a CSS animation and this
 * advances on its animationend, so the line a reader watches and the panel's
 * turn are the same object. Pausing on hover and focus is CSS too. What is
 * left is: switch panels, advance, and stop while the section is off screen.
 */
(function () {
  var tabs = document.querySelector('.cxapp-tabs');
  var stage = document.querySelector('.cxapp-stage');
  if (!tabs || !stage) return;

  var panels = [].slice.call(tabs.querySelectorAll('.cxapp-tab'));
  var shots = [].slice.call(stage.querySelectorAll('.cxapp-shot'));
  if (panels.length < 2 || shots.length !== panels.length) return;

  tabs.classList.add('is-live');
  stage.classList.add('is-live');

  var at = -1;

  function show(next) {
    if (next === at) return;
    at = next;
    panels.forEach(function (panel, i) {
      var on = i === next;
      panel.classList.toggle('is-on', on);
      panel.querySelector('.cxapp-tab-head button').setAttribute('aria-expanded', String(on));
    });
    shots.forEach(function (shot, i) { shot.classList.toggle('is-on', i === next); });
  }

  /* The animation restarts by itself: the class moves to an element that did
   * not have it, so the fill runs from zero. No reflow hack is needed. */
  tabs.addEventListener('animationend', function (e) {
    if (e.animationName === 'cxapp-fill') show((at + 1) % panels.length);
  });

  tabs.addEventListener('click', function (e) {
    var button = e.target.closest('.cxapp-tab-head button');
    if (button) show(panels.indexOf(button.closest('.cxapp-tab')));
  });

  /* Off screen is the one pause CSS cannot express. Without it the sequence
   * runs down while nobody is looking and the reader arrives mid-rotation. */
  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      tabs.classList.toggle('is-held', !entries[0].isIntersecting);
    }, { threshold: 0.15 }).observe(tabs);
  }

  show(0);
})();

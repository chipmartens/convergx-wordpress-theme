/* The video facade. Swaps a locally served still for the real player, once,
 * on the reader's click. See styles.css section 46 for why the embed is not
 * in the markup.
 *
 * THE ORDER IS THE WHOLE NO-JS GUARANTEE, and it is the same order figures.js
 * uses: the markup already works before this file runs. Every [data-vid] is a
 * real <a> to the video's watch page, so a reader with scripts off, or a
 * reader who meets an error in this file, still has a route to the video. A
 * script that never runs never removes anything. Never invert this: a dead
 * <div> that only becomes clickable once JavaScript arrives is the same
 * component and a broken page.
 *
 * ZERO EXTERNAL REQUESTS UNTIL THE CLICK. Nothing here touches the network on
 * load. The iframe is constructed inside the handler and not before, which is
 * the single reason this component exists rather than an embed.
 *
 * AUTOPLAY IS THE CLICK. The player starts because a reader pressed play, so
 * autoplay=1 is carrying out the instruction rather than overriding it. There
 * is no autoplay on load anywhere in this file and there must never be one.
 *
 * nocookie, and it is not decoration: youtube-nocookie.com is the host that
 * holds off the tracking cookie until playback actually starts. */
(function () {
  "use strict";
  var links = document.querySelectorAll("a[data-vid]");
  if (!links.length) return;

  links.forEach(function (a) {
    a.addEventListener("click", function (e) {
      /* Modified clicks are the reader asking for a new tab or a saved link,
       * and the href is the honest answer to both. Let the browser have them. */
      if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;
      e.preventDefault();

      var frame = document.createElement("iframe");
      frame.className = "vid-frame";
      frame.src = "https://www.youtube-nocookie.com/embed/" +
        encodeURIComponent(a.getAttribute("data-vid")) +
        "?autoplay=1&rel=0";
      /* A frame with no accessible name is announced as "frame" and nothing
       * else. The title travels on the link that is being replaced. */
      frame.title = a.getAttribute("data-vid-title") || "Video";
      frame.allow = "autoplay; encrypted-media; picture-in-picture; fullscreen";
      frame.setAttribute("allowfullscreen", "");

      a.replaceWith(frame);
      /* The element that had focus has just been removed from the document,
       * which drops a keyboard reader back to the top of the page. Put them
       * inside the thing they asked for instead. */
      frame.focus();
    });
  });
})();

<?php
/**
 * Congress: The Congress app
 *
 * The app band.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="app" class="app-band">
    <div class="wrap">
      <!-- RESTACKED 2026-08-14 (Chip): the eyebrow and the h2 moved INTO the
           left column, so the capture top-aligns with the title and the
           taller column shows more of the app. The coming-soon eyebrow is
           still the availability statement (client instruction 2026-08-13);
           it just sits above the title now. -->
      <div class="app-inner">
        <div class="app-copy">
          <span class="label label--lo">Coming soon</span>
          <h2>The Congress app</h2>
          <p class="app-sub">The agenda, the sessions, and your meetings, in one place</p>
          <p>The Congress app carries the full programme for the three days: sessions, speakers, rooms, and the meetings arranged for you. When the schedule changes, the app is current.</p>
          <p>More detail closer to the Congress.</p>
          <p class="push-s"><a class="link-more" href="<?php echo esc_url( home_url( '/../congress/the-app/' ) ); ?>">The Congress app</a></p>
        </div>
        <!-- REAL CAPTURE landed 2026-08-13: the agenda view, from the app
             team's store screenshots. Synthetic seed data only (no real
             attendee or company names), which is what the old data-spec
             required. The home-screen capture exists too but carries a
             seeded sponsor logo and a placeholder event name, so it stays
             off this site. -->
        <figure class="mod-shot-frame">
          <img src="<?php echo esc_url( CONVERGX_URI ); ?>/assets/img/congress-app-agenda.png" width="880" height="1912" loading="lazy" decoding="async" alt="The Congress app agenda screen: Day 1 sessions with times, tracks, rooms and RSVP state">
          <figcaption>Congress app</figcaption>
        </figure>
      </div>
    </div>
  </section>

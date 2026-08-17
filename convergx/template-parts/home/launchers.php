<?php
/**
 * Homepage launcher row.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section--dense launchers-band" aria-labelledby="launch-h">
    <div class="wrap">
      <!-- The visible h2 and the "Start here" eyebrow came off on Chip's
           instruction, 2026-07-30. The heading is NOT deleted, it is .vh:
           removing it outright would leave this section as an unnamed
           region and drop a level out of the document outline, which is a
           screen-reader cost for a purely visual change. Same treatment
           the proofbar band already uses. -->
      <!-- "Two" as of 2026-08-07. This heading is visually
           hidden and exists only as the row's accessible name, which is
           exactly why it is easy to leave stale. It states a count, so it
           goes out of date the moment a door is added or removed. Count the
           <li>s below before trusting it. -->
      <h2 id="launch-h" class="vh">Two things ConvergX runs</h2>
      <!-- THE X-NAME LEADS, THE PLAIN WORD BECOMES THE EYEBROW. Chip,
           2026-08-04. The pair was the other way round: "ConvergX Xchange"
           over "Conference". It now reads "The Conference" over "Xchange",
           so the brand name is the thing sized to be remembered and the
           descriptor is the thing that explains it. -->
      <ul class="launchers">
        <li class="launcher">
          <!-- TODO: ConvergX Xchange mark. Drop the file in and replace this
               span with an <img class="launcher-logo">. Do not set it in type. -->
          <span class="launcher-logo" aria-hidden="true"></span>
          <p class="label launcher-name">The Congress</p>
          <h3 class="launcher-title"><a href="<?php echo esc_url( home_url( '/congress/' ) ); ?>">Xchange</a></h3>
          <p class="launcher-desc">Celebrating 10 years of ConvergX, the Global Xchange is where decision-makers come to solve problems, build partnerships, and create opportunity.</p>
          <p class="launcher-cta"><a href="<?php echo esc_url( home_url( '/congress/#agenda' ) ); ?>">Agenda <span aria-hidden="true">&#8594;</span></a></p>
        </li>
        <li class="launcher">
          <!-- TODO: ConvergX Xpand mark. Same treatment. -->
          <span class="launcher-logo" aria-hidden="true"></span>
          <p class="label launcher-name">Consulting</p>
          <h3 class="launcher-title"><a href="<?php echo esc_url( home_url( '/xpand/' ) ); ?>">Xpand</a></h3>
          <p class="launcher-desc">ConvergX Xpand consulting services work with decision makers to define problems, identify solutions across new industries, and pair the two.</p>
          <p class="launcher-cta"><a href="<?php echo esc_url( home_url( '/xpand/#what-xpand-does' ) ); ?>">What Xpand does <span aria-hidden="true">&#8594;</span></a></p>
        </li>
      </ul>
    </div>
  </section>

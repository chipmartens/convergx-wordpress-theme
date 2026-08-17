<?php
/**
 * Homepage proof bar.
 *
 * The marks are rendered by shell.js into the data-logo-slot placeholders, from
 * the same logo table the footer uses, so the two can never disagree.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="proofbar-band" aria-labelledby="hero-sponsors-h">
      <!-- WAS the "organisations named in published material" proof bar
           (Boeing, Lockheed, VizworX, Method Effect, Doyletech). Chip,
           2026-08-13: the sponsor carousel takes this slot with a visible
           eyebrow, and the standalone reel band below is removed. The old
           five-mark list is in git history if the proof claim returns. -->
      <div class="wrap">
        <p class="label label--edge" id="hero-sponsors-h">Sponsors, supporters and partners</p>
      </div>
      <div class="sponsor-reel">
      <div class="sponsor-reel-track">
        <ul class="sponsor-set">
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/2026-atco-frontec.png?v=2'); --mark-ratio: 5.634; --mark-cap-frac: 0.700;" role="img" aria-label="ATCO Frontec"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/wavv.png?v=3'); --mark-ratio: 0.855; --mark-cap-frac: 0.400;" role="img" aria-label="WaVv"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/prairiescan.png?v=2'); --mark-ratio: 7.843; --mark-cap-frac: 0.700;" role="img" aria-label="Prairies Economic Development Canada"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/gen2-tax.png?v=2'); --mark-ratio: 2.545; --mark-cap-frac: 0.550;" role="img" aria-label="Gen2.Tax Law Office"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/ces.png?v=3'); --mark-ratio: 3.107; --mark-cap-frac: 0.700;" role="img" aria-label="CES, produced by the Consumer Technology Association"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/lockheed-martin.png?v=3'); --mark-ratio: 4.156; --mark-cap-frac: 0.700;" role="img" aria-label="Lockheed Martin"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/halliburton.png?v=3'); --mark-ratio: 13.913; --mark-cap-frac: 0.900;" role="img" aria-label="Halliburton"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/dnd.png?v=2'); --mark-ratio: 6.400; --mark-cap-frac: 0.700;" role="img" aria-label="Department of National Defence"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/niag.png?v=3'); --mark-ratio: 1.120; --mark-cap-frac: 0.400;" role="img" aria-label="NATO Industrial Advisory Group"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/government-of-alberta.png?v=2'); --mark-ratio: 3.555; --mark-cap-frac: 0.700;" role="img" aria-label="Government of Alberta"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/government-of-canada.png?v=2'); --mark-ratio: 4.158; --mark-cap-frac: 0.700;" role="img" aria-label="Government of Canada"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/nato-act.png?v=3'); --mark-ratio: 1.000; --mark-cap-frac: 0.400;" role="img" aria-label="NATO Allied Command Transformation"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/ideas.png?v=2'); --mark-ratio: 4.800; --mark-cap-frac: 0.700;" role="img" aria-label="IDEaS, Innovation for Defence Excellence and Security"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/alberta-aviation-council.png?v=2'); --mark-ratio: 4.598; --mark-cap-frac: 0.700;" role="img" aria-label="Alberta Aviation Council"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/vuepointe-communications.png?v=2'); --mark-ratio: 0.767; --mark-cap-frac: 0.400;" role="img" aria-label="Vuepointe Communications"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/dentro-financial.png?v=2'); --mark-ratio: 0.755; --mark-cap-frac: 0.400;" role="img" aria-label="Dentro Financial"></span></li>
        </ul>
        <ul class="sponsor-set" aria-hidden="true">
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/2026-atco-frontec.png?v=2'); --mark-ratio: 5.634; --mark-cap-frac: 0.700;" role="img" aria-label="ATCO Frontec"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/wavv.png?v=3'); --mark-ratio: 0.855; --mark-cap-frac: 0.400;" role="img" aria-label="WaVv"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/prairiescan.png?v=2'); --mark-ratio: 7.843; --mark-cap-frac: 0.700;" role="img" aria-label="Prairies Economic Development Canada"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/gen2-tax.png?v=2'); --mark-ratio: 2.545; --mark-cap-frac: 0.550;" role="img" aria-label="Gen2.Tax Law Office"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/ces.png?v=3'); --mark-ratio: 3.107; --mark-cap-frac: 0.700;" role="img" aria-label="CES, produced by the Consumer Technology Association"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/lockheed-martin.png?v=3'); --mark-ratio: 4.156; --mark-cap-frac: 0.700;" role="img" aria-label="Lockheed Martin"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/halliburton.png?v=3'); --mark-ratio: 13.913; --mark-cap-frac: 0.900;" role="img" aria-label="Halliburton"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/dnd.png?v=2'); --mark-ratio: 6.400; --mark-cap-frac: 0.700;" role="img" aria-label="Department of National Defence"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/niag.png?v=3'); --mark-ratio: 1.120; --mark-cap-frac: 0.400;" role="img" aria-label="NATO Industrial Advisory Group"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/government-of-alberta.png?v=2'); --mark-ratio: 3.555; --mark-cap-frac: 0.700;" role="img" aria-label="Government of Alberta"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/government-of-canada.png?v=2'); --mark-ratio: 4.158; --mark-cap-frac: 0.700;" role="img" aria-label="Government of Canada"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/nato-act.png?v=3'); --mark-ratio: 1.000; --mark-cap-frac: 0.400;" role="img" aria-label="NATO Allied Command Transformation"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/ideas.png?v=2'); --mark-ratio: 4.800; --mark-cap-frac: 0.700;" role="img" aria-label="IDEaS, Innovation for Defence Excellence and Security"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/alberta-aviation-council.png?v=2'); --mark-ratio: 4.598; --mark-cap-frac: 0.700;" role="img" aria-label="Alberta Aviation Council"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/vuepointe-communications.png?v=2'); --mark-ratio: 0.767; --mark-cap-frac: 0.400;" role="img" aria-label="Vuepointe Communications"></span></li>
          <li><span class="mark" style="--mark-src: url('<?php echo esc_url( CONVERGX_URI ); ?>/assets/sponsors/dentro-financial.png?v=2'); --mark-ratio: 0.755; --mark-cap-frac: 0.400;" role="img" aria-label="Dentro Financial"></span></li>
        </ul>
      </div>
    </div>
    </section>

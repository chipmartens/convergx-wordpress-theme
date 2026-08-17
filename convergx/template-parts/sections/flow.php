<?php
/**
 * The flow band, as a position in the section run.
 *
 * IT IS NOT A TRAILING BLOCK. On the homepage it sits BETWEEN "What ConvergX
 * is" and "Celebrating 10 years of ConvergX", because it answers the question
 * the section above it just raised. Appending it after the editorial run put
 * the mechanism after the invitation, which is the wrong order for the
 * argument and visibly the wrong place on the page.
 *
 * The markup itself is hardcoded: generated SVG with a globe instance inside
 * it, driven by flow.js and globe.js at runtime. This layout only decides
 * WHERE it appears.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/home/flow-band' );

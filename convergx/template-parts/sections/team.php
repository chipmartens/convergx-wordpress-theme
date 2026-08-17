<?php
/**
 * Flexible-content wrapper for the leadership team.
 *
 * The block itself carries no fields: people are edited on their own screens
 * under Team, which is what makes ten bios maintainable. This only places the
 * section in the page's run.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/team' );

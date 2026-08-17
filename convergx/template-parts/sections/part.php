<?php
/**
 * Built-in section marker: places a theme-rendered section inside the
 * flexible run, so CPT-driven sections (speakers, team) and generated ones
 * (flow diagram) sit at their exact static-site position between exact rows,
 * including inside a colour band.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$convergx_part = (string) get_sub_field( 'part' );

$convergx_map = array(
	'speakers' => 'template-parts/speakers',
	'agenda'   => 'template-parts/agenda',
	'hotels'   => 'template-parts/hotels',
	'sponsors' => 'template-parts/sponsors',
	'team'     => 'template-parts/team',
	'flow'     => 'template-parts/home/flow-band',
);

if ( isset( $convergx_map[ $convergx_part ] ) ) {
	get_template_part( $convergx_map[ $convergx_part ] );
}

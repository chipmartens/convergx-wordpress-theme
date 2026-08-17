<?php
/**
 * The homepage.
 *
 * WHY front-page.php AND NOT A PAGE TEMPLATE. WordPress routes the site's front
 * page here automatically, before page.php and before any assigned template, so
 * the homepage cannot be pointed at the wrong template by accident in Settings.
 * Given four of its sections are hardcoded and order-dependent, that matters.
 *
 * THE ORDER IS THE ARGUMENT, and it is fixed here rather than left to a
 * drag-and-drop field: the globe establishes the room, the proof bar shows who
 * is in it, the launchers offer the two ways in, and only then does the flow
 * band explain the mechanism. Reordering it would not error; it would just stop
 * making the case.
 *
 * FOUR SECTIONS ARE HARDCODED AND MUST STAY THAT WAY. The globe hero and the
 * flow band are generated SVG driven by globe.js and flow.js at runtime; the
 * proof bar and launcher rows are filled by shell.js from the same tables the
 * footer uses. None of them survives a trip through an editor field. Each
 * partial carries its own note explaining what breaks.
 *
 * Everything BETWEEN them is editable: hero copy comes from the page's own
 * fields, and the editorial run comes from the flexible-content field, so an
 * editor can add, reorder and remove prose sections without being able to
 * damage the four generated ones.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	// 1. THE GLOBE. Establishes the room before a single word of argument.
	get_template_part( 'template-parts/home/hero-globe' );

	// 2. THE PROOF BAR. Who is already in it.
	get_template_part( 'template-parts/home/proofbar' );

	// 3. THE LAUNCHERS. The two ways in.
	get_template_part( 'template-parts/home/launchers' );

	/*
	 * 4. THE EDITORIAL RUN. The only part of this page an editor controls, and
	 * it sits between the launchers and the flow band exactly as the static
	 * page does. Renders nothing when the field is empty, so the page never
	 * shows a gap while someone is still writing.
	 */
	convergx_render_sections();

	// 5. THE FLOW BAND. The mechanism, once the reader has a reason to care.
	get_template_part( 'template-parts/home/flow-band' );

endwhile;

get_footer();

<?php
/**
 * Flexible-content section renderer.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the sections field.
 *
 * Each layout maps to one file in template-parts/sections/. A layout with no
 * matching partial renders nothing rather than warning: a missing partial is a
 * deploy problem, and a PHP notice on a production page is worse than a gap.
 */
function convergx_render_sections( $post_id = null ) {
	if ( ! function_exists( 'have_rows' ) ) {
		return;
	}

	$post_id = $post_id ? $post_id : get_the_ID();

	if ( ! have_rows( 'sections', $post_id ) ) {
		return;
	}

	while ( have_rows( 'sections', $post_id ) ) {
		the_row();

		$layout = get_row_layout();
		$file   = CONVERGX_DIR . '/template-parts/sections/' . sanitize_file_name( $layout ) . '.php';

		if ( file_exists( $file ) ) {
			include $file;
		}
	}
}

/**
 * Section heading + edge label.
 *
 * Every section that has a heading renders it the same way, so the markup lives
 * here once. Eyebrows LABEL a section, they never narrate it.
 */
function convergx_section_head( $heading, $eyebrow = '' ) {
	if ( ! $heading && ! $eyebrow ) {
		return;
	}
	?>
	<div class="sec-head">
		<?php if ( $heading ) : ?>
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<?php if ( $eyebrow ) : ?>
			<span class="label label--lo label--edge"><?php echo esc_html( $eyebrow ); ?></span>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Inline an SVG figure from the theme.
 *
 * WHY INLINE AND NOT AN <img>: the figures are styled by the design system.
 * Their strokes and labels resolve currentColor and the surface custom
 * properties, so the same file reads correctly on a light and a dark ground.
 * Referenced through <img> they would render with their authored colours and go
 * near-invisible on the dark surface.
 *
 * Only files inside the theme's own assets/fig directory are ever read. The
 * slug is basename'd and realpath-checked so a field value cannot walk the
 * filesystem.
 */
function convergx_figure( $slug ) {
	$slug = basename( (string) $slug, '.svg' );

	if ( '' === $slug ) {
		return '';
	}

	$dir  = realpath( CONVERGX_DIR . '/assets/fig' );
	$file = realpath( $dir . '/' . $slug . '.svg' );

	if ( ! $dir || ! $file || 0 !== strpos( $file, $dir . DIRECTORY_SEPARATOR ) || ! is_readable( $file ) ) {
		return '';
	}

	return (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions
}

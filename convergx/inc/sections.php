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

	/*
	 * BANDS SPAN CONSECUTIVE SECTIONS, they are not a per-section wrapper.
	 *
	 * On /xpand/ one .band--navy wraps FOUR sections, from "What is
	 * commercialization?" through "What is the scale for commercialization?".
	 * Wrapping each section in its own band drew four separate blocks with the
	 * page colour showing between them, instead of one continuous ground.
	 *
	 * So the run is walked first and consecutive rows sharing a band are
	 * emitted inside a single wrapper. The wrapper opens when the band changes
	 * and closes when it changes back.
	 */
	$rows = array();
	while ( have_rows( 'sections', $post_id ) ) {
		the_row();
		$rows[] = array(
			'layout' => get_row_layout(),
			'band'   => (string) get_sub_field( 'surface' ),
		);
	}

	$open = '';
	$i    = 0;

	while ( have_rows( 'sections', $post_id ) ) {
		the_row();

		$band = isset( $rows[ $i ]['band'] ) ? $rows[ $i ]['band'] : '';
		$band = in_array( $band, array( 'light', 'dark', 'muted', 'navy' ), true ) ? $band : '';

		if ( $band !== $open ) {
			if ( '' !== $open ) {
				echo '</div>';
			}
			if ( '' !== $band ) {
				// .band--navy is a CLASS carrying its own ink; the other three
				// are colour SCOPES every token resolves against. Emitting navy
				// as data-surface would resolve nothing.
				echo 'navy' === $band
					? '<div class="band--navy">'
					: '<div data-surface="' . esc_attr( $band ) . '">';
			}
			$open = $band;
		}

		$file = CONVERGX_DIR . '/template-parts/sections/' . sanitize_file_name( get_row_layout() ) . '.php';
		if ( file_exists( $file ) ) {
			include $file;
		}

		$i++;
	}

	if ( '' !== $open ) {
		echo '</div>';
	}
}

/**
 * Whether the section run already opens with the page's hero.
 *
 * Plain heroes are seeded as verbatim rows, because several carry paragraphs
 * beyond the title and lede that the two-field template hero cannot hold.
 * When the first row brings its own <h1>, the template must not render a
 * second one above it.
 *
 * @param int|null $post_id Page ID, defaults to the current post.
 * @return bool
 */
function convergx_sections_carry_hero( $post_id = null ) {
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	$rows = get_field( 'sections', $post_id ? $post_id : get_the_ID() );

	if ( empty( $rows[0] ) || 'exact' !== ( $rows[0]['acf_fc_layout'] ?? '' ) ) {
		return false;
	}

	return false !== stripos( (string) ( $rows[0]['html'] ?? '' ), '<h1' );
}

/**
 * Section heading + edge label.
 *
 * Every section that has a heading renders it the same way, so the markup lives
 * here once. Eyebrows LABEL a section, they never narrate it.
 */
function convergx_section_head( $heading, $eyebrow = '', $level = 2 ) {
	if ( ! $heading && ! $eyebrow ) {
		return;
	}

	// A few sections head with h3 rather than h2 in the source, and that is a
	// deliberate subsection level. Flattening every heading to h2 would read the
	// same but would give the page a wrong document outline, which is what a
	// screen reader navigates by.
	$tag = ( 3 === (int) $level ) ? 'h3' : 'h2';
	?>
	<div class="sec-head">
		<?php if ( $heading ) : ?>
			<<?php echo esc_html( $tag ); ?>><?php echo esc_html( $heading ); ?></<?php echo esc_html( $tag ); ?>>
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

/**
 * Resolve a link carried over from the static tree.
 *
 * Those hrefs are document-relative ("../access/request/"), which only resolved
 * because the static pages sat at a known depth. Under WordPress the page URL
 * says nothing about where anything else lives, so every one of them has to
 * become a real site URL. External links and fragments are returned untouched.
 *
 * @param string $href Raw href.
 * @return string
 */
function convergx_local_url( $href ) {
	$href = (string) $href;

	if ( '' === $href ) {
		return '';
	}

	if ( preg_match( '#^(https?:|mailto:|tel:|\#|//)#i', $href ) ) {
		return $href;
	}

	return home_url( '/' . ltrim( preg_replace( '#^(\.\./)+#', '', $href ), '/' ) );
}

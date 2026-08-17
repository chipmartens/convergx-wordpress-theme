<?php
/**
 * Sponsors, supporters and partners.
 *
 * THE MARK SYSTEM. Each logo renders as an empty <span> whose image is a CSS
 * custom property rather than an <img>. That is not decoration: the marks are
 * supplied at wildly different aspect ratios and weights, and a row of plain
 * <img> tags set to one height makes a square crest tower over a long wordmark
 * while a thin wordmark disappears. Two custom properties fix it:
 *
 *   --mark-ratio     the image's true aspect ratio, measured from the file
 *   --mark-cap-frac  how much of the row height this mark should fill, which is
 *                    an OPTICAL judgement a human makes, not a measurement
 *
 * The ratio is derived here from the attachment metadata, so it can never drift
 * from the actual file. The cap fraction is a field, because nothing can
 * compute it.
 *
 * CLEARANCE IS PER MARK. An unticked mark does not render at all. Showing
 * another organisation's logo implies a relationship, so the default is off and
 * a missing logo is the system working.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$sponsors = convergx_field( 'sponsors', null, array() );
$lede     = convergx_field( 'sponsors_lede' );

if ( ! $sponsors ) {
	return;
}

// Resolve the marks first, so an entirely uncleared list renders no heading
// and no empty rule rather than a section with nothing in it.
$marks = array();

foreach ( $sponsors as $s ) {
	if ( empty( $s['cleared'] ) || empty( $s['mark'] ) ) {
		continue;
	}

	$id  = (int) $s['mark'];
	$src = wp_get_attachment_image_url( $id, 'full' );

	if ( ! $src ) {
		continue;
	}

	$meta  = wp_get_attachment_metadata( $id );
	$w     = ! empty( $meta['width'] ) ? (float) $meta['width'] : 0;
	$h     = ! empty( $meta['height'] ) ? (float) $meta['height'] : 0;
	$ratio = ( $w && $h ) ? round( $w / $h, 3 ) : 1;

	$cap = isset( $s['cap_frac'] ) ? (float) $s['cap_frac'] : 0.7;
	$cap = max( 0.2, min( 1, $cap ) );

	$marks[] = array(
		'src'   => $src,
		'label' => isset( $s['label'] ) ? $s['label'] : '',
		'ratio' => $ratio,
		'cap'   => $cap,
	);
}

if ( ! $marks ) {
	return;
}
?>
<section id="sponsors" class="section--dense">
	<div class="wrap">
		<?php convergx_section_head( __( 'Sponsors, supporters and partners', 'convergx' ) ); ?>

		<?php if ( $lede ) : ?>
			<div class="editorial push-end-l">
				<div class="lede"><p><?php echo esc_html( $lede ); ?></p></div>
			</div>
		<?php endif; ?>

		<ul class="proofbar">
			<?php foreach ( $marks as $m ) : ?>
				<li>
					<?php
					/*
					 * role="img" plus aria-label, because the element has no
					 * content and no <img> for a screen reader to announce. A
					 * mark with no label would be silent, which is worse than
					 * absent: the reader is told nothing is there.
					 */
					printf(
						'<span class="mark" style="--mark-src: url(%s); --mark-ratio: %s; --mark-cap-frac: %s;" role="img" aria-label="%s"></span>',
						esc_url( $m['src'] ),
						esc_attr( number_format( $m['ratio'], 3, '.', '' ) ),
						esc_attr( number_format( $m['cap'], 3, '.', '' ) ),
						esc_attr( $m['label'] )
					);
					?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

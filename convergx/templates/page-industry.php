<?php
/**
 * Template Name: Industry
 *
 * One template for all eight industry pages (aerospace-defence, agriculture,
 * construction, energy, manufacturing, military, mining-natural-resources,
 * technology). They differ in copy and hero image, never in structure.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$eyebrow    = convergx_field( 'eyebrow', null, get_the_title() );
	$hero_title = convergx_field( 'hero_title', null, get_the_title() );
	$hero_lede  = convergx_field( 'hero_lede' );
	$hero_img   = convergx_field( 'hero_image' );
	$say        = convergx_field( 'say' );
	$say_body   = convergx_field( 'say_body' );
	?>

	<?php
	/*
	 * THE HERO VEIL BAND.
	 *
	 * data-surface="dark" is set on the SECTION, not inherited from the body.
	 * These pages run light below the fold, so the hero carries its own surface
	 * scope. Without it the copy resolves light-on-light and disappears.
	 *
	 * The image is aria-hidden and alt="" on purpose: it is atmosphere behind
	 * type, and the heading already carries the meaning. A described background
	 * would be read out before the headline for no gain.
	 */
	?>
	<section class="hero-veil-band" data-surface="dark">
		<?php if ( $hero_img ) : ?>
			<div class="hero-veil" aria-hidden="true">
				<?php
				echo wp_get_attachment_image(
					$hero_img,
					'full',
					false,
					array(
						'alt'            => '',
						'fetchpriority'  => 'high',
						'decoding'       => 'async',
					)
				);
				?>
				<span></span>
			</div>
		<?php endif; ?>

		<div class="editorial">
			<div class="hero-veil-copy">
				<?php if ( $eyebrow ) : ?>
					<header><span class="label label--edge"><?php echo esc_html( $eyebrow ); ?></span></header>
				<?php endif; ?>
				<h1 class="<?php echo esc_attr( convergx_h1_class() ); ?>"><?php echo esc_html( $hero_title ); ?></h1>
				<?php
				// Each line is its own .lede-text paragraph, matching the source
				// pages. One field, split on blank lines, so an editor writes
				// paragraphs rather than managing a repeater of one-liners.
				foreach ( preg_split( '/\R{2,}/', (string) $hero_lede, -1, PREG_SPLIT_NO_EMPTY ) as $line ) :
					?>
					<p class="lede-text"><?php echo esc_html( trim( $line ) ); ?></p>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php
	/*
	 * The opening .say section is NOT rendered from fields any more: it
	 * arrives as the first verbatim row of the section run, exactly as the
	 * static page sets it (on /industries/military/ the say sits BETWEEN two
	 * plain paragraphs in a rail-less section, which a field could not
	 * express). Rendering the old say/say_body fields here printed the same
	 * line twice.
	 */
	convergx_render_sections();
	?>
	<?php
endwhile;

get_footer();

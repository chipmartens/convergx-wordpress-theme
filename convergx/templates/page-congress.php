<?php
/**
 * Template Name: Congress
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$hero_title = convergx_field( 'hero_title', null, get_the_title() );
	$hero_lede  = convergx_field( 'hero_lede' );
	$hero_img   = convergx_field( 'hero_image' );
	?>

	<?php
	/*
	 * THE DUOTONE HERO. Sibling of the sector pages' veil hero, not a variant.
	 * This one flattens the frame to luminance and pushes a blue through it on
	 * a colour blend; the sector heroes take no filter and no tint at all. The
	 * treatment is the whole brief on both, and it is the opposite instruction,
	 * so the two components stay separate. `data-hero="photo"` on <body> is what
	 * turns this one on: see convergx_hero().
	 */
	?>
	<section id="overview" class="section--open hero-photo-band">
		<?php if ( $hero_img ) : ?>
			<div class="hero-photo" aria-hidden="true">
				<?php
				echo wp_get_attachment_image(
					$hero_img,
					'full',
					false,
					array( 'alt' => '', 'fetchpriority' => 'high', 'decoding' => 'async' )
				);
				?>
				<span></span>
			</div>
		<?php endif; ?>

		<?php
		/*
		 * THE STATIC HERO'S OWN MARKUP, element for element: the copy sits in
		 * .editorial.hero (not a bare rail), the label in header.hero-head,
		 * and the button in p.hero-cta. The stylesheet positions the hero by
		 * those classes, so a plainer structure rendered the same words in
		 * the wrong geometry. There is NO lede here: the static hero carries
		 * only the two hero-subs and the button.
		 */
		$eyebrow   = convergx_field( 'eyebrow' );
		$hero_subs = convergx_field( 'hero_subs' );
		?>
		<div class="editorial hero">
			<?php if ( $eyebrow ) : ?>
				<header class="hero-head">
					<?php
					// ONE ELEMENT, NOT THREE. The label is slash-separated
					// spec metadata and that is the established form for it.
					?>
					<span class="label label--edge"><?php echo esc_html( $eyebrow ); ?></span>
				</header>
			<?php endif; ?>

			<h1 class="<?php echo esc_attr( convergx_h1_class() ); ?>"><?php echo esc_html( $hero_title ); ?></h1>

			<?php if ( $hero_subs ) : ?>
				<?php
				/*
				 * TWO COLUMNS, ONE SIZE. Neither paragraph is a lede: the
				 * pair sit side by side at the same size and the same
				 * measure. Do not reintroduce .lede-text here.
				 */
				?>
				<div class="hero-subs">
					<?php foreach ( preg_split( '/\R{2,}/', (string) $hero_subs, -1, PREG_SPLIT_NO_EMPTY ) as $sub ) : ?>
						<p class="hero-sub"><?php echo esc_html( trim( $sub ) ); ?></p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			$hero_cta     = convergx_field( 'hero_cta', null, __( 'Attend the Congress', 'convergx' ) );
			$hero_cta_url = convergx_field( 'hero_cta_url' );
			if ( $hero_cta ) :
				?>
				<p class="hero-cta">
					<a class="btn btn--solid" href="<?php echo esc_url( $hero_cta_url ? convergx_local_url( $hero_cta_url ) : home_url( '/congress/register/' ) ); ?>"><?php echo esc_html( $hero_cta ); ?></a>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<?php
	/*
	 * THE DARK RUN, in the static page's order: the impact statement, then who
	 * attends, then the flow diagram, which is the last of the dark ground
	 * before the page turns light.
	 *
	 * Hardcoded for the same reason as the homepage's: the flow band is
	 * generated SVG with a globe instance inside it, driven at runtime, and it
	 * registers ids that collide if it appears twice on a page.
	 */
	get_template_part( 'template-parts/congress/impact' );
	get_template_part( 'template-parts/congress/flow-band' );

	/*
	 * EVERYTHING BETWEEN THE FLOW BAND AND THE APP BAND IS THE SECTION RUN,
	 * matching the static page's order exactly: the subnav, the overview and
	 * who-attends sections, then the speakers/agenda/hotels/sponsors part
	 * markers (each rendered from its own screen), then the sponsorship
	 * contact section. Hardcoding those parts here put them in a fixed order
	 * the static page does not have; the run carries the order now, including
	 * the light band that spans from the overview through the sponsor wall.
	 */
	convergx_render_sections();

	get_template_part( 'template-parts/congress/app-band' );
	?>
	<?php
endwhile;

get_footer();

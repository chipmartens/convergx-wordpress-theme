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

		<div class="wrap">
			<div class="editorial">
				<h1 class="display--hero"><?php echo esc_html( $hero_title ); ?></h1>
				<?php foreach ( preg_split( '/\R{2,}/', (string) $hero_lede, -1, PREG_SPLIT_NO_EMPTY ) as $line ) : ?>
					<p class="lede-text"><?php echo esc_html( trim( $line ) ); ?></p>
				<?php endforeach; ?>
			</div>
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
	get_template_part( 'template-parts/congress/who-attends' );
	get_template_part( 'template-parts/congress/flow-band' );

	// Editorial sections, from the flexible-content field.
	convergx_render_sections();

	// The speakers grid and every bio overlay, from the Speakers post type.
	get_template_part( 'template-parts/speakers' );

	/*
	 * ORDER: speakers, then agenda, then where to stay, then who paid for it.
	 * A reader decides on the room and the programme before logistics, and
	 * logistics before the sponsor wall. Each of these renders nothing at all
	 * when its field is empty, so the page stays coherent while it is being
	 * filled in rather than showing empty headings.
	 */
	get_template_part( 'template-parts/agenda' );
	get_template_part( 'template-parts/hotels' );
	get_template_part( 'template-parts/sponsors' );
	get_template_part( 'template-parts/congress/app-band' );
	?>
	<?php
endwhile;

get_footer();

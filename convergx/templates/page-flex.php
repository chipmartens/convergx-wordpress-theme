<?php
/**
 * Template Name: Sections (flexible)
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$hero_title = convergx_field( 'hero_title', null, get_the_title() );
	$hero_lede  = convergx_field( 'hero_lede' );
	?>
	<section class="section--open">
		<div class="wrap">
			<div class="editorial">
				<h1 class="display--hero"><?php echo esc_html( $hero_title ); ?></h1>
				<?php if ( $hero_lede ) : ?>
					<p class="lede-text"><?php echo esc_html( $hero_lede ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php convergx_render_sections(); ?>
	<?php
endwhile;

get_footer();

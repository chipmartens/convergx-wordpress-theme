<?php
/**
 * Default page template.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	if ( ! convergx_sections_carry_hero() ) :
		$hero_title = convergx_field( 'hero_title', null, get_the_title() );
		$hero_lede  = convergx_field( 'hero_lede' );
		?>
		<section class="section--open">
			<div class="wrap">
				<div class="editorial">
					<h1 class="<?php echo esc_attr( convergx_h1_class() ); ?>"><?php echo esc_html( $hero_title ); ?></h1>
					<?php if ( $hero_lede ) : ?>
						<p class="lede-text"><?php echo esc_html( $hero_lede ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( get_the_content() ) : ?>
		<section>
			<div class="wrap">
				<div class="editorial">
					<div class="body"><?php the_content(); ?></div>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
endwhile;

get_footer();

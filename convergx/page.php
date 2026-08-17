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
		<?php
		/*
		 * NO EDITORIAL RAIL AROUND THE MONEY PATH. The cart and checkout
		 * pages hold WooCommerce's React blocks in their content, and the
		 * rail's .body track is a 450px column on the right of the page.
		 * Squeezed into it, the cart block collapsed its two-column layout
		 * and drew the product name over the price. Those pages get a plain
		 * wrap; the card look comes from woocommerce.css section 13.
		 */
		$convergx_woo_page = function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() );
		?>
		<section>
			<div class="wrap">
				<?php if ( $convergx_woo_page ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<div class="editorial">
						<div class="body"><?php the_content(); ?></div>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
	<?php
endwhile;

get_footer();

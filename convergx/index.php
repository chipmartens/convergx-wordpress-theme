<?php
/**
 * The fallback template. WordPress will not activate a classic theme without
 * this file, and every request that matches no more specific template lands
 * here. On convergx.co that means the 46 legacy posts, whose content is Divi
 * shortcode text and will render as literal `[et_pb_section...]` under any
 * non-Divi theme. convergx_shortcode_residue_notice() below is what stops that
 * being silent.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="section--open">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="editorial">
				<?php if ( is_home() && ! is_front_page() ) : ?>
					<h1 class="display--hero"><?php single_post_title(); ?></h1>
				<?php elseif ( is_archive() ) : ?>
					<h1 class="display--hero"><?php the_archive_title(); ?></h1>
				<?php elseif ( is_search() ) : ?>
					<h1 class="display--hero"><?php printf( esc_html__( 'Search: %s', 'convergx' ), esc_html( get_search_query() ) ); ?></h1>
				<?php endif; ?>
			</div>

			<ul class="store">
				<?php while ( have_posts() ) : the_post(); ?>
					<li class="store-item">
						<h2 class="store-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="body"><?php the_excerpt(); ?></div>
					</li>
				<?php endwhile; ?>
			</ul>

			<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
		<?php else : ?>
			<div class="editorial">
				<h1 class="display--hero"><?php esc_html_e( 'Nothing here', 'convergx' ); ?></h1>
				<div class="lede"><p><?php esc_html_e( 'There is no content at this address.', 'convergx' ); ?></p></div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();

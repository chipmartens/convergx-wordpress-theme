<?php
/**
 * Single post.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>
		<section class="section--open">
			<div class="wrap">
				<div class="editorial">
					<h1 class="display--hero"><?php the_title(); ?></h1>
					<?php if ( get_the_date() ) : ?>
						<p class="lede-text"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<section>
			<div class="wrap">
				<div class="editorial">
					<div class="body"><?php the_content(); ?></div>
				</div>
			</div>
		</section>
	</article>
	<?php
endwhile;

get_footer();

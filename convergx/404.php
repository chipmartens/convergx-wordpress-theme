<?php
/**
 * 404.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="section--open">
	<div class="wrap">
		<div class="editorial">
			<h1 class="display--hero"><?php esc_html_e( 'That page is not here', 'convergx' ); ?></h1>
			<div class="lede">
				<p><?php esc_html_e( 'The address you followed does not resolve to a page on this site.', 'convergx' ); ?></p>
			</div>
			<p class="store-act">
				<a class="btn btn--solid" href="<?php echo esc_url( home_url( '/congress/' ) ); ?>"><?php esc_html_e( 'The Congress', 'convergx' ); ?></a>
			</p>
		</div>
	</div>
</section>

<?php
get_footer();

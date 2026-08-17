<?php
/**
 * Editorial section.
 *
 * THE COLOUR BAND. A section can sit on its own [data-surface] ground rather
 * than inheriting the page's. That is how the static pages break a long run
 * into alternating bands: the homepage is dark with two light bands in it,
 * /about/ is light with dark ones.
 *
 * The attribute has to go on a wrapper that CONTAINS the section, not on the
 * <section> itself, because the surface scope sets the background and the
 * section's own padding has to sit inside that background. On the section it
 * would paint the colour only behind the content box and leave the block
 * padding showing the page colour through, which reads as a misaligned stripe.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$convergx_dense   = get_sub_field( 'dense' ) ? ' section--dense' : '';
$convergx_surface = (string) get_sub_field( 'surface' );
$convergx_surface = in_array( $convergx_surface, array( 'light', 'dark', 'muted' ), true ) ? $convergx_surface : '';

if ( $convergx_surface ) :
	?>
	<div data-surface="<?php echo esc_attr( $convergx_surface ); ?>">
<?php endif; ?>

<section class="<?php echo esc_attr( trim( $convergx_dense ) ); ?>">
	<div class="wrap">
		<?php convergx_section_head( get_sub_field( 'heading' ), get_sub_field( 'eyebrow' ) ); ?>

		<?php if ( get_sub_field( 'say' ) ) : ?>
			<?php
			// Full measure, a direct child of the wrap rather than inside the
			// .editorial rail. In the rail it starts at the 5th of 12 columns,
			// which makes the site's largest non-heading line begin halfway
			// across the page and read as a pull quote instead of a statement.
			?>
			<p class="say"><?php echo esc_html( get_sub_field( 'say' ) ); ?></p>
		<?php endif; ?>

		<div class="editorial">
			<?php if ( get_sub_field( 'lede' ) ) : ?>
				<div class="lede"><p><?php echo esc_html( get_sub_field( 'lede' ) ); ?></p></div>
			<?php endif; ?>
			<?php if ( get_sub_field( 'body' ) ) : ?>
				<div class="body"><?php echo wp_kses_post( get_sub_field( 'body' ) ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( $convergx_surface ) : ?>
	</div>
<?php endif; ?>

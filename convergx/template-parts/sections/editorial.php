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
		<?php convergx_section_head( get_sub_field( 'heading' ), get_sub_field( 'eyebrow' ), get_sub_field( 'level' ) ?: 2 ); ?>

		<?php if ( get_sub_field( 'say' ) ) : ?>
			<?php
			// Full measure, a direct child of the wrap rather than inside the
			// .editorial rail. In the rail it starts at the 5th of 12 columns,
			// which makes the site's largest non-heading line begin halfway
			// across the page and read as a pull quote instead of a statement.
			?>
			<p class="say"><?php echo esc_html( get_sub_field( 'say' ) ); ?></p>
		<?php endif; ?>

		<?php
		$convergx_links   = get_sub_field( 'links' );
		$convergx_twopath = get_sub_field( 'twopath' );
		?>

		<div class="editorial">
			<?php if ( get_sub_field( 'lede' ) ) : ?>
				<div class="lede"><p><?php echo esc_html( get_sub_field( 'lede' ) ); ?></p></div>
			<?php endif; ?>
			<?php if ( get_sub_field( 'body' ) ) : ?>
				<div class="body"><?php echo wp_kses_post( get_sub_field( 'body' ) ); ?></div>
			<?php endif; ?>
		</div>
		<?php if ( $convergx_links ) : ?>
			<?php
			/*
			 * A LINK INDEX, NOT A BULLETED LIST. Each row is a destination with
			 * a descriptor saying what the page is. Rendered as prose bullets it
			 * reads as navigation dumped into the middle of an argument, which
			 * is exactly what the first port did.
			 */
			?>
			<ul class="link-index">
				<?php foreach ( $convergx_links as $l ) : ?>
					<?php if ( empty( $l['label'] ) ) { continue; } ?>
					<li>
						<a href="<?php echo esc_url( convergx_local_url( $l['href'] ) ); ?>"><?php echo esc_html( $l['label'] ); ?></a>
						<?php if ( ! empty( $l['note'] ) ) : ?>
							<span class="descriptor"><?php echo esc_html( $l['note'] ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $convergx_twopath ) : ?>
			<?php
			// The chooser. Two doors, foot of the page, never a hero.
			?>
			<div class="two-path">
				<?php foreach ( $convergx_twopath as $t ) : ?>
					<?php if ( empty( $t['label'] ) ) { continue; } ?>
					<a href="<?php echo esc_url( convergx_local_url( $t['href'] ) ); ?>">
						<span class="label"><?php echo esc_html( $t['label'] ); ?></span>
						<?php if ( ! empty( $t['cta'] ) ) : ?>
							<span class="link-more"><?php echo esc_html( $t['cta'] ); ?></span>
						<?php endif; ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php if ( $convergx_surface ) : ?>
	</div>
<?php endif; ?>

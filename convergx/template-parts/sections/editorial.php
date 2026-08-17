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
$convergx_surface = in_array( $convergx_surface, array( 'light', 'dark', 'muted', 'navy' ), true ) ? $convergx_surface : '';

/*
 * THE NAVY BAND IS A CLASS, NOT A SURFACE. The three surfaces are colour
 * scopes that every token resolves against; .band--navy is the brand accent
 * ground and carries its own ink. Emitting it as data-surface="navy" would
 * resolve nothing, because no such scope exists, and the section would render
 * with invalid colours rather than an obvious error.
 */
$convergx_band = ( 'navy' === $convergx_surface ) ? 'band--navy' : '';
$convergx_scope = $convergx_band ? '' : $convergx_surface;

if ( $convergx_band ) :
	?>
	<div class="<?php echo esc_attr( $convergx_band ); ?>">
<?php elseif ( $convergx_scope ) : ?>
	<div data-surface="<?php echo esc_attr( $convergx_scope ); ?>">
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
		$convergx_claims  = get_sub_field( 'claims' );
		$convergx_whatis  = get_sub_field( 'whatis' );
		$convergx_store   = get_sub_field( 'store' );
		?>

		<div class="editorial">
			<?php if ( get_sub_field( 'lede' ) ) : ?>
				<div class="lede"><p><?php echo esc_html( get_sub_field( 'lede' ) ); ?></p></div>
			<?php endif; ?>
			<?php if ( get_sub_field( 'body' ) ) : ?>
				<div class="body"><?php echo wp_kses_post( get_sub_field( 'body' ) ); ?></div>
			<?php endif; ?>
		</div>
		<?php if ( $convergx_whatis ) : ?>
			<?php foreach ( $convergx_whatis as $w ) : ?>
				<?php if ( empty( $w['title'] ) ) { continue; } ?>
				<div class="whatis-row">
					<h3 class="whatis-title"><?php echo esc_html( $w['title'] ); ?></h3>
					<div class="whatis-body"><?php echo wp_kses_post( $w['body'] ); ?></div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( $convergx_claims ) : ?>
			<?php
			// Each claim opens to what backs it. Closed by default, so the page
			// reads as a list of statements and the evidence is one tap away
			// rather than three screens of prose nobody scrolls.
			?>
			<div class="claim">
				<?php foreach ( $convergx_claims as $c ) : ?>
					<?php if ( empty( $c['title'] ) ) { continue; } ?>
					<details class="sess">
						<summary><?php echo esc_html( $c['title'] ); ?></summary>
						<?php echo wp_kses_post( $c['body'] ); ?>
					</details>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $convergx_store ) : ?>
			<ul class="store">
				<?php foreach ( $convergx_store as $ev ) : ?>
					<?php if ( empty( $ev['name'] ) ) { continue; } ?>
					<li class="store-item">
						<h3 class="store-name"><?php echo esc_html( $ev['name'] ); ?></h3>
						<?php if ( ! empty( $ev['meta'] ) ) : ?>
							<p class="label label--lo"><?php echo esc_html( $ev['meta'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $ev['desc'] ) ) : ?>
							<p class="store-desc"><?php echo wp_kses_post( $ev['desc'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $ev['url'] ) ) : ?>
							<p class="store-act">
								<a class="btn" href="<?php echo esc_url( $ev['url'] ); ?>" rel="noopener"><?php echo esc_html( $ev['cta'] ?: __( 'Visit', 'convergx' ) ); ?></a>
							</p>
						<?php endif; ?>
						<?php if ( ! empty( $ev['away'] ) ) : ?>
							<?php // Registration for a partner event is the host's, never ConvergX's. ?>
							<p class="store-away"><?php echo esc_html( $ev['away'] ); ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

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
						<?php
						// A row is either a LINK or a plain label-and-descriptor
						// definition. /congress/the-app/ uses both shapes in one
						// page, so a renderer that assumes an anchor drops half
						// of them.
						?>
						<?php if ( ! empty( $l['href'] ) ) : ?>
							<a href="<?php echo esc_url( convergx_local_url( $l['href'] ) ); ?>"><?php echo esc_html( $l['label'] ); ?></a>
						<?php else : ?>
							<span class="label"><?php echo esc_html( $l['label'] ); ?></span>
						<?php endif; ?>
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

<?php if ( $convergx_band || $convergx_scope ) : ?>
	</div>
<?php endif; ?>

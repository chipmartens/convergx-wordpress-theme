<?php
/**
 * Editorial section.
 *
 * THE COLOUR BAND IS NOT EMITTED HERE. convergx_render_sections() groups
 * consecutive sections that share a band into a single wrapper, because a band
 * spans a RUN of sections rather than one: /xpand/ carries one .band--navy
 * across four of them. Wrapping per section drew four separate blocks with the
 * page colour showing through between them.
 *
 * .editorial is a two-track rail: .lede takes the left columns and .body the
 * right. Anything belonging to the argument goes INSIDE one of those two. A
 * component placed as a sibling of .editorial falls outside the rail and runs
 * the full page width, which is what flattened the homepage's two-column
 * reading into one.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$convergx_dense   = get_sub_field( 'dense' ) ? 'section--dense' : 'section--open';
$convergx_links   = get_sub_field( 'links' );
$convergx_twopath = get_sub_field( 'twopath' );
$convergx_claims  = get_sub_field( 'claims' );
$convergx_whatis  = get_sub_field( 'whatis' );
$convergx_store   = get_sub_field( 'store' );
?>

<section class="<?php echo esc_attr( $convergx_dense ); ?>">
	<div class="wrap">
		<?php convergx_section_head( get_sub_field( 'heading' ), get_sub_field( 'eyebrow' ), get_sub_field( 'level' ) ?: 2 ); ?>

		<?php if ( get_sub_field( 'say' ) ) : ?>
			<?php
			// Full measure, a direct child of the wrap rather than inside the
			// rail. In the rail it starts at the 5th of 12 columns, which makes
			// the site's largest non-heading line begin halfway across the page
			// and read as a pull quote instead of a statement.
			?>
			<p class="say"><?php echo esc_html( get_sub_field( 'say' ) ); ?></p>
		<?php endif; ?>

		<div class="editorial">
			<?php if ( get_sub_field( 'lede' ) ) : ?>
				<div class="lede"><p><?php echo esc_html( get_sub_field( 'lede' ) ); ?></p></div>
			<?php endif; ?>

			<?php if ( get_sub_field( 'body' ) || $convergx_claims ) : ?>
				<div class="body">
					<?php echo wp_kses_post( (string) get_sub_field( 'body' ) ); ?>

					<?php
					/*
					 * ONE .claim WRAPPER PER CLAIM, not one around the set. The
					 * rule between rows is drawn by the wrapper's own edge, so a
					 * single wrapper round all of them draws one rule where the
					 * design wants one per claim.
					 */
					foreach ( (array) $convergx_claims as $c ) :
						if ( empty( $c['title'] ) ) {
							continue;
						}
						?>
						<div class="claim">
							<details class="sess">
								<summary><?php echo esc_html( $c['title'] ); ?></summary>
								<?php echo wp_kses_post( $c['body'] ); ?>
							</details>
						</div>
					<?php endforeach; ?>
				</div>
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
			<ul class="link-index">
				<?php foreach ( $convergx_links as $l ) : ?>
					<?php if ( empty( $l['label'] ) ) { continue; } ?>
					<li>
						<?php
						// A row is either a LINK or a plain label-and-descriptor
						// definition. /congress/the-app/ uses both shapes in one
						// page, so a renderer that assumes an anchor drops half.
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
			<?php // The chooser. Two doors, foot of the page, never a hero. ?>
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

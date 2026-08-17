<?php
/**
 * Accommodation.
 *
 * ConvergX's own published delegate logistics, reproduced for its own
 * delegates. Nothing here asserts anything ABOUT a hotel: no rating, no
 * relationship, no availability, no rate. A rate code is not a rate.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$hotels = convergx_field( 'hotels', null, array() );
if ( ! $hotels ) {
	return;
}
?>
<section id="accommodations" class="section--dense">
	<div class="wrap">
		<?php convergx_section_head( __( 'Where to stay', 'convergx' ), __( 'Accommodation', 'convergx' ) ); ?>

		<div class="mod-faq-list">
			<?php foreach ( $hotels as $h ) : ?>
				<?php
				$name = isset( $h['name'] ) ? $h['name'] : '';
				if ( ! $name ) {
					continue;
				}
				$steps = isset( $h['steps'] ) ? preg_split( '/\R+/', (string) $h['steps'], -1, PREG_SPLIT_NO_EMPTY ) : array();
				?>
				<details class="faq">
					<summary><span><?php echo esc_html( $name ); ?></span></summary>
					<div class="faq-a">
						<div class="hotel">
							<?php
							if ( ! empty( $h['photo'] ) ) {
								echo wp_get_attachment_image(
									$h['photo'],
									'large',
									false,
									array(
										'class'    => 'hotel-shot',
										'alt'      => isset( $h['alt'] ) ? $h['alt'] : '',
										'loading'  => 'lazy',
										'decoding' => 'async',
									)
								);
							}
							?>
							<div class="hotel-detail">
								<?php if ( ! empty( $h['address'] ) ) : ?>
									<p><?php echo esc_html( $h['address'] ); ?></p>
								<?php endif; ?>

								<?php if ( ! empty( $h['phone'] ) ) : ?>
									<p>
										<?php esc_html_e( 'Reservations', 'convergx' ); ?>
										<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $h['phone'] ) ); ?>"><?php echo esc_html( $h['phone'] ); ?></a>
									</p>
								<?php endif; ?>

								<?php if ( ! empty( $h['rate'] ) ) : ?>
									<p><?php echo esc_html( $h['rate'] ); ?></p>
								<?php endif; ?>

								<?php if ( $steps ) : ?>
									<p class="label push-s"><?php esc_html_e( 'Booking with the code', 'convergx' ); ?></p>
									<ol>
										<?php foreach ( $steps as $step ) : ?>
											<li><?php echo esc_html( trim( $step ) ); ?></li>
										<?php endforeach; ?>
									</ol>
								<?php endif; ?>

								<?php if ( ! empty( $h['url'] ) ) : ?>
									<p class="push-s">
										<a class="btn" href="<?php echo esc_url( $h['url'] ); ?>" rel="noopener">
											<?php echo esc_html( ! empty( $h['cta'] ) ? $h['cta'] : __( 'Book', 'convergx' ) ); ?>
										</a>
									</p>
									<?php // Says out loud that the reader is leaving. The booking is the hotel's, not ConvergX's. ?>
									<p class="hotel-away"><?php esc_html_e( "Opens the hotel's own booking site.", 'convergx' ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
/**
 * Template Name: Congress registration
 *
 * Port of the static /congress/register/ page.
 *
 * ORDER IS THE WHOLE POINT, and it is what stops this page becoming a ticket
 * page. Hero, then who the room is for and the fact that attendees are vetted,
 * and only THEN the prices. Never reorder so a price appears above the
 * admission standard. This is enforced in markup here rather than left to a
 * drag-and-drop field, because a Flexible Content field would make the one
 * forbidden edit the easiest edit in the interface.
 *
 * THE HONESTY CONDITION. Payment is taken by WooCommerce, and this page never
 * implies otherwise. There is no cart, no quantity selector, no total-to-pay
 * and no field that looks like it takes a card. Each action is a link to that
 * product's own page, and each one says so underneath. Presenting the products
 * is honest. Simulating the checkout would not be.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$hero_title  = convergx_field( 'hero_title', null, get_the_title() );
	$hero_lede   = convergx_field( 'hero_lede' );
	$passes_lede = convergx_field( 'passes_lede' );
	$passes      = convergx_field( 'passes', null, array() );
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

	<hr class="rule-double">

	<?php
	// ------------------------------------------------------------------
	// WHO THE ROOM IS FOR. Sits ABOVE the prices. See the note at the top.
	// ------------------------------------------------------------------
	if ( get_the_content() ) :
		?>
		<section>
			<div class="wrap">
				<div class="sec-head">
					<h2><?php esc_html_e( 'Who the three days are for', 'convergx' ); ?></h2>
					<span class="label label--lo label--edge"><?php esc_html_e( 'Who is here', 'convergx' ); ?></span>
				</div>
				<div class="editorial">
					<div class="body"><?php the_content(); ?></div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $passes ) : ?>
		<section>
			<div class="wrap">
				<div class="sec-head">
					<h2><?php esc_html_e( 'Pricing', 'convergx' ); ?></h2>
					<span class="label label--lo label--edge"><?php esc_html_e( 'Passes', 'convergx' ); ?></span>
				</div>

				<?php if ( $passes_lede ) : ?>
					<div class="editorial push-end-l">
						<div class="lede"><p><?php echo esc_html( $passes_lede ); ?></p></div>
					</div>
				<?php endif; ?>

				<ul class="store">
					<?php
					foreach ( $passes as $pass ) :
						$product_id = isset( $pass['product_id'] ) ? (int) $pass['product_id'] : 0;
						$product    = $product_id ? convergx_get_registration_product( $product_id ) : null;

						/*
						 * NO PRODUCT, NO CARD.
						 *
						 * If WooCommerce is inactive, the ID is wrong, or the
						 * product has been unpublished, this renders nothing
						 * rather than a card with an empty price and a dead
						 * button. A missing card is a visible, obvious problem.
						 * A card advertising a pass nobody can buy is a support
						 * ticket and a lost registration.
						 */
						if ( ! $product ) {
							continue;
						}

						$qualifier = isset( $pass['qualifier'] ) ? $pass['qualifier'] : '';
						$total     = isset( $pass['total_line'] ) ? trim( (string) $pass['total_line'] ) : '';
						$verified  = isset( $pass['total_verified'] ) ? $pass['total_verified'] : '';
						$includes  = isset( $pass['includes'] ) ? (array) $pass['includes'] : array();
						$cta       = isset( $pass['cta_label'] ) && $pass['cta_label'] ? $pass['cta_label'] : __( 'Register', 'convergx' );
						?>
						<li class="store-item">
							<h3 class="store-name"><?php echo esc_html( $product['name'] ); ?></h3>

							<p class="store-price">
								<?php echo esc_html( $product['price_fmt'] ); ?>
								<span class="store-cur"><?php echo esc_html( $product['currency'] ); ?></span>
							</p>

							<?php if ( $qualifier ) : ?>
								<p class="store-qual"><?php echo esc_html( $qualifier ); ?></p>
							<?php endif; ?>

							<?php
							/*
							 * THE TOTAL LINE RENDERS ONLY WITH ITS AS-OF DATE.
							 *
							 * It is our arithmetic on ConvergX's fee structure,
							 * and ConvergX publishes that rate nowhere except
							 * the cart, so it goes stale the moment a fee
							 * changes and nothing announces it. Requiring the
							 * date means an unverified total cannot render at
							 * all: whoever clears the date clears the number.
							 * A reader who meets a different figure at checkout
							 * needs to know when ours was taken.
							 */
							if ( $total && $verified ) :
								?>
								<p class="store-total">
									<strong><?php echo esc_html( $total ); ?></strong>
									<span class="store-asof">
										<?php
										printf(
											/* translators: %s: ISO date the total was measured. */
											esc_html__( 'Checked %s.', 'convergx' ),
											esc_html( $verified )
										);
										?>
									</span>
								</p>
							<?php endif; ?>

							<?php if ( $includes ) : ?>
								<ul class="store-includes">
									<?php foreach ( $includes as $line ) : ?>
										<?php if ( ! empty( $line['text'] ) ) : ?>
											<li><?php echo esc_html( $line['text'] ); ?></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<p class="store-act">
								<?php if ( $product['in_stock'] ) : ?>
									<a class="btn btn--solid" href="<?php echo esc_url( $product['permalink'] ); ?>">
										<?php echo esc_html( $cta ); ?>
									</a>
								<?php else : ?>
									<span class="btn btn--solid is-disabled" aria-disabled="true">
										<?php esc_html_e( 'Not available', 'convergx' ); ?>
									</span>
								<?php endif; ?>
							</p>

							<?php
							/*
							 * VERBATIM FROM CONVERGX'S OWN PAGE. It names the
							 * domain rather than saying "the shop" because the
							 * reader is being told they are leaving, and the
							 * thing that tells them that is the domain name.
							 * Filterable for the case where the shop and this
							 * page end up on the same host.
							 */
							$away = apply_filters( 'convergx_checkout_away_text', __( 'Checkout completes on convergx.co.', 'convergx' ) );
							?>
							<p class="store-away"><?php echo esc_html( $away ); ?></p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
	<?php endif; ?>

	<?php
endwhile;

get_footer();

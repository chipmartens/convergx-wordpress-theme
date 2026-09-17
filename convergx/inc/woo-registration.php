<?php
/**
 * Registration review: attendee details, then charge.
 *
 * Checkout collects one participant record per pass in the cart AND a card
 * via Stripe (authorized, not captured). Kim accepts or rejects in wp-admin.
 * Accept captures the authorization; reject voids it. A refused application
 * never incurs a refund fee because the card was never charged.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Days Kim has to accept or reject before the review window lapses.
 *
 * The window is a reminder, not an auto-cancel.
 */
function convergx_review_days() {
	return (int) apply_filters( 'convergx_review_days', 10 );
}

/**
 * Fields collected for each participant.
 *
 * Name, title, organisation and email are the four Kim asked for. Phone is
 * optional so a reviewer can reach someone without making it a blocker.
 *
 * @return array
 */
function convergx_attendee_fields() {
	return (array) apply_filters(
		'convergx_attendee_fields',
		array(
			'name'  => array(
				'label'        => __( 'Full name', 'convergx' ),
				'type'         => 'text',
				'required'     => true,
				'class'        => array( 'form-row-first' ),
				'autocomplete' => 'name',
			),
			'title' => array(
				'label'        => __( 'Job title', 'convergx' ),
				'type'         => 'text',
				'required'     => true,
				'class'        => array( 'form-row-last' ),
				'autocomplete' => 'organization-title',
			),
			'org'   => array(
				'label'        => __( 'Organisation', 'convergx' ),
				'type'         => 'text',
				'required'     => true,
				'class'        => array( 'form-row-first' ),
				'autocomplete' => 'organization',
			),
			'email' => array(
				'label'        => __( 'Email', 'convergx' ),
				'type'         => 'email',
				'required'     => true,
				'class'        => array( 'form-row-last' ),
				'autocomplete' => 'email',
			),
			'phone' => array(
				'label'        => __( 'Phone', 'convergx' ),
				'type'         => 'tel',
				'required'     => false,
				'class'        => array( 'form-row-wide' ),
				'autocomplete' => 'tel',
			),
		)
	);
}

/**
 * One slot per pass quantity in the cart.
 *
 * @return array
 */
function convergx_cart_attendee_slots() {
	$slots = array();

	if ( ! WC()->cart ) {
		return $slots;
	}

	foreach ( WC()->cart->get_cart() as $item ) {
		$product = isset( $item['data'] ) ? $item['data'] : null;
		$qty     = isset( $item['quantity'] ) ? (int) $item['quantity'] : 0;
		$name    = $product ? $product->get_name() : '';
		$pid     = isset( $item['product_id'] ) ? (int) $item['product_id'] : 0;

		for ( $i = 0; $i < $qty; $i++ ) {
			$slots[] = array(
				'product_id'   => $pid,
				'product_name' => $name,
			);
		}
	}

	return $slots;
}

/**
 * @return bool
 */
function convergx_is_pay_for_order() {
	return function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-pay' );
}

/**
 * Posted attendee rows, aligned to the current cart slots.
 *
 * @return array
 */
function convergx_posted_attendees() {
	$slots  = convergx_cart_attendee_slots();
	$posted = isset( $_POST['cx_attendee'] ) ? wp_unslash( $_POST['cx_attendee'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$out    = array();
	$defs   = convergx_attendee_fields();

	foreach ( $slots as $i => $slot ) {
		$row = array(
			'product_id'   => $slot['product_id'],
			'product_name' => $slot['product_name'],
		);

		foreach ( $defs as $key => $field ) {
			$raw         = isset( $posted[ $i ][ $key ] ) ? $posted[ $i ][ $key ] : '';
			$row[ $key ] = 'email' === $field['type']
				? sanitize_email( $raw )
				: sanitize_text_field( $raw );
		}

		$out[] = $row;
	}

	return $out;
}

/**
 * Attendees stored on an order.
 *
 * @param WC_Order $order Order.
 * @return array
 */
function convergx_order_attendees( $order ) {
	if ( ! $order ) {
		return array();
	}

	$raw = $order->get_meta( '_convergx_attendees' );

	return is_array( $raw ) ? $raw : array();
}

/**
 * An order waiting for Kim: authorized card (on-hold) or the old no-card review status.
 *
 * @param WC_Order $order Order.
 * @return bool
 */
function convergx_order_needs_review( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return false;
	}

	if ( $order->has_status( 'cx-review' ) ) {
		return true;
	}

	if ( ! $order->has_status( 'on-hold' ) ) {
		return false;
	}

	if ( convergx_order_attendees( $order ) ) {
		return true;
	}

	$ids = array_map( 'intval', array_values( convergx_registration_product_ids() ) );
	$ids = array_merge( $ids, array( 60, 61, 62 ) );

	foreach ( $order->get_items() as $item ) {
		if ( in_array( (int) $item->get_product_id(), $ids, true ) ) {
			return true;
		}
	}

	return false;
}

/**
 * @param WC_Order $order Order.
 * @return bool
 */
function convergx_order_has_uncaptured_card( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return false;
	}

	$method = $order->get_payment_method();

	if ( ! $method || 'convergx_review' === $method ) {
		return false;
	}

	if ( class_exists( 'WC_Stripe_Order_Helper' ) ) {
		$helper = WC_Stripe_Order_Helper::get_instance();
		if ( $helper && method_exists( $helper, 'get_stripe_charge_captured' ) ) {
			return 'no' === $helper->get_stripe_charge_captured( $order );
		}
	}

	$captured = $order->get_meta( '_stripe_charge_captured' );

	return 'no' === $captured || ( '' === $captured && $order->has_status( 'on-hold' ) && 0 === strpos( $method, 'stripe' ) );
}

add_action( 'init', 'convergx_register_review_status' );
function convergx_register_review_status() {
	register_post_status(
		'wc-cx-review',
		array(
			'label'                     => _x( 'Registration review', 'Order status', 'convergx' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: order count */
			'label_count'               => _n_noop( 'Registration review <span class="count">(%s)</span>', 'Registration review <span class="count">(%s)</span>', 'convergx' ),
		)
	);
}

add_filter( 'wc_order_statuses', 'convergx_list_review_status' );
function convergx_list_review_status( $statuses ) {
	$out = array();

	foreach ( $statuses as $key => $label ) {
		$out[ $key ] = $label;

		if ( 'wc-on-hold' === $key ) {
			$out['wc-cx-review'] = _x( 'Registration review', 'Order status', 'convergx' );
		}
	}

	if ( ! isset( $out['wc-cx-review'] ) ) {
		$out['wc-cx-review'] = _x( 'Registration review', 'Order status', 'convergx' );
	}

	return $out;
}

add_filter( 'woocommerce_valid_order_statuses_for_payment', 'convergx_review_not_payable' );
add_filter( 'woocommerce_valid_order_statuses_for_payment_complete', 'convergx_review_not_payable' );
function convergx_review_not_payable( $statuses ) {
	return array_values( array_diff( (array) $statuses, array( 'cx-review' ) ) );
}

/**
 * Flat admin fee charged per registrant (per pass in the cart).
 *
 * @return float
 */
function convergx_admin_fee_amount() {
	return (float) apply_filters( 'convergx_admin_fee_amount', 100.0 );
}

/**
 * Whether an applied coupon waives the admin fee.
 *
 * Complimentary registrants (e.g. speakers on cxspeaker26) get a 100%-off
 * percentage coupon and must pay nothing at all, fee included. The check is
 * on the coupon's TYPE AND AMOUNT, not its code, so any future 100%-off
 * code ConvergX creates waives the fee too. Partial discounts of any kind
 * still pay the full fee.
 *
 * @param WC_Coupon $coupon Applied coupon.
 * @return bool
 */
function convergx_coupon_waives_admin_fee( $coupon ) {
	$waives = 'percent' === $coupon->get_discount_type() && (float) $coupon->get_amount() >= 100;

	return (bool) apply_filters( 'convergx_coupon_waives_admin_fee', $waives, $coupon );
}

/*
 * THE ADMIN FEE. Removed once before at ConvergX's request and it turned out
 * they only wanted it removed for complimentary (100%-off) registrants,
 * which cost them USD 100 per registrant until it was restored. Do not
 * delete this hook; change convergx_coupon_waives_admin_fee() or
 * convergx_admin_fee_amount() instead.
 *
 * The fee is deliberately NOT taxable: the verified totals tax the pass only
 * (2,000 becomes 2,200 = pass + 100 fee + 5% GST on the pass).
 */
add_action( 'woocommerce_cart_calculate_fees', 'convergx_apply_admin_fee' );
function convergx_apply_admin_fee( $cart ) {
	if ( ! $cart instanceof WC_Cart || $cart->is_empty() ) {
		return;
	}

	foreach ( $cart->get_applied_coupons() as $code ) {
		if ( convergx_coupon_waives_admin_fee( new WC_Coupon( $code ) ) ) {
			return;
		}
	}

	$registrants = 0;

	foreach ( $cart->get_cart() as $item ) {
		$registrants += isset( $item['quantity'] ) ? (int) $item['quantity'] : 0;
	}

	if ( $registrants < 1 ) {
		return;
	}

	$cart->add_fee(
		__( 'Admin fee', 'convergx' ),
		convergx_admin_fee_amount() * $registrants,
		false
	);
}

/*
 * ============================================================================
 * NO EXPRESS CHECKOUT. Apple Pay / Google Pay / Link buttons are hidden on
 * the product page, the cart and the checkout page, and the Store API
 * checkout is hard-blocked below.
 * ============================================================================
 *
 * WHY. The participant fields (name, title, organisation, email per pass)
 * are rendered and validated by the CLASSIC checkout form only. Stripe's
 * Express Checkout Element (plugin 10.9) places orders through the Store API
 * (POST /wc/store/v1/checkout), which never runs the classic form or its
 * validation. Measured live 2026-09-08: a buyer tapping Apple Pay on the
 * block cart registered two passes with NO participant details at all.
 *
 * The button filters are the UX half (nobody sees a pay button that would
 * fail); convergx_block_store_api_checkout() is the guarantee (no order can
 * be created without participant data, whatever renders the button).
 *
 * The order-pay page keeps express buttons: that order already carries the
 * participant data collected at the original checkout, and the accept-link
 * review flow emails buyers straight to that page.
 */
add_filter( 'wc_stripe_show_payment_request_on_cart', '__return_false' );
add_filter( 'wc_stripe_hide_payment_request_on_product_page', '__return_true' );

add_filter( 'wc_stripe_show_payment_request_on_checkout', 'convergx_express_only_on_order_pay', 10, 2 );
function convergx_express_only_on_order_pay( $show, $post = null ) {
	unset( $post );

	return convergx_is_pay_for_order() ? $show : false;
}

add_action( 'woocommerce_store_api_checkout_update_order_from_request', 'convergx_block_store_api_checkout' );
function convergx_block_store_api_checkout() {
	throw new \Automattic\WooCommerce\StoreApi\Exceptions\RouteException(
		'convergx_participants_required',
		sprintf(
			/* translators: %s: checkout page URL */
			__( 'Each pass needs its own participant details, which express payment cannot collect. Please complete your registration on the checkout page: %s', 'convergx' ),
			esc_url( wc_get_checkout_url() )
		),
		400
	);
}

/*
 * Repeating participant fields need classic checkout. The block checkout
 * cannot grow one fieldset per cart quantity, which is the case Kim asked for
 * when one order covers several people.
 */
add_action( 'init', 'convergx_ensure_classic_checkout_page', 30 );
function convergx_ensure_classic_checkout_page() {
	if ( ! function_exists( 'wc_get_page_id' ) ) {
		return;
	}

	$id = wc_get_page_id( 'checkout' );

	if ( $id <= 0 ) {
		return;
	}

	$post = get_post( $id );

	if ( ! $post || ! has_block( 'woocommerce/checkout', $post ) ) {
		return;
	}

	wp_update_post(
		array(
			'ID'           => $id,
			'post_content' => "<!-- wp:shortcode -->\n[woocommerce_checkout]\n<!-- /wp:shortcode -->",
		)
	);
}

add_action( 'woocommerce_before_checkout_form', 'convergx_checkout_review_notice', 5 );
function convergx_checkout_review_notice() {
	if ( convergx_is_pay_for_order() ) {
		return;
	}

	// A zero-total registration (100%-off complimentary coupon) shows no
	// card form, so the "enter your card" copy would be wrong.
	if ( ! convergx_cart_needs_payment() ) {
		wc_print_notice(
			__( 'This registration is complimentary. No card is required and nothing will be charged.', 'convergx' ),
			'notice'
		);
		return;
	}

	wc_print_notice(
		sprintf(
			/* translators: %d: review window in days */
			__( 'Enter your card to submit this registration. The card is authorized now and charged only if ConvergX accepts the application, within %d days.', 'convergx' ),
			convergx_review_days()
		),
		'notice'
	);
}

/**
 * Whether the current cart requires payment at all.
 *
 * False for zero-total carts, e.g. a complimentary speaker using a 100%-off
 * coupon: WooCommerce renders no payment gateways for those, so there is no
 * card to demand.
 *
 * @return bool
 */
function convergx_cart_needs_payment() {
	return WC()->cart ? WC()->cart->needs_payment() : true;
}

add_action( 'woocommerce_before_checkout_billing_form', 'convergx_render_attendee_fields' );
function convergx_render_attendee_fields() {
	if ( convergx_is_pay_for_order() ) {
		return;
	}

	$slots = convergx_cart_attendee_slots();

	if ( ! $slots ) {
		return;
	}

	$defs   = convergx_attendee_fields();
	$count  = count( $slots );
	$posted = isset( $_POST['cx_attendee'] ) ? wp_unslash( $_POST['cx_attendee'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	?>
	<div class="cx-attendees">
		<h3><?php echo esc_html( _n( 'Who is attending', 'Who is attending', $count, 'convergx' ) ); ?></h3>
		<p class="cx-attendees-lede">
			<?php
			echo esc_html(
				$count > 1
					? __( 'Each pass needs its own participant. Billing below is the person paying; it can be someone else.', 'convergx' )
					: __( 'Participant details are what ConvergX reviews. Billing below is the person paying; it can be someone else.', 'convergx' )
			);
			?>
		</p>
		<?php foreach ( $slots as $i => $slot ) : ?>
			<fieldset class="cx-attendee">
				<legend>
					<?php
					echo esc_html(
						sprintf(
							/* translators: 1: attendee number, 2: pass name */
							__( 'Participant %1$d — %2$s', 'convergx' ),
							$i + 1,
							$slot['product_name']
						)
					);
					?>
				</legend>
				<?php
				foreach ( $defs as $key => $field ) {
					$value = isset( $posted[ $i ][ $key ] ) ? $posted[ $i ][ $key ] : '';
					woocommerce_form_field(
						"cx_attendee[{$i}][{$key}]",
						array(
							'type'         => $field['type'],
							'label'        => $field['label'],
							'required'     => ! empty( $field['required'] ),
							'class'        => $field['class'],
							'autocomplete' => isset( $field['autocomplete'] ) ? $field['autocomplete'] : '',
						),
						$value
					);
				}
				?>
			</fieldset>
		<?php endforeach; ?>
	</div>
	<?php
}

add_action( 'woocommerce_after_checkout_validation', 'convergx_require_card_at_checkout', 5, 2 );
function convergx_require_card_at_checkout( $data, $errors ) {
	if ( convergx_is_pay_for_order() ) {
		return;
	}

	// Zero-total registration (100%-off complimentary coupon): WooCommerce
	// shows no gateways and posts no payment_method, and there is nothing
	// to authorize. Demanding a card here made free checkouts impossible.
	if ( ! convergx_cart_needs_payment() ) {
		return;
	}

	$method = isset( $_POST['payment_method'] ) ? sanitize_key( wp_unslash( $_POST['payment_method'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

	if ( '' === $method || 'convergx_review' === $method ) {
		$errors->add(
			'cx_card',
			__( 'A credit card is required. Your card is authorized now and charged only if ConvergX accepts this registration.', 'convergx' )
		);
	}
}

add_action( 'woocommerce_after_checkout_validation', 'convergx_validate_attendees', 10, 2 );
function convergx_validate_attendees( $data, $errors ) {
	if ( convergx_is_pay_for_order() ) {
		return;
	}

	$slots = convergx_cart_attendee_slots();
	$rows  = convergx_posted_attendees();
	$defs  = convergx_attendee_fields();

	foreach ( $slots as $i => $slot ) {
		$row = isset( $rows[ $i ] ) ? $rows[ $i ] : array();
		$n   = $i + 1;

		foreach ( $defs as $key => $field ) {
			$val = isset( $row[ $key ] ) ? trim( (string) $row[ $key ] ) : '';

			if ( ! empty( $field['required'] ) && '' === $val ) {
				$errors->add(
					'cx_attendee_' . $i . '_' . $key,
					sprintf(
						/* translators: 1: field label, 2: attendee number */
						__( '%1$s is required for participant %2$d.', 'convergx' ),
						$field['label'],
						$n
					)
				);
			}

			if ( 'email' === $field['type'] && '' !== $val && ! is_email( $val ) ) {
				$errors->add(
					'cx_attendee_' . $i . '_email',
					sprintf(
						/* translators: %d: attendee number */
						__( 'Enter a valid email for participant %d.', 'convergx' ),
						$n
					)
				);
			}
		}
	}
}

add_action( 'woocommerce_checkout_create_order', 'convergx_store_attendees_on_order', 10, 2 );
function convergx_store_attendees_on_order( $order, $data ) {
	$order->update_meta_data( '_convergx_attendees', convergx_posted_attendees() );
}

add_filter( 'woocommerce_order_button_text', 'convergx_order_button_text' );
function convergx_order_button_text( $text ) {
	return convergx_is_pay_for_order()
		? __( 'Pay now', 'convergx' )
		: __( 'Submit registration', 'convergx' );
}

add_filter( 'woocommerce_thankyou_order_received_text', 'convergx_thankyou_text', 10, 2 );
function convergx_thankyou_text( $text, $order ) {
	if ( ! $order || ! convergx_order_needs_review( $order ) ) {
		return $text;
	}

	return sprintf(
		/* translators: %d: review window in days */
		__( 'Your registration has been submitted. Your card has been authorized, not charged. ConvergX will review it within %d days.', 'convergx' ),
		convergx_review_days()
	);
}

add_action( 'woocommerce_email_after_order_table', 'convergx_email_attendee_table', 10, 4 );
add_action( 'woocommerce_order_details_after_order_table', 'convergx_front_attendee_table' );
function convergx_front_attendee_table( $order ) {
	convergx_email_attendee_table( $order, false, false, false );
}

function convergx_email_attendee_table( $order, $sent_to_admin = false, $plain_text = false, $email = null ) {
	unset( $sent_to_admin, $email );

	$rows = convergx_order_attendees( $order );

	if ( ! $rows ) {
		return;
	}

	if ( $plain_text ) {
		echo "\n" . esc_html__( 'Participants', 'convergx' ) . "\n";
		foreach ( $rows as $i => $row ) {
			echo sprintf(
				"%d. %s, %s, %s, %s\n",
				$i + 1,
				isset( $row['name'] ) ? $row['name'] : '',
				isset( $row['title'] ) ? $row['title'] : '',
				isset( $row['org'] ) ? $row['org'] : '',
				isset( $row['email'] ) ? $row['email'] : ''
			);
		}
		return;
	}

	echo convergx_attendee_table_html( $rows ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built below.
}

/**
 * @param array $rows Attendee rows.
 * @return string
 */
function convergx_attendee_table_html( $rows ) {
	$defs = convergx_attendee_fields();
	ob_start();
	?>
	<section class="cx-attendee-admin">
		<h3><?php esc_html_e( 'Participants', 'convergx' ); ?></h3>
		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Pass', 'convergx' ); ?></th>
					<?php foreach ( $defs as $field ) : ?>
						<th><?php echo esc_html( $field['label'] ); ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $rows as $row ) : ?>
					<tr>
						<td><?php echo esc_html( isset( $row['product_name'] ) ? $row['product_name'] : '' ); ?></td>
						<?php foreach ( array_keys( $defs ) as $key ) : ?>
							<td><?php echo esc_html( isset( $row[ $key ] ) ? $row[ $key ] : '' ); ?></td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</section>
	<?php
	return ob_get_clean();
}

add_action( 'add_meta_boxes', 'convergx_attendee_metabox' );
function convergx_attendee_metabox() {
	foreach ( array( 'shop_order', 'woocommerce_page_wc-orders' ) as $screen ) {
		add_meta_box(
			'convergx-attendees',
			__( 'Participants', 'convergx' ),
			'convergx_render_attendee_metabox',
			$screen,
			'normal',
			'high'
		);
	}
}

function convergx_render_attendee_metabox( $post_or_order ) {
	$order = $post_or_order instanceof WC_Order ? $post_or_order : wc_get_order( $post_or_order );

	if ( ! $order ) {
		return;
	}

	$rows = convergx_order_attendees( $order );
	$due  = $order->get_meta( '_convergx_review_due' );

	if ( convergx_order_needs_review( $order ) ) {
		echo '<p><strong>';
		if ( $due ) {
			echo esc_html(
				sprintf(
					/* translators: %s: review-by date */
					__( 'Review by %s. The card is authorized, not charged. Accept to capture; reject to void.', 'convergx' ),
					$due
				)
			);
		} else {
			echo esc_html__( 'The card is authorized, not charged. Accept to capture; reject to void.', 'convergx' );
		}
		echo '</strong></p>';
		echo '<p>' . esc_html__( 'Stripe authorizations expire after 7 days. Accept before then or the hold is released and the card cannot be charged from this order.', 'convergx' ) . '</p>';
	}

	if ( ! $rows ) {
		echo '<p>' . esc_html__( 'No participant details were submitted with this order.', 'convergx' ) . '</p>';
		return;
	}

	echo convergx_attendee_table_html( $rows ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

add_filter( 'woocommerce_order_actions', 'convergx_review_order_actions', 10, 2 );
function convergx_review_order_actions( $actions, $order ) {
	if ( convergx_order_needs_review( $order ) ) {
		$actions['convergx_accept'] = convergx_order_has_uncaptured_card( $order )
			? __( 'Accept registration (charge the card)', 'convergx' )
			: __( 'Accept registration (send payment link)', 'convergx' );
		$actions['convergx_reject'] = __( 'Reject registration (no charge)', 'convergx' );
	}

	return $actions;
}

add_action( 'woocommerce_order_action_convergx_accept', 'convergx_accept_registration' );
function convergx_accept_registration( $order ) {
	if ( ! $order instanceof WC_Order || ! convergx_order_needs_review( $order ) ) {
		return;
	}

	if ( convergx_order_has_uncaptured_card( $order ) || $order->has_status( 'on-hold' ) ) {
		$order->update_status(
			'processing',
			__( 'Registration accepted. Capturing the authorized card.', 'convergx' )
		);
		$order = wc_get_order( $order->get_id() );
		convergx_mail_review_decision( $order, $order && $order->has_status( 'failed' ) ? 'accept-failed' : 'accept' );
		return;
	}

	$order->update_status(
		'pending',
		__( 'Registration accepted. Customer sent a payment link. No charge has been taken yet.', 'convergx' )
	);

	convergx_mail_review_decision( $order, 'accept-link' );
}

add_action( 'woocommerce_order_action_convergx_reject', 'convergx_reject_registration' );
function convergx_reject_registration( $order ) {
	if ( ! $order instanceof WC_Order || ! convergx_order_needs_review( $order ) ) {
		return;
	}

	$order->update_status(
		'cancelled',
		__( 'Registration rejected. The card authorization was voided and no charge was taken.', 'convergx' )
	);

	convergx_mail_review_decision( $order, 'reject' );
}

/**
 * @param WC_Order $order    Order.
 * @param string   $decision accept|reject.
 */
function convergx_mail_review_decision( $order, $decision ) {
	$to = $order->get_billing_email();

	if ( ! $to || ! WC()->mailer() ) {
		return;
	}

	$mailer = WC()->mailer();

	if ( 'accept' === $decision ) {
		$heading = __( 'Your ConvergX registration was accepted', 'convergx' );
		$subject = $heading;
		$body    = '<p>' . esc_html__( 'ConvergX has accepted this registration and charged the card you entered at checkout.', 'convergx' ) . '</p>';
	} elseif ( 'accept-failed' === $decision ) {
		$heading = __( 'Your ConvergX registration was accepted', 'convergx' );
		$subject = $heading;
		$body    = '<p>' . esc_html__( 'ConvergX has accepted this registration, but the authorized card could not be charged. Please complete payment using the link below.', 'convergx' ) . '</p>';
		$body   .= '<p><a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay for this registration', 'convergx' ) . '</a></p>';
	} elseif ( 'accept-link' === $decision ) {
		$heading = __( 'Your ConvergX registration was accepted', 'convergx' );
		$subject = $heading;
		$body    = '<p>' . esc_html__( 'ConvergX has accepted this registration. Complete payment using the link below. You have not been charged yet.', 'convergx' ) . '</p>';
		$body   .= '<p><a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay for this registration', 'convergx' ) . '</a></p>';
	} else {
		$heading = __( 'Your ConvergX registration was not accepted', 'convergx' );
		$subject = $heading;
		$body    = '<p>' . esc_html__( 'ConvergX has not accepted this registration. You have not been charged.', 'convergx' ) . '</p>';
	}

	$mailer->send( $to, $subject, $mailer->wrap_message( $heading, $body ) );
}

add_action( 'woocommerce_order_status_cx-review', 'convergx_mail_application_received', 20, 2 );
add_action( 'woocommerce_order_status_pending_to_on-hold', 'convergx_mail_application_received', 20, 2 );
add_filter( 'woocommerce_email_enabled_customer_on_hold_order', 'convergx_suppress_generic_on_hold_email', 10, 2 );
function convergx_suppress_generic_on_hold_email( $enabled, $order ) {
	return convergx_order_needs_review( $order ) ? false : $enabled;
}
function convergx_mail_application_received( $order_id, $order = null ) {
	$order = $order instanceof WC_Order ? $order : wc_get_order( $order_id );

	if ( ! $order || ! $order->get_billing_email() || ! WC()->mailer() ) {
		return;
	}

	if ( ! convergx_order_attendees( $order ) && ! convergx_order_needs_review( $order ) ) {
		return;
	}

	$mailer  = WC()->mailer();
	$heading = __( 'Registration received', 'convergx' );
	$body    = '<p>' . esc_html(
		sprintf(
			/* translators: %d: review window in days */
			__( 'We have your registration. Your card has been authorized, not charged. ConvergX will review it within %d days.', 'convergx' ),
			convergx_review_days()
		)
	) . '</p>';
	$body .= convergx_attendee_table_html( convergx_order_attendees( $order ) );

	$mailer->send( $order->get_billing_email(), $heading, $mailer->wrap_message( $heading, $body ) );
}

add_filter( 'woocommerce_email_actions', 'convergx_review_email_actions' );
function convergx_review_email_actions( $actions ) {
	$actions[] = 'woocommerce_order_status_pending_to_cx-review';
	$actions[] = 'woocommerce_order_status_failed_to_cx-review';

	return $actions;
}

add_action( 'woocommerce_email', 'convergx_hook_new_order_email' );
function convergx_hook_new_order_email( $emails ) {
	if ( empty( $emails->emails['WC_Email_New_Order'] ) ) {
		return;
	}

	$new_order = $emails->emails['WC_Email_New_Order'];
	add_action( 'woocommerce_order_status_pending_to_cx-review_notification', array( $new_order, 'trigger' ), 10, 2 );
	add_action( 'woocommerce_order_status_failed_to_cx-review_notification', array( $new_order, 'trigger' ), 10, 2 );
}

add_filter( 'manage_edit-shop_order_columns', 'convergx_order_attendee_column', 20 );
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'convergx_order_attendee_column', 20 );
function convergx_order_attendee_column( $columns ) {
	$columns['cx_attendees'] = __( 'Participants', 'convergx' );
	return $columns;
}

add_action( 'manage_shop_order_posts_custom_column', 'convergx_order_attendee_column_content', 10, 2 );
add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'convergx_render_hpos_attendee_column', 10, 2 );
function convergx_render_hpos_attendee_column( $column, $order ) {
	convergx_order_attendee_column_content( $column, $order );
}

function convergx_order_attendee_column_content( $column, $post_or_order ) {
	if ( 'cx_attendees' !== $column ) {
		return;
	}

	$order = $post_or_order instanceof WC_Order ? $post_or_order : wc_get_order( $post_or_order );
	$rows  = convergx_order_attendees( $order );

	if ( ! $rows ) {
		echo '&mdash;';
		return;
	}

	$names = array();
	foreach ( $rows as $row ) {
		if ( ! empty( $row['name'] ) ) {
			$names[] = $row['name'];
		}
	}

	echo esc_html( $names ? implode( ', ', $names ) : (string) count( $rows ) );
}

add_filter( 'woocommerce_available_payment_gateways', 'convergx_gateways_for_stage' );
function convergx_gateways_for_stage( $gateways ) {
	unset( $gateways['convergx_review'] );
	return $gateways;
}

add_filter( 'wc_stripe_generate_create_intent_request', 'convergx_stripe_authorize_only', 20 );
function convergx_stripe_authorize_only( $request ) {
	if ( is_array( $request ) ) {
		$request['capture_method'] = 'manual';
	}

	return $request;
}

add_action( 'woocommerce_checkout_order_processed', 'convergx_mark_review_window' );
function convergx_mark_review_window( $order_id ) {
	$order = wc_get_order( $order_id );

	if ( ! $order || $order->get_meta( '_convergx_review_due' ) ) {
		return;
	}

	$order->update_meta_data( '_convergx_review_due', wp_date( 'Y-m-d', time() + ( convergx_review_days() * DAY_IN_SECONDS ) ) );
	$order->save();
}

add_filter( 'woocommerce_payment_gateways', 'convergx_register_review_gateway' );
function convergx_register_review_gateway( $methods ) {
	$methods[] = 'ConvergX_Gateway_Review';
	return $methods;
}

add_action( 'init', 'convergx_disable_review_only_gateway', 1 );
function convergx_disable_review_only_gateway() {
	$settings = get_option( 'woocommerce_convergx_review_settings', array() );

	if ( ! is_array( $settings ) ) {
		$settings = array();
	}

	if ( ! isset( $settings['enabled'] ) || 'yes' === $settings['enabled'] ) {
		$settings['enabled'] = 'no';
		update_option( 'woocommerce_convergx_review_settings', $settings );
	}

	$stripe = get_option( 'woocommerce_stripe_settings', array() );

	if ( is_array( $stripe ) && ! empty( $stripe ) && ( ! isset( $stripe['capture'] ) || 'no' !== $stripe['capture'] ) ) {
		$stripe['capture'] = 'no';
		update_option( 'woocommerce_stripe_settings', $stripe );
	}
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	/**
	 * Application gateway: creates the order, takes no money.
	 */
	class ConvergX_Gateway_Review extends WC_Payment_Gateway {

		public function __construct() {
			$this->id                 = 'convergx_review';
			$this->method_title       = __( 'Registration review', 'convergx' );
			$this->method_description = __( 'Holds a registration for review. No payment is taken until the order is accepted.', 'convergx' );
			$this->has_fields         = false;
			$this->supports           = array( 'products' );

			$this->init_form_fields();
			$this->init_settings();

			$this->enabled     = $this->get_option( 'enabled', 'yes' );
			$this->title       = $this->get_option( 'title', __( 'Submit for review', 'convergx' ) );
			$this->description = $this->get_option( 'description' );

			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		}

		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'     => array(
					'title'   => __( 'Enable/Disable', 'convergx' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable registration review (no charge at checkout)', 'convergx' ),
					'default' => 'yes',
				),
				'title'       => array(
					'title'   => __( 'Title', 'convergx' ),
					'type'    => 'text',
					'default' => __( 'Submit for review', 'convergx' ),
				),
				'description' => array(
					'title'   => __( 'Description', 'convergx' ),
					'type'    => 'textarea',
					'default' => sprintf(
						/* translators: %d: review window in days */
						__( 'ConvergX reviews every registration within %d days. You will not be charged unless it is accepted.', 'convergx' ),
						convergx_review_days()
					),
				),
			);
		}

		public function process_payment( $order_id ) {
			$order = wc_get_order( $order_id );

			if ( ! $order ) {
				return array( 'result' => 'failure' );
			}

			$due = wp_date( 'Y-m-d', time() + ( convergx_review_days() * DAY_IN_SECONDS ) );
			$order->update_meta_data( '_convergx_review_due', $due );
			$order->update_status(
				'cx-review',
				__( 'Registration submitted for review. No payment has been taken.', 'convergx' )
			);

			if ( WC()->cart ) {
				WC()->cart->empty_cart();
			}

			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		}

		public function thankyou_page() {
			echo '<p>' . esc_html(
				sprintf(
					/* translators: %d: review window in days */
					__( 'Your card has been authorized, not charged. ConvergX will review this registration within %d days.', 'convergx' ),
					convergx_review_days()
				)
			) . '</p>';
		}
	}
}

<?php
/**
 * Seed the three registration products, mirroring the LIVE convergx.co
 * catalog (verified 2026-08-19 against the live product pages):
 *
 *   live #230    ConvergX® Global Congress Standard Registration   2,000 USD
 *   live #11306  Military Registration                               400 USD
 *   live #12764  Government Registration                           1,000 USD
 *
 * The live products carry no descriptions; titles and prices are the whole
 * catalog. Short descriptions here restate what the register page's cards
 * promise (including the checkout-total sentence), so the product page and
 * the card cannot drift apart in wording.
 *
 * Idempotent: finds each pass by slug and updates it. On the production
 * install these REPLACE the placeholders; the old live products keep their
 * IDs and can be retired once orders are confirmed flowing.
 *
 * Run: wp eval-file seeders/cx-products.php
 */

if ( ! function_exists( 'wc_get_product' ) ) {
	WP_CLI::error( 'WooCommerce is not active.' );
}

$passes = array(
	array(
		'slug'  => 'standard-registration',
		'name'  => 'ConvergX® Global Congress Standard Registration',
		'card'  => 'Standard',
		'price' => '2000',
		'short' => "<p>Registration for all three days of the 2026 Global Congress, Sep 22 to 24 in Calgary.</p>\n<p><strong>Total at checkout: 2,200 USD.</strong> The 2,000 above, plus a 5 percent admin fee and 5 percent tax.</p>",
	),
	array(
		'slug'  => 'military-registration',
		'name'  => 'Military Registration',
		'card'  => 'Military',
		'price' => '400',
		'short' => "<p>Registration for serving military personnel, for all three days of the 2026 Global Congress, Sep 22 to 24 in Calgary.</p>\n<p><strong>Total at checkout: 420 USD.</strong> The 400 above, plus a 5 percent admin fee. No tax applies.</p>",
	),
	array(
		'slug'  => 'government-registration',
		'name'  => 'Government Registration',
		'card'  => 'Government',
		'price' => '1000',
		'short' => "<p>Registration for government employees, for all three days of the 2026 Global Congress, Sep 22 to 24 in Calgary.</p>\n<p><strong>Total at checkout: 1,050 USD.</strong> The 1,000 above, plus a 5 percent admin fee. No tax applies.</p>",
	),
);

$done    = array();
$by_slug = array();

foreach ( $passes as $p ) {
	$existing = get_page_by_path( $p['slug'], OBJECT, 'product' );

	if ( $existing ) {
		$product = wc_get_product( $existing->ID );
	} else {
		$product = new WC_Product_Simple();
		$product->set_slug( $p['slug'] );
	}

	$product->set_name( $p['name'] );
	$product->set_regular_price( $p['price'] );
	$product->set_short_description( $p['short'] );
	$product->set_virtual( true );
	$product->set_sold_individually( false );
	$product->set_reviews_allowed( false );
	$product->set_status( 'publish' );
	$id = $product->save();

	$done[]               = "{$p['card']} (#{$id})";
	$by_slug[ $p['slug'] ] = $id;
}

/*
 * Repoint the register page's pass cards at these products, matched by SLUG
 * (the product titles now carry the live catalog's full names, so a name
 * match would break). Without this the cards keep whatever IDs they were
 * seeded with and a card can show one price while its button sells another
 * product.
 */
$card_slug = array(
	'Standard'   => 'standard-registration',
	'Military'   => 'military-registration',
	'Government' => 'government-registration',
);

$page = get_page_by_path( 'congress/register' );

if ( $page && function_exists( 'get_field' ) ) {
	$rows    = (array) get_field( 'passes', $page->ID );
	$changed = false;

	foreach ( $rows as &$row ) {
		$old  = get_post( (int) $row['product_id'] );
		$slug = $old && isset( $by_slug[ $old->post_name ] ) ? $old->post_name : null;

		if ( ! $slug ) {
			continue;
		}

		if ( (int) $row['product_id'] !== $by_slug[ $slug ] ) {
			$row['product_id'] = $by_slug[ $slug ];
			$changed           = true;
		}

		// The card keeps the short static-page name; the product title is
		// the live catalog's full name.
		$label = (string) array_search( $slug, $card_slug, true );
		if ( $label && ( $row['card_label'] ?? '' ) !== $label ) {
			$row['card_label'] = $label;
			$changed           = true;
		}
	}
	unset( $row );

	if ( $changed ) {
		update_field( 'passes', $rows, $page->ID );
		$done[] = 'register cards repointed';
	}
}

WP_CLI::success( 'products: ' . implode( ', ', $done ) );

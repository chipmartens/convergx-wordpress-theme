<?php
/**
 * Seed the three TEST registration products.
 *
 * Idempotent: finds each pass by slug and updates it, creating only what is
 * missing. These are placeholders standing in for ConvergX's real products
 * (live IDs: Standard 230, Military 11306, Government 12764 — see README);
 * the register page reads whatever IDs convergx_registration_product_ids()
 * returns, so a real install repoints the IDs rather than reseeding.
 *
 * The short description is what the single product page says under the
 * price: the same qualifier and checkout-total sentence the register page
 * carries, so the two surfaces cannot drift apart in wording.
 *
 * Run: wp eval-file seeders/cx-products.php
 */

if ( ! function_exists( 'wc_get_product' ) ) {
	WP_CLI::error( 'WooCommerce is not active.' );
}

$passes = array(
	array(
		'slug'  => 'standard-registration',
		'name'  => 'Standard',
		'price' => '2000',
		'short' => "<p>Registration for all three days of the 2026 Global Congress, Sep 22 to 24 in Calgary.</p>\n<p><strong>Total at checkout: 2,200 USD.</strong> The 2,000 above, plus a 5 percent admin fee and 5 percent tax.</p>",
	),
	array(
		'slug'  => 'military-registration',
		'name'  => 'Military',
		'price' => '400',
		'short' => "<p>Registration for serving military personnel, for all three days of the 2026 Global Congress, Sep 22 to 24 in Calgary.</p>\n<p><strong>Total at checkout: 420 USD.</strong> The 400 above, plus a 5 percent admin fee. Tax is not applicable to this registration.</p>",
	),
	array(
		'slug'  => 'government-registration',
		'name'  => 'Government',
		'price' => '1000',
		'short' => "<p>Registration for government employees, for all three days of the 2026 Global Congress, Sep 22 to 24 in Calgary.</p>\n<p><strong>Total at checkout: 1,100 USD.</strong> The 1,000 above, plus a 5 percent admin fee and 5 percent tax.</p>",
	),
);

$done = array();

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

	$done[]                = "{$p['name']} (#{$id})";
	$by_name[ $p['name'] ] = $id;
}

/*
 * Repoint the register page's pass cards at these products, matched by pass
 * name. Without this the cards keep whatever IDs they were seeded with and
 * the two surfaces drift: a card can show one price while its button sells
 * another product.
 */
$page = get_page_by_path( 'congress/register' );

if ( $page && function_exists( 'get_field' ) ) {
	$rows    = (array) get_field( 'passes', $page->ID );
	$changed = false;

	foreach ( $rows as &$row ) {
		$name = trim( (string) get_the_title( (int) $row['product_id'] ) );

		foreach ( $by_name as $pass_name => $pid ) {
			if ( 0 === strcasecmp( $name, $pass_name ) && (int) $row['product_id'] !== $pid ) {
				$row['product_id'] = $pid;
				$changed           = true;
			}
		}
	}
	unset( $row );

	if ( $changed ) {
		update_field( 'passes', $rows, $page->ID );
		$done[] = 'register cards repointed';
	}
}

WP_CLI::success( 'products: ' . implode( ', ', $done ) );

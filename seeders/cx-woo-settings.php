<?php
/**
 * WooCommerce tax setup for the registration store.
 *
 * The register-page cards promise: Standard 2,000 -> 2,200 (5% fee + 5% tax),
 * Military 400 -> 420 and Government 1,000 -> 1,050 (5% fee, no tax). The fee
 * lives in the theme (inc/woo.php); this seeder owns the tax half: one 5%
 * standard rate, applied only to Standard, with prices entered tax-exclusive.
 *
 * Run: wp eval-file seeders/cx-woo-settings.php
 */

update_option( 'woocommerce_calc_taxes', 'yes' );
update_option( 'woocommerce_prices_include_tax', 'no' );
update_option( 'woocommerce_tax_display_shop', 'excl' );
update_option( 'woocommerce_tax_display_cart', 'excl' );
update_option( 'woocommerce_tax_total_display', 'itemized' );

global $wpdb;

// One standard rate: 5%, every location. Replaces any prior seeded rate so
// re-running cannot stack a second 5%.
$wpdb->query( "DELETE FROM {$wpdb->prefix}woocommerce_tax_rates WHERE tax_rate_name = 'Tax'" );
$wpdb->insert(
	$wpdb->prefix . 'woocommerce_tax_rates',
	array(
		'tax_rate_country'  => '',
		'tax_rate_state'    => '',
		'tax_rate'          => '5.0000',
		'tax_rate_name'     => 'Tax',
		'tax_rate_priority' => 1,
		'tax_rate_compound' => 0,
		'tax_rate_shipping' => 0,
		'tax_rate_order'    => 0,
		'tax_rate_class'    => '',
	)
);

// Standard is taxable; Military and Government are not ("No tax applies").
$by_slug = array();
foreach ( get_posts( array( 'post_type' => 'product', 'numberposts' => -1 ) ) as $p ) {
	$by_slug[ $p->post_name ] = $p->ID;
}

$tax_status = array(
	'standard-registration'   => 'taxable',
	'military-registration'   => 'none',
	'government-registration' => 'none',
);

foreach ( $tax_status as $slug => $status ) {
	if ( empty( $by_slug[ $slug ] ) ) {
		WP_CLI::warning( "product {$slug} not found" );
		continue;
	}
	$product = wc_get_product( $by_slug[ $slug ] );
	$product->set_tax_status( $status );
	$product->save();
}

if ( function_exists( 'wc_delete_product_transients' ) ) {
	wc_delete_product_transients();
}
WP_CLI::runcommand( 'transient delete --all' );

WP_CLI::success( 'tax enabled: one 5% rate, Standard taxable, Military/Government exempt' );

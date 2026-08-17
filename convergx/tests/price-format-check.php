<?php
/**
 * Price formatting self-check.
 *
 * Run against a WordPress install with the theme active and WooCommerce on:
 *
 *     wp eval-file wp-content/themes/convergx/tests/price-format-check.php
 *
 * WHAT IT PROTECTS. inc/woo.php reformats every price on the site to
 * "2,000 USD" by trimming trailing zeros. The dangerous way to get that result
 * is to set the decimal count to zero, which ROUNDS: a 10.50 admin fee silently
 * becomes 11, on the money path, with nothing to notice it. This asserts the
 * displayed amount still equals the input for whole AND fractional values.
 *
 * If someone later "simplifies" the formatting by setting
 * woocommerce_price_num_decimals to 0, this fails loudly.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_price' ) ) {
	echo "SKIP: WooCommerce is not active.\n";
	return;
}

$cases = array( 2000, 400.00, 10.50, 1234.5, 0.99, 19.95 );
$fail  = 0;

foreach ( $cases as $value ) {
	$rendered = str_replace( "\xc2\xa0", ' ', html_entity_decode( wp_strip_all_tags( wc_price( $value ) ) ) );
	$numeric  = (float) str_replace( array( ',', ' USD', '$' ), '', $rendered );

	// Half a cent of tolerance: the assertion is "the amount is unchanged",
	// not "the string is byte-identical".
	$ok = abs( $numeric - round( (float) $value, 2 ) ) < 0.005;

	if ( ! $ok ) {
		$fail++;
	}

	printf( "  %-10s -> %-16s %s\n", $value, $rendered, $ok ? 'ok' : '*** AMOUNT CHANGED ***' );
}

// A whole amount must lose its .00; a fractional one must keep its cents.
$whole      = str_replace( "\xc2\xa0", ' ', html_entity_decode( wp_strip_all_tags( wc_price( 2000 ) ) ) );
$fractional = str_replace( "\xc2\xa0", ' ', html_entity_decode( wp_strip_all_tags( wc_price( 10.50 ) ) ) );

if ( false !== strpos( $whole, '.00' ) ) {
	echo "  FAIL: whole amounts should not render trailing .00\n";
	$fail++;
}

if ( false === strpos( $fractional, '.50' ) ) {
	echo "  FAIL: fractional amounts must keep their cents\n";
	$fail++;
}

echo $fail ? "\nFAIL: {$fail} problem(s)\n" : "\nPASS: amounts unchanged, whole trimmed, cents kept\n";

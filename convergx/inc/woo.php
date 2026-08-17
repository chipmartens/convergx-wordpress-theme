<?php
/**
 * WooCommerce support.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

/**
 * ============================================================================
 * READ THIS BEFORE ADDING A SINGLE WOOCOMMERCE TEMPLATE OVERRIDE.
 * ============================================================================
 *
 * THIS THEME DELIBERATELY OVERRIDES NO WOOCOMMERCE TEMPLATES. There is no
 * woocommerce/ directory in this theme and adding one is how you break the
 * money path. Three findings from the 2026-08-14 review, all measured live:
 *
 * 1. convergx.co's checkout page `post_content` contains ONLY Divi shortcodes.
 *    There is no [woocommerce_checkout] and no checkout block. Cart, checkout,
 *    my-account AND the product buy UI are rendered by DIVI THEME BUILDER. A
 *    theme override of cart/cart.php or checkout/form-checkout.php would never
 *    execute, because the shortcode that would call it does not exist on the
 *    page. Activating a non-Divi theme leaves the site with NO PAYMENT FORM.
 *    That is a prerequisite to fix on the site, not something a theme can fix.
 *
 * 2. Stripe express checkout (Apple Pay / Google Pay) mounts on the standard
 *    hook stack inside Woo's own templates. A hand-authored template that
 *    prints markup without those do_action calls drops the buttons silently.
 *    No error, no warning, just fewer conversions.
 *
 * 3. cart.php and form-checkout.php are the two highest-churn templates in
 *    WooCommerce. Copy them and they go stale on the next Woo update. The
 *    Congress is 2026-09-22 and Woo on convergx.co is 11.0.1.
 *
 * So: Woo renders its own markup, we skin it with CSS scoped under
 * [data-surface="light"], and the checkout keeps working. If a future change
 * genuinely needs different checkout markup, use a HOOK, never a template copy.
 */
function convergx_woo_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'convergx_woo_support' );

/**
 * Replace WooCommerce's default content wrapper with the theme's page rail.
 *
 * THE BUG THIS FIXES. With add_theme_support('woocommerce') and no wrapper of
 * our own, Woo emits its fallback markup:
 *
 *     <div id="primary" class="content-area"><main id="main" class="site-main">
 *
 * That lands INSIDE header.php's <main id="main">, so every store page shipped
 * TWO elements with id="main". Duplicate ids are invalid, and the skip link at
 * the top of every page targets #main, so keyboard users were being sent to
 * whichever one the browser picked first. It also meant store content sat
 * outside the site's page rail and ran flush to the viewport edge.
 *
 * Hooks, not a template override: these two actions are the documented seam for
 * exactly this, and nothing about Woo's inner markup is touched.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

function convergx_woo_wrapper_open() {
	echo '<div class="wrap woo-rail">';
}
add_action( 'woocommerce_before_main_content', 'convergx_woo_wrapper_open', 10 );

function convergx_woo_wrapper_close() {
	echo '</div>';
}
add_action( 'woocommerce_after_main_content', 'convergx_woo_wrapper_close', 10 );

/**
 * No sidebar on store pages.
 *
 * The design system has no sidebar anywhere, and Woo calls get_sidebar() on
 * archive pages. Removing the action is cleaner than shipping an empty
 * sidebar.php that exists only to be ignored.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Keep Woo's own stylesheets loaded.
 *
 * The reflex on a bespoke theme is to dequeue woocommerce-general and restyle
 * from scratch. Do not. Woo's CSS carries the layout for notices, form rows,
 * the gallery, quantity inputs and the checkout grid. This design system has no
 * equivalent for any of them and was never built to. Ours layers on top.
 */

/**
 * Fetch a registration product as plain display data.
 *
 * WHAT THIS RETURNS AND WHAT IT DELIBERATELY DOES NOT.
 *
 * It returns the BASE price, live from the product object, so the figure on the
 * register page can never drift from the shop. That is the whole point.
 *
 * It does NOT return a checkout total, and no amount of WooCommerce API will
 * give you one from a product. Verified 2026-08-14 via the Store API: the three
 * products are flat 2,000 / 400 / 1,000 USD with no fee, no tax and no price
 * suffix on the object. The 5 percent admin fee and the 5 percent tax that turn
 * 2,000 into 2,200 are applied at CART level by custom code whose source is not
 * identifiable from outside the site (no known fee plugin is installed). A
 * WC_Product cannot tell you a cart fee rate.
 *
 * So the total line stays an ACF field with an explicit as-of date, and if that
 * field is empty the line does not render at all. An absent total is honest. A
 * stale total on the money path is the worst failure this page can have: a
 * delegate who meets a higher number at checkout than the page showed was
 * misled by us, not by ConvergX.
 *
 * @param int $product_id Product ID.
 * @return array|null
 */
function convergx_get_registration_product( $product_id ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return null;
	}

	$product = wc_get_product( $product_id );

	if ( ! $product || ! $product->is_purchasable() ) {
		return null;
	}

	return array(
		'id'        => $product_id,
		'name'      => $product->get_name(),
		'price'     => $product->get_price(),
		'price_fmt' => convergx_format_price( $product->get_price() ),
		'currency'  => get_woocommerce_currency(),
		'permalink' => get_permalink( $product_id ),
		'in_stock'  => $product->is_in_stock(),
	);
}

/**
 * Format a price the way the design system writes it.
 *
 * The static site writes "2,000 USD" with the currency as a separate small
 * element. WooCommerce writes "$2,000.00". Both are correct; they are just
 * different house styles, and the page's type scale is built for the former.
 * Trailing .00 is dropped because every registration product is a whole number
 * and ".00" on a display-size figure reads as noise.
 *
 * @param string|float $price Raw price.
 * @return string
 */
function convergx_format_price( $price ) {
	$price = (float) $price;
	$dp    = ( fmod( $price, 1.0 ) === 0.0 ) ? 0 : 2;

	return number_format_i18n( $price, $dp );
}

/**
 * ============================================================================
 * PRICE DISPLAY: "2,000 USD", not "$2,000.00".
 * ============================================================================
 *
 * The register page writes prices as a figure followed by a small currency
 * word, because the display type scale is built for that shape. WooCommerce
 * writes "$2,000.00". Both are correct house styles; having both on one site is
 * what looks broken, and a buyer moving from the register page to the shop met
 * two different renderings of the same number.
 *
 * THREE CHANGES, AND WHAT EACH ONE IS CAREFUL ABOUT.
 *
 * 1. The symbol becomes the ISO code. USD only. "$" is ambiguous across USD,
 *    CAD, AUD and more, and this is a Canadian congress selling in US dollars
 *    to an international room, so the ambiguity is real rather than theoretical.
 *    Any other currency keeps its own symbol untouched.
 *
 * 2. The figure comes first, symbol after.
 *
 * 3. Trailing ".00" is trimmed. NOT by setting decimals to zero: that would
 *    ROUND, and rounding on a money path is how a 10.50 fee silently becomes
 *    11. Woo's own trim-zeros filter drops ".00" only when the amount is whole
 *    and leaves 10.50 alone.
 *
 * This runs through Woo's own formatting pipeline, so it reaches the shop, the
 * product page, cart and checkout totals, order emails and the Store API
 * together. There is no second place where a price is formatted by hand.
 */
function convergx_currency_symbol( $symbol, $currency ) {
	return 'USD' === $currency ? 'USD' : $symbol;
}
add_filter( 'woocommerce_currency_symbol', 'convergx_currency_symbol', 10, 2 );

/**
 * Figure first, currency word after, with a space.
 *
 * Only for USD, and only for the left-positioned formats. If someone sets the
 * store to a right-positioned currency they have chosen that deliberately and
 * this leaves it alone.
 */
function convergx_price_format( $format, $position ) {
	if ( 'USD' !== get_woocommerce_currency() ) {
		return $format;
	}

	return in_array( $position, array( 'left', 'left_space' ), true ) ? '%2$s&nbsp;%1$s' : $format;
}
add_filter( 'woocommerce_price_format', 'convergx_price_format', 10, 2 );

/**
 * Drop ".00" on whole amounts. Never rounds; see the note above.
 */
add_filter( 'woocommerce_price_trim_zeros', '__return_true' );

/**
 * Never let the dead duplicate products surface in a catalogue query.
 *
 * The catalogue still carries `government-registration` (8599) and
 * `active-military-registration` (8601) alongside the live
 * `government-registration-2` (12764) and `military-government-registration`
 * (11306). Two products with nearly identical names, one of which nobody is
 * meant to buy, is a support ticket waiting to happen.
 *
 * This hides them from the shop archive only. It does NOT unpublish them or
 * touch their URLs: old order emails and links in the wild still have to
 * resolve. Retiring them properly is ConvergX's call, not a theme's.
 */
function convergx_hide_superseded_products( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! function_exists( 'is_shop' ) || ! ( is_shop() || is_product_category() ) ) {
		return;
	}

	$superseded = (array) apply_filters( 'convergx_superseded_product_ids', array( 8599, 8601 ) );

	$query->set( 'post__not_in', array_merge( (array) $query->get( 'post__not_in' ), $superseded ) );
}
add_action( 'pre_get_posts', 'convergx_hide_superseded_products' );

/*
 * NO REVIEWS ON A REGISTRATION. Every product this store will ever hold is a
 * congress pass, and a pass with a star-rating box reads as a mistake, not a
 * feature. Both halves are needed: closing comments kills the form, removing
 * the tab kills the empty "Reviews (0)" heading it left behind.
 */
add_filter( 'comments_open', 'convergx_no_product_reviews', 10, 2 );
function convergx_no_product_reviews( $open, $post_id ) {
	return 'product' === get_post_type( $post_id ) ? false : $open;
}

add_filter( 'woocommerce_product_tabs', 'convergx_drop_reviews_tab', 98 );
function convergx_drop_reviews_tab( $tabs ) {
	unset( $tabs['reviews'] );
	return $tabs;
}

/*
 * No placeholder image either: paired with the :has() rules in
 * woocommerce.css section 15, an imageless pass renders a full-measure
 * summary instead of half a page reserved for a stock illustration.
 */
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'convergx_no_placeholder_thumb', 10, 2 );
function convergx_no_placeholder_thumb( $html, $attachment_id ) {
	return $attachment_id ? $html : '';
}

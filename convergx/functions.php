<?php
/**
 * ConvergX theme bootstrap.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

define( 'CONVERGX_VERSION', '0.1.0' );
define( 'CONVERGX_DIR', get_template_directory() );
define( 'CONVERGX_URI', get_template_directory_uri() );

/**
 * The three live registration products on convergx.co, by ID.
 *
 * Verified live 2026-08-14: 230 / 11306 / 12764 all HTTP 200. Note the
 * Government product sits at a `-2` slug and the Military product at a
 * `military-government-registration` slug. Those are ConvergX's slugs, not
 * errors, and there are older duplicates still in the catalogue
 * (`government-registration`, `active-military-registration`). Targeting by ID
 * rather than slug is what keeps the register page off the dead duplicates.
 *
 * Order is ConvergX's own shop order (Standard, Military, Government), not
 * price order. If their shop reorders, follow it. Do not re-sort by price.
 */
function convergx_registration_product_ids() {
	return (array) apply_filters(
		'convergx_registration_product_ids',
		array(
			'standard'   => 230,
			'military'   => 11306,
			'government' => 12764,
		)
	);
}

require_once CONVERGX_DIR . '/inc/enqueue.php';
require_once CONVERGX_DIR . '/inc/acf.php';
require_once CONVERGX_DIR . '/inc/cpt.php';
require_once CONVERGX_DIR . '/inc/nav.php';
require_once CONVERGX_DIR . '/inc/sections.php';
require_once CONVERGX_DIR . '/inc/congress.php';
require_once CONVERGX_DIR . '/inc/woo.php';
require_once CONVERGX_DIR . '/inc/forms.php';
require_once CONVERGX_DIR . '/inc/seo.php';

/**
 * Theme supports.
 */
function convergx_setup() {
	load_theme_textdomain( 'convergx', CONVERGX_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	// Menu locations are registered in inc/nav.php, which also carries the
	// descriptor / CTA / live fields and feeds the assigned menu to shell.js.
}
add_action( 'after_setup_theme', 'convergx_setup' );

/**
 * The surface a page renders on.
 *
 * Every colour in styles.css resolves against a [data-surface] scope. The
 * static site sets this on <body> per page: dark for the homepage, muted for
 * register, light for most inner pages. Outside a surface scope, custom
 * properties fall back to invalid and text renders near-invisible, so this must
 * always emit one of the three.
 *
 * @return string dark|light|muted
 */
function convergx_surface() {
	$surface = 'light';

	if ( is_front_page() ) {
		$surface = 'dark';
	} elseif ( is_page() ) {
		$acf = convergx_field( 'surface' );
		if ( $acf ) {
			$surface = $acf;
		}
	}

	// WooCommerce pages are light. Woo's own markup was never designed to sit
	// inside a dark surface and its notices, form rows and price colours are
	// not part of this design system. See inc/woo.php.
	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		$surface = 'light';
	}

	$allowed = array( 'dark', 'light', 'muted' );

	return in_array( $surface, $allowed, true ) ? $surface : 'light';
}

/**
 * The hero mode a page opts into.
 *
 * THIS IS A SECOND BODY ATTRIBUTE AND IT IS NOT OPTIONAL. Both photographic
 * hero components opt in with an attribute on <body> PLUS their band class on
 * the section, and the positioning half of each component lives behind the
 * attribute selector. Emit `.hero-veil-band` without `data-hero="veil"` and the
 * markup all renders, but `.hero-veil` never leaves the normal flow: the
 * photograph stacks ABOVE the copy as a plain block instead of sitting behind
 * it. Nothing errors and nothing looks broken enough to notice in markup, which
 * is exactly why it is called out here.
 *
 * The two modes are siblings, not variants of one component:
 *   veil  -> the eight sector pages. No filter, no tint, neutral dark veil,
 *            the photograph keeps its own colour.
 *   photo -> /congress/. A duotone: flattened to luminance with a blue pushed
 *            through on a colour blend.
 * Do not merge them.
 *
 * @return string '' | veil | photo
 */
function convergx_hero() {
	$hero = '';

	if ( is_page_template( 'templates/page-industry.php' ) ) {
		$hero = 'veil';
	}

	// An explicit field wins, so /congress/ and any future photographic hero
	// can opt in without needing its own template.
	$field = convergx_field( 'hero_mode' );
	if ( $field ) {
		$hero = $field;
	}

	return in_array( $hero, array( 'veil', 'photo' ), true ) ? $hero : '';
}

/**
 * Read an ACF field with a safe fallback when ACF is not active.
 *
 * The theme must not fatal on a site without ACF: it is not installed on
 * convergx.co today, and a theme that white-screens the moment a plugin is
 * deactivated is a theme nobody can safely roll back to.
 *
 * @param string   $selector Field name.
 * @param int|null $post_id  Post ID, defaults to current.
 * @param mixed    $default  Returned when ACF is absent or the field is empty.
 * @return mixed
 */
function convergx_field( $selector, $post_id = null, $default = '' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$value   = function_exists( 'get_field' ) ? get_field( $selector, $post_id ) : null;

	if ( null === $value || '' === $value || array() === $value ) {
		$value = $default;
	}

	/**
	 * Filter a resolved field value.
	 *
	 * This is the single seam every template reads content through, which makes
	 * it the one place to substitute content without ACF Pro present: a fallback
	 * on a site that has not licensed it, and the test harness that proves the
	 * templates render. Templates never call get_field() directly, so this
	 * filter is complete rather than partial.
	 *
	 * @param mixed  $value    Resolved value.
	 * @param string $selector Field name.
	 * @param int    $post_id  Post ID.
	 */
	return apply_filters( 'convergx_field', $value, $selector, $post_id );
}

/**
 * Strip the static site's staging robots meta if it ever reaches a template.
 *
 * Every one of the 24 static source pages carries
 * `<meta name="robots" content="noindex, nofollow, noarchive">`. That is a
 * staging posture and it must never ship to production: it would deindex the
 * live domain, and it would collide with All in One SEO, which is active on
 * convergx.co and emits its own robots meta. Two robots tags means Google
 * honours the most restrictive.
 *
 * No template in this theme writes a robots meta. This filter is the belt to
 * that braces: robots policy belongs to the SEO plugin, full stop.
 */
add_filter( 'wp_robots', 'convergx_robots_are_not_ours', 99 );
function convergx_robots_are_not_ours( $robots ) {
	// If an SEO plugin is active, let it own the tag entirely.
	if ( defined( 'AIOSEO_VERSION' ) || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return array();
	}

	return $robots;
}

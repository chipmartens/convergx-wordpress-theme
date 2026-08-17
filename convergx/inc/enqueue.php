<?php
/**
 * Asset loading.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

/**
 * Version string for a theme asset, derived from its modification time.
 *
 * WHY NOT JUST CONVERGX_VERSION. Because a human has to remember to bump it,
 * and when they forget, every browser that has already loaded the site keeps
 * serving the OLD file from cache. That is not a cosmetic problem: shell.js
 * builds the header, the footer and every nav link, so a stale copy means the
 * person testing the site is looking at bugs that were fixed hours ago and
 * reporting them again. That happened during this build, more than once.
 *
 * filemtime cannot be forgotten. Edit the file, the query string changes, the
 * browser refetches.
 *
 * @param string $rel Path relative to the theme root, e.g. 'assets/styles.css'.
 * @return string
 */
function convergx_asset_version( $rel ) {
	$path = CONVERGX_DIR . '/' . ltrim( $rel, '/' );
	$mtime = file_exists( $path ) ? filemtime( $path ) : 0;

	return $mtime ? CONVERGX_VERSION . '.' . $mtime : CONVERGX_VERSION;
}

/**
 * ORDER IS LOAD BEARING.
 *
 * tokens.css declares 130 custom properties. styles.css resolves every colour,
 * font, space and tracking value through them. Enqueue styles.css first and the
 * whole site renders with invalid values, which does not error, it just looks
 * subtly wrong. The dependency array below is what guarantees the order, not
 * the call order.
 */
function convergx_assets() {
	wp_enqueue_style(
		'convergx-tokens',
		CONVERGX_URI . '/assets/tokens.css',
		array(),
		convergx_asset_version( 'assets/tokens.css' )
	);

	wp_enqueue_style(
		'convergx-styles',
		CONVERGX_URI . '/assets/styles.css',
		array( 'convergx-tokens' ),
		convergx_asset_version( 'assets/styles.css' )
	);

	// WordPress requires the theme's own style.css to be registered under the
	// 'style' handle by convention. Ours is a header-only file, so it depends on
	// the real stylesheet rather than the other way round.
	wp_enqueue_style( 'convergx-theme', get_stylesheet_uri(), array( 'convergx-styles' ), CONVERGX_VERSION );

	/*
	 * The WooCommerce skin, loaded only on store pages.
	 *
	 * Depends on convergx-styles so it lands AFTER it, and Woo's own handles are
	 * left alone entirely: this file is additive over woocommerce-general and
	 * wc-blocks, never a replacement for them. Dequeuing those would strip the
	 * layout for notices, form rows, the gallery and the whole block checkout.
	 */
	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_style(
			'convergx-woo',
			CONVERGX_URI . '/assets/woocommerce.css',
			array( 'convergx-styles' ),
			convergx_asset_version( 'assets/woocommerce.css' )
		);
	}

	/*
	 * shell.js injects the header, the mega panels, the notice bar and the
	 * footer. It is ported UNCHANGED from the static site.
	 *
	 * WHY UNCHANGED, rather than rewritten as a PHP nav walker: every href it
	 * carries is already ROOT-RELATIVE (/congress/, /xpand/#our-process), so it
	 * resolves correctly at a domain root with no edit. More importantly the nav
	 * data carries per-row `note` descriptors, standfirsts and `cta` flags that
	 * a wp_nav_menu cannot express without adding ACF fields to every menu item.
	 * Porting it would lose the descriptors or triple the field surface.
	 *
	 * The notice bar's self-removal (NOTICE_UNTIL = 2026-09-25) also stays
	 * correct in JS: it is evaluated on every pageview against the visitor's
	 * clock. Rendered server-side under any page cache, a page cached on Sep 24
	 * would keep showing the bar after the Congress ends.
	 *
	 * The cost is that the nav needs JS. header.php carries ONE no-JS fallback
	 * for the whole site, which replaces the 24 hand-maintained <noscript>
	 * blocks in the static tree whose own comments admit they drift.
	 */
	wp_enqueue_script( 'convergx-shell', CONVERGX_URI . '/assets/js/shell.js', array(), convergx_asset_version( 'assets/js/shell.js' ), true );

	/*
	 * TELL shell.js WHERE THE SITE ROOT IS.
	 *
	 * It otherwise derives the root by stripping "_system/shell.js" off its own
	 * script src, which is correct for the static site and wrong here: the file
	 * lives under /wp-content/themes/, the pattern never matches, and every
	 * injected link ends up prefixed with the script's own URL.
	 *
	 * Path only, never the full home_url(). ROOT is prepended to root-absolute
	 * hrefs, so an absolute value would make every internal link cross-origin
	 * the moment the site is reached over a different host or scheme, which is
	 * exactly what happens behind a CDN or on a staging domain.
	 */
	$convergx_root = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	wp_add_inline_script(
		'convergx-shell',
		'window.CONVERGX_ROOT = ' . wp_json_encode( $convergx_root ? trailingslashit( $convergx_root ) : '/' ) . ';',
		'before'
	);

	/*
	 * Page-scoped, because these two are the expensive ones and most pages
	 * carry neither.
	 *
	 * GLOBE AND FLOW LOAD TOGETHER, and that is not laziness. The congress
	 * flow band has a globe instance INSIDE it (the homepage globe,
	 * reparented), so a page with the flow band always needs globe.js too.
	 * Gating the globe on is_front_page() alone shipped the congress page a
	 * static, unanimated globe with no error and nothing in the console: the
	 * SVG renders either way, it just never moves. Anywhere the flow band can
	 * appear, both scripts load.
	 */
	if ( is_front_page() || is_page_template( 'templates/page-congress.php' ) ) {
		wp_enqueue_script( 'convergx-globe', CONVERGX_URI . '/assets/js/globe.js', array(), convergx_asset_version( 'assets/js/globe.js' ), true );
		wp_enqueue_script( 'convergx-flow', CONVERGX_URI . '/assets/js/flow.js', array(), convergx_asset_version( 'assets/js/flow.js' ), true );
	}

	wp_enqueue_script( 'convergx-figures', CONVERGX_URI . '/assets/js/figures.js', array(), convergx_asset_version( 'assets/js/figures.js' ), true );
	wp_enqueue_script( 'convergx-video', CONVERGX_URI . '/assets/js/video.js', array(), convergx_asset_version( 'assets/js/video.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'convergx_assets', 20 );

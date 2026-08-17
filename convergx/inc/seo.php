<?php
/**
 * Structured data and resource hints.
 *
 * Ported from the SEO/AEO/GEO audit of the static site (mode40, 2026-08-14).
 *
 * WHAT THIS FILE DELIBERATELY DOES NOT DO, AND WHY.
 *
 * The audit produced 12 fixes. Most of them are not this theme's job, because
 * WordPress or the SEO plugin already does them, and emitting them here would
 * produce TWO of each tag rather than a better one:
 *
 *   title / description / robots  -> All in One SEO, active on convergx.co.
 *                                    See convergx_robots_are_not_ours() and the
 *                                    comment block in header.php.
 *   canonical                     -> AIOSEO, and WP core when no plugin is on.
 *   Open Graph / Twitter cards    -> AIOSEO.
 *   sitemap.xml                   -> AIOSEO, and WP core's own sitemap when no
 *                                    plugin is on.
 *   Organization / Breadcrumb     -> AIOSEO emits both in its free tier.
 *   img width + height            -> WP core adds them on the fly.
 *   loading="lazy"                -> WP core, since 5.5.
 *   favicon / touch icon          -> the Site Icon SETTING, not theme code.
 *                                    Appearance > Customize > Site Identity.
 *                                    Source art is in the audit deliverable.
 *   WebP                          -> an upload-time concern (core or a plugin),
 *                                    not something a theme should rewrite <img>
 *                                    tags to fake.
 *
 * That leaves exactly two things nothing else will do, and they are what this
 * file is for:
 *
 *   1. Event + Offer schema for the Congress. AIOSEO's free tier has no Event
 *      schema. This is the single highest-value item in the audit: it is what
 *      lets Google show the Congress in event results, and what lets an AI
 *      assistant state the dates and the price from a source instead of
 *      inferring them from prose.
 *   2. Font preload for the two weights used above the fold.
 *
 * THE NO-INVENTED-FACTS RULE. Every value below is read from a field or from a
 * real WooCommerce product. Nothing is hardcoded and nothing is guessed. If the
 * data is not there, this file emits NOTHING rather than a plausible value.
 * That is not defensiveness for its own sake: a round of fabricated content was
 * removed from this site in August 2026, and structured data is the easiest
 * place in a stack for a made-up fact to get laundered into something a machine
 * repeats confidently.
 *
 * THE VENUE IS NOT PUBLIC. The static congress pages carry an explicit
 * "DO NOT INFER THE VENUE" instruction: the venue name appears only inside HTML
 * comments describing copy that was deliberately cut. So `location` carries the
 * city and nothing else unless someone fills the venue field on purpose.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether an SEO plugin owns the document-level meta on this install.
 *
 * Mirrors convergx_robots_are_not_ours(). Where a plugin already emits a thing,
 * the theme stands down rather than competing with it.
 *
 * @return bool
 */
function convergx_seo_plugin_active() {
	return defined( 'AIOSEO_VERSION' ) || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' );
}

/**
 * The Congress page, as a post ID.
 *
 * Resolved by template rather than by a hardcoded ID or slug, so it survives the
 * page being rebuilt or renamed. Returns 0 when there is no such page, which is
 * the normal state on a fresh install and must not fatal.
 *
 * @return int
 */
function convergx_congress_page_id() {
	$id = 0;

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'templates/page-congress.php',
			'fields'         => 'ids',
		)
	);

	if ( $pages ) {
		$id = (int) $pages[0];
	}

	/**
	 * Filter the page treated as the Congress for Event schema.
	 *
	 * @param int $id Post ID, 0 when unresolved.
	 */
	return (int) apply_filters( 'convergx_congress_page_id', $id );
}

/**
 * The three registration passes as schema.org Offer nodes.
 *
 * Built from the live WooCommerce products via convergx_registration_product_ids(),
 * so the price in the markup is the price in the shop, permanently. The static
 * site hardcoded 2000 / 1000 / 400 into every page, which is correct exactly
 * until the day it is not.
 *
 * Prices here are the base product price, not the checkout total. Admin fees and
 * tax are surfaced to the reader on the register page and are calculated at
 * checkout; schema.org `price` is the advertised price, and inflating it to the
 * all-in figure would misreport the offer.
 *
 * @return array Offer nodes, empty when Woo is absent or no product resolves.
 */
function convergx_registration_offers() {
	$offers = array();

	if ( ! function_exists( 'wc_get_product' ) || ! function_exists( 'get_woocommerce_currency' ) ) {
		return $offers;
	}

	$currency = get_woocommerce_currency();

	foreach ( convergx_registration_product_ids() as $key => $product_id ) {
		$product = wc_get_product( $product_id );

		// A missing product is a real possibility: the IDs point at
		// convergx.co's catalogue, and this theme also runs on local installs
		// that have no such products. Skip, never substitute.
		if ( ! $product ) {
			continue;
		}

		$price = $product->get_price();

		if ( '' === $price || null === $price ) {
			continue;
		}

		$offers[] = array(
			'@type'         => 'Offer',
			'name'          => $product->get_name(),
			'price'         => (string) $price,
			'priceCurrency' => $currency,
			'url'           => get_permalink( $product_id ),
			'availability'  => $product->is_in_stock()
				? 'https://schema.org/InStock'
				: 'https://schema.org/SoldOut',
		);
	}

	/**
	 * Filter the Offer nodes attached to the Congress Event.
	 *
	 * @param array $offers Offer nodes.
	 */
	return (array) apply_filters( 'convergx_registration_offers', $offers );
}

/**
 * The Congress as a schema.org Event node.
 *
 * Every field is read from the Congress page. Nothing has a hardcoded default,
 * because a default here is a fabricated fact. If the start date is missing the
 * whole node is withheld: an Event with no date is not a useful Event, and
 * Google will reject it anyway.
 *
 * @return array Event node, empty when the data is not there.
 */
function convergx_congress_event() {
	$page_id = convergx_congress_page_id();

	if ( ! $page_id ) {
		return array();
	}

	$start = (string) convergx_field( 'event_start', $page_id );

	// The one hard requirement. No date, no Event.
	if ( ! $start ) {
		return array();
	}

	$permalink = get_permalink( $page_id );

	$event = array(
		'@type'     => 'Event',
		// A stable @id means the Event emitted on /congress/ and the one on the
		// register page are ONE entity to a crawler, not two competing copies
		// of the same conference.
		'@id'       => $permalink . '#event',
		'name'      => get_the_title( $page_id ),
		'startDate' => $start,
		'url'       => $permalink,
	);

	$end = (string) convergx_field( 'event_end', $page_id );
	if ( $end ) {
		$event['endDate'] = $end;
	}

	$description = get_the_excerpt( $page_id );
	if ( $description ) {
		$event['description'] = wp_strip_all_tags( $description );
	}

	// Location. City only, unless someone deliberately fills the venue field.
	// See the DO NOT INFER THE VENUE note at the top of this file.
	$city  = (string) convergx_field( 'event_city', $page_id );
	$venue = (string) convergx_field( 'event_venue', $page_id );

	if ( $city || $venue ) {
		$place = array( '@type' => 'Place' );

		if ( $venue ) {
			$place['name'] = $venue;
		}

		if ( $city ) {
			$place['address'] = array(
				'@type'           => 'PostalAddress',
				'addressLocality' => $city,
			);
		}

		$event['location'] = $place;
	}

	$offers = convergx_registration_offers();
	if ( $offers ) {
		$event['offers'] = $offers;
	}

	$event['organizer'] = array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);

	/**
	 * Filter the Congress Event node before it is printed.
	 *
	 * @param array $event   Event node.
	 * @param int   $page_id Congress page ID.
	 */
	return (array) apply_filters( 'convergx_congress_event', $event, $page_id );
}

/**
 * Print the Event JSON-LD on the pages that describe the Congress.
 *
 * Emitted on the Congress page itself and on the register page, sharing one
 * @id so they resolve to a single event.
 */
function convergx_print_event_schema() {
	$congress_id = convergx_congress_page_id();

	if ( ! $congress_id ) {
		return;
	}

	$is_congress = is_page( $congress_id );
	$is_register = is_page_template( 'templates/page-register.php' );

	if ( ! $is_congress && ! $is_register ) {
		return;
	}

	$event = convergx_congress_event();

	if ( ! $event ) {
		return;
	}

	$graph = array_merge( array( '@context' => 'https://schema.org' ), $event );

	echo "\n<script type=\"application/ld+json\">" .
		wp_json_encode( $graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) .
		"</script>\n";
}
add_action( 'wp_head', 'convergx_print_event_schema', 20 );

/**
 * Preload the font weights used above the fold.
 *
 * Only 400 and 700 are preloaded. Preloading all four Manrope weights makes the
 * first paint slower, not faster: the browser spends bandwidth on files the top
 * of the page never uses.
 *
 * WHY THIS LIVES HERE AND NOT IN inc/enqueue.php. Placement is a compromise, not
 * a preference. Resource hints belong with the rest of asset loading, and this
 * should move to enqueue.php once the WooCommerce work in flight there has
 * settled. It is functionally identical either way: both end up in wp_head.
 */
function convergx_preload_fonts() {
	$weights = (array) apply_filters( 'convergx_preload_font_weights', array( '400', '700' ) );

	foreach ( $weights as $weight ) {
		$rel  = 'assets/fonts/Manrope-' . $weight . '.woff2';
		$path = CONVERGX_DIR . '/' . $rel;

		// Never advertise a preload for a file that is not there: it costs a
		// console warning on every page load and buys nothing.
		if ( ! file_exists( $path ) ) {
			continue;
		}

		printf(
			"<link rel=\"preload\" as=\"font\" type=\"font/woff2\" href=\"%s\" crossorigin>\n",
			esc_url( CONVERGX_URI . '/' . $rel )
		);
	}
}
add_action( 'wp_head', 'convergx_preload_fonts', 1 );

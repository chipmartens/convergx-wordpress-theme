<?php
/**
 * WordPress menus, fed into shell.js.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

/**
 * ============================================================================
 * THE APPROACH: WORDPRESS OWNS THE DATA, shell.js KEEPS THE BEHAVIOUR.
 * ============================================================================
 *
 * shell.js is 1,511 lines and only a small part of it is the nav list. The rest
 * is the mega panels, the mobile drawer, the sticky states, the section subnav,
 * the notice bar's countdown and the footer build. Rewriting all of that as PHP
 * to gain editable menus would throw away working, tested interaction code and
 * replace it with a nav walker plus a pile of new bugs.
 *
 * So the menu is injected instead. WordPress emits `window.CONVERGX_NAV` before
 * shell.js runs, and shell.js prefers it over its built-in array. One line
 * changes in shell.js; everything it does with the data is untouched.
 *
 * If no menu is assigned, CONVERGX_NAV is not emitted at all and shell.js falls
 * back to its own definition, so the site never renders navless.
 *
 * WHAT A MENU ITEM CARRIES BEYOND A NORMAL WP MENU:
 *   - `note`  a one-line descriptor shown beside the link in the mega panel
 *   - `cta`   renders the row as a button rather than a link
 *   - `live`  unticked hides the row entirely, for a page that is not written
 *             yet. A nav row pointing at an unwritten page is a link to a 404
 *             on every page of the site.
 * These are ACF fields on the menu item, which is why the menu screen is the
 * one place all three can be edited together.
 */

/**
 * Register the menu locations.
 */
function convergx_register_menus() {
	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'convergx' ),
			'footer'  => __( 'Footer navigation', 'convergx' ),
		)
	);
}
add_action( 'after_setup_theme', 'convergx_register_menus' );

/**
 * Fields on a menu item.
 */
function convergx_register_menu_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_convergx_menu_item',
			'title'    => 'Navigation row',
			'location' => array( array( array( 'param' => 'nav_menu_item', 'operator' => '==', 'value' => 'all' ) ) ),
			'fields'   => array(
				array(
					'key'          => 'field_convergx_nav_note',
					'label'        => 'Descriptor',
					'name'         => 'note',
					'type'         => 'text',
					'instructions' => 'One short line shown beside this link inside the drop-down panel. Says what the page is, not what to do on it. Top-level items ignore it.',
				),
				array(
					'key'           => 'field_convergx_nav_cta',
					'label'         => 'Render as a button',
					'name'          => 'cta',
					'type'          => 'true_false',
					'ui'            => 1,
					'instructions'  => 'For the one or two rows in a panel that are actions rather than destinations, e.g. Register.',
				),
				array(
					'key'           => 'field_convergx_nav_live',
					'label'         => 'Live',
					'name'          => 'live',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 1,
					'instructions'  => 'Untick to hide this row everywhere without deleting it. Use it for a page that is planned but not written: a nav row pointing at an unwritten page is a link to a 404 on every page of the site.',
				),
			),
		)
	);
}
add_action( 'acf/init', 'convergx_register_menu_fields' );

/**
 * Build a menu location into the shape shell.js expects.
 *
 * @param string $location Menu location slug.
 * @return array
 */
function convergx_menu_tree( $location ) {
	$locations = get_nav_menu_locations();

	if ( empty( $locations[ $location ] ) ) {
		return array();
	}

	$items = wp_get_nav_menu_items( $locations[ $location ] );

	if ( ! $items ) {
		return array();
	}

	$by_parent = array();

	foreach ( $items as $item ) {
		// `live` defaults to true so a menu built before ACF is installed, or
		// by someone who never opened the panel, still renders.
		$live = convergx_field( 'live', $item->ID, true );

		if ( ! $live ) {
			continue;
		}

		$by_parent[ (int) $item->menu_item_parent ][] = array(
			'id'    => (int) $item->ID,
			'label' => $item->title,
			'href'  => convergx_relative_href( $item->url ),
			'note'  => (string) convergx_field( 'note', $item->ID ),
			'cta'   => (bool) convergx_field( 'cta', $item->ID ),
			'live'  => true,
		);
	}

	if ( empty( $by_parent[0] ) ) {
		return array();
	}

	$tree = array();

	foreach ( $by_parent[0] as $top ) {
		$children = isset( $by_parent[ $top['id'] ] ) ? $by_parent[ $top['id'] ] : array();

		$top['children'] = array_map(
			function ( $child ) {
				unset( $child['id'] );
				return $child;
			},
			$children
		);

		unset( $top['id'] );
		$tree[] = $top;
	}

	return $tree;
}

/**
 * Make an internal URL root-relative.
 *
 * shell.js compares hrefs against location.pathname to mark the current
 * section, and an absolute URL with a scheme and host never matches. External
 * links are left exactly as entered.
 *
 * @param string $url Menu item URL.
 * @return string
 */
function convergx_relative_href( $url ) {
	$home = home_url();

	if ( 0 === strpos( $url, $home ) ) {
		$path = substr( $url, strlen( $home ) );
		return '' === $path ? '/' : $path;
	}

	return $url;
}

/**
 * Emit the nav data before shell.js runs.
 */
function convergx_localize_nav() {
	$primary = convergx_menu_tree( 'primary' );
	$footer  = convergx_menu_tree( 'footer' );

	// NO MENU, NO INJECTION. shell.js then uses its own definition and the site
	// renders exactly as it did before this file existed. This is what stops a
	// half-configured install shipping an empty header.
	if ( ! $primary && ! $footer ) {
		return;
	}

	$data = array();

	if ( $primary ) {
		$data['primary'] = $primary;
	}

	if ( $footer ) {
		$data['footer'] = $footer;
	}

	wp_add_inline_script(
		'convergx-shell',
		'window.CONVERGX_NAV = ' . wp_json_encode( $data ) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'convergx_localize_nav', 21 );

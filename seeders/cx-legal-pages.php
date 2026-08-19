<?php
/**
 * Seed the four legal pages, ported from the client's live site so checkout
 * has real pages to link to on day one: Privacy Policy, Cookie Policy,
 * Terms & Conditions, Trademark.
 *
 * (The live /copyright/ page is only a link hub and /security/ is an old
 * speaker bio; both are handled by the redirect table instead.)
 *
 * Content lives in data/cx-legal.json, extracted from the live pages by the
 * command in that file's git history. They render through the default page
 * template: the title as the hero, the body as the editorial run.
 *
 * Run: wp eval-file seeders/cx-legal-pages.php
 */

$pages = json_decode( file_get_contents( __DIR__ . '/data/cx-legal.json' ), true );

if ( ! is_array( $pages ) ) {
	WP_CLI::error( 'cx-legal.json missing or unreadable' );
}

$done = array();

foreach ( $pages as $slug => $p ) {
	$existing = get_page_by_path( $slug );

	$args = array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_name'    => $slug,
		'post_title'   => $p['title'],
		'post_content' => $p['body'],
	);

	if ( $existing ) {
		$args['ID'] = $existing->ID;
		wp_update_post( $args );
		$id = $existing->ID;
	} else {
		$id = wp_insert_post( $args );
	}

	$done[] = "{$p['title']} (#{$id})";
}

WP_CLI::success( 'legal pages: ' . implode( ', ', $done ) );

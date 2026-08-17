<?php
/**
 * Seed the verbatim section rows produced by extract.py.
 *
 * REPLACES the whole sections field on every listed page. The old
 * field-by-field rows (editorial/form/team/flow) are superseded by the exact
 * rows plus part markers, and leaving any of them behind would render the
 * same content twice. Pages not present in the JSON (e.g. /congress/register/,
 * which is wholly template-owned) are left untouched.
 *
 * Run: wp eval-file seeders/cx-exact-rows.php
 */

$data = json_decode( file_get_contents( __DIR__ . '/data/cx-exact-rows.json' ), true );

if ( ! is_array( $data ) ) {
	WP_CLI::error( 'cx-exact-rows.json missing or unreadable' );
}

$pages = 0;
$rows  = 0;

foreach ( $data as $path => $secs ) {
	$path = trim( (string) $path, '/' );

	if ( '' === $path ) {
		$pid = (int) get_option( 'page_on_front' );
	} else {
		$p   = get_page_by_path( $path );
		$pid = $p ? $p->ID : 0;
	}

	if ( ! $pid ) {
		WP_CLI::warning( "no page for /{$path}/" );
		continue;
	}

	$out = array();

	foreach ( $secs as $s ) {
		if ( 'part' === $s['layout'] ) {
			$out[] = array(
				'acf_fc_layout' => 'part',
				'surface'       => (string) ( $s['surface'] ?? '' ),
				'band_group'    => (int) ( $s['group'] ?? 0 ),
				'part'          => (string) $s['part'],
			);
		} else {
			$out[] = array(
				'acf_fc_layout' => 'exact',
				'surface'       => (string) ( $s['surface'] ?? '' ),
				'band_group'    => (int) ( $s['group'] ?? 0 ),
				'html'          => (string) $s['html'],
			);
		}
	}

	update_field( 'sections', $out, $pid );
	$pages++;
	$rows += count( $out );
}

WP_CLI::success( "seeded {$pages} pages, {$rows} exact/part rows" );

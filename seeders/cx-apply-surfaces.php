<?php
/**
 * Apply the per-section colour bands measured from the static pages.
 *
 * Matched on the section heading, because that is the only stable key between
 * the two: the static pages have no ids on these sections and the WP rows were
 * seeded from the same headings.
 */
$map = json_decode( file_get_contents(__DIR__ . '/data/cx-surfaces.json'), true );
$applied = 0; $pages = 0; $misses = array();

foreach ( $map as $path => $sections ) {
    $path = trim( (string) $path, '/' );
    if ( '' === $path ) {
        $pid = (int) get_option( 'page_on_front' );
    } else {
        $p = get_page_by_path( $path );
        $pid = $p ? $p->ID : 0;
    }
    if ( ! $pid ) { $misses[] = "/{$path} (no page)"; continue; }

    $rows = get_field( 'sections', $pid );
    if ( ! $rows ) { continue; }

    $changed = false;
    foreach ( $rows as $i => $row ) {
        $h = trim( (string) ( $row['heading'] ?? '' ) );
        if ( $h && isset( $sections[ $h ] ) ) {
            $rows[ $i ]['surface'] = $sections[ $h ];
            $changed = true; $applied++;
        }
    }
    if ( $changed ) { update_field( 'sections', $rows, $pid ); $pages++; }
}

WP_CLI::success( "surfaces applied to {$applied} sections across {$pages} pages" );
if ( $misses ) { WP_CLI::warning( implode( ', ', $misses ) ); }

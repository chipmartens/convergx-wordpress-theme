<?php
/**
 * The congress page's flexible-content run was seeded from the static page's
 * section headings, before the agenda, hotels, sponsors and speakers partials
 * existed. Those four are now rendered by real partials with real data, so the
 * seeded editorial stubs are duplicate empty headings sitting above them.
 *
 * Drop any editorial row whose heading is now owned by a partial.
 */
$page = get_page_by_path('congress');
$pid  = $page->ID;

// Headings now owned by a template part.
$owned = array( 'the speakers', 'the agenda', 'accommodations', 'sponsors, supporters and partners', 'where to stay' );

$rows = get_field( 'sections', $pid );
$keep = array();
$dropped = array();

foreach ( (array) $rows as $r ) {
    $h = strtolower( trim( (string) ( $r['heading'] ?? '' ) ) );
    if ( in_array( $h, $owned, true ) ) { $dropped[] = $r['heading']; continue; }
    $keep[] = $r;
}

update_field( 'sections', $keep, $pid );
WP_CLI::success( sprintf( 'kept %d editorial sections, dropped %d duplicates: %s',
    count($keep), count($dropped), implode(', ', $dropped) ?: 'none' ) );

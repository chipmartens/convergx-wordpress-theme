<?php
/**
 * Re-seed every page's editorial run with the FULL section bodies.
 *
 * The first pass extracted only a heading, a standfirst and up to four
 * paragraphs per section, capped at eight sections. That silently dropped
 * lists and most body copy: /about/ shipped 2 text blocks against the live
 * site's 73. This replaces the run wholesale.
 *
 * Colour bands are preserved: the surface already applied to a matching
 * heading is carried onto the new row rather than reset.
 */
$pages = json_decode( file_get_contents(__DIR__ . '/data/cx-full.json'), true );
$surf  = json_decode( file_get_contents(__DIR__ . '/data/cx-surfaces.json'), true );

// Headings owned by a dedicated template part, never editorial rows.
$owned = array('the speakers','the agenda','accommodations','where to stay',
               'sponsors, supporters and partners','who is in the room','who attends');

$done=0; $rows_total=0;
foreach ( $pages as $path => $secs ) {
    $path = trim((string)$path,'/');
    if ( '' === $path ) { $pid = (int) get_option('page_on_front'); }
    else { $p = get_page_by_path($path); $pid = $p ? $p->ID : 0; }
    if ( ! $pid ) { continue; }

    // Preserve any surface already set, keyed by heading.
    $existing = array();
    foreach ( (array) get_field('sections',$pid) as $r ) {
        if ( ! empty($r['heading']) ) { $existing[ $r['heading'] ] = $r['surface'] ?? ''; }
    }

    $rows=array();
    foreach ( $secs as $s ) {
        $h = trim($s['heading']);
        if ( ! $h ) { continue; }
        if ( in_array( strtolower($h), $owned, true ) ) { continue; }

        $surface = $existing[$h] ?? '';
        if ( ! $surface && isset($surf[$path][$h]) ) { $surface = $surf[$path][$h]; }

        $rows[] = array(
            'acf_fc_layout' => 'editorial',
            'heading' => $h,
            'eyebrow' => $s['eyebrow'],
            'lede'    => $s['lede'],
            'body'    => $s['body'],
            'dense'   => (int) $s['dense'],
            'surface' => $surface,
        );
    }
    if ( $rows ) { update_field('sections',$rows,$pid); $done++; $rows_total += count($rows); }
}
WP_CLI::success("re-seeded {$done} pages, {$rows_total} sections");

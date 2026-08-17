<?php
/** Set each page's headline scale from the static site's own h1 class. */
$map = json_decode( file_get_contents('/tmp/cx-h1.json'), true );
$n=0;
foreach ( $map as $path => $cls ) {
    $path = trim((string)$path,'/');
    if ( '' === $path ) { $pid = (int) get_option('page_on_front'); }
    else { $p = get_page_by_path($path); $pid = $p ? $p->ID : 0; }
    if ( ! $pid ) { continue; }
    update_field( 'hero_class', $cls, $pid );
    $n++;
}
WP_CLI::success("headline scale set on {$n} pages");

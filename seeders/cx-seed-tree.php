<?php
/** Seed the full 23-page tree, preserving the static site's URL hierarchy. */
$pages = json_decode( file_get_contents( __DIR__ . '/data/cx-pages.json' ), true );

// Sort shallow-first so a parent always exists before its child.
usort( $pages, function ( $a, $b ) {
    return substr_count( $a['path'], '/' ) <=> substr_count( $b['path'], '/' );
} );

$ids = array();   // path => post ID
$made = 0; $updated = 0;

foreach ( $pages as $p ) {
    $path = trim( $p['path'], '/' );

    // The homepage is handled separately below.
    if ( '' === $path ) { $ids[''] = 0; continue; }

    $parts  = explode( '/', $path );
    $slug   = end( $parts );
    $parent = count( $parts ) > 1 ? ( $ids[ implode( '/', array_slice( $parts, 0, -1 ) ) ] ?? 0 ) : 0;

    $existing = get_page_by_path( $path );

    // Template: industries get the industry template, congress gets its own,
    // register gets the storefront, everything else is the flexible page.
    if ( 'congress' === $path )                      { $tpl = 'templates/page-congress.php'; }
    elseif ( 'congress/register' === $path )         { $tpl = 'templates/page-register.php'; }
    elseif ( 0 === strpos( $path, 'industries/' ) )  { $tpl = 'templates/page-industry.php'; }
    else                                             { $tpl = 'templates/page-flex.php'; }

    $args = array(
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_title'   => $p['title'] ?: ucfirst( $slug ),
        'post_name'    => $slug,
        'post_parent'  => $parent,
        'post_content' => '',
    );
    if ( $existing ) { $args['ID'] = $existing->ID; $id = wp_update_post( $args ); $updated++; }
    else             { $id = wp_insert_post( $args ); $made++; }

    if ( is_wp_error( $id ) || ! $id ) { continue; }
    $ids[ $path ] = $id;

    update_post_meta( $id, '_wp_page_template', $tpl );
    update_field( 'surface', $p['surface'] ?: 'light', $id );
    if ( $p['hero'] ) { update_field( 'hero_mode', $p['hero'], $id ); }
    if ( $p['h1'] )   { update_field( 'hero_title', $p['h1'], $id ); }
    if ( $p['ledes'] ){ update_field( 'hero_lede', implode( "\n\n", $p['ledes'] ), $id ); }

    if ( 0 === strpos( $path, 'industries/' ) ) {
        update_field( 'eyebrow', $p['title'], $id );
    }

    // Sections. The register and congress templates own their own body, so they
    // do not take the generic editorial run.
    if ( ! in_array( $path, array( 'congress/register' ), true ) && $p['sections'] ) {
        $rows = array();
        $i = 0;
        foreach ( $p['sections'] as $s ) {
            if ( ! $s['heading'] ) { continue; }
            $rows[] = array(
                'acf_fc_layout' => 'editorial',
                'heading' => $s['heading'],
                'eyebrow' => $s['eyebrow'],
                'lede'    => $s['lede'],
                'body'    => $s['body'],
                'dense'   => ( $i % 2 ) ? 1 : 0,
            );
            $i++;
        }
        if ( $rows ) { update_field( 'sections', $rows, $id ); }
    }
}

// ---------- HOMEPAGE ----------
$home = null;
foreach ( $pages as $p ) { if ( '' === trim( $p['path'], '/' ) ) { $home = $p; break; } }
if ( $home ) {
    $front = get_page_by_path( 'home' );
    $args = array( 'post_type'=>'page','post_status'=>'publish','post_title'=>'Home','post_name'=>'home','post_content'=>'' );
    if ( $front ) { $args['ID'] = $front->ID; $fid = wp_update_post( $args ); }
    else { $fid = wp_insert_post( $args ); }
    update_post_meta( $fid, '_wp_page_template', 'templates/page-flex.php' );
    update_field( 'surface', $home['surface'] ?: 'dark', $fid );
    update_field( 'hero_title', $home['h1'], $fid );
    update_field( 'hero_lede', implode( "\n\n", $home['ledes'] ), $fid );
    $rows=array(); $i=0;
    foreach ( $home['sections'] as $s ) {
        if ( ! $s['heading'] ) continue;
        $rows[] = array('acf_fc_layout'=>'editorial','heading'=>$s['heading'],'eyebrow'=>$s['eyebrow'],
                        'lede'=>$s['lede'],'body'=>$s['body'],'dense'=>($i%2)?1:0);
        $i++;
    }
    if ($rows) update_field( 'sections', $rows, $fid );
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $fid );
}

// Retire the flat /register/ and /manufacturing/ stubs from the earlier pass:
// the real pages now live at /congress/register/ and /industries/manufacturing/.
foreach ( array( 'register', 'manufacturing' ) as $stale ) {
    $s = get_page_by_path( $stale );
    if ( $s && ! $s->post_parent ) { wp_trash_post( $s->ID ); }
}

WP_CLI::success( "pages created={$made} updated={$updated}, front page set" );

<?php
require_once ABSPATH.'wp-admin/includes/media.php';
require_once ABSPATH.'wp-admin/includes/file.php';
require_once ABSPATH.'wp-admin/includes/image.php';

$d = json_decode( file_get_contents(__DIR__ . '/data/cx-congress.json'), true );
$page = get_page_by_path('congress');
if ( ! $page ) { WP_CLI::error('no congress page'); }
$pid = $page->ID;

/** Import a staged file once, keyed by filename so re-runs reuse it. */
function cx_media( $dir, $file, $title = '' ) {
    if ( ! $file ) { return 0; }
    $found = get_posts( array('post_type'=>'attachment','post_status'=>'inherit','numberposts'=>1,
        'meta_key'=>'_cx_seed_file','meta_value'=>$file) );
    if ( $found ) { return $found[0]->ID; }
    $src = ABSPATH . $dir . $file;
    if ( ! file_exists($src) ) { return 0; }
    $tmp = wp_tempnam($file);
    if ( ! copy($src,$tmp) ) { return 0; }
    $id = media_handle_sideload( array('name'=>$file,'tmp_name'=>$tmp), 0, $title );
    if ( is_wp_error($id) ) { @unlink($tmp); return 0; }
    update_post_meta($id,'_cx_seed_file',$file);
    return $id;
}

// ---- AGENDA ----
$days = array();
foreach ( $d['days'] as $day ) {
    $rows = array();
    foreach ( $day['rows'] as $r ) {
        $rows[] = array( 'time'=>$r['time'], 'session'=>$r['session'], 'detail'=>$r['detail'] );
    }
    $days[] = array( 'title'=>$day['title'], 'caption'=>$day['caption'], 'rows'=>$rows );
}
update_field( 'agenda_days', $days, $pid );

// ---- HOTELS ----
$hotels = array();
foreach ( $d['hotels'] as $h ) {
    $hotels[] = array(
        'name'=>$h['name'],
        'photo'=>cx_media('wp-content/uploads/cx-hotels/', $h['photo'], $h['name']),
        'alt'=>$h['alt'], 'address'=>$h['address'], 'phone'=>$h['phone'],
        'rate'=>$h['rate'], 'steps'=>$h['steps'], 'url'=>$h['url'],
        'cta'=>$h['cta'] ?: 'Book',
    );
}
update_field( 'hotels', $hotels, $pid );

// ---- SPONSORS ----
// Every mark below is already on ConvergX's own published sponsor wall, which
// is what makes cleared=1 correct here. A NEW mark added later starts unticked.
$sponsors = array();
foreach ( $d['sponsors'] as $s ) {
    $id = cx_media('wp-content/uploads/cx-sponsors/', $s['file'], $s['label']);
    if ( ! $id ) { continue; }
    $sponsors[] = array( 'mark'=>$id, 'label'=>$s['label'], 'cap_frac'=>$s['cap'], 'cleared'=>1 );
}
update_field( 'sponsors', $sponsors, $pid );
update_field( 'sponsors_lede', 'ConvergX thanks the sponsors, supporters and partners of the 2026 Global Congress.', $pid );

WP_CLI::success( sprintf('congress seeded: %d days, %d hotels, %d marks', count($days), count($hotels), count($sponsors)) );

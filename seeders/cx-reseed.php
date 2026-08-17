<?php
/**
 * Re-seed editorial runs, now including HEADLESS sections.
 *
 * The previous extractor required an <h2> and silently skipped every section
 * without one. That is where the congress overview prose, the industry pull
 * quotes and the NATO policy paragraph live, so they never reached WordPress.
 *
 * Sections owned by a template part (hero, impact, flow band, agenda, hotels,
 * sponsors, speakers, team) are excluded at extraction time, so re-running this
 * cannot duplicate them.
 */
$pages = json_decode( file_get_contents( __DIR__ . '/data/cx-sections.json' ), true );
$surf  = json_decode( file_get_contents(__DIR__.'/data/cx-surfaces.json'), true );
if ( ! $surf ) { $surf = json_decode( file_get_contents('/tmp/cx-surfaces.json'), true ) ?: array(); }

$pagesDone=0; $rowsDone=0;
foreach ( $pages as $path => $secs ) {
    $path = trim((string)$path,'/');
    if ( '' === $path ) { $pid = (int) get_option('page_on_front'); }
    else { $p = get_page_by_path($path); $pid = $p ? $p->ID : 0; }
    if ( ! $pid ) { continue; }

    // Keep the non-editorial blocks already placed on the page (team, form),
    // and keep any surface already chosen per heading.
    $keep=array(); $surfaces=array();
    foreach ( (array) get_field('sections',$pid) as $r ) {
        $lay = $r['acf_fc_layout'] ?? '';
        if ( 'editorial' !== $lay ) { $keep[]=$r; }
        elseif ( ! empty($r['heading']) ) { $surfaces[$r['heading']] = $r['surface'] ?? ''; }
    }

    $rows=array();
    foreach ( $secs as $s ) {
        $h = trim($s['heading']);
        if ( ! $h && ! trim($s['body']) ) { continue; }
        $sf = $surfaces[$h] ?? ( $surf[$path][$h] ?? '' );
        $rows[] = array(
            'acf_fc_layout' => 'editorial',
            'heading' => $h, 'eyebrow' => $s['eyebrow'], 'lede' => $s['lede'],
            'body' => $s['body'], 'say' => ($s['say'] ?? ''), 'level' => ($s['level'] ?? 2), 'links' => ($s['links'] ?? array()), 'twopath' => ($s['twopath'] ?? array()), 'claims' => ($s['claims'] ?? array()), 'whatis' => ($s['whatis'] ?? array()), 'store' => ($s['store'] ?? array()), 'dense' => (int)$s['dense'], 'surface' => $sf,
        );
    }
    // Editorial run first, then whatever else was on the page (team, forms).
    update_field('sections', array_merge($rows,$keep), $pid);
    $pagesDone++; $rowsDone+=count($rows);
}
WP_CLI::success("re-seeded {$pagesDone} pages, {$rowsDone} editorial sections");

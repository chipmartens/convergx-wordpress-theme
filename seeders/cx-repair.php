<?php
require_once ABSPATH.'wp-admin/includes/media.php';
require_once ABSPATH.'wp-admin/includes/file.php';
require_once ABSPATH.'wp-admin/includes/image.php';

// ---- 1. RESTORE THE REGISTER PASSES ----
// The tree seeder recreated /congress/register/ and the passes repeater from
// the earlier flat /register/ page did not come with it. This is the money
// page, so it is repaired first.
$reg = get_page_by_path( 'congress/register' );
if ( $reg ) {
    $inc = array(
        array('text'=>'All three days of programming'),
        array('text'=>'Curated meetings and focused discussions'),
        array('text'=>'Receptions and industry showcases'),
    );
    update_field( 'surface', 'muted', $reg->ID );
    update_field( 'passes_lede', 'Passes are for September 22 to 24, 2026, in Calgary. Each pass is admission for all three days.', $reg->ID );
    update_field( 'passes', array(
        array('product_id'=>10,'qualifier'=>'Price does not include Admin Fees + Tax',
              'total_line'=>'Total at checkout: 2,200 USD.','total_verified'=>'2026-07-31',
              'includes'=>$inc,'cta_label'=>'Register, Standard'),
        array('product_id'=>11,'qualifier'=>'Price does not include Admin Fees. Tax is not applicable to this registration.',
              'total_line'=>'Total at checkout: 420 USD.','total_verified'=>'2026-07-31',
              'includes'=>$inc,'cta_label'=>'Register, Military'),
        array('product_id'=>12,'qualifier'=>'Price does not include Admin Fees. Tax is not applicable to this registration.',
              'total_line'=>'Total at checkout: 1,050 USD.','total_verified'=>'2026-07-31',
              'includes'=>$inc,'cta_label'=>'Register, Government'),
    ), $reg->ID );
    if ( ! $reg->post_content ) {
        wp_update_post( array('ID'=>$reg->ID,'post_content'=>
        "<p>ConvergX brings together senior industry leaders to seek shared solutions to common problems.</p>\n<p>Attendees include executives, owners, operators, procurement leaders, government officials, military leadership, investors, innovators, and technology providers who influence strategy, purchasing, partnerships, and deployment.</p>\n<p>All attendees are individually vetted, VP level or higher decision makers.</p>") );
    }
    WP_CLI::log( "  register passes restored (post {$reg->ID})" );
}

// ---- 2. RESTORE .say AND HERO IMAGES ON THE EIGHT INDUSTRY PAGES ----
$say = json_decode( file_get_contents(__DIR__ . '/data/cx-say.json'), true );
$dir = ABSPATH.'wp-content/uploads/cx-industries/';
$n=0; $imgs=0;
foreach ( $say as $slug => $d ) {
    $p = get_page_by_path( 'industries/'.$slug );
    if ( ! $p ) { continue; }
    // Unconditional, so clearing a value in cx-say.json also clears it on a
    // seeded install. The band paragraphs live in the sections; say_body only
    // carries copy the sections do not.
    update_field( 'say', $d['say'], $p->ID );
    update_field( 'say_body', $d['say_body'], $p->ID );

    if ( $d['hero'] && ! convergx_field('hero_image',$p->ID) ) {
        $src = $dir.$d['hero'];
        if ( file_exists($src) ) {
            $att = get_posts(array('post_type'=>'attachment','post_status'=>'inherit','numberposts'=>1,
                'meta_key'=>'_cx_seed_file','meta_value'=>$d['hero']));
            if ( $att ) { $aid = $att[0]->ID; }
            else {
                $tmp = wp_tempnam($d['hero']);
                copy($src,$tmp);
                $aid = media_handle_sideload(array('name'=>$d['hero'],'tmp_name'=>$tmp),0);
                if ( ! is_wp_error($aid) ) { update_post_meta($aid,'_cx_seed_file',$d['hero']); }
            }
            if ( ! is_wp_error($aid) ) { update_field('hero_image',$aid,$p->ID); $imgs++; }
        }
    }
    $n++;
}
WP_CLI::success( "industry pages repaired: {$n}, hero images attached: {$imgs}" );

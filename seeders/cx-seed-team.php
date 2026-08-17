<?php
require_once ABSPATH.'wp-admin/includes/media.php';
require_once ABSPATH.'wp-admin/includes/file.php';
require_once ABSPATH.'wp-admin/includes/image.php';

$rows = json_decode( file_get_contents(__DIR__ . '/data/cx-team.json'), true );
$dir  = ABSPATH.'wp-content/uploads/cx-team/';
$n=0; $order=0;

foreach ( $rows as $r ) {
    $order += 10;
    $existing = get_posts(array('post_type'=>'cx_person','post_status'=>'any','numberposts'=>1,
        'meta_key'=>'_cx_seed_slug','meta_value'=>$r['slug']));
    $args = array('post_type'=>'cx_person','post_status'=>'publish','post_title'=>$r['name'],
        'post_content'=>wpautop($r['bio']),'menu_order'=>$order);
    if ($existing) { $args['ID']=$existing[0]->ID; $id=wp_update_post($args); }
    else { $id=wp_insert_post($args); }
    if (is_wp_error($id) || !$id) { continue; }

    update_post_meta($id,'_cx_seed_slug',$r['slug']);
    update_field('role',$r['role'],$id);
    update_field('org',$r['org'],$id);
    update_field('feature',$r['feature']?1:0,$id);

    if ($r['photo'] && !has_post_thumbnail($id)) {
        $file=$r['photo'];
        $att=get_posts(array('post_type'=>'attachment','post_status'=>'inherit','numberposts'=>1,
            'meta_key'=>'_cx_seed_file','meta_value'=>$file));
        if ($att) { $aid=$att[0]->ID; }
        else {
            $src=$dir.$file;
            if (!file_exists($src)) { continue; }
            $tmp=wp_tempnam($file); copy($src,$tmp);
            $aid=media_handle_sideload(array('name'=>$file,'tmp_name'=>$tmp),0,$r['name']);
            if (is_wp_error($aid)) { @unlink($tmp); continue; }
            update_post_meta($aid,'_cx_seed_file',$file);
            update_post_meta($aid,'_wp_attachment_image_alt',$r['name']);
        }
        set_post_thumbnail($id,$aid);
    }
    $n++;
}

// Place the team block on /about/, once.
$about = get_page_by_path('about');
if ($about) {
    $secs = (array) get_field('sections',$about->ID);
    $has=false;
    foreach ($secs as $s) { if (($s['acf_fc_layout'] ?? '')==='team') { $has=true; break; } }
    if (!$has) {
        // Before the contact form, after the prose.
        $out=array(); $placed=false;
        foreach ($secs as $s) {
            if (!$placed && ($s['acf_fc_layout'] ?? '')==='form') { $out[]=array('acf_fc_layout'=>'team'); $placed=true; }
            $out[]=$s;
        }
        if (!$placed) { $out[]=array('acf_fc_layout'=>'team'); }
        update_field('sections',$out,$about->ID);
    }
}
WP_CLI::success("team seeded: {$n}");

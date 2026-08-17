<?php
require_once ABSPATH.'wp-admin/includes/media.php';
require_once ABSPATH.'wp-admin/includes/file.php';
require_once ABSPATH.'wp-admin/includes/image.php';

$p = get_page_by_path('congress');
if ( ! $p ) { WP_CLI::error('no congress page'); }

$file = 'congress-2018-ballroom-set.jpg';
$att = get_posts(array('post_type'=>'attachment','post_status'=>'inherit','numberposts'=>1,
    'meta_key'=>'_cx_seed_file','meta_value'=>$file));
if ( $att ) { $aid = $att[0]->ID; }
else {
    $src = ABSPATH.'wp-content/uploads/cx-past/'.$file;
    if ( ! file_exists($src) ) { WP_CLI::error("missing {$file}"); }
    $tmp = wp_tempnam($file); copy($src,$tmp);
    $aid = media_handle_sideload(array('name'=>$file,'tmp_name'=>$tmp), 0, 'ConvergX Congress');
    if ( is_wp_error($aid) ) { WP_CLI::error($aid->get_error_message()); }
    update_post_meta($aid,'_cx_seed_file',$file);
    // The hero photograph is atmosphere behind type; the heading carries the
    // meaning, so it renders with an empty alt.
    update_post_meta($aid,'_wp_attachment_image_alt','');
}
update_field('hero_image',$aid,$p->ID);
WP_CLI::success("congress hero image set (attachment {$aid})");

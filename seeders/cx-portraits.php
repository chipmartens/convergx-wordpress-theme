<?php
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$rows = json_decode( file_get_contents( __DIR__ . '/data/cx-speakers.json' ), true );
$dir  = ABSPATH . 'wp-content/uploads/cx-speakers/';
$ok = 0; $fail = array();

foreach ( $rows as $r ) {
    if ( empty( $r['photo'] ) ) { continue; }

    $posts = get_posts( array(
        'post_type' => 'cx_speaker', 'post_status' => 'any', 'numberposts' => 1,
        'meta_key' => '_cx_seed_slug', 'meta_value' => $r['slug'],
    ) );
    if ( ! $posts ) { $fail[] = $r['slug'] . ' (no post)'; continue; }
    $id = $posts[0]->ID;

    if ( has_post_thumbnail( $id ) ) { $ok++; continue; }

    // Reuse an already-imported attachment rather than duplicating media.
    $att = get_posts( array(
        'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1,
        'meta_key' => '_cx_seed_file', 'meta_value' => $r['photo'],
    ) );

    if ( $att ) {
        $aid = $att[0]->ID;
    } else {
        $src = $dir . $r['photo'];
        if ( ! file_exists( $src ) ) { $fail[] = $r['photo'] . ' (missing file)'; continue; }

        // Copy into a real temp file first. media_handle_sideload MOVES the
        // file it is given, so handing it the source would empty the staging dir.
        $tmp = wp_tempnam( $r['photo'] );
        if ( ! copy( $src, $tmp ) ) { $fail[] = $r['photo'] . ' (copy failed)'; continue; }

        $aid = media_handle_sideload(
            array( 'name' => $r['photo'], 'tmp_name' => $tmp ),
            0,
            sprintf( 'Photograph of %s.', $r['name'] )
        );

        if ( is_wp_error( $aid ) ) {
            @unlink( $tmp );
            $fail[] = $r['photo'] . ' (' . $aid->get_error_message() . ')';
            continue;
        }
        update_post_meta( $aid, '_cx_seed_file', $r['photo'] );
        // Alt text matches what the theme generates for the card and overlay.
        update_post_meta( $aid, '_wp_attachment_image_alt', sprintf( 'Photograph of %s.', $r['name'] ) );
    }

    set_post_thumbnail( $id, $aid );
    $ok++;
}

WP_CLI::success( "portraits attached: {$ok}" );
if ( $fail ) { WP_CLI::warning( "failed: " . implode( ', ', $fail ) ); }

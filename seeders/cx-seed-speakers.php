<?php
/** Seeder: speakers from the static congress page into the cx_speaker post type. */
$rows = json_decode( file_get_contents( __DIR__ . '/data/cx-speakers.json' ), true );
$dir  = __DIR__ . '/wp-content/uploads/cx-speakers/';
$n = 0; $order = 0;

foreach ( $rows as $r ) {
    $order += 10;

    // Idempotent: key on the slug the theme derives, so re-running updates
    // rather than duplicating.
    $existing = get_posts( array(
        'post_type'   => 'cx_speaker',
        'post_status' => 'any',
        'numberposts' => 1,
        'meta_key'    => '_cx_seed_slug',
        'meta_value'  => $r['slug'],
    ) );

    $args = array(
        'post_type'    => 'cx_speaker',
        'post_status'  => 'publish',
        'post_title'   => $r['name'],
        'post_content' => wpautop( $r['bio'] ),
        'menu_order'   => $order,
    );

    if ( $existing ) {
        $args['ID'] = $existing[0]->ID;
        $id = wp_update_post( $args );
    } else {
        $id = wp_insert_post( $args );
    }

    if ( is_wp_error( $id ) || ! $id ) { continue; }

    update_post_meta( $id, '_cx_seed_slug', $r['slug'] );
    update_field( 'role', $r['role'], $id );
    // A row without a bio_role key shows the card role on the overlay too.
    // An explicit "" means the overlay renders no role line (Tracy LaTourette:
    // ConvergX publishes no title for her, so the static overlay omits it).
    update_field( 'bio_role', array_key_exists( 'bio_role', $r ) ? $r['bio_role'] : $r['role'], $id );
    update_field( 'billing', $r['billing'], $id );
    update_field( 'feature', $r['feature'] ? 1 : 0, $id );

    // Portrait, keyed by filename so a re-run does not duplicate the media.
    if ( $r['photo'] && ! has_post_thumbnail( $id ) ) {
        $file = $dir . $r['photo'];
        if ( file_exists( $file ) ) {
            $att = get_posts( array(
                'post_type'   => 'attachment',
                'numberposts' => 1,
                'meta_key'    => '_cx_seed_file',
                'meta_value'  => $r['photo'],
            ) );
            if ( $att ) {
                $aid = $att[0]->ID;
            } else {
                $aid = media_handle_sideload( array(
                    'name'     => $r['photo'],
                    'tmp_name' => wp_tempnam( $r['photo'] ),
                ), 0, $r['name'] );
                if ( is_wp_error( $aid ) ) {
                    copy( $file, $tmp = wp_tempnam( $r['photo'] ) );
                    $aid = media_handle_sideload( array( 'name' => $r['photo'], 'tmp_name' => $tmp ), 0 );
                }
                if ( ! is_wp_error( $aid ) ) { update_post_meta( $aid, '_cx_seed_file', $r['photo'] ); }
            }
            if ( ! is_wp_error( $aid ) ) { set_post_thumbnail( $id, $aid ); }
        }
    }
    $n++;
}
WP_CLI::success( "seeded/updated {$n} speakers" );

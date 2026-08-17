<?php
/** Seed real ACF content so nothing depends on a test filter. */

function cx_page( $slug, $title, $template, $content = '' ) {
    $p = get_page_by_path( $slug );
    $args = array(
        'post_type' => 'page', 'post_status' => 'publish',
        'post_title' => $title, 'post_name' => $slug, 'post_content' => $content,
    );
    if ( $p ) { $args['ID'] = $p->ID; $id = wp_update_post( $args ); }
    else { $id = wp_insert_post( $args ); }
    update_post_meta( $id, '_wp_page_template', $template );
    return $id;
}

// ---------- REGISTER ----------
$reg = cx_page( 'register', 'Register', 'templates/page-register.php',
  "<p>ConvergX brings together senior industry leaders to seek shared solutions to common problems.</p>\n<p>Attendees include executives, owners, operators, procurement leaders, government officials, military leadership, investors, innovators, and technology providers who influence strategy, purchasing, partnerships, and deployment.</p>\n<p>All attendees are individually vetted, VP level or higher decision makers.</p>" );

update_field( 'surface', 'muted', $reg );
update_field( 'hero_title', 'Save your spot at the 2026 Global Congress', $reg );
update_field( 'hero_lede', 'September 22 to 24, 2026, in Calgary.', $reg );
update_field( 'passes_lede', 'Passes are for September 22 to 24, 2026, in Calgary. Each pass is admission for all three days.', $reg );

$inc = array(
    array( 'text' => 'All three days of programming' ),
    array( 'text' => 'Curated meetings and focused discussions' ),
    array( 'text' => 'Receptions and industry showcases' ),
);
// Product IDs are the LOCAL test products. On convergx.co these are 230 / 11306 / 12764.
update_field( 'passes', array(
    array( 'product_id' => 10, 'qualifier' => 'Price does not include Admin Fees + Tax',
        'total_line' => 'Total at checkout: 2,200 USD.', 'total_verified' => '2026-07-31',
        'includes' => $inc, 'cta_label' => 'Register, Standard' ),
    array( 'product_id' => 11, 'qualifier' => 'Price does not include Admin Fees. Tax is not applicable to this registration.',
        'total_line' => 'Total at checkout: 420 USD.', 'total_verified' => '2026-07-31',
        'includes' => $inc, 'cta_label' => 'Register, Military' ),
    array( 'product_id' => 12, 'qualifier' => 'Price does not include Admin Fees. Tax is not applicable to this registration.',
        'total_line' => 'Total at checkout: 1,050 USD.', 'total_verified' => '2026-07-31',
        'includes' => $inc, 'cta_label' => 'Register, Government' ),
), $reg );

// ---------- MANUFACTURING ----------
$man = cx_page( 'manufacturing', 'Manufacturing', 'templates/page-industry.php' );
update_field( 'surface', 'light', $man );
update_field( 'eyebrow', 'Manufacturing', $man );
update_field( 'hero_title', 'Part approval belongs to the tooling rather than the shop', $man );
update_field( 'hero_lede', "Part approval covers this part, on this tooling, in this plant.\n\nThe tooling is your property, and it is bolted into somebody else's press.", $man );
update_field( 'say', 'Better is weighed against the change.', $man );
update_field( 'say_body', 'An alternative has to beat the incumbent by more than a tool move, a bank of finished parts built to cover the weeks nothing is made, and a full approval run again from the start.', $man );
$hero = get_posts( array( 'post_type'=>'attachment','numberposts'=>1,'s'=>'manufacturing-hero' ) );
if ( $hero ) { update_field( 'hero_image', $hero[0]->ID, $man ); }
update_field( 'sections', array(
    array( 'acf_fc_layout' => 'editorial', 'heading' => 'Every part carries its own approval',
        'eyebrow' => 'The constraint', 'lede' => 'Approval is granted against a part, its tooling and its plant together.',
        'body' => '<p>Move any one of the three and the approval does not travel with it. That is the rule the rest of this page follows from.</p>', 'dense' => 1 ),
    array( 'acf_fc_layout' => 'editorial', 'heading' => 'ConvergX is not in the approval chain',
        'eyebrow' => 'Where we sit', 'lede' => 'ConvergX convenes, vets and brokers. It does not approve parts.',
        'body' => '<p>What it can do is put the people who hold the approval in a room with the people who need it, having already established that both are who they say they are.</p>', 'dense' => 0 ),
), $man );

// ---------- CONGRESS ----------
$con = cx_page( 'congress', 'The Congress', 'templates/page-congress.php' );
update_field( 'surface', 'dark', $con );
update_field( 'hero_mode', 'photo', $con );
update_field( 'hero_title', 'Ten years of putting the right people in one room', $con );
update_field( 'hero_lede', "September 22 to 24, 2026, in Calgary.", $con );
update_field( 'sections', array(
    array( 'acf_fc_layout' => 'editorial', 'heading' => 'Who is in the room',
        'eyebrow' => 'The room', 'lede' => 'ConvergX brings together senior industry leaders to seek shared solutions to common problems.',
        'body' => '<p>All attendees are individually vetted, VP level or higher decision makers.</p>', 'dense' => 0 ),
), $con );

WP_CLI::success( "seeded pages: register={$reg} manufacturing={$man} congress={$con}" );

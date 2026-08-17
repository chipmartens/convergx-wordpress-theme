<?php
/**
 * Restore ConvergX's exact wording where the port paraphrased or truncated.
 */

// ---- REGISTER: full total sentences, verbatim ----
$reg = get_page_by_path('congress/register');
if ( $reg ) {
    $rows = get_field('passes',$reg->ID);
    // The <strong> wraps only the opening sentence, exactly as the static page
    // marks it up; the template whitelists that one tag.
    $totals = array(
        10 => '<strong>Total at checkout: 2,200 USD.</strong> The 2,000 above, plus a 5 percent admin fee and 5 percent tax.',
        11 => '<strong>Total at checkout: 420 USD.</strong> The 400 above, plus a 5 percent admin fee. No tax applies.',
        12 => '<strong>Total at checkout: 1,050 USD.</strong> The 1,000 above, plus a 5 percent admin fee. No tax applies.',
    );
    foreach ( (array)$rows as $i => $r ) {
        $pid = (int)($r['product_id'] ?? 0);
        if ( isset($totals[$pid]) ) { $rows[$i]['total_line'] = $totals[$pid]; }
    }
    update_field('passes',$rows,$reg->ID);

    // "Who the three days are for", verbatim from the static page. The tree
    // seeder blanks post_content on every page it touches, so the money page's
    // audience copy has to be restored here, after it.
    wp_update_post( array( 'ID' => $reg->ID, 'post_content' =>
        "<p>ConvergX brings together senior industry leaders to seek shared solutions to common problems.</p>\n" .
        "<p>Attendees include executives, owners, operators, procurement leaders, government officials, military leadership, investors, innovators, and technology providers who influence strategy, purchasing, partnerships, and deployment.</p>\n" .
        "<p>All attendees are individually vetted, VP level or higher decision makers.</p>" ) );
    WP_CLI::log("  register totals + audience copy restored");
}

// ---- CONGRESS: sponsors body + sponsorship contact section ----
$con = get_page_by_path('congress');
if ( $con ) {
    update_field('sponsors_lede',
      'ConvergX thanks the sponsors, supporters and partners of the 2026 Global Congress.',$con->ID);
    update_field('sponsors_body','The opening reception is sponsored by ATCO Frontec.',$con->ID);

    // The sponsorship form section: match ConvergX's own heading and prose.
    $secs = (array) get_field('sections',$con->ID);
    $found=false;
    foreach ($secs as $i=>$s) {
        if ( ($s['acf_fc_layout'] ?? '')==='form' && ($s['form_key'] ?? '')==='sponsor' ) {
            $secs[$i]['heading']='Contact us for sponsorship information';
            $secs[$i]['eyebrow']='Sponsorship';
            $secs[$i]['lede']='Sponsorship places your organization in front of the leaders attending the 2026 Global Congress, Sep 22 to 24 in Calgary. Tell us who you are and what you have in mind, and the team will follow up.';
            $found=true;
        }
    }
    if ($found) { update_field('sections',$secs,$con->ID); WP_CLI::log("  sponsorship section reworded"); }
}

// ---- INDUSTRIES: Fig. 7 between the opening editorial and the link index ----
// The static page inlines fig-7-lines-disappear inside its first section. The
// reseed only rebuilds editorial rows and appends kept rows at the end, so this
// runs after it and pins the figure back to position 1. Idempotent.
$ind = get_page_by_path('industries');
if ( $ind ) {
    $fig = array(
        'acf_fc_layout' => 'figure',
        'slug'          => 'fig-7-lines-disappear',
        'caption'       => 'Fig. 7. Industries meeting at one shared point. A requirement raised in one can be answered from any of the others.',
    );
    $secs = array();
    foreach ( (array) get_field('sections',$ind->ID) as $s ) {
        if ( ($s['acf_fc_layout'] ?? '') === 'figure' && ($s['slug'] ?? '') === $fig['slug'] ) { continue; }
        $secs[] = $s;
    }
    array_splice( $secs, 1, 0, array( $fig ) );
    update_field('sections',$secs,$ind->ID);
    WP_CLI::log('  industries Fig. 7 placed');
}
WP_CLI::success('exact-wording pass done');

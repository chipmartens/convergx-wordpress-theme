<?php
/**
 * Restore ConvergX's exact wording where the port paraphrased or truncated.
 */

// ---- REGISTER: full total sentences, verbatim ----
$reg = get_page_by_path('congress/register');
if ( $reg ) {
    $rows = get_field('passes',$reg->ID);
    $totals = array(
        10 => 'Total at checkout: 2,200 USD. The 2,000 above, plus a 5 percent admin fee and 5 percent tax.',
        11 => 'Total at checkout: 420 USD. The 400 above, plus a 5 percent admin fee. No tax applies.',
        12 => 'Total at checkout: 1,050 USD. The 1,000 above, plus a 5 percent admin fee. No tax applies.',
    );
    foreach ( (array)$rows as $i => $r ) {
        $pid = (int)($r['product_id'] ?? 0);
        if ( isset($totals[$pid]) ) { $rows[$i]['total_line'] = $totals[$pid]; }
    }
    update_field('passes',$rows,$reg->ID);
    WP_CLI::log("  register totals restored");
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
            $secs[$i]['lede']='Sponsorship places your organization in front of the leaders attending the 2026 Global Congress, Sep 22 to 24 in Calgary.';
            $found=true;
        }
    }
    if ($found) { update_field('sections',$secs,$con->ID); WP_CLI::log("  sponsorship section reworded"); }
}
WP_CLI::success('exact-wording pass done');

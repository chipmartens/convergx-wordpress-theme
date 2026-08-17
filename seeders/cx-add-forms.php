<?php
/** Append the right form to each page that carries one in the static site. */
$map = array(
  'about'           => array('contact','Get in touch','Contact',''),
  'access/request'  => array('request','Request access','Access','Tell ConvergX what you are looking for.'),
  'access/apply'    => array('apply','Apply to join','Access','Tell ConvergX what you have built.'),
  'requirement'     => array('requirement','Tell ConvergX what you need','The requirement','Every question is required unless the label says optional.'),
  'congress'        => array('sponsor','Sponsor the Congress','Sponsorship',''),
);
$n=0;
foreach ($map as $path => [$key,$heading,$eyebrow,$lede]) {
    $p = get_page_by_path($path);
    if (!$p) { continue; }
    $rows = (array) get_field('sections',$p->ID);
    // Idempotent: never add a second copy of the same form.
    foreach ($rows as $r) { if (($r['acf_fc_layout'] ?? '')==='form' && ($r['form_key'] ?? '')===$key) { continue 2; } }
    $rows[] = array('acf_fc_layout'=>'form','heading'=>$heading,'eyebrow'=>$eyebrow,'lede'=>$lede,'form_key'=>$key);
    update_field('sections',$rows,$p->ID);
    $n++;
}
WP_CLI::success("forms added to {$n} pages");

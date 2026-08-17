<?php
/** Append the right form to each page that carries one in the static site. */
$map = array(
  'about'           => array('contact','Get in touch','Contact','For general inquiries, send a note and the right person at ConvergX will follow up.'),
  // On these three pages the static site sets the form INSIDE the preceding
  // section ("The application", "Six questions", "Eleven questions"), so the
  // form block itself carries no heading, label or lede of its own.
  'access/request'  => array('request','','',''),
  'access/apply'    => array('apply','','',''),
  'requirement'     => array('requirement','','',''),
  'congress'        => array('sponsor','Sponsor the Congress','Sponsorship',''),
);
$n=0;
foreach ($map as $path => [$key,$heading,$eyebrow,$lede]) {
    $p = get_page_by_path($path);
    if (!$p) { continue; }
    $rows = (array) get_field('sections',$p->ID);
    // Idempotent: an existing copy of the form is updated in place rather than
    // duplicated, so heading and lede corrections here reach seeded installs.
    $found = false;
    foreach ($rows as $i => $r) {
        if (($r['acf_fc_layout'] ?? '')==='form' && ($r['form_key'] ?? '')===$key) {
            $rows[$i]['heading']=$heading; $rows[$i]['eyebrow']=$eyebrow; $rows[$i]['lede']=$lede;
            $found = true;
        }
    }
    if (!$found) { $rows[] = array('acf_fc_layout'=>'form','heading'=>$heading,'eyebrow'=>$eyebrow,'lede'=>$lede,'form_key'=>$key); }
    update_field('sections',$rows,$p->ID);
    $n++;
}
WP_CLI::success("forms added to {$n} pages");

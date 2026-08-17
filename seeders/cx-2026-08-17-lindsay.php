<?php
/**
 * Client copy changes, Lindsay Robertson, email 2026-08-17 "Convergx.co changes".
 *
 * Field-level changes only. The two ROW-level changes from the same email
 * (the "Speed to deal" sentence and the Xpand TRL table) live in
 * extract.py's PATCHES so a re-extraction from the launch site cannot
 * silently revert them; this file carries the change to the Congress hero,
 * which is a page field rather than a section row.
 *
 * Wording is hers, verbatim, per the recorded precedent that ConvergX's own
 * published strings ship as she writes them. The one normalisation is the
 * site-wide typographic gate: her "12-18months" gains its missing space.
 * Note for review: this copy renames the portfolio ("ConvergX Congresses",
 * was "ConvergX Xchange") ONLY here; the homepage link descriptor still says
 * Xchange, flagged to Chip rather than propagated.
 *
 * Run: wp eval-file seeders/cx-2026-08-17-lindsay.php
 */

$page = get_page_by_path( 'congress' );

if ( ! $page || ! function_exists( 'update_field' ) ) {
	WP_CLI::error( 'congress page or ACF missing' );
}

$subs = "The ConvergX Global Congress is the flagship event within the ConvergX Congresses portfolio, bringing together leaders responsible for solving some of industry's most pressing challenges.\n\n"
	. 'This is not a traditional conference. It is a curated, decision-maker-only environment designed to accelerate the adoption of proven technologies, forge strategic partnerships, and create commercial opportunities across industries. ConvergX is by invitation only, is closed door, Chatham house rule applies, and no media is allowed. The objective of Congress is to move from business opportunity to deal closure in 12-18 months.';

update_field( 'hero_subs', $subs, $page->ID );

WP_CLI::success( 'congress hero subs updated per Lindsay 2026-08-17' );

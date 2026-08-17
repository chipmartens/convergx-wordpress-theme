<?php
/**
 * ACF field registration.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

/**
 * ============================================================================
 * WHY FIELDS ARE REGISTERED IN PHP AND NOT AS acf-json
 * ============================================================================
 *
 * acf-json auto-sync is the usual answer and it is the wrong one here. It
 * creates a two-way channel: an editor changes a field group in wp-admin on a
 * host where the theme directory is not writable, the change lives only in the
 * database, and the next theme deploy silently reverts it while content rows
 * still reference sub-field keys that no longer exist.
 *
 * acf_add_local_field_group() is one-way. The theme is the only definition,
 * field groups are read-only in wp-admin, and there is no drift to reconcile.
 * On a site this theme has to be rolled back on, one-way is worth more than
 * editable field groups.
 *
 * REQUIRES ACF PRO. Repeater and Flexible Content are Pro-only. So is an
 * Options Page, which the site notice would need if it ever moves out of
 * shell.js. Everything below degrades to nothing (not to a fatal) when ACF is
 * absent, via convergx_field() in functions.php.
 */
function convergx_register_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	convergx_register_page_shell_fields();
	convergx_register_register_page_fields();
	convergx_register_industry_fields();
	convergx_register_congress_fields();
	convergx_register_flexible_fields();
}

/**
 * Agenda, accommodation and sponsors. Congress page only.
 */
function convergx_register_congress_fields() {
	$loc = array( array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/page-congress.php' ) ) );

	// ---------------- AGENDA ----------------
	acf_add_local_field_group(
		array(
			'key'      => 'group_convergx_agenda',
			'title'    => 'Agenda',
			'location' => $loc,
			'fields'   => array(
				array(
					'key'          => 'field_cx_agenda_days',
					'label'        => 'Days',
					'name'         => 'agenda_days',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add day',
					'instructions' => 'One row per day. Each day renders as a disclosure that opens to its own timetable.',
					'sub_fields'   => array(
						array( 'key' => 'field_cx_agenda_day_title', 'label' => 'Day', 'name' => 'title', 'type' => 'text', 'instructions' => 'As a reader would say it, e.g. "Tuesday, September 22nd".' ),
						array( 'key' => 'field_cx_agenda_day_caption', 'label' => 'Table caption', 'name' => 'caption', 'type' => 'text', 'instructions' => 'Read by screen readers only, never shown. Short form, e.g. "Tuesday 22 September". Leave empty to reuse the day title.' ),
						array(
							'key'          => 'field_cx_agenda_rows',
							'label'        => 'Sessions',
							'name'         => 'rows',
							'type'         => 'repeater',
							'layout'       => 'table',
							'button_label' => 'Add session',
							'sub_fields'   => array(
								array( 'key' => 'field_cx_agenda_time', 'label' => 'Time', 'name' => 'time', 'type' => 'text' ),
								array( 'key' => 'field_cx_agenda_session', 'label' => 'Session', 'name' => 'session', 'type' => 'text' ),
								array(
									'key'          => 'field_cx_agenda_detail',
									'label'        => 'Detail',
									'name'         => 'detail',
									'type'         => 'textarea',
									'rows'         => 2,
									'instructions' => 'OPTIONAL, and it is what decides the shape of the row. A session WITH detail becomes its own disclosure the reader can open; a session without one is a plain line. Do not add detail to every row: if everything opens, the disclosure stops meaning "there is more here".',
								),
							),
						),
					),
				),
			),
		)
	);

	// ---------------- ACCOMMODATION ----------------
	acf_add_local_field_group(
		array(
			'key'      => 'group_convergx_hotels',
			'title'    => 'Accommodation',
			'location' => $loc,
			'fields'   => array(
				array(
					'key'     => 'field_cx_hotels_msg',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => "<strong>These are ConvergX's own published delegate logistics.</strong> Reproduce the address, phone and rate code exactly as they publish them.<br><br>Do NOT add: room rates (none are published, and a rate code is not a rate), booking deadlines or room-block cutoffs (none are published, and inventing one manufactures urgency), or a hotel's own marketing superlatives. Nothing here should let a reader infer the venue, which ConvergX has not announced.",
				),
				array(
					'key'          => 'field_cx_hotels',
					'label'        => 'Hotels',
					'name'         => 'hotels',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add hotel',
					'sub_fields'   => array(
						array( 'key' => 'field_cx_hotel_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text' ),
						array( 'key' => 'field_cx_hotel_photo', 'label' => 'Photo', 'name' => 'photo', 'type' => 'image', 'return_format' => 'id' ),
						array( 'key' => 'field_cx_hotel_alt', 'label' => 'Photo description', 'name' => 'alt', 'type' => 'text', 'instructions' => 'What the photograph shows, for screen readers. Describe the building, not the brand.' ),
						array( 'key' => 'field_cx_hotel_address', 'label' => 'Address', 'name' => 'address', 'type' => 'text' ),
						array( 'key' => 'field_cx_hotel_phone', 'label' => 'Reservations phone', 'name' => 'phone', 'type' => 'text' ),
						array( 'key' => 'field_cx_hotel_rate', 'label' => 'Rate code line', 'name' => 'rate', 'type' => 'text', 'instructions' => 'e.g. "Rate code S7156", or "No rate code is published for this hotel." A rate code is not a rate; never write a price here.' ),
						array( 'key' => 'field_cx_hotel_steps', 'label' => 'Booking steps', 'name' => 'steps', 'type' => 'textarea', 'rows' => 3, 'instructions' => 'Optional, one step per line. Renders as a numbered list.' ),
						array( 'key' => 'field_cx_hotel_url', 'label' => 'Booking link', 'name' => 'url', 'type' => 'url' ),
						array( 'key' => 'field_cx_hotel_cta', 'label' => 'Link label', 'name' => 'cta', 'type' => 'text', 'default_value' => 'Book the ConvergX rate' ),
					),
				),
			),
		)
	);

	// ---------------- SPONSORS ----------------
	acf_add_local_field_group(
		array(
			'key'      => 'group_convergx_sponsors',
			'title'    => 'Sponsors, supporters and partners',
			'location' => $loc,
			'fields'   => array(
				array(
					'key'          => 'field_cx_sponsors_lede',
					'label'        => 'Standfirst',
					'name'         => 'sponsors_lede',
					'type'         => 'textarea',
					'rows'         => 2,
				),
				array(
					'key'          => 'field_cx_sponsors',
					'label'        => 'Marks',
					'name'         => 'sponsors',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add mark',
					'instructions' => 'CLEARANCE IS PER MARK, and unticked is the safe default. Another organisation\'s logo is their property and showing it implies a relationship. An uncleared mark does not render, and that is the system working, not a bug.',
					'sub_fields'   => array(
						array( 'key' => 'field_cx_sponsor_img', 'label' => 'Mark', 'name' => 'mark', 'type' => 'image', 'return_format' => 'id' ),
						array( 'key' => 'field_cx_sponsor_label', 'label' => 'Organisation', 'name' => 'label', 'type' => 'text', 'instructions' => 'The full name, read aloud by screen readers in place of the image.' ),
						array(
							'key'          => 'field_cx_sponsor_cap',
							'label'        => 'Optical size',
							'name'         => 'cap_frac',
							'type'         => 'number',
							'min'          => 0.2,
							'max'          => 1,
							'step'         => 0.05,
							'default_value' => 0.7,
							'instructions' => 'How much of the row height this mark should fill, 0.2 to 1. A wordmark sits around 0.7; a square badge or crest around 0.4. This is an optical judgement, not a measurement: it is what stops a tall crest towering over a long wordmark on the same row. Leave the default if unsure.',
						),
						array( 'key' => 'field_cx_sponsor_cleared', 'label' => 'Cleared to display', 'name' => 'cleared', 'type' => 'true_false', 'ui' => 1 ),
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'convergx_register_fields' );

/**
 * Surface + hero. Applies to every page.
 */
function convergx_register_page_shell_fields() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_convergx_shell',
			'title'    => 'Page shell',
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ) ) ),
			'position' => 'acf_after_title',
			'fields'   => array(
				array(
					'key'           => 'field_convergx_surface',
					'label'         => 'Surface',
					'name'          => 'surface',
					'type'          => 'select',
					'instructions'  => 'The colour ground this page renders on. Every colour in the design system resolves against this. Light is the default for inner pages; dark is the homepage; muted is used for the registration page. If you are unsure, leave it on light.',
					'choices'       => array(
						'light' => 'Light',
						'dark'  => 'Dark',
						'muted' => 'Muted',
					),
					'default_value' => 'light',
					'return_format' => 'value',
				),
				array(
					'key'          => 'field_convergx_hero_title',
					'label'        => 'Hero heading',
					'name'         => 'hero_title',
					'type'         => 'text',
					'instructions' => 'HEADING DOCTRINE: one complete declarative sentence. No aphorisms, no inverted constructions, no tricolons. Say the plain thing. Leave empty to use the page title.',
				),
				array(
					'key'          => 'field_convergx_hero_lede',
					'label'        => 'Hero standfirst',
					'name'         => 'hero_lede',
					'type'         => 'textarea',
					'rows'         => 2,
					'instructions' => 'One or two sentences under the heading. Plain sentences, no marketing fragments.',
				),
			),
		)
	);
}

/**
 * The registration page.
 *
 * PRICES ARE NOT FIELDS. There is no price field below and there must never be
 * one: the base price is read live from the WooCommerce product object so it
 * cannot drift from the shop. What IS a field is ConvergX's own qualifier
 * string and our computed total, because neither can be derived from a product.
 */
function convergx_register_register_page_fields() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_convergx_register',
			'title'    => 'Registration passes',
			'location' => array( array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/page-register.php' ) ) ),
			'fields'   => array(
				array(
					'key'     => 'field_convergx_register_notice',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => "<strong>Two rules on this page, both load bearing.</strong><br><br>
1. <strong>Order.</strong> Who the room is for, and the fact that every attendee is vetted, come BEFORE any figure. Never reorder so a price appears above the admission standard. That ordering is what stops this page reading as a ticket page.<br><br>
2. <strong>Totals go stale silently.</strong> The prices below are read live from the shop and cannot drift. The TOTAL line is our arithmetic on ConvergX's fee structure, and ConvergX publishes that rate nowhere except the cart. If a fee changes, nothing announces it. Re-measure by adding each product to a cart and reading the totals block. If you cannot verify a total today, CLEAR THE FIELD. A missing total is honest; a stale one on the money path is the worst failure this page has.",
					'new_lines' => '',
				),
				array(
					'key'          => 'field_convergx_passes_lede',
					'label'        => 'Passes standfirst',
					'name'         => 'passes_lede',
					'type'         => 'textarea',
					'rows'         => 2,
					'instructions' => 'States the dates and what a pass covers, once, above the cards. Stating it here is why the cards carry no description.',
				),
				array(
					'key'          => 'field_convergx_passes',
					'label'        => 'Passes',
					'name'         => 'passes',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add pass',
					'instructions' => 'ORDER MATCHES CONVERGX\'S OWN SHOP (Standard, Military, Government). It is not price order and is not meant to be. If their shop reorders, follow it. Do not re-sort by price.',
					'sub_fields'   => array(
						array(
							'key'           => 'field_convergx_pass_product',
							'label'         => 'WooCommerce product',
							'name'          => 'product_id',
							'type'          => 'number',
							'instructions'  => 'Product ID. Live IDs as of 2026-08-14: Standard 230, Military 11306, Government 12764. Use IDs, not slugs: the catalogue still carries superseded duplicates at similar slugs. The name, price and link are all read from this product, so they can never drift from the shop.',
							'required'      => 1,
						),
						array(
							'key'          => 'field_convergx_pass_qualifier',
							'label'        => 'Price qualifier',
							'name'         => 'qualifier',
							'type'         => 'text',
							'instructions' => "ConvergX's own wording, VERBATIM. Do not paraphrase, do not fix the capitalisation. Standard reads: Price does not include Admin Fees + Tax. Military and Government read: Price does not include Admin Fees. Tax is not applicable to this registration.",
						),
						array(
							'key'          => 'field_convergx_pass_total',
							'label'        => 'Total at checkout',
							'name'         => 'total_line',
							'type'         => 'text',
							'instructions' => 'OPTIONAL, and leave it empty unless you have verified it in a live cart today. Renders under the qualifier as additional information, never as a replacement price. Empty = the line does not render.',
						),
						array(
							'key'          => 'field_convergx_pass_verified',
							'label'        => 'Total verified on',
							'name'         => 'total_verified',
							'type'         => 'date_picker',
							'return_format' => 'Y-m-d',
							'display_format' => 'Y-m-d',
							'instructions' => 'The date the total above was measured in a live cart. Shown to the reader. A total with no date does not render: a reader who meets a different number at checkout needs to know when ours was taken.',
						),
						array(
							'key'          => 'field_convergx_pass_includes',
							'label'        => 'What it includes',
							'name'         => 'includes',
							'type'         => 'repeater',
							'layout'       => 'table',
							'button_label' => 'Add line',
							'instructions' => 'THE THREE LISTS ARE DELIBERATELY IDENTICAL. ConvergX publishes one sentence per product and it is the same sentence three times. The passes differ on price and on nothing else that is published. Writing three different lists would invent a tier structure that does not exist. If ConvergX later publishes per-pass differences, this is where they go. Do not manufacture them in the meantime.',
							'sub_fields'   => array(
								array(
									'key'   => 'field_convergx_pass_include_text',
									'label' => 'Line',
									'name'  => 'text',
									'type'  => 'text',
								),
							),
						),
						array(
							'key'           => 'field_convergx_pass_cta',
							'label'         => 'Button label',
							'name'          => 'cta_label',
							'type'          => 'text',
							'default_value' => 'Register',
						),
					),
				),
			),
		)
	);
}

/**
 * The eight industry pages, plus the Congress hero which shares the image field.
 */
function convergx_register_industry_fields() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_convergx_industry',
			'title'    => 'Hero',
			'location' => array(
				array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/page-industry.php' ) ),
				array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/page-congress.php' ) ),
			),
			'fields'   => array(
				array(
					'key'          => 'field_convergx_ind_eyebrow',
					'label'        => 'Sector label',
					'name'         => 'eyebrow',
					'type'         => 'text',
					'instructions' => 'The sector name, shown above the heading. Defaults to the page title if empty.',
				),
				array(
					'key'           => 'field_convergx_ind_hero_img',
					'label'         => 'Hero image',
					'name'          => 'hero_image',
					'type'          => 'image',
					'return_format' => 'id',
					'instructions'  => 'Sits behind the heading under a dark veil, so it is atmosphere rather than content: low key, one subject, dark surround. No clean-room white, no spark showers, no aerials, no stock gloss, no readable logos and no identifiable faces. It renders with an empty alt because the heading already carries the meaning.',
				),
				array(
					'key'          => 'field_convergx_hero_subs',
					'label'        => 'Hero paragraphs',
					'name'         => 'hero_subs',
					'type'         => 'textarea',
					'rows'         => 5,
					'instructions' => 'Congress hero only. Blank line between paragraphs; they render side by side in equal columns at the SAME size. Neither is a lede: setting the first one larger is what made the hero run too tall, so keep them close in length as well as size.',
				),
				array(
					'key'          => 'field_convergx_ind_say',
					'label'        => 'Opening statement',
					'name'         => 'say',
					'type'         => 'text',
					'instructions' => 'ONE PER PAGE. The largest non-heading line on the page, and it stops being emphatic the moment there are two. One short declarative sentence. There is deliberately no way to add a second further down.',
				),
				array(
					'key'          => 'field_convergx_ind_say_body',
					'label'        => 'Opening body',
					'name'         => 'say_body',
					'type'         => 'textarea',
					'rows'         => 4,
					'instructions' => 'The paragraph or two that follow the opening statement. Blank line between paragraphs.',
				),
			),
		)
	);
}

/**
 * Flexible content for editorial pages.
 *
 * WHAT IS NOT IN HERE, ON PURPOSE.
 *
 * The globe hero, the flow band, and the agenda's nested disclosures are NOT
 * layouts and must never become fields. All three are generated or JS-coupled:
 * the globe SVG is machine-generated with data-globe-* attributes that globe.js
 * rewrites at runtime, the flow band registers clipPath ids that collide if the
 * section is duplicated, and the agenda's disclosure structure follows a
 * mechanical rule about which rows get one.
 *
 * Put any of them behind a WYSIWYG and the first editor save strips the inline
 * SVG and data attributes through wp_kses. They live in template-parts/ as PHP.
 *
 * THE RULE, in one line: fields hold text, URLs, images and repeater rows.
 * SVG and data-* markup never enters a field.
 */
function convergx_register_flexible_fields() {
	acf_add_local_field_group(
		array(
			'key'      => 'group_convergx_sections',
			'title'    => 'Page sections',
			// Available on both the generic editorial template and the eight
			// industry pages, whose body below the opening statement is the
			// same alternating run of editorial sections.
			'location' => array(
				array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/page-flex.php' ) ),
				array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/page-industry.php' ) ),
				array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/page-congress.php' ) ),
			),
			'fields'   => array(
				array(
					'key'          => 'field_convergx_sections',
					'label'        => 'Sections',
					'name'         => 'sections',
					'type'         => 'flexible_content',
					'button_label' => 'Add section',
					'layouts'      => array(
						'editorial' => array(
							'key'        => 'layout_convergx_editorial',
							'name'       => 'editorial',
							'label'      => 'Editorial',
							'display'    => 'block',
							'sub_fields' => array(
								array( 'key' => 'field_cx_ed_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text', 'instructions' => 'A section heading is a LABEL: a noun phrase, not an instruction. The action belongs on buttons, which is where a reader looks for it.' ),
								array( 'key' => 'field_cx_ed_level', 'label' => 'Heading level', 'name' => 'level', 'type' => 'select',
									'choices' => array( 2 => 'Section (h2)', 3 => 'Subsection (h3)' ), 'default_value' => 2, 'return_format' => 'value',
									'instructions' => 'Leave on Section unless this genuinely sits under the one above it. The level is what a screen reader navigates by, so it is structure rather than size.' ),
								array( 'key' => 'field_cx_ed_eyebrow', 'label' => 'Edge label', 'name' => 'eyebrow', 'type' => 'text', 'instructions' => 'Eyebrows LABEL the section, they do not narrate it. Two or three words.' ),
								array( 'key' => 'field_cx_ed_lede', 'label' => 'Standfirst', 'name' => 'lede', 'type' => 'textarea', 'rows' => 2 ),
								array(
									'key'          => 'field_cx_ed_say',
									'label'        => 'Opening statement',
									'name'         => 'say',
									'type'         => 'text',
									'instructions' => 'Optional. The largest non-heading line in the section, set at full measure above the body. It stops being emphatic when there are several, so use it for the one sentence the section turns on, and leave it empty otherwise.',
								),
								array( 'key' => 'field_cx_ed_body', 'label' => 'Body', 'name' => 'body', 'type' => 'wysiwyg', 'media_upload' => 0, 'toolbar' => 'basic', 'instructions' => 'Paragraphs and lists only. Plain complete sentences. No inline SVG, no data attributes, no pasted markup: it will be stripped on save.' ),
								array(
									'key'           => 'field_cx_ed_dense',
									'label'         => 'Tighter spacing',
									'name'          => 'dense',
									'type'          => 'true_false',
									'ui'            => 1,
									'instructions'  => 'The industry pages alternate normal and tighter spacing down the page, so consecutive sections do not read as equally weighted. Tick every second one.',
								),
								array(
									'key'          => 'field_cx_ed_claims',
									'label'        => 'Expandable claims',
									'name'         => 'claims',
									'type'         => 'repeater',
									'layout'       => 'block',
									'button_label' => 'Add claim',
									'instructions' => 'Each row states a claim the reader can open for the detail behind it. The claim is the sentence; the detail is what backs it. If a row has no detail worth opening, it belongs in the body as a plain paragraph.',
									'sub_fields'   => array(
										array( 'key' => 'field_cx_claim_title', 'label' => 'The claim', 'name' => 'title', 'type' => 'text' ),
										array( 'key' => 'field_cx_claim_body', 'label' => 'What backs it', 'name' => 'body', 'type' => 'wysiwyg', 'media_upload' => 0, 'toolbar' => 'basic' ),
									),
								),
								array(
									'key'          => 'field_cx_ed_whatis',
									'label'        => 'Titled rows',
									'name'         => 'whatis',
									'type'         => 'repeater',
									'layout'       => 'block',
									'button_label' => 'Add row',
									'instructions' => 'A run of titled blocks, each a short name and the paragraphs under it. Always visible, never a disclosure: use Expandable claims when the detail should start closed.',
									'sub_fields'   => array(
										array( 'key' => 'field_cx_whatis_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text' ),
										array( 'key' => 'field_cx_whatis_body', 'label' => 'Body', 'name' => 'body', 'type' => 'wysiwyg', 'media_upload' => 0, 'toolbar' => 'basic' ),
									),
								),
								array(
									'key'          => 'field_cx_ed_store',
									'label'        => 'Event cards',
									'name'         => 'store',
									'type'         => 'repeater',
									'layout'       => 'block',
									'button_label' => 'Add event',
									'instructions' => 'Partner events. Registration for these is the HOST\'s, never ConvergX\'s, so every link goes to the host and each card says so underneath. Do not add a price or a ConvergX registration link here.',
									'sub_fields'   => array(
										array( 'key' => 'field_cx_st_name', 'label' => 'Event', 'name' => 'name', 'type' => 'text' ),
										array( 'key' => 'field_cx_st_meta', 'label' => 'Dates and place', 'name' => 'meta', 'type' => 'text' ),
										array( 'key' => 'field_cx_st_desc', 'label' => 'Description', 'name' => 'desc', 'type' => 'wysiwyg', 'media_upload' => 0, 'toolbar' => 'basic' ),
										array( 'key' => 'field_cx_st_url', 'label' => "Host's link", 'name' => 'url', 'type' => 'url' ),
										array( 'key' => 'field_cx_st_cta', 'label' => 'Link label', 'name' => 'cta', 'type' => 'text' ),
										array( 'key' => 'field_cx_st_away', 'label' => 'Handoff line', 'name' => 'away', 'type' => 'text', 'instructions' => 'Says out loud that the reader is leaving and whose site they are going to.' ),
									),
								),
								array(
									'key'          => 'field_cx_ed_links',
									'label'        => 'Link index',
									'name'         => 'links',
									'type'         => 'repeater',
									'layout'       => 'table',
									'button_label' => 'Add link',
									'instructions' => 'An index of links with a one-line descriptor each. This is a COMPONENT, not a bulleted list: it sets beside the copy in its own column. Leave empty on sections that are just prose.',
									'sub_fields'   => array(
										array( 'key' => 'field_cx_link_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text' ),
										array( 'key' => 'field_cx_link_href', 'label' => 'Link', 'name' => 'href', 'type' => 'text' ),
										array( 'key' => 'field_cx_link_note', 'label' => 'Descriptor', 'name' => 'note', 'type' => 'text', 'instructions' => 'Says what the page is, not what to do on it.' ),
									),
								),
								array(
									'key'          => 'field_cx_ed_twopath',
									'label'        => 'Two-path chooser',
									'name'         => 'twopath',
									'type'         => 'repeater',
									'layout'       => 'table',
									'max'          => 2,
									'button_label' => 'Add path',
									'instructions' => 'The closer that sends a reader down one of two doors. TWO rows, never three: the whole point is that a reader picks a side, and a third choice is what turns a decision back into a menu. Foot of the page only, never a hero.',
									'sub_fields'   => array(
										array( 'key' => 'field_cx_tp_label', 'label' => 'The reader says', 'name' => 'label', 'type' => 'text' ),
										array( 'key' => 'field_cx_tp_cta', 'label' => 'Action', 'name' => 'cta', 'type' => 'text' ),
										array( 'key' => 'field_cx_tp_href', 'label' => 'Link', 'name' => 'href', 'type' => 'text' ),
									),
								),
								array(
									'key'           => 'field_cx_ed_surface',
									'label'         => 'Colour band',
									'name'          => 'surface',
									'type'          => 'select',
									'choices'       => array(
										''      => 'Inherit the page',
										'light' => 'Light',
										'dark'  => 'Dark',
										'muted' => 'Muted',
									),
									'default_value' => '',
									'allow_null'    => 1,
									'return_format' => 'value',
									'instructions'  => 'Sets this section on its own colour ground, which is how the pages break a long run into bands rather than one continuous field of colour. Leave on Inherit unless you are deliberately starting a new band. Every colour in the section resolves against this, so changing it re-colours the type, rules and buttons together rather than leaving one of them behind.',
								),
							),
						),
						'figure' => array(
							'key'        => 'layout_convergx_figure',
							'name'       => 'figure',
							'label'      => 'Figure',
							'display'    => 'block',
							'sub_fields' => array(
								array(
									'key'           => 'field_cx_fig_slug',
									'label'         => 'Figure',
									'name'          => 'slug',
									'type'          => 'select',
									'choices'       => convergx_figure_choices(),
									'instructions'  => 'Figures are pre-built SVG files in the theme, not uploads. They carry the diagram system\'s own line weights and label placement rules, which an uploaded image would not.',
									'return_format' => 'value',
								),
								array( 'key' => 'field_cx_fig_caption', 'label' => 'Caption', 'name' => 'caption', 'type' => 'text' ),
							),
						),
						'store' => array(
							'key'        => 'layout_convergx_store',
							'name'       => 'store',
							'label'      => 'Product row',
							'display'    => 'block',
							'sub_fields' => array(
								array( 'key' => 'field_cx_store_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
								array(
									'key'          => 'field_cx_store_ids',
									'label'        => 'Product IDs',
									'name'         => 'product_ids',
									'type'         => 'text',
									'instructions' => 'Comma-separated WooCommerce product IDs, in the order ConvergX\'s own shop lists them. Names, prices and links are read live from the products.',
								),
							),
						),
						'people' => array(
							'key'        => 'layout_convergx_people',
							'name'       => 'people',
							'label'      => 'People grid',
							'display'    => 'block',
							'sub_fields' => array(
								array( 'key' => 'field_cx_people_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
								array(
									'key'          => 'field_cx_people_rows',
									'label'        => 'People',
									'name'         => 'rows',
									'type'         => 'repeater',
									'layout'       => 'block',
									'instructions' => 'ORDER IS CONVERGX\'S OWN PUBLISHED ORDER. New people are not appended to the end unless their own listing appends them.',
									'sub_fields'   => array(
										array( 'key' => 'field_cx_person_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text' ),
										array( 'key' => 'field_cx_person_role', 'label' => 'Role', 'name' => 'role', 'type' => 'text' ),
										array( 'key' => 'field_cx_person_org', 'label' => 'Organisation', 'name' => 'org', 'type' => 'text' ),
										array( 'key' => 'field_cx_person_photo', 'label' => 'Portrait', 'name' => 'photo', 'type' => 'image', 'return_format' => 'id' ),
										array( 'key' => 'field_cx_person_bio', 'label' => 'Bio', 'name' => 'bio', 'type' => 'textarea', 'rows' => 4 ),
									),
								),
							),
						),
						'logos' => array(
							'key'        => 'layout_convergx_logos',
							'name'       => 'logos',
							'label'      => 'Logo row',
							'display'    => 'block',
							'sub_fields' => array(
								array( 'key' => 'field_cx_logos_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
								array(
									'key'          => 'field_cx_logos_rows',
									'label'        => 'Marks',
									'name'         => 'rows',
									'type'         => 'repeater',
									'layout'       => 'table',
									'instructions' => 'CLEARANCE IS PER MARK. A mark whose Cleared box is unticked does not render. Another organisation\'s logo is their property and using it implies a relationship; unticked is the safe default and is not a bug.',
									'sub_fields'   => array(
										array( 'key' => 'field_cx_logo_img', 'label' => 'Mark', 'name' => 'mark', 'type' => 'image', 'return_format' => 'id' ),
										array( 'key' => 'field_cx_logo_label', 'label' => 'Name', 'name' => 'label', 'type' => 'text' ),
										array( 'key' => 'field_cx_logo_cleared', 'label' => 'Cleared', 'name' => 'cleared', 'type' => 'true_false', 'ui' => 1 ),
									),
								),
							),
						),
						'team' => array(
							'key'        => 'layout_convergx_team',
							'name'       => 'team',
							'label'      => 'Leadership team',
							'display'    => 'block',
							'sub_fields' => array(
								array(
									'key'     => 'field_cx_team_msg',
									'label'   => '',
									'name'    => '',
									'type'    => 'message',
									'message' => 'Renders everyone under <strong>Team</strong> in the sidebar, in their Order. Tick <em>Feature</em> on a person to move them into the larger row above the grid. There is nothing to configure here: add and edit people on their own screens, not in this block.',
								),
							),
						),
						'form' => array(
							'key'        => 'layout_convergx_form',
							'name'       => 'form',
							'label'      => 'Form',
							'display'    => 'block',
							'sub_fields' => array(
								array( 'key' => 'field_cx_form_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
								array( 'key' => 'field_cx_form_eyebrow', 'label' => 'Edge label', 'name' => 'eyebrow', 'type' => 'text' ),
								array( 'key' => 'field_cx_form_lede', 'label' => 'Standfirst', 'name' => 'lede', 'type' => 'textarea', 'rows' => 2 ),
								array(
									'key'          => 'field_cx_form_which',
									'label'        => 'Which form',
									'name'         => 'form_key',
									'type'         => 'select',
									'choices'      => array(
										'contact'     => 'General inquiry',
										'sponsor'     => 'Sponsorship inquiry (goes to Adam)',
										'request'     => 'Request access',
										'apply'       => 'Apply to join',
										'requirement' => 'Submit a requirement',
									),
									'return_format' => 'value',
									'instructions' => 'The questions are fixed per form and defined in the theme, because each one asks a specific set of questions rather than being a generic contact box. Submissions are stored under Form submissions AND emailed, so a mail failure never loses an enquiry.',
								),
							),
						),
						'cta' => array(
							'key'        => 'layout_convergx_cta',
							'name'       => 'cta',
							'label'      => 'Call to action',
							'display'    => 'block',
							'sub_fields' => array(
								array( 'key' => 'field_cx_cta_heading', 'label' => 'Heading', 'name' => 'heading', 'type' => 'text' ),
								array( 'key' => 'field_cx_cta_body', 'label' => 'Body', 'name' => 'body', 'type' => 'textarea', 'rows' => 2 ),
								array( 'key' => 'field_cx_cta_label', 'label' => 'Button label', 'name' => 'label', 'type' => 'text' ),
								array( 'key' => 'field_cx_cta_url', 'label' => 'Button URL', 'name' => 'url', 'type' => 'url' ),
							),
						),
					),
				),
			),
		)
	);
}

/**
 * The figure files shipped with the theme.
 *
 * @return array
 */
function convergx_figure_choices() {
	$choices = array();
	$dir     = CONVERGX_DIR . '/assets/fig';

	if ( ! is_dir( $dir ) ) {
		return $choices;
	}

	foreach ( (array) glob( $dir . '/*.svg' ) as $file ) {
		$slug             = basename( $file, '.svg' );
		$choices[ $slug ] = ucfirst( str_replace( '-', ' ', $slug ) );
	}

	return $choices;
}

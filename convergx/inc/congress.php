<?php
/**
 * Congress helpers.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

/**
 * The slug a speaker's four cross-references are built from.
 *
 * ============================================================================
 * THIS FUNCTION IS THE WHOLE POINT OF THE SPEAKERS SECTION.
 * ============================================================================
 *
 * Every speaker carries FOUR ids that must agree, and in the static site all
 * four were typed by hand, sixteen times over:
 *
 *   1. the card            id="speaker-{slug}"
 *   2. the card's link     href="#bio-{slug}"
 *   3. the overlay         id="bio-{slug}"       + aria-labelledby="bio-{slug}-name"
 *   4. the overlay's close href="#speaker-{slug}"   (returns focus to the card)
 *
 * A typo in any one of them is silent. The card still renders, the overlay
 * still renders, the link just does nothing, and no validator complains. That
 * is 64 hand-maintained strings with no failure signal.
 *
 * Deriving all four from the name means they cannot disagree. The only way to
 * break the contract now is two speakers whose names slugify identically, which
 * convergx_speaker_slug() disambiguates by index.
 *
 * @param string $name  Speaker name.
 * @param int    $index Row index, used only to break a collision.
 * @return string
 */
function convergx_speaker_slug( $name, $index = 0 ) {
	$slug = sanitize_title( $name );

	if ( '' === $slug ) {
		$slug = 'speaker-' . (int) $index;
	}

	static $seen = array();

	if ( isset( $seen[ $slug ] ) ) {
		$slug .= '-' . (int) $index;
	}

	$seen[ $slug ] = true;

	return $slug;
}

/**
 * People (the About page team), in published order.
 *
 * Same contract as convergx_speakers(): four cross-references per person, all
 * derived from one slug so they cannot disagree.
 *
 * THE CARD PREFIX DIFFERS FROM SPEAKERS ON PURPOSE. A speaker's card is
 * `#speaker-{slug}` and a team member's is `#card-{slug}`, matching the static
 * site. Kimberley Van Vliet appears in BOTH sets: as a Congress speaker and as
 * ConvergX's founder. With one prefix her two cards would collide on a single
 * id, and the overlay close link would return the reader to whichever the
 * browser found first. Two prefixes, one overlay namespace, no collision.
 *
 * @return array{feature: array, grid: array, all: array}
 */
function convergx_people() {
	$out = array( 'feature' => array(), 'grid' => array(), 'all' => array() );

	$posts = get_posts(
		array(
			'post_type'   => 'cx_person',
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby'     => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		)
	);

	$i = 0;

	foreach ( $posts as $post ) {
		$name = trim( get_the_title( $post ) );

		if ( '' === $name ) {
			continue;
		}

		$row = array(
			'name'      => $name,
			'role'      => (string) convergx_field( 'role', $post->ID ),
			'org'       => (string) convergx_field( 'org', $post->ID ),
			'feature'   => (bool) convergx_field( 'feature', $post->ID ),
			'photo'     => get_post_thumbnail_id( $post->ID ),
			'bio'       => trim( (string) apply_filters( 'the_content', $post->post_content ) ),
			'slug'      => convergx_speaker_slug( $name, 1000 + $i ),
			// The portrait is a headshot the person publishes of themselves, so
			// their name is the whole of the description.
			'photo_alt' => $name,
		);

		$out['all'][] = $row;

		if ( $row['feature'] ) {
			$out['feature'][] = $row;
		} else {
			$out['grid'][] = $row;
		}

		$i++;
	}

	return $out;
}

/**
 * Normalise the speakers repeater into render-ready rows.
 *
 * Splits the feature row from the grid, because the two render in different
 * containers (.speaker-feature and .speaker-grid) but come from one list, so an
 * editor promotes a speaker with a checkbox rather than by moving them between
 * two fields.
 *
 * @return array{feature: array, grid: array, all: array}
 */
function convergx_speakers() {
	$out = array(
		'feature' => array(),
		'grid'    => array(),
		'all'     => array(),
	);

	$posts = get_posts(
		array(
			'post_type'      => 'cx_speaker',
			'post_status'    => 'publish',
			'numberposts'    => -1,
			// ConvergX's published running order, set with the Order box on
			// each speaker. Never alphabetical: see the note in inc/cpt.php.
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
		)
	);

	$i = 0;

	foreach ( $posts as $post ) {
		$name = trim( get_the_title( $post ) );

		// No name, no card. The slug and all four ids derive from it, so a
		// nameless row cannot produce a working card or a reachable overlay.
		if ( '' === $name ) {
			continue;
		}

		$row = array(
			'name'    => $name,
			'role'    => (string) convergx_field( 'role', $post->ID ),
			'billing' => (string) convergx_field( 'billing', $post->ID ),
			'feature' => (bool) convergx_field( 'feature', $post->ID ),
			'photo'   => get_post_thumbnail_id( $post->ID ),
			'bio'     => trim( (string) apply_filters( 'the_content', $post->post_content ) ),
		);

		$row['slug'] = convergx_speaker_slug( $name, $i );

		// The static site writes alt as "Photograph of {name}." on both the
		// card and the overlay. Generated here so the two always match.
		$row['photo_alt'] = sprintf(
			/* translators: %s: speaker name. */
			__( 'Photograph of %s.', 'convergx' ),
			$name
		);

		$out['all'][] = $row;

		if ( ! empty( $row['feature'] ) ) {
			$out['feature'][] = $row;
		} else {
			$out['grid'][] = $row;
		}

		$i++;
	}

	return $out;
}

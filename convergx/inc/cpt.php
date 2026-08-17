<?php
/**
 * Post types: Speakers and Team.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

/**
 * ============================================================================
 * WHY THESE ARE POST TYPES AND NOT REPEATER ROWS
 * ============================================================================
 *
 * The first build had speakers as a repeater on the Congress page and team as a
 * repeater layout. That renders correctly and is miserable to run:
 *
 *   - Adding a speaker means opening the Congress page, scrolling past every
 *     other section, and expanding a collapsed row. Sixteen of them.
 *   - Two people cannot edit two speakers at once without overwriting each
 *     other, because it is all one post.
 *   - No search, no sort, no "who has no bio yet", no revision per person.
 *   - Photos land in one giant repeater and nothing tells you which rows are
 *     missing one.
 *
 * A post type gives each person their own row in a list table, their own edit
 * screen, their own revisions, and a Featured Image control that everyone who
 * has used WordPress already understands. That is what "add a speaker from the
 * back end" actually means.
 *
 * Both are `public => false` with `show_ui => true`: they are editable in
 * wp-admin but have no front-end archive or single URL of their own. They
 * render inside the Congress and About pages, which is where they belong. A
 * speaker at /speaker/jane-doe/ would be a thin orphan page nobody linked to
 * and Google would index it.
 */
function convergx_register_post_types() {
	register_post_type(
		'cx_speaker',
		array(
			'labels'          => array(
				'name'                  => __( 'Speakers', 'convergx' ),
				'singular_name'         => __( 'Speaker', 'convergx' ),
				'add_new_item'          => __( 'Add speaker', 'convergx' ),
				'edit_item'             => __( 'Edit speaker', 'convergx' ),
				'all_items'             => __( 'All speakers', 'convergx' ),
				'search_items'          => __( 'Search speakers', 'convergx' ),
				'not_found'             => __( 'No speakers yet.', 'convergx' ),
				'featured_image'        => __( 'Portrait', 'convergx' ),
				'set_featured_image'    => __( 'Set portrait', 'convergx' ),
				'remove_featured_image' => __( 'Remove portrait', 'convergx' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-microphone',
			'menu_position'   => 21,
			'supports'        => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'revisions' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'capability_type' => 'post',
		)
	);

	register_post_type(
		'cx_person',
		array(
			'labels'          => array(
				'name'                  => __( 'Team', 'convergx' ),
				'singular_name'         => __( 'Team member', 'convergx' ),
				'add_new_item'          => __( 'Add team member', 'convergx' ),
				'edit_item'             => __( 'Edit team member', 'convergx' ),
				'all_items'             => __( 'All team', 'convergx' ),
				'search_items'          => __( 'Search team', 'convergx' ),
				'not_found'             => __( 'No team members yet.', 'convergx' ),
				'featured_image'        => __( 'Portrait', 'convergx' ),
				'set_featured_image'    => __( 'Set portrait', 'convergx' ),
				'remove_featured_image' => __( 'Remove portrait', 'convergx' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-groups',
			'menu_position'   => 22,
			'supports'        => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'revisions' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'capability_type' => 'post',
		)
	);
}
add_action( 'init', 'convergx_register_post_types' );

/**
 * ORDER IS MANUAL, AND THAT IS DELIBERATE.
 *
 * The static site's own instruction is that the speaker order is ConvergX's
 * published order, not alphabetical and not newest-first. `page-attributes`
 * gives every speaker an Order box, and the list table sorts by it, so the
 * running order is set the same way page order is set.
 *
 * Sorting alphabetically here would look tidier and would quietly override the
 * client's running order every time someone is added.
 */
function convergx_order_cpt_admin( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( in_array( $query->get( 'post_type' ), array( 'cx_speaker', 'cx_person' ), true ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'menu_order title' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'convergx_order_cpt_admin' );

/**
 * Fields on a speaker / team member.
 *
 * Role, billing and the feature flag. The name is the post title, the bio is
 * the editor, and the portrait is the featured image, so the three things an
 * editor changes most often are the three WordPress already gives them.
 */
function convergx_register_people_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_convergx_speaker',
			'title'    => 'Speaker details',
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'cx_speaker' ) ) ),
			'fields'   => array(
				array(
					'key'          => 'field_convergx_sp_role',
					'label'        => 'Role',
					'name'         => 'role',
					'type'         => 'text',
					'instructions' => 'Title and organisation, or the talk title for a keynote. Shown under the name on the card.',
				),
				array(
					'key'          => 'field_convergx_sp_billing',
					'label'        => 'Billing',
					'name'         => 'billing',
					'type'         => 'text',
					'instructions' => 'Only for the featured speakers, e.g. "Conference host" or "Opening keynote". Leave empty for everyone else.',
				),
				array(
					'key'          => 'field_convergx_sp_feature',
					'label'        => 'Feature this speaker',
					'name'         => 'feature',
					'type'         => 'true_false',
					'ui'           => 1,
					'instructions' => 'Moves them into the large row above the grid. Keep this to two or three: the row stops reading as featured when everyone is in it.',
				),
				array(
					'key'          => 'field_convergx_sp_clearance',
					'label'        => 'Clearance note',
					'name'         => 'clearance',
					'type'         => 'textarea',
					'rows'         => 2,
					'instructions' => 'INTERNAL, never rendered on the page. Record anything about this bio that was cleared, quarantined or is still unconfirmed, and who said so. Some bios carry affiliations that were shipped on a specific instruction, and that context is otherwise lost the moment someone edits the text.',
				),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_convergx_person',
			'title'    => 'Team member details',
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'cx_person' ) ) ),
			'fields'   => array(
				array(
					'key'          => 'field_convergx_pe_role',
					'label'        => 'Role',
					'name'         => 'role',
					'type'         => 'text',
					'instructions' => 'Job title. Shown under the name.',
				),
				array(
					'key'   => 'field_convergx_pe_org',
					'label' => 'Organisation',
					'name'  => 'org',
					'type'  => 'text',
				),
			),
		)
	);
}
add_action( 'acf/init', 'convergx_register_people_fields' );

/**
 * Show the portrait and role in the list tables.
 *
 * "Which speakers still have no portrait" is the single most common question
 * when a roster is being assembled, and the default list table cannot answer
 * it. This makes it a glance.
 */
function convergx_people_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		if ( 'title' === $key ) {
			$new['cx_portrait'] = __( 'Portrait', 'convergx' );
		}
		$new[ $key ] = $label;
	}

	$new['cx_role'] = __( 'Role', 'convergx' );

	return $new;
}
add_filter( 'manage_cx_speaker_posts_columns', 'convergx_people_columns' );
add_filter( 'manage_cx_person_posts_columns', 'convergx_people_columns' );

/**
 * Fill the custom list-table columns.
 */
function convergx_people_column_content( $column, $post_id ) {
	if ( 'cx_portrait' === $column ) {
		if ( has_post_thumbnail( $post_id ) ) {
			echo get_the_post_thumbnail( $post_id, array( 44, 44 ), array( 'style' => 'border-radius:2px;object-fit:cover;' ) );
		} else {
			echo '<span style="color:#b32d2e;">' . esc_html__( 'none', 'convergx' ) . '</span>';
		}
	}

	if ( 'cx_role' === $column ) {
		echo esc_html( (string) convergx_field( 'role', $post_id ) );
	}
}
add_action( 'manage_cx_speaker_posts_custom_column', 'convergx_people_column_content', 10, 2 );
add_action( 'manage_cx_person_posts_custom_column', 'convergx_people_column_content', 10, 2 );

/**
 * Make the Order column sortable so the running order can be dragged into shape.
 */
function convergx_people_sortable( $columns ) {
	$columns['menu_order'] = 'menu_order';
	return $columns;
}
add_filter( 'manage_edit-cx_speaker_sortable_columns', 'convergx_people_sortable' );
add_filter( 'manage_edit-cx_person_sortable_columns', 'convergx_people_sortable' );

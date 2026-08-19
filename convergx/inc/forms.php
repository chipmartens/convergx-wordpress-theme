<?php
/**
 * Forms.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

/**
 * ============================================================================
 * WHY THE THEME HANDLES THESE AT ALL, AND WHAT IT REFUSES TO DO.
 * ============================================================================
 *
 * The static site's five forms POST to formsubmit.co, a third-party relay:
 *
 *   /access/apply/     /access/request/     /requirement/
 *   /about/            /congress/ (sponsorship, to adam@)
 *
 * Two problems with shipping that into WordPress. First, it sends every
 * enquiry a delegate or a sponsor types through a service ConvergX has no
 * contract with, on a site that already has its own mail transport. Second,
 * formsubmit activates per (email, domain) pair: the first submission from a
 * NEW domain triggers an activation email nobody is expecting, and until
 * someone clicks it the submissions go nowhere. Moving the site to a new
 * domain silently breaks every form on it, during the weeks the sponsorship
 * form matters most.
 *
 * SO: EMAIL IS NEVER THE ONLY COPY. Every submission is stored as a post
 * BEFORE any mail is attempted. If SMTP is misconfigured, if the relay is
 * down, if the address bounces, the enquiry is still in wp-admin. A
 * sponsorship enquiry lost to a mail failure is real money, and mail is the
 * least reliable part of any WordPress install.
 *
 * WHAT THIS DELIBERATELY IS NOT. It is not a form builder, and it does not
 * try to be. There is no UI for adding fields: the five forms are defined in
 * code because they are five specific forms with specific questions, not a
 * feature. convergx.co already runs WPForms; if ConvergX would rather own
 * these there, the section can render a WPForms shortcode instead and this
 * file stops being used. Nothing else in the theme depends on it.
 */

/**
 * Store submissions where a human can find them.
 */
function convergx_register_submission_type() {
	register_post_type(
		'cx_submission',
		array(
			'labels'          => array(
				'name'          => __( 'Form submissions', 'convergx' ),
				'singular_name' => __( 'Submission', 'convergx' ),
				'all_items'     => __( 'Form submissions', 'convergx' ),
				'search_items'  => __( 'Search submissions', 'convergx' ),
				'not_found'     => __( 'No submissions yet.', 'convergx' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => false,
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 23,
			'supports'        => array( 'title' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'convergx_register_submission_type' );

/**
 * The five forms.
 *
 * Field order, labels and required flags are carried from the static pages
 * unchanged. The wording of a question is the question, so none of it is
 * paraphrased here.
 *
 * @return array
 */
function convergx_forms() {
	$industries = array(
		'Aerospace and defence', 'Agriculture', 'Construction', 'Energy',
		'Manufacturing', 'Military', 'Mining and natural resources',
		'Technology', 'Government', 'Investment', 'Other',
	);

	return (array) apply_filters(
		'convergx_forms',
		array(
			'contact' => array(
				'subject' => 'Website: general inquiry',
				'submit'  => 'Send',
				'to'      => 'info@convergx.co',
				'fields'  => array(
					array( 'name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'autocomplete' => 'name' ),
					array( 'name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'autocomplete' => 'email' ),
					array( 'name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => true ),
				),
			),
			'sponsor' => array(
				'subject'    => 'Website: sponsorship inquiry',
				'submit'     => 'Send',
				'form_class' => 'sponsor-form-box',
				// The sponsorship enquiry goes to a different person from
				// everything else. Carried from the static form's own action.
				'to'      => 'adam@convergx.co',
				'fields'  => array(
					array( 'name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'autocomplete' => 'name' ),
					array( 'name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'autocomplete' => 'email' ),
					array( 'name' => 'industry', 'label' => 'Industry', 'type' => 'select', 'required' => true, 'options' => $industries ),
					array( 'name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => true ),
				),
			),
			'request' => array(
				'to'           => 'info@convergx.co',
				'subject'      => 'Website: access request',
				'intro'        => 'What you send here goes to ConvergX by email, and a person reads it.',
				'submit'       => 'Send request',
				'submit_class' => 'btn',
				'submit_wrap'  => false,
				'fields'  => array(
					array( 'name' => 'org', 'label' => 'Organisation', 'type' => 'text', 'required' => true, 'hint' => 'The legal entity making the request, not a division or programme name.', 'autocomplete' => 'organization' ),
					array( 'name' => 'role', 'label' => 'Your role', 'type' => 'text', 'required' => true, 'hint' => 'What you are accountable for on this requirement.' ),
					array( 'name' => 'email', 'label' => 'Work email', 'type' => 'email', 'required' => true, 'hint' => 'An address at your organisation\'s own domain.', 'autocomplete' => 'email' ),
					array( 'name' => 'requirement', 'label' => 'The requirement, in one sentence', 'type' => 'textarea', 'rows' => 3, 'required' => true, 'hint' => 'Do not include controlled, export-restricted or classified technical data. One sentence is enough.' ),
					array( 'name' => 'sourcing', 'label' => 'The industries you already source from', 'type' => 'text', 'required' => false, 'hint' => 'Plain sector names. This is how ConvergX works out which industries have not been looked at.' ),
					array( 'name' => 'programme-date', 'label' => 'Is it tied to a programme date', 'type' => 'select', 'required' => false, 'hint' => 'A date changes how a requirement is routed, though not whether it is read.', 'options' => array( 'Yes', 'No', 'Not yet' ) ),
				),
			),
			'apply' => array(
				'to'           => 'info@convergx.co',
				'subject'      => 'Website: application to join',
				'submit'       => 'Send application',
				'submit_class' => 'btn',
				'submit_wrap'  => false,
				'fields'  => array(
					array( 'name' => 'company', 'label' => 'Company', 'type' => 'text', 'required' => true, 'hint' => 'The legal entity applying, not a product or brand name.', 'autocomplete' => 'organization' ),
					array( 'name' => 'email', 'label' => 'Work email', 'type' => 'email', 'required' => true, 'hint' => 'An address at your company\'s own domain.', 'autocomplete' => 'email' ),
					array( 'name' => 'built', 'label' => 'What you have built', 'type' => 'textarea', 'rows' => 3, 'required' => true, 'hint' => 'What it does, in the words you would use with an engineer, rather than what it enables.' ),
					array( 'name' => 'deployed', 'label' => 'Where it is already deployed', 'type' => 'textarea', 'rows' => 3, 'required' => true, 'hint' => 'Sites, programmes or operations running it today. A description works where a name cannot be given.' ),
					array( 'name' => 'trl', 'label' => 'Is it commercialised at TRL 8 to 9', 'type' => 'select', 'required' => true, 'hint' => 'Complete and running in its real environment, in service rather than in trial. Answer it honestly. A No does not end the application: ConvergX runs a separate arm for early-stage and research work, and that is where one like it goes.', 'options' => array( 'Yes', 'No', 'Not sure' ) ),
					array( 'name' => 'sectors', 'label' => 'The industries you already serve', 'type' => 'text', 'required' => false, 'hint' => 'Plain sector names. This is how ConvergX works out which industries have never seen it.' ),
					array( 'name' => 'certs', 'label' => 'Certifications or accreditations held', 'type' => 'textarea', 'rows' => 2, 'required' => false, 'hint' => 'List them in your own words. They are recorded as you state them, and nothing on this form is validated.' ),
				),
			),
			'requirement' => array(
				'to'         => 'info@convergx.co',
				'subject'    => 'Website: requirement submitted',
				'submit'     => 'Send this to ConvergX',
				'form_class' => 'req-form',
				'fields'  => array(
					array( 'legend' => 'The problem' ),
					array( 'name' => 'kind', 'label' => 'Is this a problem or an RFP?', 'type' => 'select', 'required' => true, 'hint' => 'An RFP has a process and a clock on it, and a problem does not have one yet.', 'options' => array( 'A problem', 'An RFP', 'Not sure yet' ) ),
					array( 'name' => 'problem', 'label' => 'What is the problem?', 'type' => 'textarea', 'rows' => 8, 'required' => true, 'hint' => 'What is going wrong, or what you cannot get made. Write it the way you would say it to a colleague. Do not include controlled, export-restricted or classified technical data.' ),
					array( 'name' => 'fix', 'label' => 'What would fix it?', 'type' => 'textarea', 'rows' => 6, 'required' => true, 'hint' => 'What has to be made or done, and to what standard. Name the specification or the certification if there is one.' ),
					array( 'name' => 'due', 'label' => 'When does it have to be solved by? (optional)', 'type' => 'date', 'required' => false, 'hint' => 'The programme date or milestone this hangs on. Leave it empty if there is not one yet.' ),
					array( 'name' => 'context', 'label' => 'What else would help? (optional)', 'type' => 'textarea', 'required' => false, 'hint' => 'Why it matters now, what you have already tried, and what is in the way.' ),
					array( 'legend' => 'You' ),
					array( 'name' => 'name', 'label' => 'What is your name?', 'type' => 'text', 'required' => true, 'autocomplete' => 'name' ),
					array( 'name' => 'role', 'label' => 'What is your role?', 'type' => 'text', 'required' => true, 'hint' => 'What you are accountable for on this.' ),
					array( 'name' => 'company', 'label' => 'What company do you work for?', 'type' => 'text', 'required' => true, 'hint' => 'The legal entity, not a division or a programme name. This goes to ConvergX and nowhere else.', 'autocomplete' => 'organization' ),
					array( 'name' => 'email', 'label' => 'What is your work email?', 'type' => 'email', 'required' => true, 'hint' => 'Use your company\'s own domain.', 'autocomplete' => 'email' ),
					array( 'name' => 'industry', 'label' => 'What industry are you in?', 'type' => 'select', 'required' => true, 'hint' => 'The ten ConvergX convenes, and the answer often comes from another one of them.', 'options' => $industries ),
					array( 'name' => 'attending', 'label' => 'Are you coming to the Congress?', 'type' => 'select', 'required' => true, 'hint' => 'Sep 22 to 24, 2026, in Calgary.', 'options' => array( 'Yes', 'No', 'Not decided' ) ),
				),
			),
		)
	);
}

/**
 * Where a form's mail goes.
 *
 * Defaults to the site admin address rather than to a hardcoded convergx.co
 * inbox: on a staging clone, hardcoded recipients mean test submissions land
 * in a real person's inbox.
 *
 * @param string $key Form key.
 * @return string
 */
function convergx_form_recipient( $key ) {
	$forms = convergx_forms();

	// Each form hard-codes its recipient in its own definition, carried from
	// the static forms' action addresses (info@convergx.co everywhere except
	// sponsorship, which goes to Adam). admin_email is only the backstop for
	// a form someone adds later without one; the filter exists so a staging
	// clone can point everything at a test inbox without editing the theme.
	$to = isset( $forms[ $key ]['to'] ) ? $forms[ $key ]['to'] : get_option( 'admin_email' );

	return (string) apply_filters( 'convergx_form_recipient', $to, $key );
}

/**
 * Render a form.
 *
 * @param string $key Form key.
 */
function convergx_render_form( $key ) {
	$forms = convergx_forms();

	if ( ! isset( $forms[ $key ] ) ) {
		return;
	}

	$form = $forms[ $key ];
	$sent = isset( $_GET['cx_sent'] ) && $key === sanitize_key( wp_unslash( $_GET['cx_sent'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$err  = isset( $_GET['cx_error'] ) && $key === sanitize_key( wp_unslash( $_GET['cx_error'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$uid  = 'cx-' . $key;
	?>

	<?php
	/*
	 * The success and failure messages are aria-live so a screen reader is
	 * told the outcome. Without it the page simply reloads and a non-sighted
	 * reader has no idea whether the thing they typed went anywhere.
	 */
	?>
	<div class="form-status" aria-live="polite">
		<?php if ( $sent ) : ?>
			<p class="form-ok"><strong><?php esc_html_e( 'Thank you. That has been sent.', 'convergx' ); ?></strong></p>
		<?php elseif ( $err ) : ?>
			<p class="form-err"><strong><?php esc_html_e( 'That did not send. Please check the required fields and try again.', 'convergx' ); ?></strong></p>
		<?php endif; ?>
	</div>

	<?php if ( ! $sent ) : ?>
		<?php
		/*
		 * No intro line here: every form now renders inside a verbatim
		 * section that already carries the static page's surrounding prose,
		 * so emitting one would print the same sentence twice.
		 *
		 * The class is the static form's own (.body on the rail forms,
		 * .sponsor-form-box on the congress one, .req-form on the
		 * requirement page) because the stylesheet lays the form out by it.
		 */
		$form_class = $form['form_class'] ?? 'body';
		?>
		<form class="<?php echo esc_attr( $form_class ); ?> cx-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="convergx_form">
			<input type="hidden" name="cx_form" value="<?php echo esc_attr( $key ); ?>">
			<input type="hidden" name="cx_return" value="<?php echo esc_url( home_url( add_query_arg( array() ) ) ); ?>">
			<?php wp_nonce_field( 'convergx_form_' . $key, 'cx_nonce' ); ?>

			<?php
			/*
			 * The honeypot. Hidden from people, irresistible to naive bots.
			 * Not a substitute for the nonce, which is what actually stops a
			 * cross-site post; this only trims the volume of dumb spam so a
			 * real enquiry is not buried in it.
			 *
			 * aria-hidden + tabindex -1 keeps it out of the keyboard path and
			 * out of the accessibility tree, so no real person can fill it in
			 * by accident and get silently rejected.
			 */
			?>
			<div class="cx-hp" aria-hidden="true">
				<label for="<?php echo esc_attr( $uid ); ?>-website"><?php esc_html_e( 'Leave this field empty', 'convergx' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $uid ); ?>-website" name="cx_website" tabindex="-1" autocomplete="off">
			</div>

			<?php if ( ! empty( $form['intro'] ) ) : ?>
				<?php // A plain <p> inside the form, before the first field: the static request form sets its intro exactly there. ?>
				<p><?php echo esc_html( $form['intro'] ); ?></p>
			<?php endif; ?>

			<?php $fieldset_open = false; ?>
			<?php foreach ( $form['fields'] as $f ) : ?>
				<?php
				// A legend row opens a fieldset, closing the previous one:
				// the requirement form groups its questions under "The
				// problem" and "You" exactly as the static page does.
				if ( isset( $f['legend'] ) ) {
					if ( $fieldset_open ) {
						echo '</fieldset>';
					}
					echo '<fieldset><legend>' . esc_html( $f['legend'] ) . '</legend>';
					$fieldset_open = true;
					continue;
				}

				$fid  = $uid . '-' . $f['name'];
				$req  = ! empty( $f['required'] );
				$auto = isset( $f['autocomplete'] ) ? $f['autocomplete'] : '';
				?>
				<div class="field">
					<?php
					// "(optional)" lives in the label text where the static
					// labels carry it; no extra marker span is added.
					?>
					<label for="<?php echo esc_attr( $fid ); ?>"><?php echo esc_html( $f['label'] ); ?></label>

					<?php
					/*
					 * THE HINT IS NOT DECORATION AND IT IS NOT OPTIONAL.
					 *
					 * These lines carry the instructions that make an answer
					 * usable ("the legal entity, not a division") and, on the
					 * access and requirement forms, the export-control warning:
					 * "Do not include controlled, export-restricted or
					 * classified technical data." Dropping that one moves a real
					 * compliance risk onto a stranger typing into a text box.
					 *
					 * It renders BEFORE the control and is wired to it with
					 * aria-describedby, so a screen reader announces the caveat
					 * as part of the field rather than after the person has
					 * already answered.
					 */
					$hint    = isset( $f['hint'] ) ? $f['hint'] : '';
					$hint_id = $hint ? $fid . '-hint' : '';
					?>
					<?php if ( $hint ) : ?>
						<?php // p.helper is the static site's own hint element and class. ?>
						<p class="helper" id="<?php echo esc_attr( $hint_id ); ?>"><?php echo esc_html( $hint ); ?></p>
					<?php endif; ?>

					<?php if ( 'textarea' === $f['type'] ) : ?>
						<textarea id="<?php echo esc_attr( $fid ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>" rows="<?php echo esc_attr( $f['rows'] ?? 5 ); ?>" <?php echo $hint_id ? 'aria-describedby="' . esc_attr( $hint_id ) . '"' : ''; ?> <?php echo $req ? 'required' : ''; ?>></textarea>

					<?php elseif ( 'select' === $f['type'] ) : ?>
						<select id="<?php echo esc_attr( $fid ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>" <?php echo $hint_id ? 'aria-describedby="' . esc_attr( $hint_id ) . '"' : ''; ?> <?php echo $req ? 'required' : ''; ?>>
							<option value=""><?php esc_html_e( 'Choose one', 'convergx' ); ?></option>
							<?php foreach ( (array) $f['options'] as $opt ) : ?>
								<option value="<?php echo esc_attr( $opt ); ?>"><?php echo esc_html( $opt ); ?></option>
							<?php endforeach; ?>
						</select>

					<?php else : ?>
						<input
							type="<?php echo esc_attr( $f['type'] ); ?>"
							id="<?php echo esc_attr( $fid ); ?>"
							name="<?php echo esc_attr( $f['name'] ); ?>"
							<?php echo $auto ? 'autocomplete="' . esc_attr( $auto ) . '"' : ''; ?>
							<?php echo $hint_id ? 'aria-describedby="' . esc_attr( $hint_id ) . '"' : ''; ?>
							<?php echo $req ? 'required' : ''; ?>>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
			<?php if ( $fieldset_open ) : ?></fieldset><?php endif; ?>

			<?php
			/*
			 * Button text, class and wrapper all come from the form's own
			 * definition, because the static pages differ: the contact,
			 * sponsor and requirement forms wrap a solid button in a <p>,
			 * while apply and request set a plain .btn bare before </form>.
			 */
			$submit_class = $form['submit_class'] ?? 'btn btn--solid';
			$submit_wrap  = $form['submit_wrap'] ?? true;
			?>
			<?php if ( $submit_wrap ) : ?><p><?php endif; ?><button class="<?php echo esc_attr( $submit_class ); ?>" type="submit"><?php echo esc_html( $form['submit'] ); ?></button><?php if ( $submit_wrap ) : ?></p><?php endif; ?>
		</form>
	<?php endif; ?>
	<?php
}

/**
 * Handle a submission.
 *
 * Store first, mail second. See the file header.
 */
function convergx_handle_form() {
	$key   = isset( $_POST['cx_form'] ) ? sanitize_key( wp_unslash( $_POST['cx_form'] ) ) : '';
	$forms = convergx_forms();

	$return = isset( $_POST['cx_return'] ) ? esc_url_raw( wp_unslash( $_POST['cx_return'] ) ) : home_url( '/' );

	// Only ever redirect back into this site, whatever was posted.
	if ( 0 !== strpos( $return, home_url() ) ) {
		$return = home_url( '/' );
	}

	$fail = add_query_arg( 'cx_error', $key, $return );

	if ( ! $key || ! isset( $forms[ $key ] ) ) {
		wp_safe_redirect( $fail );
		exit;
	}

	// The nonce is what actually stops a cross-site post.
	if ( ! isset( $_POST['cx_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cx_nonce'] ) ), 'convergx_form_' . $key ) ) {
		wp_safe_redirect( $fail );
		exit;
	}

	// Honeypot. Silently accept and drop, so a bot is not told why it failed.
	if ( ! empty( $_POST['cx_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'cx_sent', $key, $return ) );
		exit;
	}

	$form   = $forms[ $key ];
	$values = array();

	foreach ( $form['fields'] as $f ) {
		$raw = isset( $_POST[ $f['name'] ] ) ? wp_unslash( $_POST[ $f['name'] ] ) : '';

		if ( 'email' === $f['type'] ) {
			$val = sanitize_email( $raw );
			// A required email that does not survive sanitising is a failure,
			// not an empty string to be stored and puzzled over later.
			if ( ! empty( $f['required'] ) && ! is_email( $val ) ) {
				wp_safe_redirect( $fail );
				exit;
			}
		} elseif ( 'textarea' === $f['type'] ) {
			$val = sanitize_textarea_field( $raw );
		} elseif ( 'select' === $f['type'] ) {
			$val = sanitize_text_field( $raw );
			// Only values this form actually offers. Stops a crafted post from
			// storing arbitrary text in a field the page presents as a choice.
			if ( '' !== $val && ! in_array( $val, (array) $f['options'], true ) ) {
				wp_safe_redirect( $fail );
				exit;
			}
		} else {
			$val = sanitize_text_field( $raw );
		}

		if ( ! empty( $f['required'] ) && '' === trim( (string) $val ) ) {
			wp_safe_redirect( $fail );
			exit;
		}

		$values[ $f['name'] ] = $val;
	}

	// ---- STORE FIRST. Email is the copy that can fail. ----
	$who   = isset( $values['name'] ) ? $values['name'] : ( isset( $values['company'] ) ? $values['company'] : ( isset( $values['org'] ) ? $values['org'] : __( 'Unknown', 'convergx' ) ) );
	$title = sprintf( '%s — %s', $form['subject'], $who );

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'cx_submission',
			'post_status' => 'publish',
			'post_title'  => wp_strip_all_tags( $title ),
		),
		true
	);

	if ( ! is_wp_error( $post_id ) && $post_id ) {
		foreach ( $values as $k => $v ) {
			update_post_meta( $post_id, 'cx_' . $k, $v );
		}
		update_post_meta( $post_id, 'cx_form_key', $key );
		update_post_meta( $post_id, 'cx_ip_hash', wp_hash( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' ) );
	}

	// ---- THEN MAIL. A failure here no longer loses the enquiry. ----
	$lines = array();
	foreach ( $form['fields'] as $f ) {
		$lines[] = $f['label'] . ': ' . ( '' === $values[ $f['name'] ] ? '—' : $values[ $f['name'] ] );
	}
	$lines[] = '';
	$lines[] = sprintf( 'Sent from %s', $return );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

	// Reply-To, never From. Sending AS the visitor's address is what gets a
	// site's mail marked as spoofed and dropped by SPF and DMARC.
	if ( ! empty( $values['email'] ) && is_email( $values['email'] ) ) {
		$headers[] = 'Reply-To: ' . $values['email'];
	}

	wp_mail( convergx_form_recipient( $key ), $form['subject'], implode( "\n", $lines ), $headers );

	wp_safe_redirect( add_query_arg( 'cx_sent', $key, $return ) );
	exit;
}
add_action( 'admin_post_nopriv_convergx_form', 'convergx_handle_form' );
add_action( 'admin_post_convergx_form', 'convergx_handle_form' );

/**
 * Show the submitted values on the submission edit screen.
 *
 * Without this the post type stores everything in meta and shows a title and
 * nothing else, which makes the store-first design useless in practice.
 */
function convergx_submission_metabox() {
	add_meta_box(
		'cx_submission_values',
		__( 'Submitted', 'convergx' ),
		function ( $post ) {
			$key   = get_post_meta( $post->ID, 'cx_form_key', true );
			$forms = convergx_forms();
			$flds  = isset( $forms[ $key ] ) ? $forms[ $key ]['fields'] : array();

			echo '<table class="widefat striped"><tbody>';
			foreach ( $flds as $f ) {
				$v = get_post_meta( $post->ID, 'cx_' . $f['name'], true );
				printf(
					'<tr><th style="width:16rem;text-align:left;">%s</th><td>%s</td></tr>',
					esc_html( $f['label'] ),
					'' === $v ? '<em>—</em>' : nl2br( esc_html( $v ) )
				);
			}
			echo '</tbody></table>';
		},
		'cx_submission',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'convergx_submission_metabox' );

/**
 * A shortcode, so a form can also be dropped into any page's content.
 */
function convergx_form_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'id' => 'contact' ), $atts, 'convergx_form' );

	ob_start();
	convergx_render_form( sanitize_key( $atts['id'] ) );

	return ob_get_clean();
}
add_shortcode( 'convergx_form', 'convergx_form_shortcode' );

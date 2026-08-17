<?php defined( 'ABSPATH' ) || exit; ?>
<section>
	<div class="wrap">
		<?php convergx_section_head( get_sub_field( 'heading' ) ); ?>
		<?php if ( have_rows( 'rows' ) ) : ?>
			<ul class="proofbar">
				<?php
				while ( have_rows( 'rows' ) ) :
					the_row();
					// CLEARANCE IS PER MARK. An uncleared mark does not render.
					// Another organisation's logo is their property and showing it
					// implies a relationship. Unticked is the safe default.
					if ( ! get_sub_field( 'cleared' ) ) {
						continue;
					}
					$mark = get_sub_field( 'mark' );
					if ( ! $mark ) {
						continue;
					}
					?>
					<li class="proofbar-item">
						<?php
						echo wp_get_attachment_image(
							$mark,
							'medium',
							false,
							array(
								'class'   => 'proofbar-mark',
								'alt'     => get_sub_field( 'label' ),
								'loading' => 'lazy',
							)
						);
						?>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>

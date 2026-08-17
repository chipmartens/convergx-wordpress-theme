<?php defined( 'ABSPATH' ) || exit; ?>
<section>
	<div class="wrap">
		<?php convergx_section_head( get_sub_field( 'heading' ) ); ?>
		<?php if ( have_rows( 'rows' ) ) : ?>
			<ul class="people">
				<?php
				while ( have_rows( 'rows' ) ) :
					the_row();
					$photo = get_sub_field( 'photo' );
					?>
					<li class="person">
						<?php if ( $photo ) : ?>
							<?php echo wp_get_attachment_image( $photo, 'medium', false, array( 'class' => 'person-photo', 'loading' => 'lazy' ) ); ?>
						<?php endif; ?>
						<h3 class="person-name"><?php echo esc_html( get_sub_field( 'name' ) ); ?></h3>
						<?php if ( get_sub_field( 'role' ) ) : ?>
							<p class="person-role"><?php echo esc_html( get_sub_field( 'role' ) ); ?></p>
						<?php endif; ?>
						<?php if ( get_sub_field( 'org' ) ) : ?>
							<p class="person-org"><?php echo esc_html( get_sub_field( 'org' ) ); ?></p>
						<?php endif; ?>
						<?php if ( get_sub_field( 'bio' ) ) : ?>
							<div class="person-bio"><p><?php echo esc_html( get_sub_field( 'bio' ) ); ?></p></div>
						<?php endif; ?>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>

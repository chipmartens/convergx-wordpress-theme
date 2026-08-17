<?php defined( 'ABSPATH' ) || exit; ?>
<section>
	<div class="wrap">
		<div class="editorial">
			<?php if ( get_sub_field( 'heading' ) ) : ?>
				<h2><?php echo esc_html( get_sub_field( 'heading' ) ); ?></h2>
			<?php endif; ?>
			<?php if ( get_sub_field( 'body' ) ) : ?>
				<div class="lede"><p><?php echo esc_html( get_sub_field( 'body' ) ); ?></p></div>
			<?php endif; ?>
			<?php if ( get_sub_field( 'url' ) && get_sub_field( 'label' ) ) : ?>
				<p class="store-act">
					<a class="btn btn--solid" href="<?php echo esc_url( get_sub_field( 'url' ) ); ?>"><?php echo esc_html( get_sub_field( 'label' ) ); ?></a>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
defined( 'ABSPATH' ) || exit;
$svg = convergx_figure( get_sub_field( 'slug' ) );
if ( ! $svg ) {
	return;
}
?>
<section>
	<div class="wrap">
		<figure class="fig">
			<?php
			// Inlined from the theme's own assets/fig directory only, path-checked
			// in convergx_figure(). Not user-supplied markup, so not kses'd: kses
			// would strip the SVG geometry this whole section exists to render.
			echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			<?php if ( get_sub_field( 'caption' ) ) : ?>
				<figcaption class="fig-cap"><?php echo esc_html( get_sub_field( 'caption' ) ); ?></figcaption>
			<?php endif; ?>
		</figure>
	</div>
</section>

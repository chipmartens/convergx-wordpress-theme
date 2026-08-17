<?php
/**
 * A form section.
 *
 * The form itself is defined in inc/forms.php, not here and not in a field:
 * each of these asks a specific set of questions and the wording of a question
 * IS the question. What an editor controls is the framing around it.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$key = (string) get_sub_field( 'form_key' );
if ( ! $key ) {
	return;
}
?>
<section id="<?php echo esc_attr( 'form-' . $key ); ?>" class="section--dense">
	<div class="wrap">
		<?php convergx_section_head( get_sub_field( 'heading' ), get_sub_field( 'eyebrow' ) ); ?>
		<div class="editorial">
			<?php if ( get_sub_field( 'lede' ) ) : ?>
				<div class="lede"><p><?php echo esc_html( get_sub_field( 'lede' ) ); ?></p></div>
			<?php endif; ?>
			<?php convergx_render_form( $key ); ?>
		</div>
	</div>
</section>

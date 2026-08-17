<?php
/**
 * The speakers section and its bio overlays.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$convergx_sp = convergx_speakers();

if ( ! $convergx_sp['all'] ) {
	return;
}

/**
 * Render one speaker card.
 *
 * @param array $s       Speaker row.
 * @param bool  $feature Whether it renders in the feature row.
 */
function convergx_speaker_card( $s, $feature = false ) {
	$class = 'speaker-card' . ( $feature ? ' speaker-card--feature' : '' );
	?>
	<article class="<?php echo esc_attr( $class ); ?>" id="speaker-<?php echo esc_attr( $s['slug'] ); ?>">
		<?php
		if ( ! empty( $s['photo'] ) ) {
			echo wp_get_attachment_image(
				$s['photo'],
				'medium_large',
				false,
				array(
					'class'    => 'speaker-card-photo',
					'alt'      => $s['photo_alt'],
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
		}
		?>

		<?php if ( ! empty( $s['billing'] ) ) : ?>
			<p class="label speaker-card-billing"><?php echo esc_html( $s['billing'] ); ?></p>
		<?php endif; ?>

		<p class="speaker-card-name">
			<?php if ( ! empty( $s['bio'] ) ) : ?>
				<a class="bio-trigger" href="#bio-<?php echo esc_attr( $s['slug'] ); ?>"><?php echo esc_html( $s['name'] ); ?></a>
			<?php else : ?>
				<?php
				// NO BIO, NO TRIGGER. A .bio-trigger pointing at an overlay that
				// was never rendered is a link that silently does nothing. The
				// name renders as plain text instead, which is honest.
				echo esc_html( $s['name'] );
				?>
			<?php endif; ?>
		</p>

		<?php if ( ! empty( $s['role'] ) ) : ?>
			<p class="speaker-card-role"><?php echo esc_html( $s['role'] ); ?></p>
		<?php endif; ?>
	</article>
	<?php
}
?>

<section id="speakers">
	<div class="wrap">
		<?php convergx_section_head( convergx_field( 'speakers_heading', null, __( 'The speakers', 'convergx' ) ), convergx_field( 'speakers_eyebrow' ) ); ?>

		<?php if ( $convergx_sp['feature'] ) : ?>
			<div class="speaker-feature">
				<?php foreach ( $convergx_sp['feature'] as $s ) : ?>
					<?php convergx_speaker_card( $s, true ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $convergx_sp['grid'] ) : ?>
			<div class="speaker-grid">
				<?php foreach ( $convergx_sp['grid'] as $s ) : ?>
					<?php convergx_speaker_card( $s, false ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
/*
 * THE BIO OVERLAYS.
 *
 * Rendered outside the speakers section, at the end of the page, because they
 * are dialogs rather than page content. Each one's close link points back at
 * its own card rather than at "#", so dismissing an overlay returns the reader
 * to the speaker they opened, which is also what returns keyboard focus.
 *
 * Every id here is derived, never typed. See convergx_speaker_slug().
 */
foreach ( $convergx_sp['all'] as $s ) :
	if ( empty( $s['bio'] ) ) {
		continue;
	}
	?>
	<div class="bio-overlay" id="bio-<?php echo esc_attr( $s['slug'] ); ?>" data-surface="dark" role="dialog" aria-modal="true" aria-labelledby="bio-<?php echo esc_attr( $s['slug'] ); ?>-name">
		<div class="bio-head">
			<a class="bio-close" href="#speaker-<?php echo esc_attr( $s['slug'] ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" aria-hidden="true" focusable="false"><path d="M4 4 L20 20 M20 4 L4 20"></path></svg>
				<span class="vh"><?php esc_html_e( 'Close', 'convergx' ); ?></span>
			</a>
		</div>
		<div class="bio-scroll">
			<div class="wrap">
				<div class="bio-figure">
					<?php
					if ( ! empty( $s['photo'] ) ) {
						echo wp_get_attachment_image(
							$s['photo'],
							'large',
							false,
							array(
								'class'    => 'bio-photo',
								'alt'      => $s['photo_alt'],
								'loading'  => 'lazy',
								'decoding' => 'async',
							)
						);
					}
					?>
					<div class="bio-text">
						<h2 class="bio-name" id="bio-<?php echo esc_attr( $s['slug'] ); ?>-name"><?php echo esc_html( $s['name'] ); ?></h2>
						<?php if ( ! empty( $s['bio_role'] ) ) : ?>
							<p class="bio-role"><?php echo esc_html( $s['bio_role'] ); ?></p>
						<?php endif; ?>
						<div class="bio-prose"><?php echo wp_kses_post( $s['bio'] ); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
endforeach;

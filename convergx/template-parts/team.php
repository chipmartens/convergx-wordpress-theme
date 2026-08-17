<?php
/**
 * The leadership team, and every bio overlay for it.
 *
 * Two featured members in .team-list above the rest in .team-grid, exactly as
 * the speakers section splits. An editor promotes someone with a checkbox
 * rather than by moving them between two lists.
 *
 * Cards are `#card-{slug}` here and `#speaker-{slug}` on the Congress page.
 * Kimberley Van Vliet is in both sets, so a single prefix would give her two
 * elements with one id. See convergx_people().
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$convergx_team = convergx_people();

if ( ! $convergx_team['all'] ) {
	return;
}

/**
 * One team card.
 *
 * @param array $p Person row.
 */
function convergx_team_card( $p ) {
	?>
	<article class="bio-figure team-member" id="card-<?php echo esc_attr( $p['slug'] ); ?>">
		<?php
		if ( ! empty( $p['photo'] ) ) {
			echo wp_get_attachment_image(
				$p['photo'],
				'medium_large',
				false,
				array(
					'class'    => 'bio-photo',
					'alt'      => $p['photo_alt'],
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
		}
		?>
		<div class="bio-text">
			<h3 class="bio-name"><?php echo esc_html( $p['name'] ); ?></h3>

			<?php if ( $p['role'] || $p['org'] ) : ?>
				<?php
				// Title and organisation on separate lines, which is how the
				// static cards set them. A <br> rather than two paragraphs so
				// the pair stays one block for spacing.
				?>
				<p class="bio-role">
					<?php echo esc_html( $p['role'] ); ?>
					<?php if ( $p['role'] && $p['org'] ) : ?><br><?php endif; ?>
					<?php echo esc_html( $p['org'] ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $p['bio'] ) : ?>
				<p class="push-s">
					<a class="bio-trigger" href="#bio-<?php echo esc_attr( $p['slug'] ); ?>"><?php esc_html_e( 'View full bio', 'convergx' ); ?></a>
				</p>
			<?php endif; ?>
		</div>
	</article>
	<?php
}
?>

<section id="leadership">
	<div class="wrap">
		<?php convergx_section_head( __( 'Leadership', 'convergx' ), __( 'Who we are', 'convergx' ) ); ?>

		<?php if ( $convergx_team['feature'] ) : ?>
			<div class="team-list">
				<?php foreach ( $convergx_team['feature'] as $p ) : ?>
					<?php convergx_team_card( $p ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $convergx_team['grid'] ) : ?>
			<div class="team-grid">
				<?php foreach ( $convergx_team['grid'] as $p ) : ?>
					<?php convergx_team_card( $p ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
// The overlays, outside the section: they are dialogs, not page content. The
// close link returns to the person's own card, which is what returns focus.
foreach ( $convergx_team['all'] as $p ) :
	if ( ! $p['bio'] ) {
		continue;
	}
	?>
	<div class="bio-overlay" id="bio-<?php echo esc_attr( $p['slug'] ); ?>" data-surface="dark" role="dialog" aria-modal="true" aria-labelledby="bio-<?php echo esc_attr( $p['slug'] ); ?>-name">
		<div class="bio-head">
			<a class="bio-close" href="#card-<?php echo esc_attr( $p['slug'] ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" aria-hidden="true" focusable="false"><path d="M4 4 L20 20 M20 4 L4 20"></path></svg>
				<span class="vh"><?php esc_html_e( 'Close', 'convergx' ); ?></span>
			</a>
		</div>
		<div class="bio-scroll">
			<div class="wrap">
				<div class="bio-figure">
					<?php
					if ( ! empty( $p['photo'] ) ) {
						echo wp_get_attachment_image(
							$p['photo'],
							'large',
							false,
							array( 'class' => 'bio-photo', 'alt' => $p['photo_alt'], 'loading' => 'lazy', 'decoding' => 'async' )
						);
					}
					?>
					<div class="bio-text">
						<h2 class="bio-name" id="bio-<?php echo esc_attr( $p['slug'] ); ?>-name"><?php echo esc_html( $p['name'] ); ?></h2>
						<?php if ( $p['role'] || $p['org'] ) : ?>
							<p class="bio-role">
								<?php echo esc_html( trim( $p['role'] . ( $p['role'] && $p['org'] ? ', ' : '' ) . $p['org'] ) ); ?>
							</p>
						<?php endif; ?>
						<div class="bio-prose"><?php echo wp_kses_post( $p['bio'] ); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
endforeach;

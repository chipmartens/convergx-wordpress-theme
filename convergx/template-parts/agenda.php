<?php
/**
 * The agenda.
 *
 * TWO LEVELS OF DISCLOSURE, and the nesting is deliberate. Each DAY is a
 * disclosure so the page does not open as three full timetables. Inside a day,
 * a SESSION becomes its own disclosure only when it has detail worth opening.
 *
 * That conditional is the whole rule: if every row opened, the affordance would
 * stop meaning "there is more here" and become decoration on every line. A row
 * with no detail is a plain line, and that contrast is what makes the ones that
 * do open worth clicking.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$days = convergx_field( 'agenda_days', null, array() );
if ( ! $days ) {
	return;
}
?>
<section id="agenda" class="section--dense">
	<div class="wrap">
		<?php convergx_section_head( __( 'The agenda', 'convergx' ), __( 'Three days', 'convergx' ) ); ?>

		<div class="mod-faq-list">
			<?php foreach ( $days as $day ) : ?>
				<?php
				$title   = isset( $day['title'] ) ? $day['title'] : '';
				$caption = ! empty( $day['caption'] ) ? $day['caption'] : $title;
				$rows    = isset( $day['rows'] ) ? (array) $day['rows'] : array();
				if ( ! $title || ! $rows ) {
					continue;
				}
				?>
				<details class="faq">
					<summary><span class="day-title"><?php echo esc_html( $title ); ?></span></summary>
					<div class="faq-a">
						<table>
							<?php // Visually hidden: the day is already stated in the summary above. ?>
							<caption class="label vh"><?php echo esc_html( $caption ); ?></caption>
							<thead>
								<tr>
									<th scope="col"><?php esc_html_e( 'Time', 'convergx' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Session', 'convergx' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $rows as $row ) : ?>
									<?php
									$time    = isset( $row['time'] ) ? $row['time'] : '';
									$session = isset( $row['session'] ) ? $row['session'] : '';
									$detail  = isset( $row['detail'] ) ? trim( (string) $row['detail'] ) : '';
									if ( ! $session ) {
										continue;
									}
									?>
									<tr>
										<th scope="row"><?php echo esc_html( $time ); ?></th>
										<td>
											<?php if ( $detail ) : ?>
												<details class="sess">
													<summary><?php echo esc_html( $session ); ?></summary>
													<?php foreach ( preg_split( '/\R+/', $detail, -1, PREG_SPLIT_NO_EMPTY ) as $para ) : ?>
														<p><?php echo wp_kses( trim( $para ), array( 'a' => array( 'href' => array(), 'class' => array() ), 'em' => array(), 'strong' => array() ) ); ?></p>
													<?php endforeach; ?>
												</details>
											<?php else : ?>
												<?php echo esc_html( $session ); ?>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

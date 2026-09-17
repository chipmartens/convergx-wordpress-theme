<?php
/**
 * Congress: The Congress app
 *
 * Progressive enhancement: without JS every panel and capture is visible.
 * cxapp.js turns it into a one-at-a-time carousel.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$convergx_app = CONVERGX_URI . '/assets/img/app';
?>
<section id="app" class="app-band">
	<div class="wrap">
		<!-- THE TITLE SITS ABOVE THE SPLIT, not in the left column. Chip,
		     2026-09-14: the capture should stand beside the feature list, not
		     beside the title. In the column it paired the screenshot with the
		     heading and left the panels reading against empty ground. -->
		<div class="cxapp-head">
			<span class="label label--lo">At the Congress</span>
			<h2 class="cxapp-title">The Congress app</h2>
			<p class="app-sub">Everything for the three days in Calgary, on the phone you are already carrying</p>
		</div>

		<div class="app-inner cxapp-inner">

			<div class="app-copy">
				<ul class="cxapp-tabs">
					<li class="cxapp-tab" data-view="home">
						<h3 class="cxapp-tab-head">
							<button type="button" id="cxapp-tab-home" aria-expanded="true" aria-controls="cxapp-panel-home">
								See what's happening now
							</button>
						</h3>
						<span class="cxapp-progress" aria-hidden="true"><span></span></span>
						<div class="cxapp-tab-body" id="cxapp-panel-home" role="region" aria-labelledby="cxapp-tab-home">
							<p>The session happening next, how many of your own are still to come, and anything waiting on you. On a full day it is often the only screen you open.</p>
						</div>
					</li>

					<li class="cxapp-tab" data-view="agenda">
						<h3 class="cxapp-tab-head">
							<button type="button" id="cxapp-tab-agenda" aria-expanded="true" aria-controls="cxapp-panel-agenda">
								Browse the full three-day schedule
							</button>
						</h3>
						<span class="cxapp-progress" aria-hidden="true"><span></span></span>
						<div class="cxapp-tab-body" id="cxapp-panel-agenda" role="region" aria-labelledby="cxapp-tab-agenda">
							<p>Every session, speaker, track and room for the three days, with a My agenda view of the ones you have chosen. When the organisers move a session the agenda updates itself, and the meetings ConvergX arranges for you sit alongside it.</p>
						</div>
					</li>

					<li class="cxapp-tab" data-view="board">
						<h3 class="cxapp-tab-head">
							<button type="button" id="cxapp-tab-board" aria-expanded="true" aria-controls="cxapp-panel-board">
								Post what you need
							</button>
						</h3>
						<span class="cxapp-progress" aria-hidden="true"><span></span></span>
						<div class="cxapp-tab-body" id="cxapp-panel-board" role="region" aria-labelledby="cxapp-tab-board">
							<p>Post a problem, a project or a need without putting your name on it. Posts are anonymous to other delegates, and ConvergX alone holds the name behind one. Another delegate answers with Participate, Feedback or Partnership, and ConvergX brokers the introduction from there.</p>
						</div>
					</li>

					<li class="cxapp-tab" data-view="alerts">
						<h3 class="cxapp-tab-head">
							<button type="button" id="cxapp-tab-alerts" aria-expanded="true" aria-controls="cxapp-panel-alerts">
								Know when something changes
							</button>
						</h3>
						<span class="cxapp-progress" aria-hidden="true"><span></span></span>
						<div class="cxapp-tab-body" id="cxapp-panel-alerts" role="region" aria-labelledby="cxapp-tab-alerts">
							<p>A rescheduled session, interest in one of your posts, or a follow-up from an organiser arrives as an alert. The alert itself carries no content, so nothing about your posts leaves the app.</p>
						</div>
					</li>
				</ul>

				<p class="push-s"><a class="btn btn--solid" href="<?php echo esc_url( home_url( '/congress/register/' ) ); ?>">Register for the Congress</a></p>
			</div>

			<div class="cxapp-stage">
				<figure class="cxapp-shot" data-view="home">
					<picture>
						<source srcset="<?php echo esc_url( $convergx_app . '/app-home.webp' ); ?>" type="image/webp">
						<img src="<?php echo esc_url( $convergx_app . '/app-home.png' ); ?>" width="880" height="742" loading="lazy" decoding="async" alt="The Congress app home screen: the session happening next, counters for your sessions, unread alerts and your posts, and a card reporting new interest in one of your posts">
					</picture>
					<figcaption>Home</figcaption>
				</figure>
				<figure class="cxapp-shot" data-view="agenda">
					<picture>
						<source srcset="<?php echo esc_url( $convergx_app . '/app-agenda.webp' ); ?>" type="image/webp">
						<img src="<?php echo esc_url( $convergx_app . '/app-agenda.png' ); ?>" width="880" height="1239" loading="lazy" decoding="async" alt="The Congress app agenda: Day 1 sessions with times, tracks, rooms and RSVP state">
					</picture>
					<figcaption>Agenda</figcaption>
				</figure>
				<figure class="cxapp-shot" data-view="board">
					<picture>
						<source srcset="<?php echo esc_url( $convergx_app . '/app-board.webp' ); ?>" type="image/webp">
						<img src="<?php echo esc_url( $convergx_app . '/app-board.png' ); ?>" width="880" height="1725" loading="lazy" decoding="async" alt="The Opportunity Board: anonymous posts tagged Problem, Project or Need, each with Participate, Feedback and Partnership buttons">
					</picture>
					<figcaption>Opportunity Board</figcaption>
				</figure>
				<figure class="cxapp-shot" data-view="alerts">
					<picture>
						<source srcset="<?php echo esc_url( $convergx_app . '/app-alerts.webp' ); ?>" type="image/webp">
						<img src="<?php echo esc_url( $convergx_app . '/app-alerts.png' ); ?>" width="880" height="1193" loading="lazy" decoding="async" alt="Alerts: new interest in a post, and a follow-up from an organiser">
					</picture>
					<figcaption>Alerts</figcaption>
				</figure>
			</div>

		</div>
	</div>
</section>

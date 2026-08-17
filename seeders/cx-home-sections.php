<?php
/** Seed the homepage editorial run between the launchers and the flow band. */
$fid = (int) get_option( 'page_on_front' );
if ( ! $fid ) { WP_CLI::error( 'no front page set' ); }

update_field( 'sections', array(
    array(
        'acf_fc_layout' => 'editorial',
        'heading' => 'What ConvergX is',
        'eyebrow' => 'The room',
        'lede'    => 'ConvergX convenes decision makers across industries to solve problems they did not know they shared.',
        'body'    => '<p>Attendees are individually vetted, VP level or higher. What happens in the room is curated meetings, focused discussions, receptions and industry showcases across three days.</p>',
        'dense'   => 0,
    ),
    array(
        'acf_fc_layout' => 'editorial',
        'heading' => 'ConvergX Xpand',
        'eyebrow' => 'Consulting',
        'say'     => 'ConvergX Xpand helps organizations identify, adapt, and deploy proven solutions from outside their own industry.',
        'body'    => '<p>The work runs from finding the capability through to the last mile of deployment, which is where most cross-sector transfers stall.</p>',
        'dense'   => 1,
    ),
), $fid );

WP_CLI::success( "homepage sections seeded on post {$fid}" );

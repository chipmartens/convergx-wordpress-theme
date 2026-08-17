<?php
/**
 * Exact section: the static site's outer HTML, stored verbatim.
 *
 * DELIBERATELY UNFILTERED OUTPUT. This field holds the launch site's own
 * markup: section ids the stylesheet keys on ([aria-labelledby="whatcx-h"],
 * #our-process), inline p.say mid-body, link-index lists inside .body,
 * details/summary claims. Every sanitiser pass we ran stripped one of those
 * and produced a visible layout bug. Only administrators can edit the field,
 * which is the same trust WordPress already extends via unfiltered_html.
 *
 * Two tokens resolve at render time, so the stored HTML stays portable
 * between the local install and production:
 *   {{ASSETS}}    -> the theme's assets directory URL
 *   {{FORM:key}}  -> convergx_render_form( key ), so the prose around a form
 *                    is byte-exact while the form itself stays the native
 *                    store-first/mail-second implementation.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

$convergx_html = (string) get_sub_field( 'html' );

if ( '' === trim( $convergx_html ) ) {
	return;
}

$convergx_html = str_replace( '{{ASSETS}}', CONVERGX_URI . '/assets', $convergx_html );

if ( false !== strpos( $convergx_html, '{{FORM:' ) ) {
	$convergx_html = preg_replace_callback(
		'/\{\{FORM:([a-z-]+)\}\}/',
		function ( $m ) {
			ob_start();
			convergx_render_form( $m[1] );
			return (string) ob_get_clean();
		},
		$convergx_html
	);
}

echo $convergx_html; // phpcs:ignore WordPress.Security.EscapeOutput -- verbatim admin-authored markup, see header.

<?php
/**
 * Site header.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
/*
 * NO TITLE TAG, NO DESCRIPTION META, NO ROBOTS META IS WRITTEN HERE.
 *
 * The static source pages each hand-wrote all three, including
 * `noindex, nofollow, noarchive`, which is a staging posture. Ported as-is it
 * would deindex the live domain and collide with All in One SEO, which is
 * active on convergx.co and emits its own. Two robots tags means the crawler
 * honours the most restrictive one.
 *
 * add_theme_support('title-tag') plus wp_head() hands the whole head over to
 * WordPress and the SEO plugin. See also convergx_robots_are_not_ours().
 */
wp_head();
?>
</head>
<?php $convergx_hero = convergx_hero(); ?>
<body <?php body_class(); ?> data-surface="<?php echo esc_attr( convergx_surface() ); ?>"<?php echo $convergx_hero ? ' data-hero="' . esc_attr( $convergx_hero ) . '"' : ''; ?>>
<?php wp_body_open(); ?>

<a class="skip" href="#main"><?php esc_html_e( 'Skip to content', 'convergx' ); ?></a>

<header data-shell="header">
	<noscript>
		<?php
		/*
		 * ONE no-JS fallback for the whole site.
		 *
		 * The static tree carried this block on all 24 pages with per-page-depth
		 * relative paths, and its own comments admit the copies drift and "must
		 * not be compared as if they were identical". Here it exists once, and
		 * the paths are absolute because WordPress knows its own home URL.
		 *
		 * The notice bar is NOT reproduced here. In shell.js it self-removes
		 * after 2026-09-25; hardcoded in a noscript block it cannot, and a
		 * "Celebrate 10 years, Sep 22 to 24" bar still showing in November is
		 * worse than no bar for the few visitors without JS.
		 */
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => 'nav',
					'container_aria_label' => __( 'Primary', 'convergx' ),
					'depth'          => 2,
				)
			);
		} else {
			?>
			<nav aria-label="<?php esc_attr_e( 'Primary', 'convergx' ); ?>">
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/congress/' ) ); ?>"><?php esc_html_e( 'Congresses', 'convergx' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/xpand/' ) ); ?>"><?php esc_html_e( 'Consulting', 'convergx' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'convergx' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/congress/register/' ) ); ?>"><?php esc_html_e( 'Attend the Congress', 'convergx' ); ?></a></li>
				</ul>
			</nav>
			<?php
		}
		?>
	</noscript>
</header>

<main id="main">

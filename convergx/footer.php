<?php
/**
 * Site footer.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer data-shell="footer">
	<noscript>
		<?php
		// shell.js builds the real footer from the same nav data it builds the
		// header from, so the two can never disagree. This is the no-JS floor.
		?>
		<nav aria-label="<?php esc_attr_e( 'Footer', 'convergx' ); ?>">
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/congress/' ) ); ?>"><?php esc_html_e( 'The Congress', 'convergx' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/congress/register/' ) ); ?>"><?php esc_html_e( 'Register', 'convergx' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/xpand/' ) ); ?>"><?php esc_html_e( 'Consulting', 'convergx' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'convergx' ); ?></a></li>
			</ul>
		</nav>
	</noscript>
</footer>

<?php wp_footer(); ?>
</body>
</html>

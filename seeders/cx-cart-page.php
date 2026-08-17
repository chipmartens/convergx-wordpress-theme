<?php
/**
 * Trim the cart page's empty state.
 *
 * WooCommerce's stock empty-cart template ends in a "New in store" heading
 * over a four-product showcase. This store sells three congress passes, so
 * that block rendered the same passes the reader just declined to keep,
 * under a merchandising headline the site's voice would never use. The
 * empty state keeps Woo's own notice and sends the reader back to the one
 * place cart traffic comes from: registration.
 *
 * Idempotent: matches the stock markup and replaces it; a second run finds
 * nothing to change.
 *
 * Run: wp eval-file seeders/cx-cart-page.php
 */

$cart_id = (int) get_option( 'woocommerce_cart_page_id' );
$post    = $cart_id ? get_post( $cart_id ) : null;

if ( ! $post ) {
	WP_CLI::error( 'no cart page' );
}

$old = <<<'HTML'
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">New in store</h2>
<!-- /wp:heading -->

<!-- wp:woocommerce/product-new {"columns":4,"rows":1} /--></div>
HTML;

$new = <<<'HTML'
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><a class="btn btn--solid" href="/congress/register/">Register for the Congress</a></p>
<!-- /wp:paragraph --></div>
HTML;

if ( false === strpos( $post->post_content, 'New in store' ) ) {
	WP_CLI::success( 'cart page already trimmed' );
	return;
}

$content = str_replace( $old, $new, $post->post_content );

if ( $content === $post->post_content ) {
	WP_CLI::error( 'stock empty-cart markup not found; content differs from expected' );
}

wp_update_post( array( 'ID' => $cart_id, 'post_content' => $content ) );
WP_CLI::success( 'cart page empty state trimmed' );

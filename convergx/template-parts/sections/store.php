<?php
defined( 'ABSPATH' ) || exit;
$ids = array_filter( array_map( 'intval', explode( ',', (string) get_sub_field( 'product_ids' ) ) ) );
if ( ! $ids ) {
	return;
}
?>
<section>
	<div class="wrap">
		<?php convergx_section_head( get_sub_field( 'heading' ) ); ?>
		<ul class="store">
			<?php
			foreach ( $ids as $id ) :
				$product = convergx_get_registration_product( $id );
				// No product, no card. See the note in templates/page-register.php.
				if ( ! $product ) {
					continue;
				}
				?>
				<li class="store-item">
					<h3 class="store-name"><?php echo esc_html( $product['name'] ); ?></h3>
					<p class="store-price">
						<?php echo esc_html( $product['price_fmt'] ); ?>
						<span class="store-cur"><?php echo esc_html( $product['currency'] ); ?></span>
					</p>
					<p class="store-act">
						<a class="btn btn--solid" href="<?php echo esc_url( $product['permalink'] ); ?>"><?php esc_html_e( 'View', 'convergx' ); ?></a>
					</p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

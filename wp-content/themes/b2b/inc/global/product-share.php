<?php
/**
 * Product share buttons
 *
 * @package WordPress
 */

$item_id             = isset( $args['item_id'] ) ? $args['item_id'] : get_the_ID();
$product_share_title = get_field( 'product_share_title', 'option' );
$product_title       = get_the_title( $item_id );
$product_permalink   = get_permalink( $item_id );
?>

<div class="product-share">
	<div class="product-share-inner">
		<?php if ( $product_share_title ) : ?>
			<div class="product-share-title">
				<?php echo esc_html( $product_share_title ); ?>
			</div>
		<?php endif; ?>
		<div class="product-share-buttons">
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( $product_permalink ); ?>" target="_blank">
				<img src="<?php echo esc_url( THEME ); ?>/images/facebook.png" alt="Facebook">
			</a>
			<a href="https://wa.me/?text=<?php echo esc_url( $product_permalink ); ?>" target="_blank">
				<img src="<?php echo esc_url( THEME ); ?>/images/whatsapp.png" alt="WhatsApp">
			</a>
			<a href="#" class="copy-url">
				<img src="<?php echo esc_url( THEME ); ?>/images/copy.png" alt="WhatsApp">
			</a>
		</div>
	</div>
</div>

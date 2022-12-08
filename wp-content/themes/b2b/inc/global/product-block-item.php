<?php
/**
 * Single product item
 * inside archive loop
 *
 * @package WordPress
 */

$this_product          = wc_get_product( get_the_ID() );
$default_product_image = get_field( 'default_product_image', 'option' );
$subtitle              = get_field( 'subtitle', get_the_ID() ) ? get_field( 'subtitle', get_the_ID() ) : "תמצית כורכום איכותית בכמוסות סופטג'ל ובתוספת שמני כורכום.";
$item_id               = ( 'simple' === $this_product->get_type() ) ? 'data-item-id=' . get_the_ID() : '';
?>

<div class="product-block-item is-product-<?php echo $this_product->get_type(); ?>" data-id="<?php echo get_the_ID(); ?>">
	<div class="item-image">
		<a href="<?php echo get_permalink( get_the_ID() ); ?>">
			<img src="<?php echo esc_url( $default_product_image['url'] ); ?>" alt="<?php echo esc_html( get_the_title() ); ?>">
		</a>
	</div>
	<div class="item-meta">
		<div class="product-title">
			<?php echo esc_html( get_the_title() ); ?>
		</div>
		<?php if ( $subtitle ) : ?>
			<div class="product-subtitle">
				<?php echo wp_kses_post( $subtitle ); ?>
			</div>
		<?php endif; ?>

		<div class="product-price-wrapper">
			<?php woocommerce_template_loop_price(); ?>
		</div>

		<div class="product-actions">

			<a href="#" class="quick-view" data-item="<?php echo esc_html( $this_product->get_id() ); ?>">
				<span></span>
				צפייה מהירה
			</a>

			<?php
			get_template_part(
				'inc/global/add-to-favorites',
				'button',
				array(
					'item_id'    => $item_id,
					'show_title' => true,
				)
			);
			?>
		</div>
	</div>

	<div class="item-add-to-cart">
		<?php woocommerce_template_single_add_to_cart(); ?>
	</div>
</div>

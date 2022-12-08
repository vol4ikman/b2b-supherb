<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

$minicart_title          = get_field( 'minicart_title', 'option' );
$clear_cart_button_title = get_field( 'clear_cart_button_title', 'option' );

do_action( 'woocommerce_before_mini_cart' ); ?>

<div class="b2b-minicart-wrapper">

<div class="minicart-inner">

	<div class="minicart-top-wrapper">

		<div class="minicart-header">
			<div class="minicart-buttons">
				<?php if ( $minicart_title ) : ?>
					<div class="minicart-title">
						<?php echo esc_html( $minicart_title ); ?>
					</div>
				<?php endif; ?>
				<div class="minicart-clear">
					<button type="button">
						<span class="icon"></span>
						<?php echo esc_html( $clear_cart_button_title ); ?>
					</button>
				</div>
			</div>
			<div class="minicart-close">
				<button type="button"></button>
			</div>
		</div>

		<?php if ( ! WC()->cart->is_empty() ) : ?>

			<div class="minicart-table-head">
				<div class="minicart-table-head-inner">
					<div class="product-name-column">
						מוצר
					</div>
					<div class="product-price-column">
						מחיר
					</div>
					<div class="product-quantity-column">
						כמות
					</div>
				</div>
			</div>

			<ul class="woocommerce-mini-cart cart_list product_list_widget
				<?php echo isset( $args['list_class'] ) ? esc_attr( $args['list_class'] ) : ''; ?>">
				<?php
				do_action( 'woocommerce_before_mini_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					$variation_data = '';
					if ( 'variation' === $_product->get_type() ) {
						$variation_data = get_variation_data_from_variation_id( $cart_item['variation_id'] );
						if ( $variation_data ) {
							$variation_data = str_replace( '%d7%9b%d7%9e%d7%95%d7%a1%d7%95%d7%aa:', '', $variation_data );
							$variation_data = str_replace( 'size:', '', $variation_data );
							$variation_data = $variation_data . ' כמוסות';
						}
					}

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						// if ( $_product->is_type( 'variable' ) ) {
						// $default_attributes = $_product->get_default_attributes();
						// var_dump( $default_attributes );
						// }
						?>
						<li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">

							<div class="product-name-column">
								<div class="product-thumbnail">
									<?php if ( empty( $product_permalink ) ) : ?>
										<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php else : ?>
										<a href="<?php echo esc_url( $product_permalink ); ?>">
											<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</a>
									<?php endif; ?>
								</div>
								<div class="product-meta">
									<div class="product-name">
										<?php echo wp_kses_post( $product_name ); ?>
									</div>
									<?php if ( $variation_data ) : ?>
										<div class="product-subtitle" data-type="<?php echo esc_html( $_product->get_type() ); ?>">
											<?php echo wp_kses_post( $variation_data ); ?>
										</div>
									<?php endif; ?>
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							</div>

							<div class="product-price-column">
								<?php echo wp_kses_post( $_product->get_price_html() ); ?>
							</div>

							<div class="product-quantity-column">
								<?php
								/*
								echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								*/
								?>
								<?php
								woocommerce_quantity_input(
									array(
										'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $_product->get_min_purchase_quantity(), $_product ),
										'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $_product->get_max_purchase_quantity(), $_product ),
										'input_value' => isset( $cart_item['quantity'] ) ? wc_stock_amount( wp_unslash( $cart_item['quantity'] ) ) : $_product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
									)
								);
								?>

								<?php get_template_part( 'inc/global/add-to-favorites', 'button', array( 'item_id' => $product_id ) ); ?>

								<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_attr__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $cart_item_key ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
								?>
							</div>

						</li>
						<?php
					}
				}

				do_action( 'woocommerce_mini_cart_contents' );
				?>
			</ul>

		<?php endif; ?>

	</div>

	<?php if ( ! WC()->cart->is_empty() ) : ?>

		<div class="mini-cart-total-wrapper">
			<p class="woocommerce-mini-cart__total total">
				<?php
				/**
				 * Hook: woocommerce_widget_shopping_cart_total.
				 *
				 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
				 */
				do_action( 'woocommerce_widget_shopping_cart_total' );
				?>
			</p>

			<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

			<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

			<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
		</div>

	<?php else : ?>

		<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>

	<?php endif; ?>

</div>

</div>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>

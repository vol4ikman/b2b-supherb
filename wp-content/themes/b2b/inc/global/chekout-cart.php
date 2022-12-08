<?php
/**
 * Checkout cart
 *
 * @package WordPress
 */

$cart_items = WC()->cart->get_cart();

$cart_total     = WC()->cart->get_cart_total();
$cart_tax_total = WC()->cart->tax_total;
?>

<div class="checkout-cart-inner mobile-view">
	<div class="mobile-checkout-products">
		<h3>מוצרים</h3>
		<div class="products-list">
		<?php
			foreach ( $cart_items as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

					$variation_id = isset( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : '';
					if( $variation_id ) {
						$vars_prod                 = wc_get_product($variation_id);
						$selected_variation_name   = reset( $vars_prod->get_variation_attributes() );
					}

					?>
					<div class="products-list-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<div class="item-image">
							<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $product_permalink ) {
									echo $thumbnail; // PHPCS: XSS ok.
								} else {
									printf( '<a href="%s" class="img-link">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
								}
							?>
						</div>

						<div class="item-data">
							<div class="data-wrapper">
								<div class="item-label">מוצר:</div>
								<div class="item-value is-bold is-green"><?php echo esc_html( $_product->get_name() ); ?></div>
							</div>

							<?php if( $variation_id && isset($selected_variation_name)) : ?>
								<div class="data-wrapper">
									<div class="item-label">כמוסות:</div>
									<div class="item-value"><?php echo esc_html( $selected_variation_name ); ?></div>
								</div>
							<?php endif; ?>

							<div class="data-wrapper">
								<div class="item-label">מחיר ליחידה:</div>
								<div class="item-value">
									<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</div>
							</div>
							<div class="data-wrapper">
								<div class="item-label">כמות:</div>
								<div class="item-value"><?php echo esc_html( $cart_item['quantity'] ); ?></div>
							</div>
							<div class="data-wrapper">
								<div class="item-label is-bold is-green">סכום ביניים:</div>
								<div class="item-value is-bold is-green">
									<?php
										echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</div>
							</div>
						</div>

					</div>
					<?php
				}
			}
			?>
		</div>
	
		<div class="checkout-coupon-container">
			<div class="checkout-coupon-wrapper">
				<div class="checkout-coupon-title">
					יש לכם קופון?
				</div>
				<div class="checkout-coupon-input">
					<input type="text" name="b2b-coupon-code" value="">
					<label>קוד קופון</label>
				</div>
				<div class="checkout-coupon-submit">
					<button type="button">החל קופון</button>
				</div>
			</div>

			<div class="checkout-coupon-message"></div>
		</div>
	
		<div id="b2b_order_review" class="woocommerce-checkout-review-order">

			<div class="b2b-order-review-row">
				<div class="title">סה"כ:</div>
				<div class="data">
					<?php echo wp_kses_post( $cart_total ); ?>
				</div>
			</div>

			<div class="b2b-order-review-row">
				<div class="title">מע"מ:</div>
				<div class="data">
					<span class="woocommerce-Price-amount amount">
						<span class="woocommerce-Price-currencySymbol"><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span><?php echo esc_html( $cart_tax_total ); ?>
					</span>
				</div>
			</div>

			<div class="b2b-order-review-row">
				<div class="title">משלוח:</div>
				<div class="data">
					<span class="woocommerce-Price-amount amount">
						<span class="woocommerce-Price-currencySymbol"><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>0
					</span>
				</div>
			</div>

			<div class="b2b-order-review-row">
				<div class="title">קופון:</div>
				<div class="data">
					<?php
					if ( count( WC()->cart->get_applied_coupons() ) > 0 ) {
						?>
						<span class="woocommerce-Price-amount amount">
							<span class="woocommerce-Price-currencySymbol"><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>0
						</span>
					<?php } else { ?>
						<span class="woocommerce-Price-amount amount">
							<span class="woocommerce-Price-currencySymbol"><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>0
						</span>
						<?php
					}
					?>
				</div>
			</div>

			<div class="b2b-order-review-row-devider"></div>

			<div class="checkout-total">
				<div class="label-title">
					סה"כ כולל מע"מ:
				</div>
				<div class="value">
					<?php
						$total_fixed = (float) WC()->cart->cart_contents_total + (float) $cart_tax_total;
						echo wc_price( $total_fixed );
					?>
				</div>
			</div>

		</div>

	</div>
</div>

<div class="checkout-cart-inner desktop-view">
	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-name">שם מוצר</th>
				<th class="product-price">מחיר ליחידה</th>
				<th class="product-quantity">כמות</th>
				<th class="product-subtotal">סכום ביניים</th>
			</tr>
		</thead>

		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( $cart_items as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
							<div class="product-name-inner">
								<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $product_permalink ) {
									echo $thumbnail; // PHPCS: XSS ok.
								} else {
									printf( '<a href="%s" class="img-link">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
								}
								?>

								<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
								} else {
									$subtitle = get_field( 'subtitle', $_product->get_id() );
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="product-title-link">%s<div class="subtitle">' . $subtitle . '</div></a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
								}

								do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

								// Meta data.
								echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								// Backorder notification.
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
								}
								?>
							</div>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
							<div class="product-quantity-inner">
								<?php echo esc_html( $cart_item['quantity'] ); ?>
							</div>
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>
					</tr>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr class="hidden-table-row">
				<td colspan="6" class="actions">

					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</td>
			</tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>

	<div class="checkout-coupon-container">
		<div class="checkout-coupon-wrapper">
			<div class="checkout-coupon-title">
				יש לכם קופון?
			</div>
			<div class="checkout-coupon-input">
				<input type="text" name="b2b-coupon-code" value="">
				<label>קוד קופון</label>
			</div>
			<div class="checkout-coupon-submit">
				<button type="button">החל קופון</button>
			</div>
		</div>

		<div class="checkout-coupon-message"></div>
	</div>

	<div id="b2b_order_review" class="woocommerce-checkout-review-order">

		<div class="b2b-order-review-row">
			<div class="title">סה"כ:</div>
			<div class="data">
				<?php echo wp_kses_post( $cart_total ); ?>
			</div>
		</div>

		<div class="b2b-order-review-row">
			<div class="title">מע"מ:</div>
			<div class="data">
				<span class="woocommerce-Price-amount amount">
					<span class="woocommerce-Price-currencySymbol"><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span><?php echo esc_html( $cart_tax_total ); ?>
				</span>
			</div>
		</div>

		<div class="b2b-order-review-row">
			<div class="title">משלוח:</div>
			<div class="data">
				<span class="woocommerce-Price-amount amount">
					<span class="woocommerce-Price-currencySymbol"><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>0
				</span>
			</div>
		</div>

		<div class="b2b-order-review-row">
			<div class="title">קופון:</div>
			<div class="data">
				<?php
				if ( count( WC()->cart->get_applied_coupons() ) > 0 ) {
					?>
					<span class="woocommerce-Price-amount amount">
						<span class="woocommerce-Price-currencySymbol"><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>0
					</span>
				<?php } else { ?>
					<span class="woocommerce-Price-amount amount">
						<span class="woocommerce-Price-currencySymbol"><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>0
					</span>
					<?php
				}
				?>
			</div>
		</div>

		<div class="b2b-order-review-row-devider"></div>

		<div class="checkout-total">
			<div class="label-title">
				סה"כ כולל מע"מ:
			</div>
			<div class="value">
				<?php
					// print_r( $cart_total );
					// print_r( $cart_tax_total );
					$total_fixed = (float) WC()->cart->cart_contents_total + (float) $cart_tax_total;
					echo wc_price( $total_fixed );
				?>
			</div>
		</div>

	</div>

</div>

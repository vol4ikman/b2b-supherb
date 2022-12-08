<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<h1 class="my_account_orders_title">ההזמנות שלי</h1>

<?php if ( $has_orders ) : ?>

	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ( $customer_orders->orders as $customer_order ) {
				$order              = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$item_count         = $order->get_item_count() - $order->get_item_count_refunded();
				$billing_first_name = $order->get_billing_first_name();
				$billing_last_name  = $order->get_billing_last_name();
				$order_items        = $order->get_items();

				$priority_order_id          = get_field( 'ordname', $order->get_id() );
				$order_status_from_priority = b2b_get_order_status_from_priority( $priority_order_id );
				?>
				<tr class="visible-row woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<?php echo esc_html( $priority_order_id ); ?>
								<?php /*
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo esc_html( $priority_order_id ); ?>
								</a>
								*/ ?>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
									<?php // echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
									<?php echo esc_html( date_format( $order->get_date_created(), 'd/m/Y' ) ); ?>
								</time>

							<?php elseif ( 'order-customer-name' === $column_id ) : ?>
								<?php echo esc_html( $billing_first_name . ' ' . $billing_last_name ); ?>

								<?php
								elseif ( 'order-status' === $column_id ) :
									if ( 'בוצעה' === $order_status_from_priority ) {
										$order_status_from_priority_text = $order_status_from_priority;
									} elseif ( 'מבוטלת' === $order_status_from_priority ) {
										$order_status_from_priority_text = $order_status_from_priority;
									} else {
										$order_status_from_priority_text = 'בביצוע';
									}
									echo esc_html( $order_status_from_priority_text );
									?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
									echo wp_kses_post( $order->get_formatted_order_total() );
								?>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );

								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
										echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
								?>
							<?php elseif ( 'order-details' === $column_id ) : ?>
								<button type="button" class="trigger-order-row"></button>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>

				<tr class="hidden-row">
					<td colspan="6">
						<table class="inner-table">
							<tbody>
								<?php
								foreach ( $order_items as $item_id => $item ) :
									$product_id = $item->get_product_id();
									$_product   = wc_get_product( $product_id );
									$thumbnail  = get_the_post_thumbnail( $product_id, 'small' );

									$allmeta      = $item->get_meta_data();
									$total        = $item->get_total();
									$subtotal     = $item->get_subtotal();
									$quantity     = $item->get_quantity();
									$product_name = $item->get_name();

									$item_price = $_product->get_price();
									?>
									<tr>
										<td colspan="3" class="order-item-metadata">
											<div class="order-item-metadata-box">
												<div class="image">
													<?php echo $thumbnail; ?>
												</div>
												<div class="details">
													<div class="title">
														<?php echo esc_html( $product_name ); ?>
													</div>
													<?php if ( get_field( 'subtitle', $product_id ) ) : ?>
														<div class="subtitle">
															<?php echo wp_kses_post( get_field( 'subtitle', $product_id ) ); ?>
														</div>
													<?php endif; ?>
												</div>
											</div>
										</td>
										<td class="order-item-unit-price">
											<span class="wrap">
												<span class="unit-qnty"><?php echo esc_html( $quantity ); ?></span>X <span class="currency"><?php echo get_woocommerce_currency_symbol(); ?></span><span class="unit-price"><?php echo wp_kses_post( $item_price ); ?></span>
											</span>
										</td>
										<td class="order-item-total-price">
											<?php echo wp_kses_post( $total ); ?> <?php echo get_woocommerce_currency_symbol(); ?>
										</td>
										<td>&nbsp;</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>

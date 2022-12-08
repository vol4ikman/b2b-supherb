<?php
/**
 * User orders list
 *
 * @package WordPress
 */

$user_orders = get_user_orders( get_current_user_id() );
?>

<div class="user-orders-list-inner">

	<div class="user-orders-list-title">
		רשימת הזמנות פתוחות
	</div>

	<div class="user-orders-list-table-wrapper">
		<?php if ( $user_orders ) : ?>
			<table class="user-orders-list-table">

				<thead>
					<th class="ord-id">
						<span class="desktop-title">מספר הזמנה</span>
						<span class="mobile-title">#הזמנה</span>
					</th>
					<th class="ord-date">
						<span class="desktop-title">תאריך הזמנה</span>
						<span class="mobile-title">ת.הזמנה</span>
					</th>
					<th class="ord-by">הוזמן ע"י</th>
					<th class="ord-total">
						<span class="desktop-title">סה"כ מחיר להזמנה</span>
						<span class="mobile-title">מחיר</span>
					</th>
					<th class="ord-status">
						<span class="desktop-title">סטטוס הזמנה</span>
						<span class="mobile-title">סטטוס</span>
					</th>
					<th class="ord-action"></th>
				</thead>

				<tbody>

						<?php
						foreach ( $user_orders as $user_order ) :
							$order_item = wc_get_order( $user_order );

							$priority_order_id          = get_field( 'ordname', $order_item->get_id() );
							$order_status_from_priority = b2b_get_order_status_from_priority( $priority_order_id );
							?>
							<tr>
								<td class="ord-id">
									<?php echo esc_html( $priority_order_id ); ?>
									<?php // echo esc_html( $order_item->get_id() ); ?>
								</td>
								<td class="ord-date"><?php echo esc_html( $order_item->get_date_created()->date_i18n( 'd.m.Y' ) ); ?></td>
								<td class="ord-by">
									<?php
									echo esc_html( $order_item->get_billing_first_name() );
									echo ' ' . esc_html( $order_item->get_billing_last_name() );
									?>
								</td>
								<td class="ord-total"><?php echo esc_html( $order_item->get_total() ); ?> <?php echo esc_html( get_woocommerce_currency_symbol() ); ?></td>
								<td class="ord-status">
									<?php
									if ( 'בוצעה' === $order_status_from_priority ) {
										$order_status_from_priority_text = $order_status_from_priority;
									} elseif ( 'מבוטלת' === $order_status_from_priority ) {
										$order_status_from_priority_text = $order_status_from_priority;
									} else {
										$order_status_from_priority_text = 'בביצוע';
									}
									echo esc_html( $order_status_from_priority_text );
									// echo $order_status_from_priority ? esc_html( $order_status_from_priority ) : esc_html( wc_get_order_status_name( $order_item->get_status() ) );
									?>
								</td>
								<td class="ord-action">
									<button class="trigger-order-details" type="button"></button>
								</td>
							</tr>
						<?php endforeach; ?>


				</tbody>

			</table>
		<?php else : ?>
			<h3>לא קיימות הזמנות במערכת</h3>
		<?php endif; ?>
	</div>
</div>

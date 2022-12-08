<?php
/**
 * Priority orders
 *
 * @package WordPress
 */

add_action( 'init', 'test_order' );
function test_order() {
	// b2b_create_order( 1830 );
}

/**
 * B2B create_order
 *
 * @param  object $order_id WC_Order.
 * @return [type]        [description]
 */
function b2b_create_order( $order_id ) {

	$order_id = 1849;
	// $api_url     = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/amb0722/ORDERS'; // v2.8.
	$api_url     = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/amb1022/ORDERS'; // v2.9.
	$order       = wc_get_order( $order_id );
	$order_items = array();

	$user_id     = $order->get_user_id();
	$customer_id = get_field( 'customer_name_id', 'user_' . $user_id );

	$billing_first_name = $order->get_billing_first_name();
	$billing_last_name  = $order->get_billing_last_name();

	if ( $order->get_items() ) {
		foreach ( $order->get_items() as $item_id => $item ) {
			$product_id = $item->get_product_id();
			// $variation_id = $item->get_variation_id();.
			$product  = $item->get_product();
			$quantity = $item->get_quantity();
			$sku      = $product->get_sku();

			$order_items[] = array(
				'PARTNAME' => $sku,
				'TQUANT'   => $quantity,
			);
		}
	}

	$order_data = array(
		'CUSTNAME'           => $customer_id,
		'DETAILS'            => $billing_first_name . ' ' . $billing_last_name,
		'ORDERITEMS_SUBFORM' => $order_items,
	);

	$response = wp_remote_post(
		$api_url,
		array(
			'timeout'     => 120,
			'redirection' => 5,
			'httpversion' => '1.0',
			'method'      => 'POST',
			'body'        => wp_json_encode( $order_data ),
			'headers'     => array(
				'Authorization' => b2b_get_api_login(),
				'Content-Type'  => 'application/json',
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( isset( $response_body['FORM']['InterfaceErrors'] ) ) {
		// Handle error message.
		$data['message'] = $response_body['FORM']['InterfaceErrors'];
	} elseif ( isset( $response_body['ORDNAME'] ) ) {
		update_field( 'ordname', $response_body['ORDNAME'], $order_id );
		update_field( 'json', wp_json_encode( $response_body ), $order_id );
	}

	// print_r( $response_body );
	// die();
}

add_action( 'woocommerce_new_order', 'b2b_wc_after_order_complete', 10, 1 );
/**
 * B2B wc_after_order_complete
 *
 * @param  string $order_id WC_Order ID.
 */
function b2b_wc_after_order_complete( $order_id ) {
	// wp_mail( 'alex.v@dooble.co.il', 'Order comeplete hook B2B', 'Order comeplete hook B2B, Order #' . $order_id );.
	b2b_create_order( $order_id );
}

b2b_get_order_status_from_priority( 'ORD22063520' );

/**
 * B2B get_order_status_from_priority
 *
 * @param  string $priority_order_id               [description]
 * @return [type]                    [description]
 */
function b2b_get_order_status_from_priority( $priority_order_id ) {
	$api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/ORDERS(%%priority_order_id%%)?$select=ORDSTATUSDES';
	$api_url = str_replace(
		array( '%%priority_order_id%%' ),
		array( "'" . $priority_order_id . "'" ),
		$api_url
	);

	$response = wp_remote_get(
		$api_url,
		array(
			'timeout'     => 120,
			'redirection' => 5,
			'httpversion' => '1.0',
			'method'      => 'GET',
			'headers'     => array(
				'Authorization' => b2b_get_api_login(),
				'Content-Type'  => 'application/json',
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = isset( $response_body['ORDSTATUSDES'] ) ? $response_body['ORDSTATUSDES'] : array();

	return $response_body;
}

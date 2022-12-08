<?php
/**
 * Collect product price from priority API
 *
 * @package WordPress
 */

add_filter( 'woocommerce_get_price_html', 'b2b_alter_price_display', 9999, 2 );
/**
 * B2B override product price HTML
 *
 * @param  string $price_html price_html.
 * @param  object $product    WC_Product.
 * @return string             [description]
 */
function b2b_alter_price_display( $price_html, $product ) {

	if ( ! is_admin() ) {

		//return $price_html;
		$sku                    = $product->get_sku();
		$user_id                = get_current_user_id();
		$customer_name_id       = get_user_meta( $user_id, 'customer_name_id', true );

		// v2 new API
		$final_product_price = get_final_product_price( $customer_name_id, $sku );
		
		if( $final_product_price && is_array($final_product_price) ) {
			$final_product_price = reset( $final_product_price );
			$price_html = wc_price( $final_product_price['PRICE'] );
		}

		/*
		$user_api_response_data = get_field( 'user_api_response_data', 'user_' . $user_id );
		if ( $user_api_response_data ) {
			$user_api_response_data = json_decode( $user_api_response_data, true );
			if ( isset( $user_api_response_data['MCUSTNAME'] ) && $user_api_response_data['MCUSTNAME'] ) {
				$customer_name_id = $user_api_response_data['MCUSTNAME'];
			}
		}

		// #1 - הנחה כללית.
		$general_discount = get_general_discount( $customer_name_id );

		// #2 - מחיר מיוחד למוצר.
		if ( $sku ) {
			$special_product_price_by_sku = get_special_product_price_by_sku( $customer_name_id, $sku );
		}
		// #3 Pricelist - מחירוני לקוח.
		$customer_pricelists = get_customer_pricelists( $customer_name_id );
		if ( $customer_pricelists ) {
			$product_price_from_pricelist = get_product_price_from_pricelist( $customer_name_id, $customer_pricelists['PLNAME'], $sku );
		}

		$price_found = false;

		// ONLY IF PRICE NOT NULL.
		if ( '' === $product->get_price() ) {
			return $price_html;
		}

		if ( $general_discount && ! $price_found ) {
			// Percent discount.
			if ( isset( $general_discount['PERCENT'] ) && $general_discount['PERCENT'] ) {
				$price_discount = (float) $product->get_price() / 100 * (int) $general_discount['PERCENT'];
				$price_value    = (float) $product->get_price() - $price_discount;
				$price_html     = wc_price( $price_value );
			}
		}

		// #2.
		if ( $special_product_price_by_sku ) {
			if ( $general_discount ) {
				foreach ( $special_product_price_by_sku as $special_price_by_sku ) {
					if ( isset( $special_price_by_sku['PARTNAME'] ) && $special_price_by_sku['PARTNAME'] === $product->get_sku() ) {

						$price_discount = (float) $special_price_by_sku['PRICE'] / 100 * (int) $general_discount['PERCENT'];
						$price_value    = (float) $special_price_by_sku['PRICE'] - $price_discount;

						$price_html  = wc_price( $price_value );
						$price_found = true;
						break;
					}
				}
			}
		}

		// #3.
		if ( isset( $product_price_from_pricelist ) && $product_price_from_pricelist ) {
			foreach ( $product_price_from_pricelist as $list_item ) {
				if ( isset( $list_item['PARTNAME'] ) && $list_item['PARTNAME'] === $product->get_sku() ) {
					$price_html = wc_price( $list_item['PRICE'] );
					break;
				}
			}
		}

		// Product discount.
		// $product_discount = get_product_discount( $customer_name_id, $sku );

		// Family discount.
		// $family_discount = get_product_family_discount( $customer_id );
		// Array ( [0] => Array ( [FAMILYNAME] => 103 [PERCENT] => 30 ) ).
		*/
	}

	return $price_html;

}

function get_final_product_price( $customer_id, $sku ) {

	$final_endpoint = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/MIIT_CUSTTOPART?$filter=CUSTNAME eq %%customer_id%% and PARTNAME eq %%sku%%';

	$final_endpoint = str_replace(
		array( '%%customer_id%%', '%%sku%%' ),
		array( "'" . $customer_id . "'", "'" . $sku . "'" ),
		$final_endpoint
	);
	
	//echo $final_endpoint . '<br>';

	$response = wp_remote_get(
		$final_endpoint,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = $response_body['value'];

	return $response_body;
}

/**
 * Special_price_list
 * מחיר מיוחד למוצר
 *
 * @param  string $customer_name_id Customer api number.
 * @param  string $sku product SKU number.
 * @return array                   [description]
 */
function get_special_product_price_by_sku( $customer_name_id, $sku ) {

	if ( ! $sku ) {
		$sku = 'SU8118TB30';
	}
	if ( ! $customer_name_id ) {
		$customer_name_id = '7777777';
	}

	// $api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/CUSTOMERS(%%customer_id%%)/CUSTPARTPRICE_SUBFORM?$select=PARTNAME, PRICE&$filter=ADVA_VALID eq %%flag%%';
	$api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/CUSTOMERS(%%customer_id%%)/CUSTPARTPRICE_SUBFORM?$select=PARTNAME,%20PRICE&?$filter=ADVA_VALID%20eq%20%27Y%27&$filter=PARTNAME%20eq %%sku%%';

	$api_url = str_replace(
		array( '%%customer_id%%', '%%sku%%' ),
		array( "'" . $customer_name_id . "'", "'" . $sku . "'" ),
		$api_url
	);

	$response = wp_remote_get(
		$api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = $response_body['value'];

	return $response_body;
}

/**
 * GET product_price_from_pricelist
 *
 * @param  string $customer_id               [description].
 * @param  string $pricelist                 [description].
 * @param  string $product_sku               product SKU.
 * @return array              [description]
 */
function get_product_price_from_pricelist( $customer_id, $pricelist, $product_sku ) {
	$api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/PRICELIST(%%pricelist_name%%)/PARTPRICE2_SUBFORM?$select=PARTNAME,PRICE&$filter=PARTNAME eq %%product_sku%%';

	$api_url = str_replace(
		array( '%%customer_id%%', '%%pricelist_name%%', '%%product_sku%%' ),
		array( "'" . $customer_id . "'", "'" . $pricelist . "'", "'" . $product_sku . "'" ),
		$api_url
	);

	$response = wp_remote_get(
		$api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = $response_body['value'];

	return $response_body;
}

/**
 * General_discount
 * הנחה כללית
 *
 * @param  string $customer_name_id Customer api number.
 * @return array                   [description]
 */
function get_general_discount( $customer_name_id ) {
	$api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/CUSTOMERS(%%customer_id%%)/CUSTDISCOUNT_SUBFORM?$select=PERCENT&$filter=ADVA_VALID eq %%flag%%';

	$api_url = str_replace(
		array( '%%customer_id%%', '%%flag%%' ),
		array( "'" . $customer_name_id . "'", "'Y'" ),
		$api_url
	);

	$response = wp_remote_get(
		$api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = isset( $response_body['value'] ) ? reset( $response_body['value'] ) : array();

	return $response_body;
}

/**
 * GET customer_pricelists
 *
 * @param  string $customer_id  api customer ID.
 * @return [type]              [description]
 */
function get_customer_pricelists( $customer_id ) {
	$api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/CUSTOMERS(%%customer_id%%)/CUSTPLIST_SUBFORM?$select=ORD,PLNAME,PLDATE';
	$api_url = str_replace( '%%customer_id%%', "'" . $customer_id . "'", $api_url );

	$response = wp_remote_get(
		$api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = $response_body['value'];

	// Order lists by ORD param.
	$filtered_response_body = array();

	foreach ( $response_body as $list_item ) {
		$filtered_response_body[ $list_item['ORD'] ] = $list_item;
	}

	$filtered_response_body = $filtered_response_body[ min( array_keys( $filtered_response_body ) ) ];

	return $filtered_response_body;
}
/**
 * Get customer bonuses per product
 *
 * @param  string $customer_id  api customer id.
 * @param  string $product_sku  product sku.
 * @return [type]              [description]
 */
function get_bonuses_per_product( $customer_id, $product_sku ) {

	$api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/CUSTOMERS(%%customer_id%%)/CUSTBONUSES_SUBFORM?$select=PARTNAME, MINQUANT, BONUSPARTNAME, BONUSQUANT&?$filter=ADVA_VALID eq %%flag%%&$filter=PARTNAME eq %%product_sku%%';

	$api_url = str_replace(
		array( '%%customer_id%%', '%%flag%%', '%%product_sku%%' ),
		array( "'" . $customer_id . "'", "'Y'", "'" . $product_sku . "'" ),
		$api_url
	);

	$response = wp_remote_get(
		$api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = $response_body['value'];

	return $response_body;
}

/**
 * Get_product_discount by SKU
 *
 * @param  string $customer_id  customer ID.
 * @param  string $product_sku  product SKU.
 * @return array              [description]
 */
function get_product_discount( $customer_id, $product_sku ) {
	$api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/CUSTOMERS(%%customer_id%%)/CUSTPARTDISC_SUBFORM?$select=PARTNAME, PERCENT&?$filter=ADVA_VALID eq %%flag%%&$filter=PARTNAME eq %%product_sku%%';

	$api_url = str_replace(
		array( '%%customer_id%%', '%%flag%%', '%%product_sku%%' ),
		array( "'" . $customer_id . "'", "'Y'", "'" . $product_sku . "'" ),
		$api_url
	);

	$response = wp_remote_get(
		$api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = $response_body['value'];

	return $response_body;
}

/**
 * Get product_family_discount
 *
 * @param  string $customer_id  Customer name id.
 * @return array              [description]
 */
function get_product_family_discount( $customer_id ) {
	$api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/CUSTOMERS(%%customer_id%%)/CUSTFAMILYDISC_SUBFORM?$select=FAMILYNAME,PERCENT&$filter=ADVA_VALID eq %%flag%%';

	$api_url = str_replace(
		array( '%%customer_id%%', '%%flag%%' ),
		array( "'" . $customer_id . "'", "'Y'" ),
		$api_url
	);

	$response = wp_remote_get(
		$api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = $response_body['value'];

	return $response_body;
}

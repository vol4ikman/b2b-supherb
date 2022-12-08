<?php
/**
 * Import products from priority json file.
 *
 * @package WordPress
 */

/**
 * Load_json_file
 *
 * @param  string $endpoint_url all products.
 * @return [type]               [description]
 */
function load_json_file_all_products() {
	$endpoint_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/LOGPART?$select=PARTNAME,PARTDES,BASEPLPRICE,%20FAMILYNAME,%20MPARTNAME,ADVA_PARTQUANT&$filter=(SPEC14%20eq%20%27%D7%A1%D7%95%D7%A4%D7%94%D7%A8%D7%91%27%20or%20SPEC14%20eq%20%27%D7%A1%D7%95%D7%9C%D7%92%D7%90%D7%A8%27)%20and%20STATDES%20eq%20%27%D7%A4%D7%A2%D7%99%D7%9C%27';
	$response     = wp_remote_get(
		$endpoint_url,
		array(
			'timeout'     => 120,
			'httpversion' => '1.1',
			'method'      => 'GET',
			'headers'     => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Basic ' . base64_encode( 'apib2b:Ambr0z2022!' ), // phpcs:ignore.
			),
		)
	);

	$body = wp_remote_retrieve_body( $response );
	$body = json_decode( $body, true );
	unset( $body['@odata.context'] );
	$body = $body['value'];

	return $body;
}

add_action( 'init', 'b2b_import_products_action' );

/**
 * B2B import_products_action
 */
function b2b_import_products_action() {
	if ( isset( $_GET['get_products'] ) ) {

		$products = load_json_file_all_products();

		$products_ids_array = array(
			'simple'   => array(),
			'variable' => array(),
		);

		$response = array(
			'simple'   => array(),
			'variable' => array(),
		);

		$products_by_type = array(
			'simple'   => array(),
			'variable' => array(),
		);

		if ( $products ) {

			foreach ( $products as $key => $product_item ) {

				if ( $product_item['MPARTNAME'] && $product_item['ADVA_PARTQUANT'] ) {
					// variable product.
					$products_by_type['variable'][] = $product_item;
				} else {
					// simple product.
					$products_by_type['simple'][] = $product_item;
				}
			}

			if ( isset( $products_by_type['simple'] ) && $products_by_type['simple'] ) {
				foreach ( $products_by_type['simple'] as $simple_product ) {
					$product_exists_id = wc_get_product_id_by_sku( $simple_product['PARTNAME'] );
					if ( ! $product_exists_id ) {
						$product_exists_id = b2b_create_simple_product( $simple_product );
						if ( $product_exists_id ) {
							$products_ids_array['simple'][] = $product_exists_id;
						}
					} else {
						$products_ids_array['simple'][] = $product_exists_id;
					}
					$response['simple'][] = $product_exists_id;
				}
			}

			if ( isset( $products_by_type['variable'] ) && $products_by_type['variable'] ) {
				$variation_products = array();
				foreach ( $products_by_type['variable'] as $variation_item ) {
					$variation_products[ $variation_item['MPARTNAME'] ][] = $variation_item;
				}

				if ( $variation_products ) {
					foreach ( $variation_products as $key => $variation_product ) {
						$variable_product_exists_id = wc_get_product_id_by_sku( $key . '_parent' );
						if ( ! $variable_product_exists_id ) {
							$variable_product_exists_id = b2b_create_variable_product( $key, $variation_product );
						}

						$response['variable'][] = $variable_product_exists_id;
					}
				}
			}
		}

		print_r( $response );
		die();
	}
}

/**
 * B2B create simple product
 *
 * @param  array $product_item  product item array.
 * @return [type]               [description]
 */
function b2b_create_simple_product( $product_item ) {
	$product_sku        = isset( $product_item['PARTNAME'] ) ? $product_item['PARTNAME'] : '';
	$product_title      = isset( $product_item['PARTDES'] ) ? $product_item['PARTDES'] : '';
	$product_base_price = isset( $product_item['BASEPLPRICE'] ) ? $product_item['BASEPLPRICE'] : '';
	$product_family     = isset( $product_item['FAMILYNAME'] ) ? $product_item['FAMILYNAME'] : '';
	$product_mpart_name = isset( $product_item['MPARTNAME'] ) ? $product_item['MPARTNAME'] : '';
	$product_part_quant = isset( $product_item['ADVA_PARTQUANT'] ) ? $product_item['ADVA_PARTQUANT'] : '';

	$product_object = new WC_Product_Simple();

	// Set product name.
	$product_object->set_name( $product_title );

	// Set product status.
	$product_object->set_status( 'publish' );

	// Set if the product is featured. | bool.
	$product_object->set_featured( false );

	// Set catalog visibility. | string $visibility Options: ‘hidden’, ‘visible’, ‘search’ and ‘catalog’.
	$product_object->set_catalog_visibility( 'visible' );

	// Set product description.
	$product_object->set_description( '' );

	// Set product short description.
	$product_object->set_short_description( '' );

	// Set SKU.
	$product_object->set_sku( $product_sku );

	// Set the product’s active price.
	$product_object->set_price( $product_base_price );

	$product_object->set_regular_price( $product_base_price );

	$new_product_id = $product_object->save();

	if ( $new_product_id ) {
		update_field( 'partname', $product_sku, $new_product_id );
		update_field( 'partdes', $product_title, $new_product_id );
		update_field( 'baseplprice', $product_base_price, $new_product_id );
		update_field( 'familyname', $product_family, $new_product_id );
		update_field( 'mpartname', $product_mpart_name, $new_product_id );
		update_field( 'adva_partquant', $product_part_quant, $new_product_id );

		return $new_product_id;
	} else {
		return false;
	}

}

/**
 * B2B create variable product
 *
 * @param  string $link_variation  variation link.
 * @param  array  $product_item  product item array.
 * @return [type]               [description]
 */
function b2b_create_variable_product( $link_variation, $product_item ) {

	$product_sku        = isset( $product_item['PARTNAME'] ) ? $product_item['PARTNAME'] : '';
	$product_title      = isset( $product_item['PARTDES'] ) ? $product_item['PARTDES'] : '';
	$product_base_price = isset( $product_item['BASEPLPRICE'] ) ? $product_item['BASEPLPRICE'] : '';
	$product_family     = isset( $product_item['FAMILYNAME'] ) ? $product_item['FAMILYNAME'] : '';
	$product_mpart_name = isset( $product_item['MPARTNAME'] ) ? $product_item['MPARTNAME'] : '';
	$product_part_quant = isset( $product_item['ADVA_PARTQUANT'] ) ? $product_item['ADVA_PARTQUANT'] : '';

	$product_variation_names        = array();
	$product_variation_names_string = '';

	if ( $product_item ) {
		foreach ( $product_item as $item ) {
			$product_variation_names[] = $item['ADVA_PARTQUANT'];
		}
	}

	if ( $product_variation_names ) {
		$product_variation_names_string = implode( ' | ', $product_variation_names );
	}

	$exists_variable_product = wc_get_product_id_by_sku( $link_variation . '_parent' );
	if ( $exists_variable_product ) {
		$new_product_id = $exists_variable_product;
	} else {

		// Create a variable product with a size (kmusot) attribute.
		$product   = new WC_Product_Variable();
		$attribute = new WC_Product_Attribute();

		$attribute->set_id( 0 );
		$attribute->set_name( 'size' );

		if ( $product_variation_names_string ) {
			$attribute->set_options( explode( WC_DELIMITER, $product_variation_names_string ) );
		}
		$attribute->set_visible( true );
		$attribute->set_variation( true );

		$product->set_name( $link_variation );
		$product->set_sku( $link_variation . '_parent' );
		$product->set_attributes( array( $attribute ) );

		$new_product_id = $product->save();

		foreach ( $product_item as $item ) {
			// Create a new variation with the color 'green'.
			$variation = new WC_Product_Variation();
			$variation->set_parent_id( $product->get_id() );

			$variation->set_attributes( array( 'size' => $item['ADVA_PARTQUANT'] ) );

			$variation->set_regular_price( $item['BASEPLPRICE'] );
			$variation->set_sku( $item['PARTNAME'] );
			$variation->set_name( $item['ADVA_PARTQUANT'] );

			$variation->set_status( 'publish' );
			$variation->save();

			// Now update some value unrelated to attributes.
			$variation = wc_get_product( $variation->get_id() );
			$variation->set_status( 'publish' );
			$variation->save();
		}
	}

	if ( $new_product_id ) {
		update_field( 'partname', $product_sku, $new_product_id );
		update_field( 'partdes', $product_title, $new_product_id );
		update_field( 'baseplprice', $product_base_price, $new_product_id );
		update_field( 'familyname', $product_family, $new_product_id );
		update_field( 'mpartname', $product_mpart_name, $new_product_id );
		update_field( 'adva_partquant', $product_part_quant, $new_product_id );
	}

	return $new_product_id;

}

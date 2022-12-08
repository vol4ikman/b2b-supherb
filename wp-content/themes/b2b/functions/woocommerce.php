<?php
/**
 * WooCommerce main functions
 *
 * @package WordPress
 */

// Remove breadcrumbs.
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

// Remove product rating.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10, 0 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40, 0 );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 6 );
add_action( 'woocommerce_single_product_summary', 'b2b_single_product_price_wrapper', 7 );

add_action( 'woocommerce_product_meta_end', 'form_of_submission' ); // צורת הגשה.

add_action( 'woocommerce_single_product_summary', 'custom_product_subtitle', 5 );
add_action( 'woocommerce_single_product_summary', 'b2b_add_to_favorites_products', 30 );
add_action( 'woocommerce_after_single_product_summary', 'b2b_product_pro', 12 );

add_filter( 'woocommerce_account_menu_items', 'b2b_my_account_links' );
add_filter( 'woocommerce_checkout_fields', 'b2b_override_checkout_fields' );
add_filter( 'woocommerce_get_price_html', 'wpa83367_price_html', 100, 2 );

add_action( 'woocommerce_before_calculate_totals', 'b2b_add_user_custom_price' );

function b2b_add_user_custom_price( $cart_object ) {
    $custom_price = 10; // This will be your custome price  
    $target_product_id = 598;

	$user_id             = get_current_user_id();
	$customer_name_id    = get_user_meta( $user_id, 'customer_name_id', true );
	
    foreach ( $cart_object->cart_contents as $value ) {
		
		$sku = $value['data']->get_sku();
		
		if( $sku && $customer_name_id ) {
			$product_price_for_customer = get_final_product_price( $customer_name_id, $sku );
			if( $product_price_for_customer && is_array($product_price_for_customer) ) {
				$product_price_for_customer = reset( $product_price_for_customer );
				if( isset( $product_price_for_customer['PRICE'] ) && $product_price_for_customer['PRICE'] ) {
					$value['data']->set_price( $product_price_for_customer['PRICE'] );
				}
			}
		} else {
			print_r("Customer name or SKU is invalid");
			die();
		}
    }
}

function wpa83367_price_html( $price, $product ){
	if( 'simple' === $product->get_type() ) {
		$stock = $product->get_stock_quantity();
		$stock_message    = '';
		$stock_html       = '';
		
		if( $stock && $stock < 300 ) {
			$stock_message = 'חסר במלאי';
		}
		if ( $stock_message ) : ?>
			<?php ob_start(); ?>
			<div class="b2b-single-product-stock-message">
				<p class="stock-message stock in-stock">
					<?php echo esc_html( $stock_message ); ?>
				</p>
			</div>
			<?php $stock_html = ob_get_clean(); ?>
		<?php endif;
		return $stock_html . $price;
	}
	return $price;
}

/**
 * B2B single_product_price_wrapper
 */
function b2b_single_product_price_wrapper() {
	global $product;
	$stock = $product->get_stock_quantity();
	$stock_message = '';
	if( $stock && $stock < 300 ) {
		$stock_message = 'חסר במלאי';
	}
	?>
	<div class="b2b-single-product-price-wrapper">
		<div class="b2b-single-product-price">
			price
		</div>
		<?php if ( $stock_message ) : ?>
			<div class="b2b-single-product-stock-message">
				<p class="stock-message stock in-stock">
					<?php echo esc_html( $stock_message ); ?>
				</p>
			</div>
		<?php endif; ?>

		<a href="#" class="replacement-products">למוצרים תחליפיים</a>
	</div>
	<?php
}

/**
 * Form _of_submission
 */
function form_of_submission() {
	$form_of_submission = get_field( 'form_of_submission', get_the_ID() );
	if ( $form_of_submission ) {
		?>
		<span class="form_of_submission">
			צורת הגשה: <?php echo esc_html( $form_of_submission ); ?>
		</span>
		<?php
	}
}

/**
 * Product_subtitle
 */
function custom_product_subtitle() {
	$product_subtitle = get_field( 'subtitle', get_the_ID() );
	if ( $product_subtitle ) {
		?>
		<div class="product-subtitle">
			<?php echo wp_kses_post( $product_subtitle ); ?>
		</div>
		<?php
	}
}

/**
 * Add to favorites
 */
function b2b_add_to_favorites_products() {
	$_product        = wc_get_product( get_the_ID() );
	$current_user_id = 'user_' . get_current_user_id();
	$user_products   = get_field( 'user_products', $current_user_id ) ? get_field( 'user_products', $current_user_id ) : array();
	$item_id         = $_product->get_id();
	$label           = 'הוספה למוצרים הקבועים שלי';

	if ( in_array( $item_id, $user_products ) ) { //phpcs:ignore.
		$user_products[ $item_id ] = $item_id;
		update_field( 'user_products', $user_products, $current_user_id );
		$label = 'נוסף למוצרים הקבועים שלי';
	}
	?>
	<div class="user-favorites-product">
		<a href="#" class="add-to-my-products" data-item-id="<?php echo esc_html( $_product->get_id() ); ?>">
			<div class="favorites-icon"></div>
			<div class="favorites-title">
				<?php echo esc_html( $label ); ?>
			</div>
		</a>
	</div>
	<?php
}

/**
 * B2B product_pro banner
 */
function b2b_product_pro() {
	$pro_hide = get_field( 'pro_hide' );
	$pro_purpose_only_title = get_field( 'pro_purpose_only_title' );
	$pro_purpose_only_icon  = get_field( 'pro_purpose_only_icon' );
	if ( $pro_hide ) {
		return;
	}
	if( ! $pro_purpose_only_title && ! $pro_purpose_only_icon ) {
		return;
	}
	?>
		<div class="b2b-product-pro-section">
			<?php if ( $pro_purpose_only_icon ) : ?>
				<div class="pro-icon">
					<img src="<?php echo esc_url( $pro_purpose_only_icon['url'] ); ?>"
						alt="<?php echo isset( $pro_purpose_only_icon['alt'] ) ? esc_html( $pro_purpose_only_icon['alt'] ) : esc_html( get_the_title() ); ?>">
				</div>
			<?php endif; ?>

			<?php if ( $pro_purpose_only_title ) : ?>
				<div class="pro-title">
					<?php echo esc_html( $pro_purpose_only_title ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php
}

/**
 * This function will return ranges of prices between product least and most expensive
 *
 * @return [type] [description]
 */
function get_price_range() {
	global $wpdb;

	$category_id = get_queried_object_id();

	$sql_fregments = array();

	$sql_fregments[] = "SELECT MAX( CAST( pm.meta_value as INT ) ) as max , MIN( CAST( pm.meta_value as INT ) ) as min
        FROM {$wpdb->posts} posts
        JOIN {$wpdb->postmeta} pm
        ON pm.post_id = posts.ID";

	if ( $category_id ) {
		$sql_fregments[] = "JOIN {$wpdb->term_relationships} rel ON posts.ID = rel.object_id";
	}

	$sql_fregments[] = "WHERE post_type = 'product'";

	if ( $category_id ) {
		$sql_fregments[] = "AND rel.term_taxonomy_id = '{$category_id}'";
	}

	$sql_fregments[] = "AND post_status = 'publish' AND pm.meta_key = '_price'";

	$sql = implode( ' ', $sql_fregments );

	$results = $wpdb->get_row( $sql, ARRAY_A ); //phpcs:ignore.

	$max = $results['max'];
	$min = $results['min'];

	$step = $max - $min;
	for ( $i = 5;$i > 0; $i++ ) {
		if ( $step / 5 > 10 ) {
			$step = ceil( $step / 5 );
			break;
		}
	}
	$ranges = range( $min, $max, $step );

	return $ranges;
}

/**
 * Get category_price_range
 *
 * @param  object $category WP_Term object.
 * @return [type]           [description]
 */
function get_category_price_range( $category ) {

	$min = PHP_FLOAT_MAX;
	$max = 0.00;

	$all_ids = get_posts(
		array(
			'post_type'   => 'product',
			'numberposts' => -1,
			'post_status' => 'publish',
			'fields'      => 'ids',
			'tax_query'   => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $category->slug,
				),
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'exclude-from-catalog',
					'operator' => 'NOT IN',
				),
			),
		)
	);

	foreach ( $all_ids as $id ) {
		$product = wc_get_product( $id );
		if ( $product->is_type( 'simple' ) ) {
			$min = $product->get_price() < $min ? $product->get_price() : $min;
			$max = $product->get_price() > $max ? $product->get_price() : $max;
		} elseif ( $product->is_type( 'variable' ) ) {
			$prices = $product->get_variation_prices();
			$min    = current( $prices['price'] ) < $min ? current( $prices['price'] ) : $min;
			$max    = end( $prices['price'] ) > $max ? end( $prices['price'] ) : $max;
		}
	}

	$array = array(
		'min' => $min,
		'max' => $max,
	);

	// return ' (' . wc_format_price_range( $min, $max ) . ')';
	return $array;
}

/**
 * This function will return a list of attributes of products in a certain category
 *
 * @param  string $category_id category ID.
 * @param  string $taxonmoy    taxonomy.
 * @return [type]              [description]
 */
function get_filter( $category_id, $taxonmoy ) {
	global $wpdb;
	$sql = "SELECT rel2.term_taxonomy_id as term_id , wptt.taxonomy , count( DISTINCT rel.object_id ) as count , wpt.name as name
    FROM {$wpdb->posts} posts
    JOIN {$wpdb->term_relationships} rel
    ON posts.ID = rel.object_id
    JOIN {$wpdb->term_relationships} rel2
    ON posts.ID = rel2.object_id
    JOIN {$wpdb->term_taxonomy} wptt
    ON wptt.term_id = rel2.term_taxonomy_id
    JOIN {$wpdb->terms} wpt
    ON wpt.term_id = rel2.term_taxonomy_id
    WHERE post_type = 'product'
    AND rel.term_taxonomy_id = '{$category_id}'
    AND post_status = 'publish'
    AND wptt.taxonomy = '{$taxonmoy}'
    GROUP BY rel2.term_taxonomy_id";

	$results = $wpdb->get_results( $sql, ARRAY_A ); //phpcs:ignore.

	return $results;
}

/**
 * My account_links
 *
 * @param  array $menu_links  menu links array.
 * @return array $menu_links
 */
function b2b_my_account_links( $menu_links ) {

	$menu_links['user-products'] = 'המוצרים שלי';
	$menu_links['orders']        = 'הזמנות שלי';
	$menu_links['edit-account']  = 'פרטים אישיים';

	unset( $menu_links['edit-address'] ); // Addresses.
	unset( $menu_links['downloads'] ); // Disable Downloads.
	unset( $menu_links['customer-logout'] ); // Remove Logout link.

	return $menu_links;

}

add_action(
	'init',
	function() {
		add_rewrite_endpoint( 'user-products', EP_ROOT | EP_PAGES );
	}
);

add_action(
	'woocommerce_account_user-products_endpoint',
	function() {
		wc_get_template( 'myaccount/user-products.php' );
	}
);

/**
 * Checkout fields
 *
 * @param  array $fields  fields.
 * @return array         fields
 */
function b2b_override_checkout_fields( $fields ) {
	unset( $fields['billing']['billing_company'] );
	unset( $fields['order']['order_comments'] );

	unset( $fields['billing']['billing_postcode'] );
	unset( $fields['billing']['shipping_postcode'] );
	unset( $fields['billing']['billing_address_2'] );

	$fields['billing']['billing_first_name']['placeholder'] = 'שם פרטי';
	$fields['billing']['billing_last_name']['placeholder']  = 'שם משפחה';

	$fields['billing']['billing_email']['priority']    = 20;
	$fields['billing']['billing_email']['placeholder'] = 'דוא"ל';
	$fields['billing']['billing_email']['class']       = array( 'form-row-first' );

	$fields['billing']['billing_address_1']['placeholder'] = 'כתובת למשלוח';

	$fields['billing']['billing_city']['placeholder'] = 'עיר';

	$fields['billing']['billing_phone']['placeholder'] = 'טלפון נייד';
	$fields['billing']['billing_phone']['priority']    = 25;
	$fields['billing']['billing_phone']['class']       = array( 'form-row-last' );

	return $fields;
}

add_filter( 'woocommerce_default_address_fields', 'b2b_override_default_address_fields' );
/**
 * B2B override_default_address_fields
 *
 * @param  array $address_fields fields.
 * @return array                 fields
 */
function b2b_override_default_address_fields( $address_fields ) {
	$address_fields['address_1']['class'] = array( 'form-row-first' );
	$address_fields['city']['class']      = array( 'form-row-last' );
	return $address_fields;
}

/**
 * Get_user_orders
 *
 * @param  string $user_id WP_User ID.
 * @return array          [description]
 */
function get_user_orders( $user_id ) {
	$user_orders    = array();
	$order_statuses = wc_get_order_statuses();
	unset( $order_statuses['wc-completed'] );
	unset( $order_statuses['wc-checkout-draft'] );

	$customer_orders = get_posts(
		array(
			'numberposts' => -1,
			'meta_key'    => '_customer_user',
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_value'  => $user_id,
			'post_type'   => wc_get_order_types(),
			'post_status' => array_keys( $order_statuses ),
		)
	);

	return $customer_orders;

}


add_filter( 'woocommerce_my_account_my_orders_columns', 'b2b_account_orders_columns', 10, 1 );
/**
 * B2B account_orders_columns
 *
 * @param  array $columns  columns list.
 * @return [type]          [description]
 */
function b2b_account_orders_columns( $columns ) {
	$columns = array(
		'order-number'        => 'הזמנה#',
		'order-date'          => 'תאריך ביצוע הזמנה',
		'order-customer-name' => 'הוזמן ע"י',
		'order-total'         => 'מחיר',
		'order-status'        => 'סטטוס הזמנה',
		'order-details'       => 'פרטים',
	);
	return $columns;
}

/**
 * Get variation_data_from_variation_id
 *
 * @param  string $item_id variation_id.
 * @return [type]          [description]
 */
function get_variation_data_from_variation_id( $item_id ) {
	$_product       = new WC_Product_Variation( $item_id );
	$variation_data = $_product->get_variation_attributes();
	// $variation_detail = woocommerce_get_formatted_variation( $variation_data, true );
	$variation_detail = wc_get_formatted_variation( $variation_data, true );
	return $variation_detail;
}

add_filter('woocommerce_get_availability_text', 'b2b_change_soldout_stock_message', 10, 2 );

function b2b_change_soldout_stock_message ( $text, $product) {
	$stock_quantity = $product->get_stock_quantity();
	if( $stock_quantity < 300 ) {
		$text = 'חסר במלאי';
	}
	return $text;
}

add_filter ( 'woocommerce_product_thumbnails_columns', 'bbloomer_change_gallery_columns' );
 
function bbloomer_change_gallery_columns() {
     return 1; 
}

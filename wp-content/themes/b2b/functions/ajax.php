<?php
/**
 * AJAX functions
 *
 * @package WordPress
 */

add_action( 'wp_ajax_add_product_to_user_favorites', 'add_product_to_user_favorites' );
add_action( 'wp_ajax_remove_product_from_favorites', 'remove_product_from_favorites' );
add_action( 'wp_ajax_filter_sidebar_products', 'filter_sidebar_products' );
add_action( 'wp_ajax_b2b_clear_cart', 'b2b_clear_cart' );
add_action( 'wp_ajax_load_product_quick_view', 'load_product_quick_view' );

add_action( 'wp_ajax_start_import_from_b2c', 'start_import_from_b2c' );

function start_import_from_b2c() {

	$target_endpoint = 'https://www.supherb.co.il/wp-json/wp/v2/product';
	$media_endpoint = 'https://www.supherb.co.il/wp-json/wp/v2/media/' . $attachment_id;

	$response = array(
		'error' => true,
		'html' => ''
	);
	$import_csv_file = get_field( 'import_csv_file', 'option' );
	if( ! $import_csv_file ) {
		wp_send_json( $response );
	} else {
		$attached_file = get_attached_file( $import_csv_file['ID'] );
		$csv_file = file($attached_file);
		$csv_data = [];
		foreach ($csv_file as $line) {
			
			$line_array = explode(',', $line);
			foreach( $line_array as $line_array_item ) {
				$line_array[$line_array_item] = $line_array_item;
			}
			print_r($line_array); die();
			$csv_data[] = str_getcsv($line);
		}
		print_r($csv_data); die();
		
	}

	wp_send_json( $response );
}

/**
 * B2B clear/remove cart items
 */
function b2b_clear_cart() {
	$response = array(
		'html' => '',
	);

	WC()->cart->empty_cart();
	ob_start();
	get_template_part( 'woocommerce/cart/mini-cart' );
	$response['html'] = ob_get_clean();
	wp_send_json( $response );
}
/**
 * Filter sidebar products
 */
function filter_sidebar_products() {
	$response = array(
		'error'   => true,
		'message' => '',
		'html'    => '',
		'total'   => '',
	);

	$form      = isset( $_POST['form'] ) ? $_POST['form'] : ''; //phpcs:ignore.
	$form_args = array();
	parse_str( $form, $form_args );

	$current_prcat_id  = isset( $form_args['current_prcat_id'] ) ? $form_args['current_prcat_id'] : '';
	$productcat        = isset( $form_args['productcat'] ) ? $form_args['productcat'] : array();
	$productbrand      = isset( $form_args['productbrand'] ) ? $form_args['productbrand'] : array();
	$productstype      = isset( $form_args['productstype'] ) ? $form_args['productstype'] : array();
	$max_price         = isset( $form_args['max_price'] ) ? $form_args['max_price'] : $form_args['min_max_price'];
	$category_order_by = isset( $form_args['category-order-by'] ) ? $form_args['category-order-by'] : '';

	$filter_args = array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
	);

	if ( $productbrand ) {
		$filter_args['tax_query'][] = array(
			'taxonomy' => 'product_brand',
			'terms'    => $productbrand,
		);
	}

	if ( $productstype ) {
		$filter_args['tax_query'][] = array(
			'taxonomy' => 'product_stype',
			'terms'    => $productstype,
		);
	}

	if ( $productcat ) {
		$filter_args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'terms'    => $productcat,
		);
	}

	if ( $max_price ) {
		$filter_args['meta_query'][] = array(
			'key'     => '_price',
			'value'   => $max_price,
			'compare' => '<=',
		);
	}

	switch ( $category_order_by ) {
		case 'date_asc':
			$filter_args['orderby'] = 'publish_date';
			$filter_args['order']   = 'ASC';
			break;
		case 'date_desc':
			$filter_args['orderby'] = 'publish_date';
			$filter_args['order']   = 'DESC';
			break;
		case 'price_asc':
			$filter_args['orderby']  = 'meta_value_num';
			$filter_args['meta_key'] = '_price'; //phpcs:ignore.
			$filter_args['order']    = 'ASC';
			break;
		case 'price_desc':
			$filter_args['orderby']  = 'meta_value_num';
			$filter_args['meta_key'] = '_price'; //phpcs:ignore.
			$filter_args['order']    = 'DESC';
			break;
	}

	$cat_products = new WP_Query( $filter_args );

	if ( $cat_products->have_posts() ) {
		ob_start();
		while ( $cat_products->have_posts() ) {
			$cat_products->the_post();
			get_template_part( 'inc/global/product-block', 'item' );
		}
		wp_reset_postdata();
		$response['html']  = ob_get_clean();
		$response['error'] = false;
		$response['total'] = count( $cat_products->posts );
	}

	wp_send_json( $response );

}
/**
 * Remove product from favorites
 */
function remove_product_from_favorites() {
	$current_user_id = 'user_' . get_current_user_id();

	$user_products = get_field( 'user_products', $current_user_id ) ? get_field( 'user_products', $current_user_id ) : array();

	$user_products_new = array();

	$response = array(
		'error'         => true,
		'message'       => '',
		'user_products' => $user_products,
		'item_id'       => '',
	);

	$item_id = isset( $_POST['item_id'] ) ? sanitize_text_field( $_POST['item_id'] ) : ''; //phpcs:ignore.

	if ( $user_products ) {
		foreach ( $user_products as $user_product ) {
			if ( (int) $user_product !== (int) $item_id ) {
				$user_products_new[] = $user_product;
			}
		}
	}

	$update_field = update_field( 'user_products', $user_products_new, $current_user_id );

	if ( $update_field ) {
		$response['user_products'] = get_field( 'user_products', $current_user_id );
		$response['error']         = false;
		$response['item_id']       = $item_id;
	}

	wp_send_json( $response );

}
/**
 * Add product_to_user_favorites
 */
function add_product_to_user_favorites() {

	$current_user_id = 'user_' . get_current_user_id();

	$user_products = get_field( 'user_products', $current_user_id ) ? get_field( 'user_products', $current_user_id ) : array();

	$response = array(
		'error'         => true,
		'message'       => '',
		'user_products' => $user_products,
		'item_id'       => '',
	);

	$item_id = isset( $_POST['item_id'] ) ? sanitize_text_field( $_POST['item_id'] ) : ''; //phpcs:ignore.

	if ( ! $item_id ) {
		wp_send_json( $response );
	} else {
		$response['item_id'] = $item_id;

		if ( ! in_array( $item_id, $user_products ) ) { //phpcs:ignore.
			$user_products[ $item_id ] = $item_id;
			update_field( 'user_products', $user_products, $current_user_id );
			$response['message'] = 'נוסף למוצרים הקבועים שלי';
		} else {
			$response['message'] = 'מוצר כבר קיים ברשימת המוצרים שלי.';
		}

		$response['user_products'] = get_field( 'user_products', $current_user_id );
		$response['error']         = false;

		wp_send_json( $response );

	}
}

/**
 * Load product_quick_view
 */
function load_product_quick_view() {

	$response = array(
		'html'          => '',
		'product_id'    => '',
		'product_url'   => '',
		'product_share' => '',
	);

	$item_id = isset( $_POST['item_id'] ) ? sanitize_text_field( $_POST['item_id'] ) : ''; //phpcs:ignore.

	if ( $item_id ) {
		$response['product_id']  = $item_id;
		$response['product_url'] = get_the_permalink( $item_id );

		ob_start();
			get_template_part( 'inc/global/product', 'share', array( 'item_id' => $item_id ) );
		$response['product_share'] = ob_get_clean();

		$products = new WP_Query(
			array(
				'post_type'      => 'product',
				'posts_per_page' => 1,
				'post__in'       => array( $item_id ),
			)
		);
		ob_start();
		?>
		<div class="quick-view-product-inner">

			<button type="button" class="close-quick-view">
				<img src="<?php echo esc_url( THEME ); ?>/images/close-floating-form.png" alt="">
			</button>

			<div class="view-product">

				<?php while ( $products->have_posts() ) : ?>
					<?php $products->the_post(); ?>

					<?php wc_get_template_part( 'content', 'single-product' ); ?>

					<?php
				endwhile;
				wp_reset_postdata(); // end of the loop.
				?>

			</div>
		</div>
		<?php
		$response['html'] = ob_get_clean();
	}

	wp_send_json( $response );
}
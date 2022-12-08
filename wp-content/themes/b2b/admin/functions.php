<?php
/**
 * Admin functions
 *
 * @package WordPress
 */

add_action( 'login_enqueue_scripts', 'b2b_admin_login_stylesheet' );
add_filter( 'login_headerurl', 'b2b_login_logo_url' );

/**
 * B2B admin_login_stylesheet
 */
function b2b_admin_login_stylesheet() {
	wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/admin/css/admin-style.css' );
}

/**
 * B2B login_logo_url
 *
 * @return string URL
 */
function b2b_login_logo_url() {
	return home_url();
}

add_filter( 'manage_edit-shop_order_columns', 'b2b_custom_shop_order_column', 20 );
/**
 * B2B custom_shop_order_column
 *
 * @param  array $columns columns.
 * @return array          [description]
 */
function b2b_custom_shop_order_column( $columns ) {
	$columns['priority_order_id'] = 'Priority order ID';
	return $columns;
}

add_action( 'manage_shop_order_posts_custom_column', 'custom_orders_list_column_content', 20, 2 );
/**
 * Adding custom fields meta data for each new column
 *
 * @param  string $column   column name.
 * @param  string $post_id  post id.
 */
function custom_orders_list_column_content( $column, $post_id ) {
	if ( 'priority_order_id' === $column ) {
		echo '<strong>' . esc_html( get_field( 'ordname', $post_id ) ) . '</strong>';
	}
}

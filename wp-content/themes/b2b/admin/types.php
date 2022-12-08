<?php
/**
 * Custom taxonomies and post types
 *
 * @package WordPress
 */

if ( ! function_exists( 'product_brand_taxonomy' ) ) {

	/**
	 * Register Custom Taxonomy
	 */
	function product_brand_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Brands', 'Taxonomy General Name', 'b2b' ),
			'singular_name'              => _x( 'Brand', 'Taxonomy Singular Name', 'b2b' ),
			'menu_name'                  => __( 'Brand', 'b2b' ),
			'all_items'                  => __( 'All Brands', 'b2b' ),
			'parent_item'                => __( 'Parent Brand', 'b2b' ),
			'parent_item_colon'          => __( 'Parent Brand:', 'b2b' ),
			'new_item_name'              => __( 'New Item Brand', 'b2b' ),
			'add_new_item'               => __( 'Add New Brand', 'b2b' ),
			'edit_item'                  => __( 'Edit Brand', 'b2b' ),
			'update_item'                => __( 'Update Brand', 'b2b' ),
			'view_item'                  => __( 'View Brand', 'b2b' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'b2b' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'b2b' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'b2b' ),
			'popular_items'              => __( 'Popular Items', 'b2b' ),
			'search_items'               => __( 'Search Brands', 'b2b' ),
			'not_found'                  => __( 'Not Found', 'b2b' ),
			'no_terms'                   => __( 'No items', 'b2b' ),
			'items_list'                 => __( 'Items list', 'b2b' ),
			'items_list_navigation'      => __( 'Items list navigation', 'b2b' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
		);
		register_taxonomy( 'product_brand', array( 'product' ), $args );

	}
	add_action( 'init', 'product_brand_taxonomy', 0 );

}

if ( ! function_exists( 'product_ctype_taxonomy' ) ) {

	/**
	 * Register Product type taxonommy
	 */
	function product_ctype_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Product Type', 'Taxonomy General Name', 'b2b' ),
			'singular_name'              => _x( 'Product Type', 'Taxonomy Singular Name', 'b2b' ),
			'menu_name'                  => __( 'Product Type', 'b2b' ),
			'all_items'                  => __( 'All Product Types', 'b2b' ),
			'parent_item'                => __( 'Parent Brand', 'b2b' ),
			'parent_item_colon'          => __( 'Parent Brand:', 'b2b' ),
			'new_item_name'              => __( 'New Item Product Type', 'b2b' ),
			'add_new_item'               => __( 'Add New Product Type', 'b2b' ),
			'edit_item'                  => __( 'Edit Product Type', 'b2b' ),
			'update_item'                => __( 'Update Brand', 'b2b' ),
			'view_item'                  => __( 'View Brand', 'b2b' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'b2b' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'b2b' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'b2b' ),
			'popular_items'              => __( 'Popular Items', 'b2b' ),
			'search_items'               => __( 'Search Product Types', 'b2b' ),
			'not_found'                  => __( 'Not Found', 'b2b' ),
			'no_terms'                   => __( 'No items', 'b2b' ),
			'items_list'                 => __( 'Items list', 'b2b' ),
			'items_list_navigation'      => __( 'Items list navigation', 'b2b' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
		);
		register_taxonomy( 'product_ctype', array( 'product' ), $args );

	}
	add_action( 'init', 'product_ctype_taxonomy', 0 );

}

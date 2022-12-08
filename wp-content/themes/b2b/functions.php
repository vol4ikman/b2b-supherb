<?php
/**
 * Main theme functions
 *
 * @package WordPress
 */

add_filter(
	'doing_it_wrong_trigger_error',
	function () {
		return false;
	},
	10,
	0
);

get_template_part( 'functions/theme-dependencies' );

if ( ! isset( $content_width ) ) {
	$content_width = 1024;
}
if ( function_exists( 'add_theme_support' ) ) {
	// Add Menu Support.
	add_theme_support( 'menus' );
	// Add Thumbnail Theme Support.
	add_theme_support( 'post-thumbnails' );

	// WooCommerce support.
	add_theme_support( 'woocommerce' );
	// add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	add_image_size( 'large', 1000, '', true );
	add_image_size( 'medium', 450, '', true );
	add_image_size( 'small', 250, '', true );
	add_image_size( 'size400', 400, 400, true );
	add_image_size( 'user-product-table', 120, 120, true );
	// Theme Support fot yoast.
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'gallery', 'script', 'style' ) );

}

// Remove default galleries css style.
add_filter( 'use_default_gallery_style', '__return_false' );

// Disable Gutenberg on the back end.
add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Gutenberg for widgets.
add_filter( 'use_widgets_blog_editor', '__return_false' );

add_action(
	'wp_enqueue_scripts',
	function() {
		// Remove CSS on the front end.
		wp_dequeue_style( 'wp-block-library' );

		// Remove Gutenberg theme.
		wp_dequeue_style( 'wp-block-library-theme' );

		// Remove inline global CSS on the front end.
		wp_dequeue_style( 'global-styles' );
	},
	20
);

// Remove admin bar.
//add_filter( 'show_admin_bar', '__return_false' );


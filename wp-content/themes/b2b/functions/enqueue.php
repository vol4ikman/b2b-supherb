<?php
/**
 * Load theme styles and scripts
 *
 * @package WordPress
 */

/**
 * Load styles
 */
function qs_theme_styles() {

	wp_register_style( 'sweetalert2', THEME . '/assets/css/sweetalert2.min.css', array(), THEME_VER . '-v-' . time(), 'all' );
	wp_enqueue_style( 'sweetalert2' );

	wp_register_style( 'main-style', THEME . '/assets/css/style.css', array(), THEME_VER . '-v-' . time(), 'all' );
	wp_enqueue_style( 'main-style' );
	if ( is_product() ) {
		wp_register_style( 'single-product', THEME . '/assets/css/single-product.css', array(), THEME_VER . '-v-' . time(), 'all' );
		wp_enqueue_style( 'single-product' );
	}
	if ( is_product_category() ) {
		wp_register_style( 'jquery-ui', '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css', array(), THEME_VER, 'all' );
		wp_enqueue_style( 'jquery-ui' );
	}
	wp_register_style( 'responsive', THEME . '/assets/css/responsive.css', array(), THEME_VER . '-v-' . time(), 'all' );
	wp_enqueue_style( 'responsive' );
	// Accessibility style.
	wp_register_style( 'a11y', CSS_INC . 'a11y.css', array(), THEME_VER, 'all' );
	wp_enqueue_style( 'a11y' );
}
/**
 * Load scripts
 */
function qs_theme_scripts() {
	date_default_timezone_set('Asia/Jerusalem'); //phpcs:ignore
	// Move jquery to footer.
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', includes_url( 'js/jquery/jquery.js' ), array(), THEME_VER, true );
	wp_enqueue_script( 'jquery' );

	wp_register_script( 'g-translate', '//translate.google.com/translate_a/element.js', array( 'jquery' ), THEME_VER, true );
	
	wp_register_script( 'assets', JS_INC . 'assets.min.js', array( 'jquery' ), THEME_VER, true );
	wp_enqueue_script( 'assets' );

	wp_register_script( 'sweetalert2', THEME . '/build/js/sweetalert2.js', array( 'jquery' ), THEME_VER, true );
	wp_enqueue_script( 'sweetalert2' );

	
	wp_register_script( 'slick', JS_ASSETS_INC . 'slick.min.js', array( 'jquery' ), THEME_VER, true );
	wp_register_script( 'blockUI', JS_INC . 'jquery.blockUI.js', array( 'jquery' ), THEME_VER, true );
	if ( is_product_category() ) {
		wp_enqueue_script( 'jquery-ui-slider' );
	}
	wp_register_script( 'scripts', JS_INC . 'scripts.js', array( 'jquery' ), THEME_VER . '-v-' . time(), true );
	wp_register_script( 'a11y', JS_INC . 'a11y.js', array( 'jquery' ), THEME_VER, true );

	

	$site_settings = array(
		'home_url'     => get_home_url(),
		'theme_url'    => THEME,
		'ajaxurl'      => admin_url( 'admin-ajax.php' ),
		'current_date' => date( 'd/m/Y H:i:s' ), //phpcs:ignore
	);
	wp_localize_script( 'scripts', 'site_settings', $site_settings );

	wp_enqueue_script( 'g-translate' );
	wp_enqueue_script( 'slick' );
	wp_enqueue_script( 'scripts' );
	wp_enqueue_script( 'a11y' );

	wp_register_script( 'ajax_custom_script', THEME . '/build/js/ajax.js', array( 'scripts' ), THEME_VER . '-v-' . time(), true );
	wp_localize_script( 'ajax_custom_script', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
	wp_enqueue_script( 'ajax_custom_script' );
}
/**
 * Load custom admin styles
 */
function qs_load_custom_admin_style() {
	wp_register_style( 'qs-admin-style', get_template_directory_uri() . '/admin/css/admin-style.css', false, '1.0.0' );
	wp_register_script( 'qs-admin-script', get_template_directory_uri() . '/admin/js/admin-script.js', array( 'jquery' ), THEME_VER, true );

	wp_enqueue_style( 'qs-admin-style' );
	wp_enqueue_script( 'qs-admin-script' );
}

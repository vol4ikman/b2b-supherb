<?php
/**
 * Admin options
 *
 * @package WordPress
 */

// If Polylang is installed.
if ( function_exists( 'pll_the_languages' ) ) {
	get_template_part( 'functions/polylang-acf' );
}

if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page(
		array(
			'page_title' => 'הגדרות מערכת',
			'menu_title' => 'הגדרות מערכת',
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
		)
	);

	// Add sub page.
	acf_add_options_sub_page(
		array(
			'page_title'  => __( 'הודעות מערכת' ),
			'menu_title'  => __( 'System messages', 'b2b' ),
			'parent_slug' => 'theme-general-settings',
		)
	);

	get_template_part( 'admin/acf-options-import' );

}

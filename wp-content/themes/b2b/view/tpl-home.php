<?php
/**
 * Template Name: Homepage Template
 *
 * @package WordPress
 */

if ( is_user_logged_in() ) {
	wp_safe_redirect( get_permalink( wc_get_page_id( 'shop' ) ) );
	exit;
}
get_header( 'login' );
?>
	<section class="section" id="login-screen-wrapper">
		<?php get_template_part( 'inc/login-page/screen', '1' ); ?>
	</section>
<?php
get_footer( 'login' );

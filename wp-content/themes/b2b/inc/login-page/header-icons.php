<?php
/**
 * Login Header Icons/logos
 *
 * @package WordPress
 */

$login_header_logo_1 = get_field( 'login_header_logo_1', 'option' );
$login_header_logo_2 = get_field( 'login_header_logo_2', 'option' );
?>

<div class="login-header-icons">
	<a href="<?php echo esc_url( get_home_url() ); ?>" class="login_header_logo_1">
		<img src="<?php echo esc_url( $login_header_logo_1['url'] ); ?>" alt="">
	</a>
	<a href="<?php echo esc_url( get_home_url() ); ?>" class="login_header_logo_2">
		<img src="<?php echo esc_url( $login_header_logo_2['url'] ); ?>" alt="">
	</a>
</div>

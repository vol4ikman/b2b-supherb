<?php
/**
 * Template Name: Register Template
 *
 * @package WordPress
 */

get_header( 'login' );
?>
	<section class="section" id="register-screen-wrapper">
		<?php get_template_part( 'inc/register-page/register' ); ?>
	</section>
<?php
get_footer( 'login' );

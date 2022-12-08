<?php
/**
 * Template Name: Cart page
 *
 * @package WordPress
 */

defined( 'ABSPATH' ) || exit;

get_template_part( 'inc/global/access', 'denied' );

get_header();
?>

<main class="main-cart-page">
	<div class="container">
		<div class="cart-page-inner">
			<h1 class="cart-page-title"><?php echo esc_html( get_the_title() ); ?></h1>
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</main>

<?php
get_footer();

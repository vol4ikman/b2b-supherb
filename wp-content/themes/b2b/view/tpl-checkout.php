<?php
/**
 * Template Name: Checkout page
 *
 * @package WordPress
 */

defined( 'ABSPATH' ) || exit;

get_template_part( 'inc/global/access', 'denied' );

get_header();
?>

<main class="main-checkout-page">
	<div class="container">
		<div class="cart-page-inner">

			<h1 class="checkout-page-title"><?php echo esc_html( get_the_title() ); ?></h1>

			<div class="cart-page-inner-holder">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				wp_reseT_postdata();
				?>

				<div class="checkout-page-cart-items">
					<?php get_template_part( 'inc/global/chekout-cart' ); ?>
				</div>
			</div>
			
		</div>
	</div>
</main>

<?php
get_footer();

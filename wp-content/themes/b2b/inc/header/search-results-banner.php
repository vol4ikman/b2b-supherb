<?php
/**
 * Search results header Banner
 *
 * @package WordPress
 */

$shop_header_image = get_field( 'sr_header_image', 'option' );
?>

<section class="section header-banner shop-header-banner" style="background-image:url(<?php echo esc_url( $shop_header_image['url'] ); ?>);">

	<div class="header-banner-inner">

		<div class="banner-inner-content">
			<div class="banner-inner-title">
				<?php
					echo sprintf( __( '%s תוצאות חיפוש עבור ', 'b2b' ), esc_html( $wp_query->found_posts ) );
					echo '"' . get_search_query() . '"';
				?>
			</div>
		</div>

	</div>

</section>

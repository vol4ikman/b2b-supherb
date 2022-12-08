<?php
/**
 * Shop Header Banner
 *
 * @package WordPress
 */

$shop_header_image        = get_field( 'shop_header_image', 'option' );
$shop_header_image_mobile = get_field( 'shop_header_image_mobile', 'option' );
$shop_header_title        = get_field( 'shop_header_title', 'option' );
$shop_header_subtitle     = get_field( 'shop_header_subtitle', 'option' );
$shop_header_desc         = get_field( 'shop_header_desc', 'option' );
?>

<section class="section header-banner shop-header-banner" style="background-image:url(<?php echo esc_url( $shop_header_image['url'] ); ?>);">

	<?php if ( wp_is_mobile() ) : ?>
		<div class="mobile-banner-image">
			<img src="<?php echo esc_url( $shop_header_image_mobile['url'] ); ?>" alt="">
		</div>
	<?php endif; ?>

	<div class="header-banner-inner">

		<div class="banner-inner-content">
			<?php if ( $shop_header_title ) : ?>
				<div class="banner-inner-title">
					<?php echo esc_html( $shop_header_title ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $shop_header_subtitle ) : ?>
				<div class="banner-inner-subtitle">
					<?php echo esc_html( $shop_header_subtitle ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $shop_header_desc ) : ?>
				<div class="banner-inner-desc">
					<?php echo esc_html( $shop_header_desc ); ?>
				</div>
			<?php endif; ?>
		</div>

	</div>

</section>

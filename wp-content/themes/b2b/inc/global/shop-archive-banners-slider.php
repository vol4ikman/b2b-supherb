<?php
/**
 * Product archive shop banner slider
 *
 * @package WordPress
 */

$banners_slider = get_field( 'banners_slider', 'option' );
if ( ! $banners_slider ) {
	return;
}
?>

<div class="shop-archive-banners-slider">
	<div class="banners-slider-inner">
		<?php
		foreach ( $banners_slider as $banners_slide ) :
			$banner_img = isset( $banners_slide['banner'] ) ? $banners_slide['banner']['url'] : '';
			if ( wp_is_mobile() ) {
				$banner_img = isset( $banners_slide['banner_mobile'] ) ? $banners_slide['banner_mobile']['url'] : '';
			}
			$banner_link = isset( $banners_slide['link'] ) ? $banners_slide['link'] : '';
			?>
			<div class="banner-slide">
				<a href="<?php echo esc_url( $banner_link ); ?>">
					<img src="<?php echo esc_url( $banner_img ); ?>" alt="">
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</div>

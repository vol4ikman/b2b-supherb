<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

$product_custom_tabs = get_field( 'product_custom_tabs' );

$b2b_product_tabs = array();

if ( get_field( 'tab_product_description' ) ) {
	$b2b_product_tabs['tab_product_description'] = array(
		'title'   => 'תיאור מוצר',
		'content' => get_field( 'tab_product_description' ),
	);
}

if ( get_field( 'tab_advantages' ) ) {
	$b2b_product_tabs['tab_advantages'] = array(
		'title'   => 'יתרונות',
		'content' => get_field( 'tab_advantages' ),
	);
}

if ( get_field( 'tab_advantages' ) ) {
	$b2b_product_tabs['tab_guide'] = array(
		'title'   => 'הנחיות שימוש',
		'content' => get_field( 'tab_guide' ),
	);
}

if ( $product_custom_tabs ) {
	foreach ( $product_custom_tabs as $key => $data ) {
		$b2b_product_tabs[ 'tab_key_' . $key ] = array(
			'title'   => $data['tab_title'],
			'content' => $data['tab_content'],
		);
	}
}

if ( ! empty( $b2b_product_tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper">
		<div class="desktop-view">
			<ul class="tabs wc-tabs" role="tablist">
				<?php foreach ( $b2b_product_tabs as $key => $product_tab ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
						<a href="#tab-<?php echo esc_attr( $key ); ?>">
							<?php // echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
							<?php echo wp_kses_post( $product_tab['title'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php foreach ( $b2b_product_tabs as $key => $product_tab ) : ?>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
					<?php
					// if ( isset( $product_tab['callback'] ) ) {
					// call_user_func( $product_tab['callback'], $key, $product_tab );
					// }
					?>
					<?php echo wp_kses_post( $product_tab['content'] ); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="mobile-view">
			<ul class="tabs wc-tabs" role="tablist">
				<?php foreach ( $b2b_product_tabs as $key => $product_tab ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
						<a href="#tab-<?php echo esc_attr( $key ); ?>">
							<?php echo wp_kses_post( $product_tab['title'] ); ?>
						</a>
						<div class="accordion-content">
							<?php echo wp_kses_post( $product_tab['content'] ); ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php get_template_part( 'inc/global/product', 'share' ); ?>
		</div>

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>



<?php endif; ?>

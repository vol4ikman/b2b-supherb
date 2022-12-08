<?php
/**
 * Sales products section
 *
 * @package WordPress
 */

$sales_product_title = get_field( 'sales_product_title', 'option' );
$sales_product_ids   = get_field( 'sales_product_ids', 'option' );

if ( $sales_product_ids ) {
	$sales_products = new WP_Query(
		array(
			'post_type' => 'product',
			'post__in'  => $sales_product_ids,
			'orderby'   => 'post__in',
		)
	);
}
?>

<div class="section-sales-products">
	<?php if ( $sales_product_title ) : ?>
		<h3><?php echo esc_html( $sales_product_title ); ?></h3>
	<?php endif; ?>
	<?php
	while ( $sales_products->have_posts() ) {
		$sales_products->the_post();
		get_template_part( 'inc/global/product-block', 'item' );
	}
	wp_reset_postdata();
	?>
</div>

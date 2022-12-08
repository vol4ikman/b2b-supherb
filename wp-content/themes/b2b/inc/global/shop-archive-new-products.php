<?php
/**
 * New products section
 *
 * @package WordPress
 */

$new_products_section_title = get_field( 'new_products_section_title', 'option' );
$new_products_ids           = get_field( 'new_products', 'option' );

if ( $new_products_ids ) {
	$new_products = new WP_Query(
		array(
			'post_type' => 'product',
			'post__in'  => $new_products_ids,
			'orderby'   => 'post__in',
		)
	);
}
?>

<div class="section-new-products">
	<?php if ( $new_products_section_title ) : ?>
		<h3><?php echo esc_html( $new_products_section_title ); ?></h3>
	<?php endif; ?>
	<?php
	while ( $new_products->have_posts() ) {
		$new_products->the_post();
		get_template_part( 'inc/global/product-block', 'item' );
	}
	wp_reset_postdata();
	?>
</div>

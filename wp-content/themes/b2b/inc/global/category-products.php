<?php
/**
 * New products section
 *
 * @package WordPress
 */

$current_prcat_id = get_queried_object_id();

$cat_products = new WP_Query(
	array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'tax_query' => array( //phpcs:ignore.
			array(
				'taxonomy' => 'product_cat',
				'terms'    => $current_prcat_id,
			),
		),
	)
);
?>

<div class="section-category-products">
	<?php
	while ( $cat_products->have_posts() ) {
		$cat_products->the_post();
		get_template_part( 'inc/global/product-block', 'item' );
	}
	wp_reset_postdata();
	?>
</div>

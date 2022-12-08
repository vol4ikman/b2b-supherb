<?php
/**
 * Product Archive Sidebar
 *
 * @package WordPress
 */

$current_prcat_id = get_queried_object_id();

$product_types = get_terms(
	array(
		'taxonomy'   => 'product_ctype',
		'hide_empty' => false,
	)
);

$product_brands = get_terms(
	array(
		'taxonomy'   => 'product_brand',
		'hide_empty' => false,
	)
);

$product_terms = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	)
);

$category_price_range = get_category_price_range( get_queried_object() ); // woocommerce.php.
?>

<div class="product-category-sidebar">

	<form class="product-sidebar-filter" method="post">

		<input type="hidden" name="current_prcat_id" value="<?php echo esc_html( $current_prcat_id ); ?>">
		<input type="hidden" name="max_price" value="">
		<input type="hidden" name="min_max_price" value="<?php echo esc_html( (int) $category_price_range['max'] + 1 ); ?>">
		<input type="hidden" name="category-order-by" value="">

		<div class="sidebar-cat-list sidebar-widget-item">
			<div class="sidebar-widget-title">
				מותג
			</div>
			<?php if ( $product_brands ) : ?>
				<ul>
					<?php foreach ( $product_brands as $product_term ) : ?>
						<li data-term="<?php echo esc_html( $product_term->term_id ); ?>">
							<label>
								<input type="checkbox" name="productbrand[]" value="<?php echo esc_html( $product_term->term_id ); ?>">
								<?php echo esc_html( $product_term->name ); ?>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="sidebar-cat-list sidebar-widget-item">
			<div class="sidebar-widget-title">
				קטגוריה
			</div>
			<?php if ( $product_terms ) : ?>
				<ul>
					<?php
					foreach ( $product_terms as $product_term ) :
						if ( 15 === $product_term->term_id ) {
							continue;
						}
						?>
						<li data-term="<?php echo esc_html( $product_term->term_id ); ?>">
							<label>
								<input type="checkbox" name="productcat[]" value="<?php echo esc_html( $product_term->term_id ); ?>"
								<?php echo $current_prcat_id === $product_term->term_id ? 'checked' : ''; ?>>
								<?php echo esc_html( $product_term->name ); ?>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="sidebar-cat-list sidebar-widget-item">
			<div class="sidebar-widget-title">
				סוג מוצר
			</div>
			<?php if ( $product_types ) : ?>
				<ul>
					<?php foreach ( $product_types as $product_term ) : ?>
						<li data-term="<?php echo esc_html( $product_term->term_id ); ?>">
							<label>
								<input type="checkbox" name="productstype[]" value="<?php echo esc_html( $product_term->term_id ); ?>">
								<?php echo esc_html( $product_term->name ); ?>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="sidebar-price-range sidebar-widget-item">
			<div class="sidebar-widget-title">
				טווח מחירים
			</div>
			<div class="price-range-inner">
				<div id="slider-range-max"
					data-min="<?php echo esc_html( (int) $category_price_range['min'] ); ?>"
					data-max="<?php echo esc_html( (int) $category_price_range['max'] + 1 ); ?>">
					<div id="custom-handle" class="ui-slider-handle">
						<div class="custom-handle-inner">
							<span></span>
						</div>
					</div>
				</div>
				<div class="range-labels">
					<div class="max">
						<?php echo wp_kses_post( wc_price( (int) $category_price_range['max'] ) ); ?>
					</div>
					<div class="min">
						<?php echo wp_kses_post( wc_price( (int) $category_price_range['min'] ) ); ?>
					</div>
				</div>
			</div>
		</div>

	</form>
</div>

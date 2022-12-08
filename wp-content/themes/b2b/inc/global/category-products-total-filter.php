<?php
/**
 * Order category products by
 *
 * @package WordPress
 */

global $wp_query;
$total_found_posts = $wp_query->found_posts;
?>

<div class="category-products-total-filter">
	<div class="view-total-posts">
		מציג <span class="total-counter"><?php echo esc_html( $total_found_posts ); ?></span> תוצאות
	</div>
	<button type="button" class="trigger-mobile-filters">
		<img src="<?php echo esc_url( THEME ); ?>/images/mobile-filter-icon.png" alt=""> פילטרים
	</button>
	<div class="filter-by">
		<select class="category-order-by" name="order_by">
			<option value="">מיון מוצרים לפי</option>
			<option value="date_asc">מהחדש לישן</option>
			<option value="date_desc">מהישן לחדש</option>
			<option value="price_asc">המחיר הכי גבוה</option>
			<option value="price_desc">המחיר הכי נמוך</option>
		</select>
	</div>
</div>

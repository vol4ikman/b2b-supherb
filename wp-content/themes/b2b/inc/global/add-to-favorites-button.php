<?php
/**
 * Add to favorites button
 *
 * @package WordPress
 */

$item_id    = isset( $args['item_id'] ) ? $args['item_id'] : '';
$show_title = isset( $args['show_title'] ) ? true : false;
?>
<a href="#" class="add-to-my-products" <?php echo $item_id ? 'data-item-id="' . esc_html( $item_id ) . '"' : ''; ?>>
	<span></span>
	<?php if ( $show_title ) : ?>
		הוספה למוצרים הקבועים שלי
	<?php endif; ?>
</a>

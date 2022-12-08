<?php
/**
 * Product Archive Sidebar
 *
 * @package WordPress
 */

$product_archive_sidebar_title = get_field( 'product_archive_sidebar_title', 'option' );
$product_terms                 = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	)
);
?>

<div class="product-archive-sidebar">
	<?php if ( $product_archive_sidebar_title ) : ?>
		<div class="sidebar-title">
			<?php echo esc_html( $product_archive_sidebar_title ); ?>
		</div>
	<?php endif; ?>

	<div class="sidebar-cat-list">
		<?php if ( $product_terms ) : ?>
			<ul>
				<?php
				foreach ( $product_terms as $product_term ) :
					if ( 15 === $product_term->term_id ) {
						continue;
						// escape כללי category.
					}
					$random_colors = array( '#34573e','#e38e2d','#aca4ce','#fe767e','#eedc4c' );
					$color = get_field( 'cube_color', 'product_cat_' . $product_term->term_id ) ? get_field( 'cube_color', 'product_cat_' . $product_term->term_id ) : $random_colors[array_rand($random_colors)];
					?>
					<li data-term="<?php echo esc_html( $product_term->term_id ); ?>">
						<a href="<?php echo esc_url( get_term_link( $product_term ) ); ?>">
							<span style="background-color:<?php echo $color; ?>;"></span>
							<?php echo esc_html( $product_term->name ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>

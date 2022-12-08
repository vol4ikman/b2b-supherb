<?php
/**
 * User favorite products
 *
 * @package WordPress
 */

$user_products_id = get_field( 'user_products', 'user_' . get_current_user_id() );
$user_products    = new WP_Query(
	array(
		'post_type' => array( 'product', 'product_variation' ),
		'post__in'  => $user_products_id,
	)
);
?>

<div class="wc-account-user-products">
	<h1 class="account-page-title"><?php echo esc_html( 'המוצרים שלי' ); ?></h1>
	<?php if ( $user_products->have_posts() ) : ?>
		<table class="user-products-table" cellspacing="0" cellpadding="0">
			<thead>
				<th class="is-checkbox">
					<input type="checkbox" name="select_all" value="">
				</th>
				<th class="product-meta">מוצר</th>
				<th class="product-sku">מק״ט</th>
				<th class="product-price">מחיר</th>
				<th class="product-qty">כמות</th>
				<th class="product-remove">
					<div class="product-remove-inner">
						<button type="button" class="add-to-cart-selected">הוסף את כל המוצרים הנבחרים</button>
					</div>
				</th>
			</thead>

			<tbody>
				<?php
				while ( $user_products->have_posts() ) :
					$user_products->the_post();
					$user_product      = wc_get_product( get_the_ID() );
					$user_product_type = $user_product->get_type();
					$subtitle          = get_field( 'subtitle', $user_product->get_id() );
					?>
					<tr data-pid="<?php echo esc_html( $user_product->get_id() ); ?>"
						data-type="<?php echo esc_html( $user_product_type ); ?>">
						<td class="is-checkbox">
							<input type="checkbox" name="pr_to_add_to_cart[]" value="<?php echo esc_html( $user_product->get_id() ); ?>">
						</td>
						<td class="product-meta">
							<div class="product-meta-inner">
								<div class="p-image">
									<a href="<?php echo esc_url( get_permalink( $user_product->get_id() ) ); ?>">
										<?php
										if ( has_post_thumbnail() ) :
											the_post_thumbnail( get_the_ID(), 'user-product-table' );
										else :
											$default_product_image = get_field( 'default_product_image', 'option' );
											if ( $default_product_image ) {
												$default_product_image = $default_product_image['url'];
											}
											?>
											<img src="<?php echo esc_url( $default_product_image ); ?>" alt="" width="120" height="120">
										<?php endif; ?>
									</a>
								</div>
								<div class="p-data">
									<div class="p-title">
										<?php echo esc_html( get_the_title( $user_product->get_id() ) ); ?>
										<?php if ( $subtitle ) : ?>
											<div class="subtitle">
												<?php echo wp_kses_post( $subtitle ); ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</td>
						<td class="product-sku">
							<?php echo esc_html( $user_product->get_sku() ); ?>
						</td>
						<td class="product-price">
							<div class="product-price-inner">
								<?php echo wp_kses_post( $user_product->get_price_html() ); ?>
							</div>
						</td>
						<td class="product-qty">
							<?php
							if ( 'variation' === $user_product_type ) {
								?>
								<form class="cart" action="<?php echo esc_url( get_permalink( $user_product->get_id() ) ); ?>"
									method="post" enctype="multipart/form-data">
									<div class="quantity">
										<label class="screen-reader-text" for="quantity_632c4aa0e2bf2">כמות של 60 ACNO כמוסות</label>
										<input type="number" id="quantity_632c4aa0e2bf2" class="input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="כמות" size="4" placeholder="" inputmode="numeric" autocomplete="off">
										<div class="b2b-quantity">
											<div class="b2b-quantity-inner">
												<button type="button" class="quantity-trigger plus">+</button>
												<input type="number" name="b2b-quantity" value="1" step="1" min="1" max="">
												<button type="button" class="quantity-trigger minus">-</button>
											</div>
										</div>
									</div>

									<button type="submit" name="add-to-cart" value="<?php echo esc_html( $user_product->get_id() ); ?>" class="single_add_to_cart_button button alt">הוספה לסל</button>

								</form>
								<?php
							} else {
								woocommerce_template_single_add_to_cart();
							}
							?>
						</td>
						<td class="product-remove">
							<button type="button" class="remove-product" data-id="<?php echo esc_html( get_the_ID() ); ?>"></button>
						</td>
					</tr>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</tbody>
		</table>
					<?php else : ?>
		<h3 class="no-user-products">לא נמצאו מוצרים.</h3>
	<?php endif; ?>

</div>

<?php
/**
 * Checkout Obligo out banner
 *
 * @package WordPress
 */

$obligo_out_title       = get_field( 'obligo_out_title', 'option' );
$obligo_out_description = get_field( 'obligo_out_description', 'option' );
?>

<div class="checkout-obligo-off">
	<div class="obligo-off-inner">
		<div class="icon">
			<img src="<?php echo esc_url( THEME ); ?>/images/checkout-obligo-off.png" alt="">
		</div>
		<div class="meta">
			<?php if ( $obligo_out_title ) : ?>
				<div class="title">
					<?php echo esc_html( $obligo_out_title ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $obligo_out_description ) : ?>
				<div class="desc">
					<?php echo esc_html( $obligo_out_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

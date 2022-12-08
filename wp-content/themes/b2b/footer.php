<?php
/**
 * Site footer
 *
 * @package WordPress
 */

$footer_links = get_field( 'footer_links', 'option' );
?>

	<footer class="footer" role="contentinfo">
		<div class="footer-container">
			<?php if ( $footer_links ) : ?>
				<div class="footer-links">
					<ul>
						<?php
						foreach ( $footer_links as $footer_link ) :
							$link_item   = isset( $footer_link['link'] ) ? $footer_link['link'] : array();
							$link_url    = isset( $link_item['url'] ) ? $link_item['url'] : '';
							$link_title  = isset( $link_item['title'] ) ? $link_item['title'] : '';
							$link_target = isset( $link_item['target'] ) ? $link_item['target'] : '';
							if ( ! $link_url ) {
								continue;
							}
							?>
							<li>
								<a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_html( $link_target ); ?>">
									<?php echo esc_html( $link_title ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<div class="footer-credits">
				<div class="credit-item">
					כל הזכויות שמורות לחברת אמברוזיה סופהרב בע"מ
				</div>
				<div class="credit-item">
					<a href="https://dooble.co.il" target="_blank">בניית אתרים <strong>Dooble</strong></a>
				</div>
			</div>
		</div>
	</footer>

	<?php get_template_part( 'inc/popup/floating-form' ); ?>
	<?php wc_get_template_part( 'cart/mini-cart' ); ?>
</div>

	<div class="quick-view-popup-holder"></div>

	<?php wp_footer(); ?>
	</body>
</html>

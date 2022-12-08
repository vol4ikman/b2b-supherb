<?php
/**
 * Mobile menu
 *
 * @package WordPress
 */

global $current_user_name;
$customer_service_title = get_field( 'customer_service_title', 'option' );
$customer_service_phone = get_field( 'customer_service_phone', 'option' );

$hours_title   = get_field( 'hours_title', 'option' );
$hours_content = get_field( 'hours_content', 'option' );
?>
<div class="mobile-menu-wrapper">
	<div class="mobile-menu-inner">
		<div class="mobile-menu-header">

			<div class="user-menu">
				<ul>
					<li>
						<a href="#" class="trigger-user-menu">
							<span class="icon"></span>
							<span class="user-welcome">
								שלום, <?php echo esc_html( $current_user_name ); ?>
							</span>
						</a>
						<ul class="user-submenu">
							<li>
								<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" title="איזור אישי">
									איזור אישי
								</a>
							</li>
							<li>
								<a href="<?php echo esc_url( wp_logout_url( get_home_url() ) ); ?>">התנתק</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>

			<?php get_template_part( 'inc/header/language', 'switcher' ); ?>

			<a href="<?php echo esc_url( get_home_url() ); ?>/my-account/orders/" class="account-button box-button">
				<img src="<?php echo esc_url( THEME ); ?>/images/mobile-menu-box-icon.png" alt="">
			</a>

			<a href="#" class="account-button contrast">
				<img src="<?php echo esc_url( THEME ); ?>/images/mobile-menu-contrast-icon.png" alt="">
			</a>

			<button type="button" class="close-mobile-menu">
				<img src="<?php echo esc_url( THEME ); ?>/images/mobile-menu-close.png" alt="">
			</button>

		</div>

		<div class="mobile-menu-services">
			<div class="info mobile-menu-services-info">
				<?php if ( $customer_service_phone || $customer_service_title ) : ?>
					<div class="info-item customer-service customer-service-icon">
						<?php if ( $customer_service_title ) : ?>
							<span class="title"><?php echo esc_html( $customer_service_title ); ?></span>
						<?php endif; ?>

						<?php if ( $customer_service_phone ) : ?>
							<a href="tel:<?php echo esc_html( $customer_service_phone ); ?>" class="value">
								<?php echo esc_html( $customer_service_phone ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $hours_title || $hours_content ) : ?>
					<div class="info-item customer-service working-hours-icon">
						<?php if ( $hours_title ) : ?>
							<span class="title"><?php echo esc_html( $hours_title ); ?></span>
						<?php endif; ?>

						<?php if ( $hours_content ) : ?>
							<span class="value"><?php echo esc_html( $hours_content ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="mobile-menu-categories">
			<?php get_template_part( 'inc/global/product-archive-sidebar' ); ?>
		</div>
	</div>
</div>

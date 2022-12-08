<?php
/**
 * Site header
 *
 * @package WordPress
 */

global $current_user_name;
$current_user_name  = get_user_meta( get_current_user_id(), 'billing_first_name', true );
$top_header_message = get_field( 'top_header_message', 'option' );

$header_logo_1 = get_field( 'header_logo_1', 'option' );
$header_logo_2 = get_field( 'header_logo_2', 'option' );

$customer_service_title = get_field( 'customer_service_title', 'option' );
$customer_service_phone = get_field( 'customer_service_phone', 'option' );

$hours_title   = get_field( 'hours_title', 'option' );
$hours_content = get_field( 'hours_content', 'option' );

$cart_items = WC()->cart->get_cart_contents_count();
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title>
		<?php bloginfo( 'name' ); ?>
		<?php wp_title( '' ); ?>
	</title>
	<!-- dns prefetch -->
	<link href="//www.google-analytics.com" rel="dns-prefetch" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div class="site-wrapper">

	<?php get_template_part( 'inc/mobile-menu' ); ?>

	<header class="header clear" role="banner">

		<div class="top-header">
			<div class="main-container">
				<div class="top-header-inner">
					<?php if ( $top_header_message ) : ?>
					<div class="top-header-message">
						<?php echo wp_kses_post( $top_header_message ); ?>
					</div>
					<?php endif; ?>

					<?php get_template_part( 'inc/header/language', 'switcher' ); ?>
				</div>
			</div>
		</div>


		<div class="main-header">
			<div class="main-container">
				<div class="main-header-inner">
					<div class="logos">
						<a href="<?php echo esc_url( get_home_url() ); ?>" class="header_logo_1">
							<img src="<?php echo esc_url( $header_logo_1['url'] ); ?>" alt="">
						</a>
						<a href="<?php echo esc_url( get_home_url() ); ?>" class="header_logo_2">
							<img src="<?php echo esc_url( $header_logo_2['url'] ); ?>" alt="">
						</a>
					</div>
					<div class="main-search">
						<?php get_search_form(); ?>
					</div>

					<div class="info">
						<?php if ( $customer_service_phone || $customer_service_title ) : ?>
							<div class="info-item customer-service">
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
							<div class="info-item customer-service">
								<?php if ( $hours_title ) : ?>
									<span class="title"><?php echo esc_html( $hours_title ); ?></span>
								<?php endif; ?>

								<?php if ( $hours_content ) : ?>
									<span class="value"><?php echo esc_html( $hours_content ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="account-buttons">
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

						<a href="<?php echo esc_url( get_home_url() ); ?>/my-account/orders/" class="account-button box-button"></a>

						<a href="#" data-href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="account-button cart-button">
							<?php if ( $cart_items ) : ?>
								<span class="cart_items"><?php echo esc_html( $cart_items ); ?></span>
							<?php endif; ?>
						</a>

						<a href="#" class="account-button contrast"></a>

						<a href="#" class="account-button mobile-search-button">
							<img src="<?php echo esc_url( THEME ); ?>/images/mobile-search-button.png" alt="">
						</a>

						<button class="mobile-button toggle-mobile-menu">
							<span></span>
							<span></span>
							<span></span>
						</button>
					</div>
				</div>
			</div>
		</div>

	</header>

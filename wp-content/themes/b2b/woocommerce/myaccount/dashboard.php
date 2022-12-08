<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);

$user_id          = $current_user->ID;
$user_obligo      = b2b_get_user_obligo( $user_id ); // helpers.php.
$customer_name_id = get_user_meta( $user_id, 'customer_name_id', true );

$customer_tigmulim = get_customer_tigmulim( $customer_name_id ); // api.php.

$tigmulim_data = array();
if ( $customer_tigmulim ) {
	foreach ( $customer_tigmulim as $item ) {
		$deal_type                     = isset( $item['DEALTYPEDES'] ) ? $item['DEALTYPEDES'] : '';
		$tigmulim_data[ $deal_type ][] = $item;
	}
}

$supherb_solgar_title = 'סופהרב + סולגר';
?>

<div class="woo-origin">
	<p>
		<?php
		printf(
			/* translators: 1: user display name 2: logout url */
			wp_kses( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ), $allowed_html ),
			'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
			esc_url( wc_logout_url() )
		);
		?>
	</p>

	<p>
		<?php
		/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
		$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' );
		if ( wc_shipping_enabled() ) {
			/* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
			$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' );
		}
		printf(
			wp_kses( $dashboard_desc, $allowed_html ),
			esc_url( wc_get_endpoint_url( 'orders' ) ),
			esc_url( wc_get_endpoint_url( 'edit-address' ) ),
			esc_url( wc_get_endpoint_url( 'edit-account' ) )
		);
		?>
	</p>
</div>

<div class="b2b-account-obligo-banner <?php echo ( $user_obligo && $user_obligo > 0 ) ? 'success' : 'error'; ?>">

	<div class="obligo-status-icon"></div>

	<div class="obligo-meta">
		<div class="obligo-total">
			<?php if ( $user_obligo < 1 ) : ?>
				הינך נמצא בפיגור תשלומים!
			<?php else : ?>
				₪ 6,520
			<?php endif; ?>
		</div>
		<div class="obligo-description">
			<?php if ( $user_obligo < 1 ) : ?>
				אנא פנה לנציג המכירות להסדרת התשלומים
			<?php else : ?>
				יתרתך בחשבון אובליגו
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="solgar-supherb-tabs" data-customer="<?php echo esc_html( $customer_name_id ); ?>">

	<?php if ( $tigmulim_data ) : ?>

		<div class="tabs-nav">
			<?php
			$tigmulim_tabs_counter = 0;
			foreach ( $tigmulim_data as $item_id => $item ) :
				$tigmulim_tabs_counter++;
				$text_id = '';
				$tab_id  = '';
				$class   = '';
				if ( 'סולגאר' === $item_id ) {
					$text_id = 'יעדים סולגאר';
					$tab_id  = 'tab-solgar';
				}
				if ( 'סופהרב' === $item_id ) {
					$text_id = 'יעדים סופהרב';
					$tab_id  = 'tab-supherb';
				}

				if ( 1 === $tigmulim_tabs_counter ) {
					$class = 'active';
				}
				?>
				<a href="#<?php echo esc_html( $tab_id ); ?>" class="<?php echo esc_html( $class ); ?>"
					data-id="<?php echo esc_html( $item_id ); ?>">
					<?php echo esc_html( $text_id ); ?>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="tabs-contents">

			<?php
			$tigmulim_content_counter = 0;
			foreach ( $tigmulim_data as $item_id => $item ) :
				$tigmulim_content_counter++;
				$text_id = '';
				$tab_id  = '';
				$class   = '';
				if ( 'סולגאר' === $item_id ) {
					$tab_id = 'tab-solgar';
				}
				if ( 'סופהרב' === $item_id ) {
					$tab_id = 'tab-supherb';
				}

				if ( 1 === $tigmulim_content_counter ) {
					$class = 'active';
				}
				?>

				<div id="<?php echo esc_html( $tab_id ); ?>" class="tab-inner <?php echo esc_html( $class ); ?>">
					<div class="target-cubes">
						<?php
						get_template_part(
							'inc/user-account/target/quoter',
							null,
							array(
								'current_user' => $current_user,
								'tigmulim'     => $tigmulim_data[ $item_id ],
							)
						);
						?>
						<?php
						get_template_part(
							'inc/user-account/target/half',
							null,
							array(
								'current_user' => $current_user,
								'tigmulim'     => $tigmulim_data[ $item_id ],
							)
						);
						?>
						<?php
						get_template_part(
							'inc/user-account/target/year',
							null,
							array(
								'current_user' => $current_user,
								'tigmulim'     => $tigmulim_data[ $item_id ],
							)
						);
						?>
					</div>
				</div>

			<?php endforeach; ?>

			<?php if ( $supherb_tab ) : ?>
				<div id="tab-supherb" class="tab-inner">

				</div>
			<?php endif; ?>
		</div>

	<?php endif; ?>
</div>

<div class="contact-cubes">

	<?php get_template_part( 'inc/user-account/agent', 'cube', array( 'current_user' => $current_user ) ); ?>

	<?php get_template_part( 'inc/user-account/contact-details', 'cube', array( 'current_user' => $current_user ) ); ?>

	<?php get_template_part( 'inc/user-account/shipping', 'cube', array( 'current_user' => $current_user ) ); ?>

</div>

<div class="user-orders-list">
	<?php get_template_part( 'inc/user-account/orders', 'list', array( 'current_user' => $current_user ) ); ?>
</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

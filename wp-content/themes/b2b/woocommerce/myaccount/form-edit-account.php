<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

$update_profile_request_button = get_field( 'update_profile_request_button', 'option' );

$b2b_user_id      = get_current_user_id();
$current_b2b_user = wp_get_current_user();

$customer_id   = get_field( 'customer_name_id', 'user_' . $b2b_user_id );
$customer_name = get_field( 'customer_name', 'user_' . $b2b_user_id );
$billing_phone = get_user_meta( $b2b_user_id, 'billing_phone', true );
$billing_email = $current_b2b_user->user_email;

$billing_address_1 = get_user_meta( $b2b_user_id, 'billing_address_1', true );
$billing_city      = get_user_meta( $b2b_user_id, 'billing_city', true );

do_action( 'woocommerce_before_edit_account_form' ); ?>

<h3 class="edit-account-title">פרטים אישיים</h3>

<?php
/*
<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_display_name"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" /> <span><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></span>
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>

	<fieldset>
		<legend><?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
		</p>
	</fieldset>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>
*/
?>

<div class="b2b-user-personal-details">
	<div class="form-row">
		<div class="input-wrap">
			<label for="customer_id">ח.פ</label>
			<input type="text" name="customer_id" id="customer_id"
				value="<?php echo esc_html( $customer_id ); ?>" readonly>
		</div>
		<div class="input-wrap">
			<label for="company_name">שם חברה</label>
			<input type="text" name="company_name" id="company_name"
				value="<?php echo esc_html( $customer_name ); ?>" readonly>
		</div>
	</div>

	<div class="form-row">
		<div class="input-wrap">
			<label for="customer_contact_name">שם איש קשר</label>
			<input type="text" name="customer_contact_name" id="customer_contact_name"
				value="<?php echo esc_html( $customer_name ); ?>" readonly>
		</div>
		<div class="input-wrap">
			<label for="customer_email">דוא"ל</label>
			<input type="text" name="customer_email" id="customer_email"
				value="<?php echo esc_html( $billing_email ); ?>" readonly>
		</div>
	</div>

	<div class="form-row">
		<div class="input-wrap">
			<label for="customer_contact_phone">טלפון ליצירת קשר</label>
			<input type="text" name="customer_contact_phone" id="customer_contact_phone"
				value="<?php echo esc_html( $billing_phone ); ?>" readonly>
		</div>
		<div class="input-wrap">
			<label for="customer_address">כתובת</label>
			<input type="text" name="customer_address" id="customer_address"
				value="<?php echo esc_html( $billing_address_1 ); ?> <?php echo esc_html( $billing_city ); ?>" readonly>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>

<div class="edit-account-request-wrapper">
	<a href="#edit-accoutn-request-form" class="inline-popup" >
		<span class="edit-icon"></span>
		<span class="title"><?php echo esc_html( $update_profile_request_button ); ?></span>
	</a>
	<?php get_template_part( 'inc/popup/update-profile-request' ); ?>
</div>

<?php get_template_part( 'inc/user-account/contact', 'list' ); ?>

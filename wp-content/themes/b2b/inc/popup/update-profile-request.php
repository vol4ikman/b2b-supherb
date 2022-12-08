<?php
/**
 * User profile
 * Update user profile info request popup
 *
 * @package WordPress
 */

$update_profile_popup_title    = get_field( 'update_profile_popup_title', 'option' );
$update_profile_popup_subtitle = get_field( 'update_profile_popup_subtitle', 'option' );
?>

<div id="edit-accoutn-request-form" class="mfp-hide">
	<div class="magnific-popup-inner">
		<?php if ( $update_profile_popup_title ) : ?>
			<div class="popup-title">
				<?php echo esc_html( $update_profile_popup_title ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $update_profile_popup_subtitle ) : ?>
			<div class="popup-sub-title">
				<?php echo esc_html( $update_profile_popup_subtitle ); ?>
			</div>
		<?php endif; ?>

		<form class="form-wrapper" id="update_profile_request" method="post">
			<input type="hidden" name="current_user_id" value="<?php echo esc_html( get_current_user_id() ); ?>">
			<div class="f-row">
				<div class="input-wrap">
					<input type="text" name="customer_fullname" value="" placeholder="שם מלא">
				</div>
				<div class="input-wrap">
					<input type="text" name="customer_email" value="" placeholder="דוא''ל">
				</div>
			</div>

			<div class="f-row">
				<div class="input-wrap">
					<input type="text" name="company_name" value="" placeholder="שם חברה">
				</div>
				<div class="input-wrap">
					<input type="text" name="contact_phone" value="" placeholder="טלפון ליצירת קשר">
				</div>
			</div>

			<div class="f-row full-width">
				<div class="input-wrap">
					<input type="text" name="address" value="" placeholder="כתובת">
				</div>
			</div>
			<div class="f-row full-width">
				<div class="input-wrap is-submit">
					<button type="submit">שליחת טופס</button>
				</div>
			</div>

			<div class="f-row full-width">
				<div class="ajax-response"></div>
			</div>
		</form>
	</div>
</div>

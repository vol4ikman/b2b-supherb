<?php
/**
 * Create new contact popup
 *
 * @package WordPress
 */

$cc_popup_title  = get_field( 'cc_popup_title', 'option' );
$cc_popup_submit = get_field( 'cc_popup_submit', 'option' );
?>

<div class="create-new-contact-wrapper">
	<a href="#create-new-contact" class="inline-popup">
		<span class="add-user"></span>
		יצירת איש קשר חדש
	</button>
</div>

<div id="create-new-contact" class="mfp-hide">
	<div class="magnific-popup-inner">
		<?php if ( $cc_popup_title ) : ?>
			<div class="popup-title">
				<?php echo esc_html( $cc_popup_title ); ?>
			</div>
		<?php endif; ?>

		<form class="form-wrapper" id="create_new_contact_form" method="post" autocomplete="off">
			<input type="hidden" name="current_user_id" value="<?php echo esc_html( get_current_user_id() ); ?>">
			<div class="f-row">
				<div class="input-wrap">
					<input type="text" name="contact_fullname" value="" placeholder="שם מלא" autocomplete="off" required>
				</div>
			</div>

			<div class="f-row">
				<div class="input-wrap">
					<input type="email" name="contact_email" value="" placeholder="אימייל" autocomplete="off" required>
				</div>
			</div>

			<div class="f-row">
				<div class="input-wrap">
					<input type="text" name="contact_role" value="" placeholder="תפקיד" autocomplete="off" required>
				</div>
			</div>

			<div class="f-row full-width">
				<div class="input-wrap">
					<input type="text" name="contact_phone" value="" placeholder="טלפון" autocomplete="off" required>
				</div>
			</div>

			<div class="f-row group-title">
				<div class="input-group-title">
					סוג הרשאה
				</div>
			</div>
			<div class="f-row full-width">
				<div class="input-wrap is-radios">
					<label class="radio-design">
						<input type="radio" name="contact_permission" value="view" checked>
						<span class="checkmark"></span>
						צפייה
					</label>
					<label class="radio-design">
						<input type="radio" name="contact_permission" value="edit">
						<span class="checkmark"></span>
						עריכה
					</label>
				</div>
			</div>

			<div class="f-row full-width">
				<div class="input-wrap is-submit">
					<button type="submit">
						<?php echo esc_html( $cc_popup_submit ); ?>
					</button>
				</div>
			</div>

			<div class="f-row full-width">
				<div class="ajax-response"></div>
			</div>
		</form>

	</div>
</div>

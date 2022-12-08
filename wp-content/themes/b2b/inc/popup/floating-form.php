<?php
/**
 * Floating contact form
 *
 * @package WordPress
 */

$floating_contact_form_id = get_field( 'floating_contact_form_id', 'option' );
if ( ! $floating_contact_form_id ) {
	return;
}
$floating_form_button   = get_field( 'floating_form_button', 'option' );
$floating_form_title    = get_field( 'floating_form_title', 'option' );
$floating_form_subtitle = get_field( 'floating_form_subtitle', 'option' );

?>


<div class="floating-contact-form">
	<div class="floating-contact-form-inner">
		<button type="button" class="floating-contact-form-trigger">
			<img src="<?php echo esc_url( $floating_form_button['url'] ); ?>" alt="">
		</button>
		<div class="floating-contact-form-shortcode">
			<button type="button" class="close-floating-form"></button>
			<?php if ( $floating_form_title ) : ?>
				<div class="floating-contact-form-title">
					<?php echo esc_html( $floating_form_title ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $floating_form_subtitle ) : ?>
				<div class="floating-contact-form-subtitle">
					<?php echo esc_html( $floating_form_subtitle ); ?>
				</div>
			<?php endif; ?>

			<div class="floating-contact-form-cf7 cf7-form-design">
				<?php echo do_shortcode( '[contact-form-7 id="' . $floating_contact_form_id . '"]' ); ?>
			</div>
		</div>
	</div>
</div>

<?php
/**
 * Login screen #2
 *
 * @package WordPress
 */

$login_screen_banner = get_field( 'login_screen_banner', 'option' );

$item_post_id = isset( $args['post_id'] ) ? $args['post_id'] : '';
$user_id      = isset( $args['user_id'] ) ? $args['user_id'] : '';

$main_title               = get_field( 'main_title2', $item_post_id );
$sub_title                = get_field( 'sub_title2', $item_post_id );
$phone_number_placeholder = get_field( 'phone_number_placeholder2', $item_post_id );
$submit_button_title      = get_field( 'submit_button_title2', $item_post_id );
$register_now_text        = get_field( 'register_now_text2', $item_post_id );
?>

<main class="main-login-screen" data-screen="2">
	<div class="login-screen-inner">
		<div class="login-sidebar">

			<?php get_template_part( 'inc/login-page/header', 'icons' ); ?>

			<div class="form-wrapper">
				<?php if ( $main_title ) : ?>
					<h3 class="form-title"><?php echo esc_html( $main_title ); ?></h3>
				<?php endif; ?>

				<?php if ( $sub_title ) : ?>
					<div class="form-subtitle">
						<?php echo esc_html( $sub_title ); ?>
					</div>
				<?php endif; ?>

				<form class="step2-form" method="post" autocomplete="off">
					<input type="hidden" name="uid" value="<?php echo esc_html( $user_id ); ?>">
					<label>
						<input type="tel" name="phone" placeholder="<?php echo esc_html( $phone_number_placeholder ); ?>" autocomplete="off">
					</label>
					<button type="submit"><?php echo esc_html( $submit_button_title ); ?></button>

					<div class="form-row">
						<label class="checkbox-style">
							<input type="checkbox" name="remember_me" checked>
							<span class="checkmark"></span>
							<span class="title">זכור אותי</span>
						</label>

						<div class="reg-text">
							<?php echo wp_kses_post( $register_now_text ); ?>
						</div>
					</div>

					<div class="ajax-response"></div>
				</form>
			</div>
		</div>

		<div class="login-banner">
			<div class="login-banner-holder" style="background-image:url(<?php echo esc_url( $login_screen_banner['url'] ); ?> );">

			</div>
		</div>
	</div>
</main>

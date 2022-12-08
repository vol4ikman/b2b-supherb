<?php
/**
 * Register screen #1
 *
 * @package WordPress
 */

$login_screen_banner = get_field( 'login_screen_banner', 'option' );

$main_title          = get_field( 'reg_main_title' );
$sub_title           = get_field( 'reg_sub_title' );
$submit_button_title = get_field( 'reg_submit_button_title' );
$login_text          = get_field( 'login_text' );

$main_title2           = get_field( 'reg_main_title2' );
$sub_title2            = get_field( 'reg_sub_title2' );
$reg_terms             = get_field( 'reg_terms' );
$reg_send_button_title = get_field( 'reg_send_button_title' );

$step3_icon      = get_field( 'step3_icon' );
$step3_title     = get_field( 'step3_title' );
$step3_sub_title = get_field( 'step3_sub_title' );

$b_types = get_field( 'b_types' );
?>

<main class="main-login-screen" data-screen="register-1">
	<div class="login-screen-inner">
		<div class="login-sidebar">

			<?php get_template_part( 'inc/login-page/header', 'icons' ); ?>

			<div class="form-wrapper">

				<form class="reg-form" method="post" autocomplete="off">
					<input type="hidden" name="date" value="<?php echo esc_html( date( 'd/m/Y H:i:s' ) ); ?>">
					<input type="hidden" name="reg_page_id" value="<?php echo esc_html( get_the_ID() ); ?>">

					<div class="reg-step-1 active">

						<?php if ( $main_title ) : ?>
							<h3 class="form-title"><?php echo esc_html( $main_title ); ?></h3>
						<?php endif; ?>

						<?php if ( $sub_title ) : ?>
							<div class="form-subtitle">
								<?php echo esc_html( $sub_title ); ?>
							</div>
						<?php endif; ?>

						<div class="reg-row">
							<div class="reg-input">
								<label>
									<input type="text" name="first_name" placeholder="שם פרטי"
										autocomplete="off"
										maxlength="20" minlength="2"
										required>
								</label>
							</div>
							<div class="reg-input">
								<label>
									<input type="text" name="last_name" placeholder="שם משפחה"
										autocomplete="off"
										maxlength="20" minlength="2"
										required>
								</label>
							</div>
						</div>

						<div class="reg-row">
							<div class="reg-input">
								<label>
									<input type="text" name="shop_name" placeholder="שם החנות"
										autocomplete="off"
										maxlength="40"
										required>
								</label>
							</div>
							<div class="reg-input">
								<label>
									<input type="text" name="shop_address" placeholder="כתובת החנות"
										autocomplete="off"
										maxlength="40"
										required>
								</label>
							</div>
						</div>

						<div class="reg-row">
							<div class="reg-input select-type">
								<label>
									<select type="text" name="buisness_type" placeholder="סוג עסק"
										autocomplete="off"
										required>
										<option value="">סוג עסק</option>
										<?php if ( $b_types ) : ?>
											<?php
											foreach ( $b_types as $b_type ) :
												$option_key   = $b_type['key'];
												$option_title = $b_type['title'];
												?>
												<option value="<?php echo esc_html( str_replace( ' ', '_', $option_key ) ); ?>">
													<?php echo esc_html( $option_title ); ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</label>
							</div>
							<div class="reg-input">
								<label>
									<input type="tel" name="phone" placeholder="טלפון"
										autocomplete="off"
										maxlength="10" minlength="9"
										required>
								</label>
							</div>
						</div>

						<div class="reg-row">
							<div class="reg-input">
								<label>
									<input type="tel" name="fax" placeholder="פקס"
										autocomplete="off"
										maxlength="10" minlength="9"
										required>
								</label>
							</div>
							<div class="reg-input">
								<label>
									<input type="email" name="email" placeholder="דוא''ל"
										autocomplete="off"
										maxlength="40" minlength="9"
										required>
								</label>
							</div>
						</div>

						<div class="reg-row">
							<div class="reg-input">
								<label>
									<input type="text" name="customer_address" placeholder="כתובת פרטית"
										autocomplete="off"
										maxlength="40"
										required>
								</label>
							</div>
							<div class="reg-input">
								<label>
									<input type="tel" name="customer_phone" placeholder="טלפון"
										autocomplete="off"
										maxlength="10" minlength="9"
										required>
								</label>
							</div>
						</div>

						<div class="reg-row">
							<div class="reg-input">
								<label>
									<input type="tel" name="customer_cellphone" placeholder="טלפון נייד"
										autocomplete="off"
										maxlength="10"
										required>
								</label>
							</div>
						</div>

						<button type="button" class="next-step">
							<?php echo esc_html( $submit_button_title ); ?>
						</button>

						<div class="reg-row">
							<div class="reg-text">
								<?php echo wp_kses_post( $login_text ); ?>
							</div>
						</div>

					</div>

					<div class="reg-step-2">

						<?php if ( $main_title2 ) : ?>
							<h3 class="form-title"><?php echo esc_html( $main_title2 ); ?></h3>
						<?php endif; ?>

						<?php if ( $sub_title2 ) : ?>
							<div class="form-subtitle">
								<?php echo esc_html( $sub_title2 ); ?>
							</div>
						<?php endif; ?>

						<div class="reg-row">
							<div class="reg-input">
								<label>
									<input type="text" name="bank_name" placeholder="שם הבנק"
										autocomplete="off"
										maxlength="30"
										required>
								</label>
							</div>
							<div class="reg-input">
								<label>
									<input type="number" name="bank_branch_id" placeholder="מספר סניף"
										autocomplete="off"
										maxlength="20"
										required>
								</label>
							</div>
						</div>

						<div class="reg-row">
							<div class="reg-input">
								<label>
									<input type="number" name="bank_account_id" placeholder="מספר חשבון בנק"
										autocomplete="off"
										maxlength="30"
										required>
								</label>
							</div>
							<div class="reg-input">
								<label>
									<input type="number" name="company_id" placeholder="מספר חברה/עוסק מורשה"
										autocomplete="off"
										maxlength="30"
										required>
								</label>
							</div>
						</div>

						<div class="reg-row">
							<div class="reg-row-title">
								אני החתום:
							</div>
						</div>

						<div class="reg-row">
							<div class="reg-input">
								<label>
									<input type="text" name="customer_fullname" placeholder="שם מלא"
										autocomplete="off"
										maxlength="40" minlength="4"
										required>
								</label>
							</div>
							<div class="reg-input">
								<label>
									<input type="number" name="customer_id" placeholder="ת.ז"
										autocomplete="off"
										maxlength="10" minlength="8"
										required>
								</label>
							</div>
						</div>

						<div class="reg-row">
							<div class="reg-row-title">
								בעלים של:
							</div>
						</div>

						<div class="reg-row">
							<div class="reg-input full-with">
								<label>
									<input type="text" name="customer_shop_name" placeholder="שם החנות"
										autocomplete="off"
										maxlength="50" minlength="2"
										required>
								</label>
							</div>
						</div>

						<?php if ( $reg_terms ) : ?>
							<div class="reg-row">
								<div class="reg-terms">
									<?php echo wp_kses_post( $reg_terms ); ?>
								</div>
							</div>
						<?php endif; ?>

						<button type="submit">
							<?php echo esc_html( $reg_send_button_title ); ?>
						</button>

						<div class="reg-row">
							<div class="reg-text">
								<?php echo wp_kses_post( $login_text ); ?>
							</div>
						</div>

					</div>

					<div class="reg-step-3">

						<?php if ( $step3_icon ) : ?>
							<img src="<?php echo esc_url( $step3_icon['url'] ); ?>" alt="">
						<?php endif; ?>

						<?php if ( $step3_title ) : ?>
							<h3 class="form-title"><?php echo esc_html( $step3_title ); ?></h3>
						<?php endif; ?>

						<?php if ( $step3_sub_title ) : ?>
							<div class="form-subtitle">
								<?php echo esc_html( $step3_sub_title ); ?>
							</div>
						<?php endif; ?>
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

<?php
/**
 * Shipping cube
 *
 * @package WordPress
 */

$this_user = isset( $args['current_user'] ) ? $args['current_user'] : false; // WP_User object.
$user_json = b2b_get_user_json( $this_user->ID ); // api.php.

$customer_contacts     = isset( $user_json['CUSTPERSONNEL_SUBFORM'] ) ? reset( $user_json['CUSTPERSONNEL_SUBFORM'] ) : array();
$customer_company_name = isset( $user_json['CUSTDES'] ) ? $user_json['CUSTDES'] : '';
$contact_person_name   = '';
$contact_person_phone  = '';
$customer_address      = $user_json['ADDRESS'] . ', ' . $user_json['STATEA'];
?>
<div class="c-cube c-cube-shipping">
	<div class="cube-header">
		<div class="icon">
			<img src="<?php echo esc_url( THEME ); ?>/images/agent-shipping-icon.png" alt="">
		</div>
		<div class="title">
			פרטי משלוח
		</div>
	</div>

	<div class="c-cube-inner">

		<div class="agent-details">
			<ul>
				<li>
					<div class="label">
						שם חברה:
					</div>
					<div class="data">
						<?php echo esc_html( $customer_company_name ); ?>
					</div>
				</li>
				<li>
					<div class="label">
						שם איש קשר:
					</div>
					<div class="data">
						<?php echo esc_html( $customer_contacts['NAME'] ); ?>
					</div>
				</li>
				<li>
					<div class="label">
						טלפון איש קשר:
					</div>
					<div class="data">
						<?php echo isset( $customer_contacts['PHONENUM'] ) ? esc_html( $customer_contacts['PHONENUM'] ) : ''; ?>
					</div>
				</li>
				<li>
					<div class="label">
						כתובת למשלוח:
					</div>
					<div class="data">
						<?php echo esc_html( $customer_address ); ?>
					</div>
				</li>
			</ul>
		</div>
	</div>

</div>

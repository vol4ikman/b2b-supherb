<?php
/**
 * Plugin Name: WooCommerce Offline Gateway
 * Plugin URI: https://www.skyverge.com/?p=3343
 * Description: Clones the "Cheque" gateway to create another manual / offline payment method; can be used for testing as well.
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com/
 * Version: 1.0.2
 * Text Domain: wc-gateway-offline
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2015-2016 SkyVerge, Inc. (info@skyverge.com) and WooCommerce
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Gateway-Offline
 * @author    SkyVerge
 * @category  Admin
 * @copyright Copyright (c) 2015-2016, SkyVerge, Inc. and WooCommerce
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * This offline gateway forks the WooCommerce core "Cheque" payment gateway to create another offline payment method.
 */

defined( 'ABSPATH' ) || exit;


// Make sure WooCommerce is active.
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}


/**
 * Add the gateway to WC Available Gateways
 *
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways all WC gateways + offline gateway
 */
function wc_offline_add_to_gateways( $gateways ) {
	$gateways[] = 'WC_Gateway_Obligo';
	return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'wc_offline_add_to_gateways' );

/**
 * Offline Payment Gateway
 *
 * Provides an Offline Payment Gateway; mainly for testing purposes.
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @class       WC_Gateway_Offline
 * @extends     WC_Payment_Gateway
 * @version     1.0.0
 * @package     WooCommerce/Classes/Payment
 */
add_action( 'init', 'wc_technion_budget_gateway_init', 11 );

function wc_technion_budget_gateway_init() {

	class WC_Gateway_Obligo extends WC_Payment_Gateway {

		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {

			$this->id           = 'technion_budget_gateway';
			$this->icon         = apply_filters( 'woocommerce_offline_icon', '' );
			$this->has_fields   = false;
			$this->method_title = __( 'Obligo Budget', 'technion' );

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Define user set variables.
			$this->title        = $this->get_option( 'title' );
			$this->description  = $this->get_option( 'description' );
			$this->instructions = $this->get_option( 'instructions', $this->description );

			// Actions.
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

			// Customer Emails.
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
		}

		/**
		 * Payment_fields
		 */
		public function payment_fields() {
				do_action( 'woocommerce_credit_card_form_start', $this->id );
				// $research_budget_text       = get_field( 'research_budget_text', 'option' );
				// $technion_budget_code_title = get_field( 'technion_budget_code_title', 'option' );
				// $order_identification_code  = generate_cart_item_code(); // helpers.php.
				// $wc_cart_item_key           = isset( $_GET['item'] ) ? wp_unslash( sanitize_text_field( $_GET['item'] ) ) : '';
			?>

				<div class="d-flex tech-budget-wrap">
					Obligo
				</div>

			<?php
			do_action( 'woocommerce_credit_card_form_end', $this->id );
		}
		/**
		 * Initialize Gateway Settings Form Fields
		 */
		public function init_form_fields() {
			$this->form_fields = apply_filters(
				'wc_offline_form_fields',
				array(
					'enabled'      => array(
						'title'   => __( 'Enable/Disable', 'technion' ),
						'type'    => 'checkbox',
						'label'   => __( 'Enable Obligo budget Payment', 'technion' ),
						'default' => 'yes',
					),

					'title'        => array(
						'title'    => __( 'Title', 'technion' ),
						'type'     => 'text',
						'default'  => __( 'מימוש מתוך חשבון אובליגו', 'technion' ),
						'desc_tip' => true,
					),

					'description'  => array(
						'title'    => __( 'Description', 'technion' ),
						'type'     => 'textarea',
						'default'  => __( 'Please remit payment to Store Name upon pickup or delivery.', 'technion' ),
						'desc_tip' => true,
					),

					'instructions' => array(
						'title'       => __( 'Instructions', 'technion' ),
						'type'        => 'textarea',
						'description' => __( 'Instructions that will be added to the thank you page and emails.', 'technion' ),
						'default'     => '',
						'desc_tip'    => true,
					),
				)
			);
		}

		// public function validate_fields() {
		//
		// if ( ! empty( $_POST['tech_budget_option'] ) ) {
		// wc_add_notice( __( 'Please choose a budget option', 'technion' ), 'error' );
		// return false;
		// }
		// return true;
		//
		// }

		/**
		 * Output for the order received page.
		 */
		public function thankyou_page() {
			if ( $this->instructions ) {
				echo wpautop( wptexturize( $this->instructions ) );
			}
		}

		/**
		 * Add content to the WC emails.
		 *
		 * @access public
		 * @param WC_Order $order
		 * @param bool     $sent_to_admin
		 * @param bool     $plain_text
		 */
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {

			if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
			}
		}

		/**
		 * Process the payment and return the result
		 *
		 * @param int $order_id
		 * @return array
		 */
		public function process_payment( $order_id ) {

			$order_identification_code = isset( $_POST['order_identification_code'] ) ? sanitize_text_field( $_POST['order_identification_code'] ) : '';
			$order                     = wc_get_order( $order_id );

			$product_id = '';
			foreach ( $order->get_items()  as $item ) {
				$product_id      = $item['product_id'];
				$product_cart_id = WC()->cart->generate_cart_id( $product_id );
				$cart_item_key   = WC()->cart->find_product_in_cart( $product_cart_id );

				if ( $_SESSION['cart_item'] === $cart_item_key ) {
					// break;
				}
			}

			// Save order identification code.
			if ( $order_identification_code ) {
				$order->update_meta_data( 'order_identification_code', $order_identification_code );

				// Send Email with $order_identification_code to the customer.
				$this->send_mail( $order_identification_code );

				// Save order items in user profile.
				// repeater field id = user_cart_items.
				// repeater field key = field_62e8d5bc1586f.
				$this->update_user_cart_items( $order_identification_code, $cart_item_key, $order_id );
			}

			// Mark as on-hold (we're awaiting the payment).
			$order->update_status( 'awaiting_approval_budget', __( 'Awaiting approval of their budget', 'technion' ) );

			// Reduce stock levels.
			$order->reduce_order_stock();

			// Save the order.
			$order->save();

			// Remove this item from cart.
			if ( $cart_item_key ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}

			// Return thankyou redirect.
			$redirect_url = $this->get_return_url( $order );
			$redirect_url = add_query_arg( 'tech-budget', '1', $redirect_url );
			return array(
				'result'   => 'success',
				'redirect' => $redirect_url,
			);
		}

		/**
		 * Send email to the customer
		 * with order identification code
		 * to complete purchase with their budget
		 *
		 * @param  string $order_identification_code order identification code.
		 * @return boolean email sent true or false
		 */
		public function send_mail( $order_identification_code ) {

			$direction = is_rtl() ? 'rtl' : 'ltr';
			$style     = 'style="direction:' . $direction . '"';

			$to            = 'alex.v@dooble.co.il';
			$additional_to = get_field( 'tech_budget_mail_to', 'option' );
			if ( $additional_to ) {
				$to = $to . ',' . $additional_to;
			}

			$subject = get_field( 'tech_budget_email_subject', 'option' );
			$body    = get_field( 'tech_budget__body', 'option' );
			$body    = str_replace( '%%cart_item_code%%', '<div class="order_identification_code_span">' . $order_identification_code . '</div>', $body );

			$headers  = 'From: Technion Shop <' . wp_strip_all_tags( 'info@technion.ac.il' ) . ">\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

			$message  = '<html><body>';
			$message .= '<div class="email-body-wrapper" ' . $style . '>';
			$message .= $body;
			$message .= '</div>';
			$message .= '</body></html>';

			$send_mail = wp_mail( $to, $subject, $body, $headers );

			return $send_mail;

		}

		/**
		 * Update_user_cart_items
		 *
		 * @param  string $order_identification_code order identification code.
		 * @param  string $cart_item_key wc cart item key.
		 * @param  string $order_id     WC_Order ID.
		 */
		public function update_user_cart_items( $order_identification_code, $cart_item_key, $order_id ) {
			// Save order items in user profile.
			// repeater field id = user_cart_items.
			// repeater field key = field_62e8d5bc1586f.

			if ( $order_identification_code && $cart_item_key && $order_id ) {
				$repeater_key = 'field_62e8d5bc1586f';

				$fields[] = array(
					'wc_order_id'      => $order_id,
					'cart_item_code'   => $order_identification_code,
					'wc_cart_item_key' => $cart_item_key,
				);

				if ( $fields ) {
					update_field( $repeater_key, $fields, 'user_' . get_current_user_id() );
				}
			}

		}

	}
}

<?php
/**
 * API functions
 *
 * @package WordPress
 */

add_action( 'wp_ajax_submit_step1_form', 'submit_step1_form' );
add_action( 'wp_ajax_nopriv_submit_step1_form', 'submit_step1_form' );

add_action( 'wp_ajax_submit_step2_form', 'submit_step2_form' );
add_action( 'wp_ajax_nopriv_submit_step2_form', 'submit_step2_form' );

add_action( 'wp_ajax_submit_registration_form', 'submit_registration_form' );
add_action( 'wp_ajax_nopriv_submit_registration_form', 'submit_registration_form' );

add_action( 'wp_ajax_update_profile_request', 'update_profile_request' );

add_action( 'wp_ajax_create_new_contact', 'create_new_contact' );

/**
 * Create new contact
 */
function create_new_contact() {
	$data = array(
		'error'   => true,
		'message' => '',
		'html'    => '',
	);

	$form_data = isset( $_POST['form'] ) ? $_POST['form'] : '';	//phpcs:ignore.
	$form_args = array();

	parse_str( $form_data, $form_args );

	$current_user_id    = isset( $form_args['current_user_id'] ) ? sanitize_text_field( $form_args['current_user_id'] ) : '';
	$contact_fullname   = isset( $form_args['contact_fullname'] ) ? sanitize_text_field( $form_args['contact_fullname'] ) : '';
	$contact_email      = isset( $form_args['contact_email'] ) ? $form_args['contact_email'] : '';
	$contact_role       = isset( $form_args['contact_role'] ) ? sanitize_text_field( $form_args['contact_role'] ) : '';
	$contact_phone      = isset( $form_args['contact_phone'] ) ? sanitize_text_field( $form_args['contact_phone'] ) : '';
	$contact_permission = isset( $form_args['contact_permission'] ) ? sanitize_text_field( $form_args['contact_permission'] ) : '';

	$user_contacts = get_field( 'user_contacts', 'user_' . $current_user_id ) ? get_field( 'user_contacts', 'user_' . $current_user_id ) : array();

	$user_api_response_data = json_decode( $user_api_response_data, true );

	$customer_name_id         = get_field( 'customer_name_id', 'user_' . $current_user_id );
	$new_contact_endpoint_url = "https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/CUSTOMERS('" . $customer_name_id . "')";

	$api_user = 'apib2b';
	$api_pass = 'Ambr0z2022!';
	$auth     = 'Basic ' . base64_encode( $api_user . ':' . $api_pass ); //phpcs:ignore

	$body_data = array(
		'CUSTPERSONNEL_SUBFORM' => array(
			array(
				'NAME'        => $contact_fullname,
				'EMAIL'       => $contact_email,
				'PHONENUM'    => $contact_phone,
				'POSITIONDES' => $contact_role,
			),
		),
	);

	$send_data = wp_json_encode( $body_data );
	$headers   = array( 'Content-Type: application/json', 'Authorization: ' . $auth );
	$curl      = curl_init(); 										//phpcs:ignore.
	curl_setopt( $curl, CURLOPT_URL, $new_contact_endpoint_url );	//phpcs:ignore.
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );				//phpcs:ignore.
	curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'PATCH' );			//phpcs:ignore.
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $send_data );			//phpcs:ignore.
	curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );				//phpcs:ignore.
	$response = curl_exec( $curl );									//phpcs:ignore.
	curl_close( $curl );											//phpcs:ignore.

	$response = json_decode( $response, true );

	unset( $response['@odata.context'] );

	$billing_phone             = get_user_meta( $current_user_id, 'billing_phone', true );
	$customer_by_phone_api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/CUSTOMERS?$filter=PHONE%20eq%20%27' . $billing_phone . '%27&$select=CUSTNAME,CUSTDES,%20WTAXNUM,PHONE,EMAIL,ADDRESS,ZIP,STATEA,MCUSTNAME,AGENTCODE,AGENTNAME,MAX_OBLIGO,STATDES&$expand=CUSTPERSONNEL_SUBFORM&?$select=NAME,%20EMAIL';

	$customer_response = wp_remote_get(
		$customer_by_phone_api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $customer_response ) ) {
		$data['message'] = $customer_response->get_error_message();
	}

	$customer_body = json_decode( wp_remote_retrieve_body( $customer_response ), true );
	unset( $customer_body['@odata.context'] );
	$customer_body = $customer_body['value'];
	$customer_body = reset( $customer_body );

	$subform           = isset( $customer_body['CUSTPERSONNEL_SUBFORM'] ) ? $customer_body['CUSTPERSONNEL_SUBFORM'] : array();
	$api_user_contacts = update_field( 'api_user_contacts', json_encode( $subform, JSON_UNESCAPED_UNICODE ), 'user_' . $current_user_id );

	if ( isset( $response['CUSTNAME'] ) && $response['CUSTNAME'] ) {
		$data['error']   = false;
		$data['message'] = 'משתמש חדש נוסף הבצלחה.';
	} else {
		$data['message'] = 'API error.';
	}
	ob_start();
	?>
	<?php
	$updated_api_user_contacts = get_field( 'api_user_contacts', 'user_' . $current_user_id );
	if ( $updated_api_user_contacts ) {
		$updated_api_user_contacts = json_decode( $updated_api_user_contacts, true );
		foreach ( $updated_api_user_contacts as $index => $user_contact ) :
			$contact_role = isset( $user_contact['POSITIONDES'] ) ? $user_contact['POSITIONDES'] : '';
			if ( 'Admin' === $contact_role || 'admin' === $contact_role ) {
				$contact_role = 'מנהל';
			}
			?>
			<tr>
				<td class="user_name">
					<?php echo $user_contact['NAME'] ? esc_html( $user_contact['NAME'] ) : ''; ?>
				</td>
				<td class="user_role">
					<?php echo $contact_role ? esc_html( $contact_role ) : ''; ?>
				</td>
				<td class="user_phone">
					<?php echo $user_contact['PHONENUM'] ? esc_html( $user_contact['PHONENUM'] ) : ''; ?>
				</td>
				<td class="actions">
					<div class="action-buttons" data-index="<?php echo esc_html( $index ); ?>">
						<a href="#" class="edit-contact"></a>
						<a href="#" class="delete-contact"></a>
					</div>
				</td>
			</tr>
			<?php
		endforeach;
	}
	$data['html'] = ob_get_clean();

	wp_send_json( $data );
}
/**
 * Update_profile_request
 */
function update_profile_request() {

	date_default_timezone_set( 'Asia/Jerusalem' ); //phpcs:ignore.

	$data = array(
		'error'   => true,
		'message' => '',
	);

	$update_profile_mailto = get_field( 'update_profile_mailto', 'option' );

	$form_data = isset( $_POST['form'] ) ? $_POST['form'] : '';	//phpcs:ignore.
	$form_args = array();

	parse_str( $form_data, $form_args );

	$to = $update_profile_mailto;

	$subject = 'בקשה לעדכון פרטים';

	$headers  = "From: B2B <info@b2b.dooble.us>\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	$message  = '<html><body style="direction: rtl; text-align: right;">';
	$message .= '<h2>בקשה לעדכון פרטים</h2>';
	$message .= '<h3>להלן פרטי המשתמש:</h3>';
	$message .= '<ol>';
	foreach ( $form_args as $key => $value ) {

		$key_title = '';
		if ( 'current_user_id' === $key ) {
			$key_title = 'מזהה של משתמש באתר B2B';
		} elseif ( 'customer_fullname' === $key ) {
			$key_title = 'שם מלא';
		} elseif ( 'customer_email' === $key ) {
			$key_title = 'אימייל';
		} elseif ( 'company_name' === $key ) {
			$key_title = 'שם חברה';
		} elseif ( 'contact_phone' === $key ) {
			$key_title = 'טלפון ליצירת קשר';
		} elseif ( 'address' === $key ) {
			$key_title = 'כתובת';
		}

		$message .= '<li>' . $key_title . ': <strong>' . $value . '</strong></li>';
	}
	$message .= '</ol>';

	$message .= '<p>טופס נשלחה ב:<br>' . date( 'd/m/Y בשעה H:i:s' ) . '<br>מכתובת IP:<br>' . b2b_get_client_ip() . '</p>'; //phpcs:ignore.

	$message .= '<p><img src="' . get_field( 'login_header_logo_1', 'option' )['url'] . '"></p>';

	$message .= '</body></html>';

	$mail = wp_mail( $to, $subject, $message, $headers );

	if ( $mail ) {
		$data['error']   = false;
		$data['message'] = get_field( 'update_profile_success_message', 'option' );
	}

	wp_send_json( $data );
}

/**
 * Submit_registration_form
 */
function submit_registration_form() {
	$data = array(
		'error'   => true,
		'message' => get_field( 'reg_email_notsend_message', 'option' ),
	);

	$form_data = isset( $_POST['form'] ) ? $_POST['form'] : '';
	$form_args = array();

	parse_str( $form_data, $form_args );

	$reg_page_id     = isset( $form_args['reg_page_id'] ) ? $form_args['reg_page_id'] : '';
	$reg_admin_email = get_field( 'reg_admin_email', $reg_page_id );

	// Step 1 fields.
	$first_name         = isset( $form_args['first_name'] ) ? $form_args['first_name'] : '';
	$last_name          = isset( $form_args['last_name'] ) ? $form_args['last_name'] : '';
	$shop_name          = isset( $form_args['shop_name'] ) ? $form_args['shop_name'] : '';
	$shop_address       = isset( $form_args['shop_address'] ) ? $form_args['shop_address'] : '';
	$buisness_type      = isset( $form_args['buisness_type'] ) ? $form_args['buisness_type'] : '';
	$phone              = isset( $form_args['phone'] ) ? $form_args['phone'] : '';
	$fax                = isset( $form_args['fax'] ) ? $form_args['fax'] : '';
	$email              = isset( $form_args['email'] ) ? $form_args['email'] : '';
	$customer_address   = isset( $form_args['customer_address'] ) ? $form_args['customer_address'] : '';
	$customer_phone     = isset( $form_args['customer_phone'] ) ? $form_args['customer_phone'] : '';
	$customer_cellphone = isset( $form_args['customer_cellphone'] ) ? $form_args['customer_cellphone'] : '';

	// Step 2 fields.
	$bank_name          = isset( $form_args['bank_name'] ) ? $form_args['bank_name'] : '';
	$bank_branch_id     = isset( $form_args['bank_branch_id'] ) ? $form_args['bank_branch_id'] : '';
	$bank_account_id    = isset( $form_args['bank_account_id'] ) ? $form_args['bank_account_id'] : '';
	$company_id         = isset( $form_args['company_id'] ) ? $form_args['company_id'] : '';
	$customer_fullname  = isset( $form_args['customer_fullname'] ) ? $form_args['customer_fullname'] : '';
	$customer_id        = isset( $form_args['customer_id'] ) ? $form_args['customer_id'] : '';
	$customer_shop_name = isset( $form_args['customer_shop_name'] ) ? $form_args['customer_shop_name'] : '';

	$to = $reg_admin_email;

	$subject = 'B2B NEW Customer Registration';

	$headers  = 'From: B2B Customer Registration <' . wp_strip_all_tags( 'info@b2b.dooble.us' ) . ">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	$message = '<html dir="rtl"><body style="direction:rtl;text-align:right;">';
	$message = '<style>th, td {
	  padding: 10px;
	}</style>';

	$message .= '<p style="text-align:right; font-weight: bold;">תאריך הגשה: ' . $form_args['date'] . '</p>';

	$message .= '<table dir="rtl" style="width:100%;"><tr style="vertical-align:top;"><td style="width: 320px;">';

	$message .= '<h3>פרטים אישיים</h3>';
	if ( $first_name ) {
		$message .= '<p>שם פרטי: ' . $first_name . '</p>';
	}
	if ( $last_name ) {
		$message .= '<p>שם משפחה: ' . $last_name . '</p>';
	}
		$message .= '<p>שם החנות: ' . $shop_name . '</p>';
		$message .= '<p>כתובת של חנות: ' . $shop_address . '</p>';
		$message .= '<p>סוג עסק: ' . $buisness_type . '</p>';
		$message .= '<p>טלפון: ' . $phone . '</p>';
		$message .= '<p>פקס: ' . $fax . '</p>';
		$message .= '<p>אימייל: ' . $email . '</p>';
		$message .= '<p>כתובת פרטית: ' . $customer_address . '</p>';
		$message .= '<p>טלפון: ' . $customer_phone . '</p>';
		$message .= '<p>טלפון נייד: ' . $customer_cellphone . '</p>';

	$message .= '</td><td style="width: 320px;">';

		$message .= '<h3>פרטי חשבון</h3>';
		$message .= '<p>שם בנק: ' . $bank_name . '</p>';
		$message .= '<p>מספר סניף: ' . $bank_branch_id . '</p>';
		$message .= '<p>מספר חשבון: ' . $bank_account_id . '</p>';
		$message .= '<p>מספר חברה/עוסק מורשה: ' . $company_id . '</p>';
		$message .= '<p><strong>אני החתום:</strong></p>';
		$message .= '<p>שם מלא: ' . $customer_fullname . '</p>';
		$message .= '<p>ת.ז: ' . $customer_id . '</p>';
		$message .= '<p><strong>בעלים של:</strong></p>';
		$message .= '<p>שם החנות: ' . $customer_shop_name . '</p>';

	$message .= '</td></tr></table>';

	$message .= '<p style="text-align:center; border-top: 3px solid #A1D45D; padding-top: 20px;"><img src="' . get_field( 'header_logo_1', 'option' )['url'] . '"></p>';

	$message .= '</body></html>';

	$mail = wp_mail( $to, $subject, $message, $headers );

	if ( $mail ) {
		$data['error']   = false;
		$data['message'] = get_field( 'reg_email_send_message', 'option' );
	}

	wp_send_json( $data );
}

/**
 * Submit_step1_form
 */
function submit_step1_form() {
	$data = array(
		'error'   => true,
		'status'  => '',
		'message' => get_field( 'login_phone_not_exists', 'option' ), // System error messages.
	);

	$form_data = isset( $_POST['form'] ) ? $_POST['form'] : '';
	$form_args = array();

	parse_str( $form_data, $form_args );

	$user_phone = isset( $form_args['phone'] ) ? $form_args['phone'] : '';
	if ( ! $user_phone ) {
		$data['message'] = get_field( 'user_phone_not_submitted', 'option' );
	}

	$clients_api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/CUSTOMERS?$filter=PHONE%20eq%20%27' . $user_phone . '%27&$select=CUSTNAME,CUSTDES,%20WTAXNUM,PHONE,EMAIL,ADDRESS,ZIP,STATEA,MCUSTNAME,AGENTCODE,AGENTNAME,MAX_OBLIGO,STATDES&$expand=CUSTPERSONNEL_SUBFORM&?$select=NAME,%20EMAIL';

	$agents_api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/AGENTS?$select=AGENTCODE,AGENTNAME,EMAIL&$filter=INACTIVE%20ne%20%27Y%27';

	$response = wp_remote_get(
		$clients_api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = $response_body['value'];

	// print_r( $response_body );
	// die();

	if ( $response_body ) {
		foreach ( $response_body as $customer ) {

			$api_client_phone = isset( $customer['PHONE'] ) ? $customer['PHONE'] : '';
			if ( $api_client_phone === $user_phone ) {

				$user_id = create_or_update_user( $customer );

				$data['error']   = false;
				$data['message'] = 'User found';
				$data['status']  = $customer['STATDES'];
				if ( 'פעיל' !== $customer['STATDES'] ) {
					$data['error']   = true;
					$data['message'] = 'לקוח לא פעיל';
				} else {
					b2b_send_sms( $user_id );
					$home_page_id = get_option( 'page_on_front' );
					ob_start();
					get_template_part(
						'inc/login-page/screen',
						'2',
						array(
							'post_id' => $home_page_id,
							'user_id' => $user_id,
						)
					);
					$data['step2'] = ob_get_clean();
				}
				$data['customer'] = $customer;
				break;
			}
		}
	}

	wp_send_json( $data );
}
/**
 * Submit_step2_form
 */
function submit_step2_form() {
	$data = array(
		'error'    => true,
		'redirect' => '',
		'message'  => get_field( 'wrong_sms_code', 'option' ), // System error messages.
	);

	$form_data = isset( $_POST['form'] ) ? $_POST['form'] : ''; //phpcs:ignore
	$form_args = array();

	parse_str( $form_data, $form_args );

	$user_id        = isset( $form_args['uid'] ) ? sanitize_text_field( $form_args['uid'] ) : '';
	$submitted_code = isset( $form_args['phone'] ) ? $form_args['phone'] : '';

	if ( $user_id && $submitted_code ) {
		$sms_code_varify = get_user_meta( (int) $user_id, 'sms_code_varify', true );
		if ( $sms_code_varify === $submitted_code ) {

			$user = get_user_by( 'id', $user_id );
			if ( $user ) {
				wp_set_current_user( $user_id, $user->user_login );
				wp_set_auth_cookie( $user_id );
			}

			$data['error']    = false;
			$data['redirect'] = get_permalink( wc_get_page_id( 'shop' ) );
		}
	}

	wp_send_json( $data );
}
/**
 * Create_or_update_user
 *
 * @param  array $customer  API customer data.
 * @return string WP_User ID.
 */
function create_or_update_user( $customer ) {
	$user_id = false;

	// print_r( $customer );
	// die();

	$customer_email = isset( $customer['EMAIL'] ) ? $customer['EMAIL'] : '';
	$customer_phone = isset( $customer['PHONE'] ) ? $customer['PHONE'] : '';

	$customer_name    = isset( $customer['CUSTDES'] ) ? $customer['CUSTDES'] : '';
	$customer_name_id = isset( $customer['CUSTNAME'] ) ? $customer['CUSTNAME'] : '';

	$customer_status     = isset( $customer['STATDES'] ) ? $customer['STATDES'] : '';
	$customer_agent_code = isset( $customer['AGENTCODE'] ) ? $customer['AGENTCODE'] : '';
	$customer_agent_name = isset( $customer['AGENTNAME'] ) ? $customer['AGENTNAME'] : '';
	// address fields.
	$customer_address          = isset( $customer['ADDRESS'] ) ? $customer['ADDRESS'] : '';
	$customer_city             = isset( $customer['STATEA'] ) ? $customer['STATEA'] : '';
	$customer_billing_postcode = isset( $customer['ZIP'] ) ? $customer['ZIP'] : '';

	$contacts = isset( $customer['CUSTPERSONNEL_SUBFORM'] ) ? $customer['CUSTPERSONNEL_SUBFORM'] : array();

	if ( $customer_status && 'פעיל' === $customer_status ) {
		$customer_status_boolean = true;
	} else {
		$customer_status_boolean = false;
	}

	if ( ! $customer_email ) {
		return $user_id;
	} else {
		$user = get_user_by( 'email', $customer_email );
		if ( $user ) {
			$user_id = $user->ID;
		} else {
			$user_role = 'customer';
			if ( 'alex.v@dooble.co.il' === $customer_email ) {
				$user_role = 'administrator';
			}
			$userdata = array(
				'user_login'           => $customer_email,
				'user_email'           => $customer_email,
				'user_url'             => $website,
				'show_admin_bar_front' => false,
				'user_pass'            => 'pass_' . $customer_phone,
				'display_name'         => $customer_name,
				'role'                 => $user_role,
			);

			$user_id = wp_insert_user( $userdata );
		}
	}

	if ( $user_id ) {
		update_field( 'user_api_response_data', json_encode( $customer, JSON_UNESCAPED_UNICODE ), 'user_' . $user_id );
		update_field( 'active_user', $customer_status_boolean, 'user_' . $user_id );
		update_field( 'user_status', $customer_status, 'user_' . $user_id );
		update_field( 'agent_code', $customer_agent_code, 'user_' . $user_id );
		update_field( 'agent_name', $customer_agent_name, 'user_' . $user_id );
		if ( $customer_name ) {
			update_field( 'customer_name', $customer_name, 'user_' . $user_id );
			$customer_name_arr = explode( ' ', $customer_name );
			if ( isset( $customer_name_arr[0] ) ) {
				update_user_meta( $user_id, 'billing_first_name', $customer_name_arr[0] );
				update_user_meta( $user_id, 'first_name', $customer_name_arr[0] );
			}
			if ( isset( $customer_name_arr[1] ) ) {
				update_user_meta( $user_id, 'billing_last_name', $customer_name_arr[1] );
				update_user_meta( $user_id, 'last_name', $customer_name_arr[1] );
			}
		}
		if ( $customer_name_id ) {
			update_field( 'customer_name_id', $customer_name_id, 'user_' . $user_id );
		}

		if ( $customer_address ) {
			update_user_meta( $user_id, 'billing_address_1', $customer_address );
		}
		if ( $customer_city ) {
			update_user_meta( $user_id, 'billing_city', $customer_city );
		}
		if ( $customer_billing_postcode ) {
			update_user_meta( $user_id, 'billing_postcode', $customer_billing_postcode );
		}
		if ( $customer_phone ) {
			update_user_meta( $user_id, 'billing_phone', $customer_phone );
		}
		update_user_meta( $user_id, 'billing_country', 'IL' );

		// save user contacts.
		update_field( 'api_user_contacts', json_encode( $contacts, JSON_UNESCAPED_UNICODE ), 'user_' . $user_id );
	}

	return $user_id;
}
/**
 * Generate SMS code
 *
 * @return string SMS code 6 digits.
 */
function generate_login_code() {
	return random_int( 100000, 999999 );
}
/**
 * B2B send_sms
 *
 * @param  string $user_id WP_User ID.
 */
function b2b_send_sms( $user_id ) {

	$to   = get_user_meta( $user_id, 'billing_phone', true );
	$code = generate_login_code();

	if ( ! $message ) {
		$message = 'קוד אימות שלך הוא: ' . $code;
	}

	$sms_endpoint_url = 'https://capi.inforu.co.il/api/v2/SMS/SendSms';
	$auth             = 'Basic YW1icm9zaWE6N2I5MTAyY2MtOTg5YS00ZDk3LTkxNmItYjhkZGFhNGEyNzYx';

	$post_fields = '{
        "Data": {
            "Message": "' . $message . '",
            "Recipients": [
                {
                    "Phone": "' . $to . '"
                }
            ],
            "Settings": {
                "Sender": "B2B Supherb"
            }
        }
    }';

	$curl = curl_init();   //phpcs:ignore

	curl_setopt_array(     //phpcs:ignore
		$curl,
		array(
			CURLOPT_URL            => $sms_endpoint_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => '',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => 'POST',
			CURLOPT_POSTFIELDS     => $post_fields,
			CURLOPT_HTTPHEADER     => array(
				'Content-Type: application/json',
				'Authorization: Basic YW1icm9zaWE6N2I5MTAyY2MtOTg5YS00ZDk3LTkxNmItYjhkZGFhNGEyNzYx',
			),
		)
	);

	$response = curl_exec( $curl );    //phpcs:ignore

	curl_close( $curl );   //phpcs:ignore

	$response = json_decode( $response, true );

	if ( isset( $response['StatusDescription'] ) && 'Success' === $response['StatusDescription'] ) {
		update_user_meta( $user_id, 'sms_code_varify', $code );
	}

	return $response;
}

/**
 * Get agent_by_agent_code
 *
 * @param  string $agent_code  Agent code priority.
 * @return array  Agent array
 */
function get_agent_by_agent_code( $agent_code ) {
	if ( ! $agent_code ) {
		return array();
	}

	$api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/AGENTS?$filter=AGENTCODE%20eq%20%27' . $agent_code . '%27&$select=AGENTCODE,AGENTNAME,EMAIL,PHONE&$expand=EXTFILES_SUBFORM($select=EXTFILENAME)';

	$response = wp_remote_get(
		$api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = $response_body['value'];

	if ( $response_body ) {
		$response_body = reset( $response_body );
	}

	return $response_body;
}

/**
 * GET customer_tigmulim
 *
 * @param  string $customer_name_id  customer id.
 * @return array                   [description]
 */
function get_customer_tigmulim( $customer_name_id ) {
	$api_url = 'https://priority.solgar.co.il/odata/Priority/tabula.ini/ambro1/ZAMB_CUSTDEALS?$select=CUSTNAME,ZMET_PRDIODCODE,DEALTYPECODE,DEALTYPEDES,TARGET1,TARGET2,TARGET3,TARGET4,FROMDATE,TOTSALESCALC&$filter=CUSTNAME%20eq%20%27%%customer_id%%%27%20and%20FROMDATE%20ge%20' . date( 'Y' ) . '-01-01T00:00:00%2B02:00';
	$api_url = str_replace( '%%customer_id%%', $customer_name_id, $api_url );

	$response = wp_remote_get(
		$api_url,
		array(
			'timeout'      => 120,
			'Content-Type' => 'application/json',
			'httpversion'  => '1.1',
			'method'       => 'GET',
			'headers'      => array(
				'Authorization' => b2b_get_api_login(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$data['message'] = $response->get_error_message();
	}

	$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
	unset( $response_body['@odata.context'] );

	$response_body = $response_body['value'];

	return $response_body;
}

/**
 * B2B API Authentication
 *
 * @return string Authentication
 */
function b2b_get_api_login() {
	$api_user = 'apib2b';
	$api_pass = 'Ambr0z2022!';
	$auth     = 'Basic ' . base64_encode( $api_user . ':' . $api_pass ); //phpcs:ignore

	return $auth;
}
/**
 * B2B get_user_json
 *
 * @param  string $user_id WP_User ID.
 * @return [type]          [description]
 */
function b2b_get_user_json( $user_id ) {
	$user_json = json_decode( get_field( 'user_api_response_data', 'user_' . $user_id ), true );
	return $user_json;
}

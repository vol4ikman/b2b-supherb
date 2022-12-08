<?php
/**
 * User contacts
 *
 * @package WordPress
 */

$user_contacts = get_field( 'api_user_contacts', 'user_' . get_current_user_id() );
$user_contacts = json_decode( $user_contacts, true );
// print_r( $user_contacts );
?>

<div class="user-account-contact-list">
	<div class="contact-list-title">
		<h3 class="edit-account-title">אנשי קשר</h3>
	</div>

	<div class="contact-list-wrapper">
		<?php if ( $user_contacts ) : ?>
			<table class="contact-list-table">
				<thead>
					<tr>
						<th class="user_name">שם מלא</th>
						<th class="user_role">תפקיד</th>
						<th class="user_phone">טלפון</th>
						<th class="actions"></th>
					</tr>
				</thead>

				<tbody>
					<?php
					foreach ( $user_contacts as $index => $user_contact ) :
						$user_status_from_api = isset( $user_contact['STATDES'] ) ? $user_contact['STATDES'] : '';
						$contact_role         = isset( $user_contact['POSITIONDES'] ) ? $user_contact['POSITIONDES'] : '';
						if ( 'Admin' === $contact_role || 'admin' === $contact_role ) {
							$contact_role = 'מנהל';
						}
						// skip not active contacts.
						if ( 'פעיל' !== $user_status_from_api ) {
							continue;
						}
						?>
						<tr data-status="<?php echo esc_html( $user_status_from_api ); ?>">
							<td class="user_name">
								<?php echo $user_contact['NAME'] ? esc_html( $user_contact['NAME'] ) : ''; ?>
							</td>
							<td class="user_role">
								<?php echo esc_html( $contact_role ); ?>
							</td>
							<td class="user_phone">
								<?php echo $user_contact['PHONENUM'] ? esc_html( $user_contact['PHONENUM'] ) : ''; ?>
							</td>
							<td class="actions">
								<div class="action-buttons" data-index="<?php echo esc_html( $index ); ?>">
									<a href="#" class="edit-contact"></a>
									<a href="#" class="delete-contact"></a>
								</div>
								<div class="action-buttons-trigger" data-index="<?php echo esc_html( $index ); ?>">
									<button class="three-dots"></button>
									<div class="actions-popup">
										<a href="#" class="edit-contact">עריכה</a>
										<a href="#" class="delete-contact">מחיקה</a>
									</div>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<div class="no-contacts-message">
				לא קיימים אנשי קשר
			</div>
		<?php endif; ?>
	</div>

	<?php get_template_part( 'inc/popup/create-new-contact' ); ?>

</div>

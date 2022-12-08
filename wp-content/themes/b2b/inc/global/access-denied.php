<?php
/**
 * Access denied message
 *
 * @package WordPress
 */

if ( ! is_user_logged_in() ) {

	date_default_timezone_set( 'Asia/Jerusalem' ); // phpcs:ignore.

	$header_logo_1 = get_field( 'header_logo_1', 'option' );
	echo "<style>@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');</style>";
	echo '<style>
	body { font-family: "Open Sans", sans-serif; }
	h1 { margin: 0 0 20px 0;}
	p {margin: 0;}
	img { margin-top: 30px; }
	.login-link {
		background: #A1D45D;
		text-decoration: none;
		color: #35563C;
		display:inline-block;
		height:40px;
		line-height: 40px;
		padding: 0 30px;
	}
	.login-link:hover {
		background:#35563C;
		color: #FFFFFF;
	}
	time {
		margin-top: 10px;
		margin-bottom: 10px;
		font-size: 14px;
		display:block;
		font-weight: bold;
	}
	.access-denied-wrapper {text-align: center;display: flex;flex-direction: column;align-items: center;justify-content: center;align-self: center;height: 90vh;}</style>';

	echo '<div class="access-denied-wrapper">';
	echo '<p><time>' . date( 'd/m/Y H:i:s' ) . '</time></p>';	// phpcs:ignore.
	echo '<h1>לקוח יקר. גישה לעמוד זה מתאפשרת אך ורק ללקוחות המחוברים למערכת. תודה</h1>';
	echo '<p><a class="login-link" href="' . esc_url( get_home_url() ) . '">התחברות</a></p>';
	echo '<img src="' . esc_url( $header_logo_1['url'] ) . '">';
	echo '</div>';
	die();
}

<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'b2bdooble_db' );

/** Database username */
define( 'DB_USER', 'b2bdooble_user' );

/** Database password */
define( 'DB_PASSWORD', '1!qaz2@wsx' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY', 'z$Cu(Lns+8`dsZ?J3&omc-Vl%kUCV)Pq,3k0*kKrV^iw~7svks3u!h^Vmku=DZ)H' );
define( 'SECURE_AUTH_KEY', 'lM]yG(Rcp{+%PZQp*c|qqUydDwOY&,^x4:Ke[hSe1eQeQT*.jT?!vNTuX 0O[_f#' );
define( 'LOGGED_IN_KEY', '*}M*2X<179#RH7`,m?Cv&#c5 Z($igUB+%%7BegtE7IW2)^gc;TO`;-gEt#<VqQ^' );
define( 'NONCE_KEY', '^jG;CMQcG@Q5G$q3C2VWW+s^jzT=?`XGlvHRBqbpbT?hlJULp*iTd^H*Du~z @1e' );
define( 'AUTH_SALT', 'KG1,Pcu&]jzI9k]VqLtE1f9:{x[Janyj@_wP~Y(FTsS&qdp t,hEvS#xvq)4QBU]' );
define( 'SECURE_AUTH_SALT', '/ux_ssBDN0()sLFEl#SB@WnJRMI9IibVRv$;$?k:I(j_aOgN8*7t8&a>TeOE>QKQ' );
define( 'LOGGED_IN_SALT', '<:[+}H=_{,+D8*?wR8^~~p-ib>P>p8aN#dt>{q35j;[emfTho1e-c=N~6:6z0hMq' );
define( 'NONCE_SALT', 'j^&I0yuwrHyqfb)D{(XNKdhatZm{Km+ (]r-vI/w0WuRdoKh@b}S|xCkgIXv!LGa' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'b2bwp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

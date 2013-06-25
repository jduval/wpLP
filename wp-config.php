<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/nims/workspace/wpLP/dossier-cache/content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'wpLP');

/** MySQL database username */
define('DB_USER', 'wpLP');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'k^yVmo|n3ACazC8~QJ-DbI=)>}v!4NcCa)01PE7$Y8|Wr<y>V^EeGd,<?mxuoM;=');
define('SECURE_AUTH_KEY',  '*tOtf-VxqznX>Lx`}AZv57AZLT*:=GK19F|kF0*}3)%Z(]%!]m:@{|i,VQ0k)g?{');
define('LOGGED_IN_KEY',    '>9FX#eDaF%RQ/ARM]BA&iK?Y_DDlb4Tqs;?a,mEh}izLvlg~hVTn2GE>KYj&6j-5');
define('NONCE_KEY',        '_ak9h.;I[q5ssa-5a:.~m|g[f|Ekrf5}FecajPHs/XYN3Mb8_qy:(:MH8b##h+XR');
define('AUTH_SALT',        'D(e5Sn5f&+*xM2-3Ma-^3P[Z}#xxsC{|zxEsPs(wiW+t+Cz(E]53q6(yC|(}Sp3j');
define('SECURE_AUTH_SALT', '|EF|A@-DZEB)RQjX4v(rs--S`D1>K}6w?0tnijY!x^<udoG;:RH/qniuwe@)*Wrt');
define('LOGGED_IN_SALT',   'J-1z>vq.|Wu:|oHwECJ#q6h4s#XCZ[Q3Oa.|1^O_twL3-/wE,>Du+c]bDNj@(q)4');
define('NONCE_SALT',       'XLY8Dj-Dk|2sBie-JU&]ah<_}j|9riE4z2wH,XT_+;K{v()a5@SnGb^+8xJDpG]h');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpLP_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
//define('WP_DEBUG', true);

/** moving wp-content folder **/
define ( 'WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/workspace/wpLP/dossier-cache/content' );
define ( 'WP_CONTENT_URL', 'http://localhost/workspace/wpLP/dossier-cache/content' );

define('WP_ADMIN_DIR', 'admin');
define( 'ADMIN_COOKIE_PATH', SITECOOKIEPATH . WP_ADMIN_DIR);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

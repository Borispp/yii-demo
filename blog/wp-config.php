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
define('DB_NAME', 'yoursturioapp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'iloveflosites');

/** MySQL hostname */
define('DB_HOST', 'office.flosites.com');

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
define('AUTH_KEY',         'pFu|nip;+FW7!F|+]aKV&oGF2r_Az1*MQofX~|ju4(o=4FN=Gx80g#/bmPJ)Slmv');
define('SECURE_AUTH_KEY',  '|?FCkq%fj8MuOJ%Y(5dAi%nEBT=C2N2eGWuEY2t~@+|T!80~j;-z)H5,=8.p}S>S');
define('LOGGED_IN_KEY',    '?hT>@hQ|</P(@f`v>K|oO;EK&](u{k7Mm.tI;4V%+`X344F&d&03a0ukJ5|)X?B<');
define('NONCE_KEY',        '-83zYLO@,l>V?&.FOY_UGw2;2/xR<!u+3i;K[l+BP#iu+]0-_p?%pDx(a1itiUEp');
define('AUTH_SALT',        'A kjg6>*TD8l)/.q/U5/{RafLHmn5is@c:6R)`I-o6W*i9xO?bK$uPOQ.t0!X)Y|');
define('SECURE_AUTH_SALT', '2QF8fdMP|[8O4}$5X6fJ3t:G5y%{JOa*Ux?y0E*a8l}Xsj [:LCLDQx@,aK|Ch4+');
define('LOGGED_IN_SALT',   '_ub?Df=8LBvb_b_Tl<`[_&@TFPP7MSmBX2B{/f$.zWRuGcS-u@{J&GKDMM<W<Y>w');
define('NONCE_SALT',       '4-3(!23%iX5<ouQ,@_yI_AmSp(J&-mIhqbbF6q^g|jDD{a7Pa@v+c)6yP_Cc^E,Y');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'blog_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

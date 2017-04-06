<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'heroku_ce7964612a981e9');

/** MySQL database username */
define('DB_USER', 'b3363acd8e25e9');

/** MySQL database password */
define('DB_PASSWORD', '9d104eb8');

/** MySQL hostname */
define('DB_HOST', 'us-cdbr-iron-east-03.cleardb.net');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'EVbHb6Aq?;~#.SJSWbKotJ!r#HI#]CU;Y`Fae}ld$=@frxYZx($D@:mcuWqJ~qf(');
define('SECURE_AUTH_KEY',  'jjRz>>PC#(T3)-]T$R0b<Po2_3GMo>~,/-T-En(XvAR>>3kqpQ1;lTQB|_@nDIw?');
define('LOGGED_IN_KEY',    'cpFfoW:3.nTY}AY^1g1m[SQ?U[q~&?8F)Y0P!:|}.fE)e~E[G+matf>Qz/02q|:?');
define('NONCE_KEY',        '@>P!Hn0YOV|+Gz#99#r%U4}o>^>2LcYy5VznwUU{Rp9Wx`z?M|zxs-,@_@-tFHly');
define('AUTH_SALT',        'F@{!)RyCR9~x~Scss7qpeo2!SG,]5h1NC3q:r(|DN&]&.Hgi4GJ)E4cl~RBhP)_M');
define('SECURE_AUTH_SALT', 'o?#>Csv?V^.{4]RW$p,T*bCIY *ooPDHh@s41CwJ1T+,Af+WW}(W{HA@:h^(,*qU');
define('LOGGED_IN_SALT',   '`i&-VtV700gy`I[28{I,HciQdwUsgIE?}d{qbNhDy]N--2Kwg&Y74 V=E%r[ahpM');
define('NONCE_SALT',       'U*1H(tN_`5_ Ns<3C(z%kX|`{8k6* 0z57eXHE[M4j^7!#[poxN0I:0G|U:2iKQG');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'eta_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

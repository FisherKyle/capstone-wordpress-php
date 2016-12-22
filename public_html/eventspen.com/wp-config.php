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
define('DB_NAME', 'kylefi5_wp55');

/** MySQL database username */
define('DB_USER', 'kylefi5_wp55');

/** MySQL database password */
define('DB_PASSWORD', '05-!nl6Sdp');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'us1do3osxmw9vqgh3h8mxgcd5v8p6punw4qsv0g1istpdayctfwn22wzb5jaqx02');
define('SECURE_AUTH_KEY',  '9yjjegijtmzxog6wxz6q8hzmtd7atnfnton2pg6hhhky7lojd4ni9jyfowq53wlu');
define('LOGGED_IN_KEY',    '7epbvyfs4prqv3e9yu0jeijv9zvlq6fdjcyo3r3w8n8zcipt20trshkzfg972lor');
define('NONCE_KEY',        '15qzhnwx7ao1sralizcpqxgpn73qypwz1qkb8zvsoycngggdy9h24h9ffwvairln');
define('AUTH_SALT',        'zrfml0abosr5at4fq6jcat7oh3sw9ldrs6xtdskzgf1snjyii1pgd7xsxfoaan1j');
define('SECURE_AUTH_SALT', 'x7xja9qov0dfn3g7stxkep3cvboovziuaaqsuoeken1dshn33bi0wc4rj4y0tqgw');
define('LOGGED_IN_SALT',   'aeykou8j8em5rvqllkd7xxmy5del9kiflag0t3tuhq5xxuojzd2im2noulqggrjv');
define('NONCE_SALT',       '66ypmrgwbuz38fayphjslzaku4gddqqstiira6rgyowkf64gabfxxr9g5f5oobjy');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpt4_';

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

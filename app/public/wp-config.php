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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '2bd95TJ+jpUwH2zLiNLcD4Fc/urxqdb8NAfZOwsT8iMlSqbUFdyeP8Zq5wtCcnT8k/+Ersg0crwKNziu/yZSag==');
define('SECURE_AUTH_KEY',  'XDVJTehAEIbtPoUB/mg+n4Nk8XuEhW5xsiqZMv0Ub5sx/2Z0bFVP51Q8T1bDeLyrKiF6S7GgQ0xz+CcG+kEY2A==');
define('LOGGED_IN_KEY',    'VYdq8BG+tUuN3AYgmZDSD5e5X1hadic6EMesDm1kgP57HDiyGNnlYW8RnZeWLoCFU9tqMjyvcPwAAMImaSRruA==');
define('NONCE_KEY',        'pJ8JCsaNypL70WJUFA0qIHYZuIQtgOeOmU3093DGTlgAMDbEfD5ShMfrQ+TvdZp52aa0wFwirRvE+FJHdIUsxg==');
define('AUTH_SALT',        'LsipynuD9fCZkMWmqnTzrZAT6dP9egui1FtAwSqjmEUcFswj/73h9+ZFLJtFg2JUy4S/3ZdXfohlxdqdIwtk5Q==');
define('SECURE_AUTH_SALT', 'feNdvGjMxPSx5nOcRBq0LQVr8pzz9lwMhzxJUPwfdX2LwxznilP2wo7BPJodx4gdB9FfyJFEhQQvbhkoIx2umA==');
define('LOGGED_IN_SALT',   'RMTU31d93eEfUpMBgQkq/jiFw34cTSc6xsYTipt4sTCit2tsC947L4SyJuVZp2MPM36bDt2OkXSvkxhg9ySNVA==');
define('NONCE_SALT',       'W0imZqiZeWW3ADFqM+V4TIkCG1Bhh4adBVwnoVCGEn5ZnWCup4qavH1oRkSjynNwE/ktJiONYt3aMExg8rgtbQ==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

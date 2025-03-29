<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sites_reactjs_2025_exam' );

/** Database username */
define( 'DB_USER', 'sitedev' );

/** Database password */
define( 'DB_PASSWORD', 'site1234' );

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
define( 'AUTH_KEY',         '&XeK5jfYPsBRyW4k!beQV+F97xj&j,iIrB~`_%SX::aDO& SHnd1!c<SZgR~H&AY' );
define( 'SECURE_AUTH_KEY',  ',+3mh-:f{=h==~?/>DDsr?;AypK#Bes}<(9^O;/j|;L9ch]Lv;{xfKG>hvCI8VXa' );
define( 'LOGGED_IN_KEY',    'xua&MJ[1s. ]0,%_/=IGhoRj7)}86bz -M45]}A/{myoJgtckQYM91DL?QUI2H@D' );
define( 'NONCE_KEY',        '4[XG[Vb| uDGh.VL1X=Yxn8(RD+ax/gM8~+o9h<o{4t?~5vUPfYGsj}}>iawt3DY' );
define( 'AUTH_SALT',        'p,q5o3#q6_/kJ{_oDBZ 2diAL!Fn-3_$daA}WtUG4_}_bJT~M,qBIr?VFwt~Z2%0' );
define( 'SECURE_AUTH_SALT', 'r2+R8y}TCc?c0r8qhs/.}7`ab mrKJm 7N85PxDx%U|Y>q-K[xvpCzXY{CQ(h {$' );
define( 'LOGGED_IN_SALT',   '$=]-x9*9,Y^W#-ZGd 3iqD7x%-2!4^+>43Bd>0` a>*;Fkno.Jnuz`f%R0kUVMA8' );
define( 'NONCE_SALT',       'Jme-KES:d6<;G#^v.@+[7z$Wytp#sQB6}]UF3J-`=KF[[TBc|Cs+Y;6J%RJCeMUk' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

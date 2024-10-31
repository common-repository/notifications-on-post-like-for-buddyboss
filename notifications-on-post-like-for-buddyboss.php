<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://acrosswp.com
 * @since             1.0.0
 * @package           Notifications_On_Post_Like_For_BuddyBoss
 *
 * @wordpress-plugin
 * Plugin Name:       Notifications On Post/Comment Like For BuddyBoss
 * Plugin URI:        https://acrosswp.com
 * Description:       Notifications to the Post author on there Post or Comment Like by Someone For BuddyBoss by AcrossWP
 * Version:           2.0.0
 * Author:            AcrossWP
 * Author URI:        https://acrosswp.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       notifications-on-post-like-for-buddyboss
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_FILES', __FILE__ );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-notifications-on-post-like-for-buddyboss.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function notifications_on_post_like_for_buddyboss_run() {
	$plugin = Notifications_On_Post_Like_For_BuddyBoss::instance();
	$plugin->run();

}
notifications_on_post_like_for_buddyboss_run();
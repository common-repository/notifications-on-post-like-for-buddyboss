<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Notifications_On_Post_Like_For_BuddyBoss
 * @subpackage Notifications_On_Post_Like_For_BuddyBoss/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Notifications_On_Post_Like_For_BuddyBoss
 * @subpackage Notifications_On_Post_Like_For_BuddyBoss/admin
 * @author     AcrossWP <contact@acrosswp.com>
 */
class Notifications_On_Post_Like_For_BuddyBoss_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $section_id = 'bb_enabled_notification_bb_messages_new';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add Settings link to plugins area.
	 *
	 * @since    1.0.3
	 *
	 * @param array  $links Links array in which we would prepend our link.
	 * @param string $file  Current plugin basename.
	 * @return array Processed links.
	 */
	public function modify_plugin_action_links( $links, $file ) {

		// Return normal links if not BuddyPress.
		if ( NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_BASENAME != $file ) {
			return $links;
		}

		
		// Add a few links to the existing links array.
		return array_merge(
			$links,
			array(
				'settings'      => '<a href="' . esc_url( admin_url( 'admin.php?page=bp-settings&tab=bp-notifications#'. $this->section_id ) ) . '">' . esc_html__( 'Settings', 'notifications-on-post-like-for-buddyboss' ) . '</a>',
				'about'         => '<a href="https://wordpress.org/plugins/notifications-on-post-like-for-buddyboss/" target="_blank">' . esc_html__( 'About plugin', 'notifications-on-post-like-for-buddyboss' ) . '</a>',
			)
		);
	}

}
<?php
/**
 * Notifications On Post Like For BuddyBoss.
 *
 * @package Notifications_On_Post_Like_For_BuddyBoss\Updater
 * @since Notifications On Post Like For BuddyBoss 1.0.1
 */

  
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The Updater-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Notifications_On_Post_Like_For_BuddyBoss
 * @subpackage Notifications_On_Post_Like_For_BuddyBoss/Updater
 * @author     AcrossWP <contact@acrosswp.com>
 */
class Notifications_On_Post_Like_For_BuddyBoss_Update {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The DB version slug of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name_db_version;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_name_db_version = '_' . $this->plugin_name . '_db_version';
		$this->version = $version;
	}

	/**
	 * Is this a fresh installation of Notifications On Post Like For BuddyBoss?
	 *
	 * If there is no raw DB version, we infer that this is the first installation.
	 *
	 * @return bool True if this is a fresh BP install, otherwise false.
	 * @since Notifications On Post Like For BuddyBoss 1.0.1
	 */
	public function is_install() {
		return ! $this->get_db_version_raw();
	}


	/**
	 * Get the DB version of Notifications On Post Like For BuddyBoss
	 * 
	 * @since Notifications On Post Like For BuddyBoss 1.0.1
	 */
	public function get_db_version_raw() {
		return get_option( $this->plugin_name_db_version, '0.0.1' );
	}

	/**
	 * Update the BP version stored in the database to the current version.
	 *
	 * @since Notifications On Post Like For BuddyBoss 1.0.1
	 */
	function version_bump() {
		update_option( $this->plugin_name_db_version, NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_VERSION );
	}

	/**
	 * Set up the Notifications On Post Like For BuddyBoss updater.
	 *
	 * @since Notifications On Post Like For BuddyBoss 1.0.1
	 */
	function setup_updater() {
		// Are we running an outdated version of Notifications On Post Like For BuddyBoss?
		if ( ! $this->is_update() ) {
			return;
		}

		$this->version_updater();
	}

	/**
	 * Is this a Notifications On Post Like For BuddyBoss update?
	 *
	 * Determined by comparing the registered Notifications On Post Like For BuddyBoss version to the version
	 * number stored in the database. If the registered version is greater, it's
	 * an update.
	 *
	 * @return bool True if update, otherwise false.
	 * @since Notifications On Post Like For BuddyBoss 1.0.1
	 */
	function is_update() {

		// Get current DB version.
		$current_db = $this->get_db_version_raw();

		// Get the raw database version.
		$current_live = NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_VERSION;


		$is_update = false;
		if ( version_compare( $current_live, $current_db ) ) {
			$is_update = true;
		}

		// Return the product of version comparison.
		return $is_update;
	}


	/**
	 * Initialize an update or installation of Notifications On Post Like For BuddyBoss.
	 *
	 * Notifications On Post Like For BuddyBoss's version updater looks at what the current database version is,
	 * and runs whatever other code is needed - either the "update" or "install"
	 * code.
	 *
	 * This is most often used when the data schema changes, but should also be used
	 * to correct issues with Notifications On Post Like For BuddyBoss metadata silently on software update.
	 *
	 * @since Notifications On Post Like For BuddyBoss 1.0.1
	 */
	function version_updater() {

		// Get current DB version.
		$current_db = $this->get_db_version_raw();

		// Get the raw database version.
		$current_live = NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_VERSION;

		if ( version_compare( '1.0.1', $current_db ) ) {
			notifications_on_post_like_for_buddyboss_to_1_0_0( $this->plugin_name );
		}

		/**
		 * Update the version
		 */
		$this->version_bump();
	}
}


/**
 * Update function which run on everytime a plugin install for the firstime
 */
function notifications_on_post_like_for_buddyboss_to_1_0_0( $plugin_name ) {

	$emails       = bp_email_get_schema();
	$descriptions = bp_email_get_type_schema( 'description' );

	$email_id = $plugin_name . '_message';

	$defaults = array(
		'post_status' => 'publish',
		'post_type'   => bp_get_email_post_type(),
	);

	// Add these emails to the database.
	foreach ( $emails as $id => $email ) {
		
		if( $email_id === $id ) {

			/**
			 * Skip if Email already exits
			 */
			if (
				term_exists( $id, bp_get_email_tax_type() ) &&
				get_terms(
					array(
						'taxonomy' => bp_get_email_tax_type(),
						'slug'     => $id,
						'fields'   => 'count',
					)
				) > 0
			) {
				continue;
			}

			// Some emails are multisite-only.
			if ( ! is_multisite() && isset( $email['args'] ) && ! empty( $email['args']['multisite'] ) ) {
				continue;
			}

			$post_id = wp_insert_post( bp_parse_args( $email, $defaults, 'install_email_' . $id ) );
			if ( ! $post_id ) {
				continue;
			}

			$tt_ids = wp_set_object_terms( $post_id, $id, bp_get_email_tax_type() );
			foreach ( $tt_ids as $tt_id ) {
				$term = get_term_by( 'term_taxonomy_id', (int) $tt_id, bp_get_email_tax_type() );
				wp_update_term(
					(int) $term->term_id,
					bp_get_email_tax_type(),
					array(
						'description' => $descriptions[ $id ],
					)
				);
			}
		}
	}
}
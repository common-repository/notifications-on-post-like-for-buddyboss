<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Notifications_On_Post_Like_For_BuddyBoss
 * @subpackage Notifications_On_Post_Like_For_BuddyBoss/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Notifications_On_Post_Like_For_BuddyBoss
 * @subpackage Notifications_On_Post_Like_For_BuddyBoss/includes
 * @author     AcrossWP <contact@acrosswp.com>
 */
final class Notifications_On_Post_Like_For_BuddyBoss {
	
	/**
	 * The single instance of the class.
	 *
	 * @var Notifications_On_Post_Like_For_BuddyBoss
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Notifications_On_Post_Like_For_BuddyBoss_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->define_constants();

		if ( defined( 'NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_VERSION' ) ) {
			$this->version = NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'notifications-on-post-like-for-buddyboss';

		$this->load_dependencies();
		$this->set_locale();
		$this->load_hooks();
	}

	/**
	 * Main Notifications_On_Post_Like_For_BuddyBoss Instance.
	 *
	 * Ensures only one instance of WooCommerce is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Notifications_On_Post_Like_For_BuddyBoss()
	 * @return Notifications_On_Post_Like_For_BuddyBoss - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Define WCE Constants
	 */
	private function define_constants() {

		$this->define( 'NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_FILE', NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_FILES );
		$this->define( 'NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_BASENAME', plugin_basename( NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_FILES ) );
		$this->define( 'NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_PATH', plugin_dir_path( NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_FILES ) );
		$this->define( 'NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_URL', plugin_dir_url( NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_FILES ) );
		
		$version = '1.0.0';

		if( function_exists( 'get_plugin_data' ) ){
			$plugin_data = get_plugin_data( NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_FILE );
			$version = $plugin_data['Version'];	
		}

		$this->define( 'NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_VERSION', $version );
	}

	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Register all the hook once all the active plugins are loaded
	 *
	 * Uses the plugins_loaded to load all the hooks and filters
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function load_hooks() {

		/**
		 * Check if plugin can be loaded safely or not
		 * 
		 * @since    1.0.0
		 */
		if( apply_filters( 'notifications-on-post-like-for-buddyboss-load', true ) ) {
			$this->define_public_hooks();
			$this->define_admin_hooks();
		}

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Notifications_On_Post_Like_For_BuddyBoss_Loader. Orchestrates the hooks of the plugin.
	 * - Notifications_On_Post_Like_For_BuddyBoss_i18n. Defines internationalization functionality.
	 * - Notifications_On_Post_Like_For_BuddyBoss_Admin. Defines all hooks for the admin area.
	 * - Notifications_On_Post_Like_For_BuddyBoss_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for loading the dependency main class
		 * core plugin.
		 */
		require_once NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/dependency/class-dependency.php';

		/**
		 * The class responsible for loading the dependency main class
		 * core plugin.
		 */
		require_once NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/dependency/buddyboss.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/class-notifications-on-post-like-for-buddyboss-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_PATH . 'includes/class-notifications-on-post-like-for-buddyboss-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_PATH . 'admin/class-notifications-on-post-like-for-buddyboss-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_PATH . 'admin/update/class-notifications-on-post-like-for-buddyboss-update.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once NOTIFICATIONS_ON_POST_LIKE_FOR_BUDDYBOSS_PLUGIN_PATH . 'public/class-notifications-on-post-like-for-buddyboss-public.php';

		$this->loader = Notifications_On_Post_Like_For_BuddyBoss_Loader::instance();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Notifications_On_Post_Like_For_BuddyBoss_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Notifications_On_Post_Like_For_BuddyBoss_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		/**
		 * For Plugin admin area
		 */
		$plugin_admin = new Notifications_On_Post_Like_For_BuddyBoss_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'plugin_action_links', $plugin_admin, 'modify_plugin_action_links', 10, 2 );

		/**
		 * For Plugin version update
		 */
		$plugin_update = new Notifications_On_Post_Like_For_BuddyBoss_Update( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'bp_admin_init', $plugin_update, 'setup_updater' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Notifications_On_Post_Like_For_BuddyBoss_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'bp_notifications_get_registered_components', $plugin_public, 'registered_components' );
		$this->loader->add_filter( 'bp_notifications_get_notifications_for_user', $plugin_public, 'format_notifications', 10000, 8 );
		$this->loader->add_action( 'bp_activity_add_user_favorite', $plugin_public, 'add_notification', 99, 2 );
		$this->loader->add_action( 'bp_activity_remove_user_favorite', $plugin_public, 'remove_notification', 99, 2 );

		$this->loader->add_action( 'bp_init', $plugin_public, 'bp_init', 99, 2 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Notifications_On_Post_Like_For_BuddyBoss_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
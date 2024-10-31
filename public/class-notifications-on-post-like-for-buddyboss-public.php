<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Notifications_On_Post_Like_For_BuddyBoss
 * @subpackage Notifications_On_Post_Like_For_BuddyBoss/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Notifications_On_Post_Like_For_BuddyBoss
 * @subpackage Notifications_On_Post_Like_For_BuddyBoss/public
 * @author     AcrossWP <contact@acrosswp.com>
 */
class Notifications_On_Post_Like_For_BuddyBoss_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The Custom ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The Custom ID of this plugin.
	 */
	private $plugin_name_action;

	/**
	 * The Custom ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The Custom ID of this plugin.
	 */
	private $plugin_name_message;

	/**
	 * The Custom ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The Custom ID of this plugin.
	 */
	private $plugin_name_action_comment;

	/**
	 * The Custom ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The Custom ID of this plugin.
	 */
	private $plugin_name_message_comment;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		
		$this->plugin_name_action = $plugin_name . '_action';
		$this->plugin_name_message = $plugin_name . '_message';

		$this->plugin_name_action_comment = $plugin_name . '_action_comment';
		$this->plugin_name_message_comment = $plugin_name . '_message_comment';

		$this->version = $version;
	}

	/**
	 * Registered  a new component for user like notifications
	 * BuddyBoss Filter Notifications Get Registered Components
	 * 
	 * @since 1.0.0
	 */
	function registered_components( $component_names = array() ) {

		// Force $component_names to be an array
		if ( ! is_array( $component_names ) ) {
			$component_names = array();
		}

		// Add 'npplfb_user_like' component to registered components array
		array_push( $component_names, $this->plugin_name );

		// Return component's with 'npplfb_user_like' appended
		return $component_names;
	}

	/**
 	 * This gets the saved item id, compiles some data and then displays the notification
	 * 
	 * @since 1.0.0
	 */
	function format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string', $main_action = '', $screen = '', $notification_id = '' ) {

		// New custom notifications
		if ( 
			! empty( $notification_id )
			&& (
				$this->plugin_name_action === $action 
				|| $this->plugin_name_action_comment === $action 
			)
		) {
			$activity = new BP_Activity_Activity( $item_id );
			$name = bp_core_get_user_displayname( $secondary_item_id );
		
			if ( $this->plugin_name_action === $action ) {
				$custom_text = sprintf( esc_html__( '%s liked on your post', 'notifications-on-post-like-for-buddyboss' ), $name );
			} else {
				$custom_text = sprintf( esc_html__( '%s liked on your comment', 'notifications-on-post-like-for-buddyboss' ), $name );
			}

			$custom_link = add_query_arg( 'rid', (int) $notification_id, bp_activity_get_permalink( $item_id ) );

			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'notifications_on_post_like_for_buddyboss_user_like_filter', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_text ) . '">' . esc_html( $custom_text ) . '</a>', $custom_text, $custom_link );

			// BuddyBoss Menu
			} else {
				$return = apply_filters( 'notifications_on_post_like_for_buddyboss_user_like_filter', array(
					'text' => $custom_text,
					'link' => $custom_link
				), $custom_link, (int) $total_items, $custom_text );
			}
			
			return $return;
		}

		return $action;
	}

	/**
	 * This hooks to comment creation and saves the comment id
	 * 
	 * @since 1.0.0
	 */
	function add_notification( $activity_id, $user_id ) {

		// Get the activity from the database.
		$activity 	= new BP_Activity_Activity( $activity_id );
		
		/**
		 * Check if the notifications is active 
		 * Check if the activity does not below to the login user and if so then do not send the notifications
		 */
		if ( 
			bp_is_active( 'notifications' ) 
			&& ! empty( $activity->user_id ) 
			&& $activity->user_id !== $user_id
		) {

			$action = $this->plugin_name_action;
			if ( $this->types( $activity->type ) ) {
				$action = $this->plugin_name_action_comment;
			}

			add_action( 'bp_notification_after_save', array( $this, 'send_email' ), 100 );

			bp_notifications_add_notification( array(
				'user_id'           => $activity->user_id,
				'item_id'           => $activity_id,
				'secondary_item_id' => $user_id,
				'component_name'    => $this->plugin_name,
				'component_action'  => $action,
				'date_notified'     => bp_core_current_time(),
			) );

			remove_action( 'bp_notification_after_save', array( $this, 'send_email' ), 100 );
		}
	}

	/**
	 * Send like notification to the user
	 */
	function send_email( $notification ) {

		$activity_id	= $notification->item_id;

		// Get the activity from the database.
		$activity 	= new BP_Activity_Activity( $activity_id );

		$action = $this->plugin_name_action;
		$message = $this->plugin_name_message;
		if ( $this->types( $activity->type ) ) {
			$action = $this->plugin_name_action_comment;
			$message = $this->plugin_name_message_comment;
		}
		
		$activity_url	= esc_url( bp_activity_get_permalink( $activity_id ) );

		$activity_author_id = $notification->user_id;
		
		$user_id = $notification->secondary_item_id;
		$name = bp_core_get_user_displayname( $user_id );
		$user_url = esc_url( bp_core_get_user_domain( $user_id ) );

		if ( true === bb_is_notification_enabled( $activity_author_id, $action ) ) {
			$args                          = array(
				'tokens' => array(
					'poster.name'   => $name,
					'poster_like.url'  	=> $user_url,
					'activity.url'  => $activity_url,
					'comment.url'  => $activity_url,
				),
			);

			$unsubscribe_args              = array(
				'user_id'           => $activity_author_id,
				'notification_type' => $message,
			);

			$args['tokens']['unsubscribe'] = esc_url( bp_email_get_unsubscribe_link( $unsubscribe_args ) );
			// Send notification email.
			bp_send_email( $message, $activity_author_id, $args );
		}
	}

	/**
	 * This hooks to comment creation and saves the comment id
	 * 
	 * @since 1.0.0
	 */
	function remove_notification( $activity_id, $user_id ) {

		if ( ! empty( $activity_id ) ) {
			// Get the activity from the database.
			$activity 	= new BP_Activity_Activity( $activity_id );

			$action = $this->plugin_name_action;
			if ( $this->types( $activity->type ) ) {
				$action = $this->plugin_name_action_comment;
			}
		
			$author_id  = $activity->user_id;
			$user_id 	= bp_loggedin_user_id();
			

			if ( bp_is_active( 'notifications' ) ) {
				bp_notifications_delete_notifications_by_item_id(
					$author_id, // Following user id.
					$activity_id,
					$this->plugin_name,
					$action,
					$user_id
				);
			}
		}
	}

	function types( $current_type ) {
		return in_array( $current_type, array( 'activity_comment', 'activity_update' ) );
	}

	/**
	 * Load file on BP init Hooks
	 */
	function bp_init() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-notifications-on-post-like-for-buddyboss-notification-type.php';
		
		/**
		 * Check if the class exits or not
		 */
		if( class_exists( 'Notifications_On_Post_Like_For_BuddyBoss_Notification' ) ) {
			$buddyboss_notification = new Notifications_On_Post_Like_For_BuddyBoss_Notification( $this->plugin_name );
		}
	}
}
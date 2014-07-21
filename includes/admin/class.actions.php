<?php
namespace aware;

/**
 * Class for processing admin action.
 *
 * @package     Connections
 * @subpackage  Admin Actions
 * @copyright   Copyright (c) 2013, Steven A. Zahm
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.7.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class actions {

	/**
	 * Stores the instance of this class.
	 *
	 * @access private
	 * @since 0.7.5
	 * @var (object)
	*/
	private static $instance;

	/**
	 * A dummy constructor to prevent the class from being loaded more than once.
	 *
	 * @access public
	 * @since 0.7.5
	 * @see cnAdminActions::init()
	 * @see cnAdminActions();
	 */
	public function __construct() { /* Do nothing here */ }

	/**
	 * Setup the class, if it has already been initialized, return the intialized instance.
	 *
	 * @access public
	 * @since 0.7.5
	 * @see cnAdminActions()
	 */
	public static function init() {

		if ( ! isset( self::$instance ) ) {

			self::$instance = new self;

			self::register();
			self::doActions();

		}

	}

	/**
	 * Return an instance of the class.
	 *
	 * @access public
	 * @since 0.7.5
	 * @return (object) cnAdminActions
	 */
	public static function getInstance() {

		return self::$instance;
	}

	/**
	 * Register admin actions.
	 *
	 * @access private
	 * @since 0.7.5
	 * @uses add_action()
	 * @return (void)
	 */
	private static function register() {

		// Role Actions
		add_action( 'aw_update_role_capabilities', array( __CLASS__, 'updateRoleCapabilities' ) );

	}

	/**
	 * Run admin actions.
	 *
	 * @access private
	 * @since 0.7.5
	 * @uses do_action()
	 * @return (void)
	 */
	private static function doActions() {

		if ( isset( $_POST['aw-action'] ) ) {

			do_action( 'aw_' . $_POST['aw-action'] );
		}

		if ( isset( $_GET['aw-action'] ) ) {

			do_action( 'aw_' . $_GET['aw-action'] );
		}
	}

	/**
	 * Update the role settings.
	 *
	 * @access private
	 * @since 0.7.5
	 * @uses current_user_can()
	 * @uses check_admin_referer()
	 * @uses wp_redirect()
	 * @uses get_admin_url()
	 * @uses get_current_blog_id()
	 * @return void
	 */
	public static function updateRoleCapabilities() {
		global $wp_roles;


		/*
		 * Check whether user can edit roles
		 */
		if ( current_user_can( 'aware_change_roles' ) || true ) {

			if ( isset( $_POST['roles'] ) ) {

				// Cycle thru each role available because checkboxes do not report a value when not checked.
				foreach ( $wp_roles->get_names() as $role => $name ) {

					if ( ! isset( $_POST['roles'][ $role ] ) ) continue;

					foreach ( $_POST['roles'][ $role ]['capabilities'] as $capability => $grant ) {

						// the admininistrator should always have all capabilities
						if ( $role == 'administrator' ) continue;

						if ( $grant == 'true' ) {
							\AWARERole::add( $role, $capability );
						} else {
							\AWARERole::remove( $role, $capability );
						}

					}
				}
			}

			if ( isset( $_POST['reset'] ) ) AWARERole::reset( $_POST['reset'] );

			if ( isset( $_POST['reset_all'] ) ) AWARERole::reset();

			wp_redirect( get_admin_url( get_current_blog_id(), 'admin.php?page=aware_roles' ) );
			exit();

		} else {

		}

	}

}

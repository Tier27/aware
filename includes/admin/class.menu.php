<?php

/**
 * Class for adding the admin menus 
 *
 * @package     AWARE
 * @subpackage  Admin Menu
 * @copyright   Copyright (c) 2014, Joshua B. Kornreich
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class AWAREAdminMenu {

	/**
	 * Stores the instance of this class.
	 *
	 * @access private
	 * @var (object)
	*/
	private static $instance;

	/**
	 * A dummy constructor to prevent the class from being loaded more than once.
	 *
	 * @access public
	 * @return (void)
	 */
	public function __construct() { /* Do nothing here */ }

	/**
	 * Setup the class, if it has already been initialized, return the intialized instance.
	 *
	 * @access public
	 * @return (void)
	 */
	public static function init() {

		if ( ! isset( self::$instance ) ) {

			self::$instance = new self;
			self::menu();
		}
	}

	/**
	 * Register the admin menus.
	 *
	 * @access private
	 * @since unknown
	 * @return (void)
	 */
	public static function menu() {


		//$instance->pageHook->topLevel = 
		add_menu_page( 'AWARE', 'AWARE', 'manage_options', 'aware_dashboard', array( __CLASS__, 'showPage' ), AWARE_URL . 'assets/images/menu.png', NULL );

		$submenu[0]   = array( 'hook' => 'dashboard', 'page_title' => 'AWARE : Dashboard', 'menu_title' => 'Dashboard', 'capability' => 'manage_options', 'menu_slug' => 'aware_dashboard', 'function' => array( __CLASS__, 'showPage' ) );
		$submenu[20]  = array( 'hook' => 'add', 'page_title' => 'AWARE : Clients', 'menu_title' => __( 'Clients', 'aware' ), 'capability' => 'manage_options', 'menu_slug' => 'aware_clients', 'function' => array( __CLASS__, 'showPage' ) );
		$submenu[40]  = array( 'hook' => 'add', 'page_title' => 'AWARE : Projects', 'menu_title' => __( 'Projects', 'aware' ), 'capability' => 'manage_options', 'menu_slug' => 'aware_projects', 'function' => array( __CLASS__, 'showPage' ) );
		$submenu[60]  = array( 'hook' => 'add', 'page_title' => 'AWARE : Events', 'menu_title' => __( 'Events', 'aware' ), 'capability' => 'manage_options', 'menu_slug' => 'aware_events', 'function' => array( __CLASS__, 'showPage' ) );
		$submenu[80]  = array( 'hook' => 'add', 'page_title' => 'AWARE : Communications', 'menu_title' => __( 'Communications', 'aware' ), 'capability' => 'manage_options', 'menu_slug' => 'aware_communications', 'function' => array( __CLASS__, 'showPage' ) );
		$submenu[100]  = array( 'hook' => 'settings', 'page_title' => 'Settings', 'menu_title' => 'Settings', 'capability' => 'manage_options', 'menu_slug' => 'aware_settings', 'function' => array( __CLASS__, 'showPage' ) );

		foreach ( $submenu as $menu ) {

			extract( $menu );

			//$instance->pageHook->{ $hook } = 
			add_submenu_page( 'aware_dashboard', $page_title, $menu_title, $capability, $menu_slug, $function );
		}

	}

	/**
	 * This is the registered function calls for the AWARE admin pages as registered
	 * using the add_menu_page() and add_submenu_page() WordPress functions.
	 *
	 * @access private
	 * @since unknown
	 * @return (void)
	 */
	public static function showPage() {

		switch ( $_GET['page'] ) {

			case 'aware_dashboard':
				include_once AWARE_PATH . 'includes/admin/pages/dashboard.php';
				break;

			case 'aware_clients':
				include_once AWARE_PATH . 'includes/admin/pages/clients.php';
				break;

			case 'aware_projects':
				include_once AWARE_PATH . 'includes/admin/pages/projects.php';
				break;

			case 'aware_events':
				include_once AWARE_PATH . 'includes/admin/pages/events.php';
				break;

			case 'aware_communications':
				include_once AWARE_PATH . 'includes/admin/pages/communications.php';
				break;

			case 'aware_settings':
				include_once AWARE_PATH . 'includes/admin/pages/settings.php';
				break;

			case 'connections_manage':
				include_once AWARE_PATH . 'includes/admin/pages/manage.php';
				$action = ( isset( $_GET['cn-action'] ) && ! empty( $_GET['cn-action'] ) ) ? $_GET['cn-action'] : '';
				connectionsShowViewPage( $action );
				break;

			case 'connections_add':
				include_once AWARE_PATH . 'includes/admin/pages/manage.php';
				connectionsShowViewPage( 'add_entry' );
				break;

			case 'connections_categories':
				include_once AWARE_PATH . 'includes/admin/pages/categories.php';
				connectionsShowCategoriesPage();
				break;

			case 'connections_settings':
				include_once AWARE_PATH . 'includes/admin/pages/settings.php';
				connectionsShowSettingsPage();
				break;

			case 'connections_templates':
				include_once AWARE_PATH . 'includes/admin/pages/templates.php';
				connectionsShowTemplatesPage();
				break;

			case 'connections_roles':
				include_once AWARE_PATH . 'includes/admin/pages/roles.php';
				connectionsShowRolesPage();
				break;
		}

	}

}

add_action( 'admin_menu', array( 'awareAdminMenu' , 'init' ) );

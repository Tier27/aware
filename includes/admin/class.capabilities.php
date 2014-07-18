<?php

/**
 * Class for registering and manageing the capabilities for AWARE.
 *
 * @package     AWARE
 * @subpackage  Roles
 * @extends	WP_Roles
 * @copyright   Copyright (c) 2014, Joshua B. Kornreich
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.0.1
 */

class AWARERole extends WP_Roles {

	/**
	 * @access private
	 * @since 0.0.1
	 * @var (object) AWARERole stores an instance of this class.
	*/
	private static $instance;

	/**
	 * Main AWARERole Instance.
	 *
	 * Ensures that only one instance of cnRole exists at any one time.
	 *
	 * @access public
	 * @since 0.7.5
	 * @return object AWARERole
	 */
	public static function getInstance() {

		if ( ! isset( self::$instance ) ) {

			/*
			 * Initiate an instance of the class.
			 */
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Returns an array of the default capabilities.
	 *
	 * @access private
	 * @since 0.0.1
	 * @return (array)
	 */
	public static function capabilities() {

		return array(
			'aware_view_menu'            => __( 'View Admin Menu', 'aware' ),
			'aware_view_dashboard'       => __( 'View Dashboard', 'aware' ),
			'aware_manage'               => __( 'View List (Manage)', 'aware' ),
			'aware_add_entry'            => __( 'Add Entry', 'aware' ),
			'aware_edit_entry'           => __( 'Edit Entry', 'aware' ),
			'aware_delete_entry'         => __( 'Delete Entry', 'aware' ),
			'aware_view_public'          => __( 'View Public Entries', 'aware' ),
			'aware_view_private'         => __( 'View Private Entries', 'aware' ),
			'aware_view_unlisted'        => __( 'View Unlisted Entries', 'aware' ),
			'aware_edit_categories'      => __( 'Edit Categories', 'aware' ),
			'aware_change_settings'      => __( 'Change Settings', 'aware' ),
			'aware_manage_template'      => __( 'Manage Templates', 'aware' ),
			'aware_change_roles'         => __( 'Change Role Capabilities', 'aware' )
		);
	}

	/**
	 * Add a capability to a role.
	 *
	 * @access public
	 * @since 0.0.1
	 * @param (string)  $role The role name.
	 * @param (string)  $cap The capability.
	 * @param (bool) $grant Whether or no to grant the capability to the roloe or not.
	 * @return void
	 */
	public static function add( $role, $cap, $grant = TRUE ) {

		// Bring a copy of this into scope.
		$instance = self::getInstance();

		if ( ! self::hasCapability( $role, $cap ) ) $instance->add_cap( $role, $cap, $grant );
	}

	/**
	 * Remove a capability from a role.
	 *
	 * @access public
	 * @since 0.0.1
	 * @param (string)  $role The role name.
	 * @param (string)  $cap  The capability.
	 * @return void
	 */
	public static function remove( $role, $cap ) {

		// Bring a copy of this into scope.
		$instance = self::getInstance();

		if ( self::hasCapability( $role, $cap ) ) $instance->remove_cap( $role, $cap );
	}

	/**
	 * Check whether a role has a capability or not.
	 *
	 * @access public
	 * @since 0.0.1
	 * @param  (string)  $role The role name.
	 * @param  (string)  $cap  The capability.
	 * @return (bool)
	 */
	public static function hasCapability( $role, $cap ) {

		// Bring a copy of this into scope.
		$instance = self::getInstance();

		if ( ! isset( $instance->roles[ $role ] ) ) return FALSE;

		$wp_role = new WP_Role( $role, $instance->roles[ $role ]['capabilities'] );

		return $wp_role->has_cap( $cap );
	}

	/**
	 * Reset all user role capabilities back to their default.
	 * If a roles has been supplied, that role will have its capabilities reset to its defaults.
	 *
	 * @access public
	 * @since 0.0.1
	 * @param (array)  $roles [optional]
	 * @return void
	 */
	public static function reset( $roles = array() ) {
		global $aware;

		// Bring a copy of this into scope.
		$instance = self::getInstance();

		/**
		 * These are the roles that will default to having full access
		 * to all capabilites. This is to maintain plugin behavior that
		 * existed prior to adding role/capability support.
		 */
		$coreRoles = array( 'administrator', 'editor', 'author' );

		/**
		 * If no roles are supplied to the method to reset; the method
		 * will reset the capabilies of all roles defined.
		 */
		if ( empty( $roles ) ) $roles = $instance->get_names();

		$capabilities = self::capabilities();

		foreach ( $roles as $role => $key ) {

			// If the current role is one of the defined core roles, grant them all capabilites
			$grant = in_array( $role, $coreRoles ) ? TRUE : FALSE;

			if ( in_array( $role, $coreRoles ) ) {

				foreach ( $capabilities as $cap => $name ) {

					if ( ! self::hasCapability( $role, $cap ) ) $instance->add_cap( $role, $cap, $grant );
				}

			} else {

				foreach ( $capabilities as $cap => $name ) {

					if ( self::hasCapability( $role, $cap ) ) $instance->remove_cap( $role, $cap );

				}

			}

			// Ensure all roles can view public entries.
			$instance->add_cap( $role, 'aware_view_public', TRUE );
		}

	}

	/**
	 * Purge all plugin capabilities.
	 *
	 * @access public
	 * @since 0.0.1
	 * @return void
	 */
	public static function purge() {

		// Bring a copy of this into scope.
		$instance = self::getInstance();

		$roles = $instance->get_names();

		foreach ( $roles as $role => $key ) {

			foreach ( self::capabilities() as $cap => $name ) {

				if ( self::hasCapability( $role, $cap ) ) $instance->remove_cap( $role, $cap );
			}
		}
	}

}

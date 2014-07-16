<?php

class AWARERetrieve {

	public function events ( $args = array() ) {

		$basis = array(
			'post_type'	=> 'event',
		);
		$args = array_merge($basis, $args);
		return get_posts( $args );

	}

	public function threads ( $args = array( 'posts_per_page' => -1 ) ) {

		$basis = array(
			'post_type'	=> 'conversation',
			'post_status'	=> 'private',
			'posts_per_page' => $args['posts_per_page'],
		);
		$args = array_merge($basis, $args);
		return get_posts( $args );

	}

	public function updates ( $args = array() ) {

		$basis = array(
			'post_type'	=> 'update',
			'post_status'	=> 'private',
			'meta_key'	=> 'client',
			'meta_value'	=> $args['client'],
		);
		$args = array_merge($basis, $args);
		return get_posts( $args );

	}

	public function clients() {

		$args = array( 'role' => 'client' );
		if( get_option( 'aware_development_mode' ) == 1 ) $args['role'] = '';
		return get_users( $args );

	}

	public function managers() {

		$args = array( 'role' => 'manager' );
		return get_users( $args );

	}

	public function projects ( $args = array() ) {

		$basis = array(
			'post_type'	=> 'project',
		);
		
		//We don't want to break the basis by passing the wrong data type into the merge
		if( is_array( $args ) )
			$args = array_merge($basis, $args);
		else
			$args = $basis;

		return get_posts( $args );

	}

	public function aware_get_ai1ec_events() {

		global $wpdb;
		return $wpdb->get_results("SELECT * FROM wp_ai1ec_events e JOIN wp_posts p ON e.post_id = p.ID");

	}

	public function aware_get_pm_projects() {

		global $wpdb;
		return $wpdb->get_results("SELECT * FROM wp_posts p JOIN wp_postmeta pm ON p.ID = pm.post_id WHERE p.post_type='project'");

	}

}
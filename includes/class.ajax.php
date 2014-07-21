<?php
namespace aware;

class ajax {

	public function __construct() {
		self::actions();
	}

	public function actions() {
		add_action( 'wp_ajax_aware_create_thread', array( __CLASS__, 'create_thread' ) );
		add_action( 'wp_ajax_aware_create_update', array( __CLASS__, 'create_update' ) );
		add_action( 'wp_ajax_aware_update_settings', array( __CLASS__, 'update_settings' ) );
		add_action( 'wp_ajax_admin_update_client', array( __CLASS__, 'update_client' ) );
		add_action( 'wp_ajax_admin_add_client', array( __CLASS__, 'add_client' ) );
		add_action( 'wp_ajax_admin_delete_client', array( __CLASS__, 'delete_client' ) );
		add_action( 'wp_ajax_admin_update_project', array( __CLASS__, 'update_project' ) );
		add_action( 'wp_ajax_admin_add_project', array( __CLASS__, 'add_project' ) );
		add_action( 'wp_ajax_admin_delete_project', array( __CLASS__, 'delete_project' ) );
		add_action( 'wp_ajax_admin_update_event', array( __CLASS__, 'update_event' ) );
		add_action( 'wp_ajax_admin_add_event', array( __CLASS__, 'add_event' ) );
		add_action( 'wp_ajax_admin_delete_event', array( __CLASS__, 'delete_event' ) );
		add_action( 'wp_ajax_aware_post_reply', array( __CLASS__, 'post_reply' ) );
	}

	public function create_thread() {

		$post = array(
			'post_content'	=> $_POST['content'],
			'post_title'	=> $_POST['title'],
			'post_name'	=> sanitize_title($_POST['title']),
			'post_type'	=> 'conversation',
			'post_status'	=> 'private',
		);
		$ID = wp_insert_post( $post );
		update_post_meta( $ID, 'client', $_POST['client'] );
		echo json_encode( array( 'action' => 'redirect', 'location' => get_the_permalink( $ID ) ) );

		die(); // this is required to return a proper result
	}

	public function create_update() {

		$post = array(
			'post_content'	=> $_POST['content'],
			'post_title'	=> $_POST['title'],
			'post_name'	=> sanitize_title($_POST['title']),
			'post_type'	=> 'update',
			'post_status'	=> 'private',
		);
		$ID = wp_insert_post( $post );
		$email = aware_email_from_id( $_POST['client'] );

		$message = 'You have a new update on your project. Click <a href="' . admin_url() . '">here</a> to view this update.';
		//echo $message;
		wp_mail( $email, 'New update from AWARE', $message );
		update_post_meta( $ID, 'client', $_POST['client'] );
		$_POST['response'] = 'Something fresh.';
		echo json_encode( $_POST );

		die(); // this is required to return a proper result
	}


	public function update_settings() {
		print_r( $_POST );
		if( !empty( $_POST['aware-administrative-email'] ) ) update_option('aware_administrative_email', $_POST['aware-administrative-email']);
		if( !empty( $_POST['aware-client-administrative-email'] ) ) update_option('aware_client_administrative_email', $_POST['aware-client-administrative-email']);
		if( !empty( $_POST['aware-administrative-name'] ) ) update_option('aware_administrative_name', $_POST['aware-administrative-name']);
		update_option('aware_development_mode', $_POST['aware-development-mode']);
		die();
	}

	public function update_client() {
		$args = array( 
			'ID' => $_POST['ID'], 
			'first_name' => $_POST['first-name'],
			'last_name' => $_POST['last-name'],
			'user_email' => $_POST['email'],
		);
		if( $_POST['password'] != '' ) $args['user_pass'] = $_POST['password'];
		$user_id = wp_update_user( $args );
		update_user_meta( $user_id, 'manager', $_POST['manager'] );
		delete_user_meta( $user_id, 'projects' );
		foreach( $_POST['projects'] as $project ) add_user_meta( $user_id, 'projects', $project );
		update_user_meta( $user_id, 'client-id', $_POST['client-id'] );
		update_user_meta( $user_id, 'notes', $_POST['notes'] );
		die();
	}


	public function add_client() {
		$args = array(
			'user_login' => $_POST['email'],
			'first_name' => $_POST['first-name'],
			'last_name' => $_POST['last-name'],
			'user_email' => $_POST['email'],
			'user_pass' => $_POST['password'],
			'role' => 'client',
		);
		$user_id = wp_insert_user( $args );
		update_user_meta( $user_id, 'manager', $_POST['manager'] );
		update_user_meta( $user_id, 'projects', $_POST['projects'] );
		foreach( $_POST['projects'] as $project ) add_user_meta( $user_id, 'projects', $project );
		update_user_meta( $user_id, 'client-id', $_POST['client-id'] );
		update_user_meta( $user_id, 'notes', $_POST['notes'] );
		$response = array(
			"ID" => $user_id,
			"text" => "The client has been created",
		);
		echo json_encode($response);
		die();
	}

	public function delete_client() {
		if( isset( $_POST['ID'] ) && !empty( $_POST['ID'] ) ) wp_delete_user( $_POST['ID'] );
	}

	public function update_project() {
		$args = array( 
			'ID' => $_POST['ID'], 
			'post_title' => $_POST['name'],
			'post_name' => sanitize_title($_POST['name']),
		);
		$post_id = wp_update_post( $args );
		update_post_meta( $post_id, 'notes', $_POST['notes'] );
		//update_post_meta( $post_id, 'clients', $_POST['clients'] );
		$clients = retrieve::clients();
		foreach( $clients as $client ) delete_user_meta( $client->ID, 'projects', $post_id );
		foreach( $_POST['clients'] as $client ) add_user_meta( $client, 'projects', $post_id );
		echo $post_id;
		die();
	}

	public function add_project() {
		$args = array( 
			'post_title' => $_POST['name'],
			'post_name' => sanitize_title($_POST['name']),
			'post_type' => 'project',
			'post_status' => 'publish'
		);
		$post_id = wp_insert_post( $args );
		update_post_meta( $post_id, 'notes', $_POST['notes'] );
		foreach( $_POST['clients'] as $client ) add_user_meta( $client, 'projects', $post_id );
		echo $post_id;
		die();
	}


	public function delete_project() {
		if( isset( $_POST['ID'] ) && !empty( $_POST['ID'] ) ) wp_delete_post( $_POST['ID'] );
	}

	public function update_event() {
		$args = array( 
			'ID' => $_POST['ID'], 
			'post_title' => $_POST['name'],
			'post_name' => sanitize_title($_POST['name']),
		);
		$post_id = wp_update_post( $args );
		$start_time = $_POST['start-date'] . ' ' . $_POST['start-hour'] . ':' . $_POST['start-minute'] . ':00' . $_POST['start-suffix'];
		$end_time = $_POST['end-date'] . ' ' . $_POST['end-hour'] . ':' . $_POST['end-minute'] . ':00' . $_POST['end-suffix'];
		update_post_meta( $post_id, 'start_time', strtotime($start_time) );
		update_post_meta( $post_id, 'end_time', strtotime($end_time) );
		update_post_meta( $post_id, 'details', $_POST['details'] );
		update_post_meta( $post_id, 'notes', $_POST['notes'] );
		//update_post_meta( $post_id, 'client', $_POST['client'] );
		$projects = retrieve::projects();
		foreach( $projects as $project ) delete_post_meta( $project->ID, 'events', $post_id );
		foreach( $_POST['projects'] as $project ) add_post_meta( $project, 'events', $post_id );
		//update_post_meta( $post_id, 'projects', $_POST['projects'] );
		//echo $post_id;
		die();
	}

	public function add_event() {
		$args = array( 
			'post_title' => $_POST['name'],
			'post_name' => sanitize_title($_POST['name']),
			'post_type' => 'event',
			'post_status' => 'publish'
		);
		$post_id = wp_insert_post( $args );
		$start_time = $_POST['start-date'] . ' ' . $_POST['start-hour'] . ':' . $_POST['start-minute'] . ':00' . $_POST['start-suffix'];
		$end_time = $_POST['end-date'] . ' ' . $_POST['end-hour'] . ':' . $_POST['end-minute'] . ':00' . $_POST['end-suffix'];
		update_post_meta( $post_id, 'start_time', strtotime($start_time) );
		update_post_meta( $post_id, 'end_time', strtotime($end_time) );
		update_post_meta( $post_id, 'details', $_POST['details'] );
		update_post_meta( $post_id, 'notes', $_POST['notes'] );
		//update_post_meta( $post_id, 'client', $_POST['client'] );
                foreach( $_POST['projects'] as $project ) add_post_meta( $project, 'events', $post_id );
		update_post_meta( $post_id, 'projects', $_POST['projects'] );
		echo $post_id;
		die();
	}

	public function delete_event() {
		if( isset( $_POST['ID'] ) && !empty( $_POST['ID'] ) ) wp_delete_post( $_POST['ID'] );
	}

	public function post_reply() {
		$post = array(
			'post_content'	=> $_POST['content'],
			'post_type'	=> 'conversation',
			'post_parent'	=> $_POST['parent'],
			'post_status'	=> 'private',
		);
		$ID = wp_insert_post( $post );
		update_post_meta( $ID, 'client', $_POST['client'] );
		echo json_encode( array( "ID" => $ID ) );

		die(); 
	}

}

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
		add_action( 'wp_ajax_admin_update_manager', array( __CLASS__, 'update_manager' ) );
		add_action( 'wp_ajax_admin_add_manager', array( __CLASS__, 'add_manager' ) );
		add_action( 'wp_ajax_admin_delete_client', array( __CLASS__, 'delete_client' ) );
		add_action( 'wp_ajax_admin_update_project', array( __CLASS__, 'update_project' ) );
		add_action( 'wp_ajax_admin_add_project', array( __CLASS__, 'add_project' ) );
		add_action( 'wp_ajax_admin_delete_project', array( __CLASS__, 'delete_project' ) );
		add_action( 'wp_ajax_admin_update_event', array( __CLASS__, 'update_event' ) );
		add_action( 'wp_ajax_admin_add_event', array( __CLASS__, 'add_event' ) );
		add_action( 'wp_ajax_admin_delete_event', array( __CLASS__, 'delete_event' ) );
		add_action( 'wp_ajax_aware_post_reply', array( __CLASS__, 'post_reply' ) );
		add_action( 'wp_ajax_aware_delete_thread', array( __CLASS__, 'delete_thread' ) );
	}

	public function create_thread() {
		return self::post_message();
		die(); 
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

		$update = Update::create($_POST);
		$update->attach($_POST['clients']);

		$email = aware_email_from_id( $_POST['client'] );

		$message = 'You have a new update on your project. Click <a href="' . admin_url() . '">here</a> to view this update.';
		//echo $message;

		foreach( $_POST['clients'] as $client_id) :
			$client = Client::find($client_id);
			if( $client->emails ) 
			{
				if( wp_mail( $client->email, 'New update from AWARE', $message ) )
				{
					echo "Mail sent successfully";
				}
			}
		endforeach;
		$_POST['response'] = 'Something fresh.';
		echo json_encode( $_POST );

		die(); // this is required to return a proper result
	}


	public function update_settings() {
		print_r( $_POST );
		if( !empty( $_POST['aware-administrator'] ) ) update_option('aware_administrator', $_POST['aware-administrator']);
		if( !empty( $_POST['aware-administrative-email'] ) ) update_option('aware_administrative_email', $_POST['aware-administrative-email']);
		if( !empty( $_POST['aware-client-administrative-email'] ) ) update_option('aware_client_administrative_email', $_POST['aware-client-administrative-email']);
		if( !empty( $_POST['aware-administrative-name'] ) ) update_option('aware_administrative_name', $_POST['aware-administrative-name']);
		update_option('aware_development_mode', $_POST['aware-development-mode']);
		die();
	}

	public function update_client() {
		/*
		print_r( $_POST );
		$args = array( 
			'ID' => $_POST['ID'], 
			'first_name' => $_POST['first-name'],
			'last_name' => $_POST['last-name'],
			'user_email' => $_POST['email'],
			'display_name' => $_POST['first-name'] . ' ' . $_POST['last-name'],
		);
		if( $_POST['password'] != '' ) $args['user_pass'] = $_POST['password'];
		$user_id = wp_update_user( $args );
		update_user_meta( $user_id, 'manager', $_POST['manager'] );
		delete_user_meta( $user_id, 'projects' );
		foreach( $_POST['projects'] as $project ) add_user_meta( $user_id, 'projects', $project );
		update_user_meta( $user_id, 'client-id', $_POST['client-id'] );
		update_user_meta( $user_id, 'notes', $_POST['notes'] );
		*/

		$user_id = $_POST['ID'];

		Client::update($user_id, 'first_name', $_POST['first-name']);
		Client::update($user_id, 'last_name', $_POST['last-name']);
		Client::update($user_id, 'manager_id', $_POST['manager']);
		Client::update($user_id, 'emails', $_POST['emails']);
		Client::update($user_id, 'notes', $_POST['notes']);

		die();
	}

	public function update_manager() {

		$user_id = $_POST['ID'];

		Manager::update($user_id, 'first_name', $_POST['first-name']);
		Manager::update($user_id, 'last_name', $_POST['last-name']);
		Manager::update($user_id, 'notes', $_POST['notes']);
		$manager = Manager::findByUser($user_id);
		foreach( $_POST['clients'] as $client_id )
		{
			Client::update(Client::userID($client_id), 'manager_id', $manager->id);
		}


		die();
	}


	public function add_client() {
		/*
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
		foreach( $_POST['projects'] as $project ) add_user_meta( $user_id, 'projects', $project );
		update_user_meta( $user_id, 'client-id', $_POST['client-id'] );
		update_user_meta( $user_id, 'notes', $_POST['notes'] );
		*/

		$user_id = Client::prepare($_POST);

		$client = Client::create($user_id);

		$response = array(
			"ID" => $user_id,
			"text" => "The client has been created",
		);
		echo json_encode($response);
		die();
	}

	public function add_manager() {

		$user_id = Manager::prepare($_POST);

		$manager = Manager::create($user_id);

		foreach( $_POST['clients'] as $client_id )
		{
			Client::update($client_id, 'manager_id', $manager->id);
		}

		$response = array(
			"ID" => $user_id,
			"text" => "The manager has been created",
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
		update_post_meta( $post_id, 'duration', $_POST['duration'] );
		update_post_meta( $post_id, 'notes', $_POST['notes'] );
		$clients = retrieve::clients();
		foreach( $clients as $client ) delete_user_meta( $client->ID, 'projects', $post_id );
		foreach( $_POST['clients'] as $client ) add_user_meta( $client, 'projects', $post_id );
		echo $post_id;

		Project::update($_POST['id'], 'client_id', $_POST['client']);
		Project::update($_POST['id'], 'name', $_POST['name']);
		Project::update($_POST['id'], 'duration', $_POST['duration']);
		Project::update($_POST['id'], 'details', $_POST['details']);
		Project::update($_POST['id'], 'notes', $_POST['notes']);

		$project = Project::find($_POST['id']);
		print_r( $project );
		$project->configureDates($_POST);
		print_r( $project );
		$project->_update('start_date', $project->start_date);
		$project->_update('end_date', $project->end_date);

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

		$project = Project::create($_POST);

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

		$event = Event::find($_POST['id']);
		$event->_update('name', $_POST['name']);
		$event->_update('details', $_POST['details']);
		$event->_update('notes', $_POST['notes']);
		$event->configureDates($_POST);
		$event->_update('start_date', $event->start_date);
		$event->_update('end_date', $event->end_date);

		die();
	}

	public function add_event() {
		/*
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
		*/

		$event = Event::create($_POST);

		die();
	}

	public function delete_event() {
		if( isset( $_POST['ID'] ) && !empty( $_POST['ID'] ) ) wp_delete_post( $_POST['ID'] );
	}

	public function post_reply() {
		return self::post_message();
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

	public function post_message() {
		if( !isset($_POST['recipient']) && isset($_POST['client']) ) $_POST['recipient'] = $_POST['client'];
		if( !isset($_POST['sender']) ) $_POST['sender'] = get_current_user_id();
		$message = Message::create($_POST);
		$content = 'You have a new message. Click <a href="' . $message->getPermalink() . '">here</a> to view it.';
		$client = Client::findByUser($_POST['recipient']);
			if( wp_mail( $client->email, 'New update from AWARE', $content ) )
			{
				echo "Mail sent successfully";
			}
		die();
	}

	public function delete_thread() {
		print_r( $_POST );
		Thread::deactivate($_POST['thread']);
		die;
	}

}

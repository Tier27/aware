<?php
namespace aware;

class maintenance {

	private $active;

	public function __construct( $args = array( 'active' => false ) ) {

		$this->active = $args['active'];	

	}
	
	public function purge_conversations() {
		if( !$this->active ) return;
		$conversations = retrieve::conversations();
		foreach( $conversations as $conversation ) wp_delete_post( $conversation->ID );
	}

	public function purge_updates() {
		if( !$this->active ) return;
		$updates = retrieve::updates();
		foreach( $updates as $update ) wp_delete_post( $update->ID );
	}

	public function purge_clients() {
		if( !$this->active ) return;
		$clients = retrieve::__clients();
		foreach( $clients as $client ) wp_delete_user( $client->ID );
	}

}

?>

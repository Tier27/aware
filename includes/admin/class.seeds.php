<?php
namespace aware;

class seeds {

	public function clients() {

		$clients = array();
		$clients[] = array(
			'user_login' 	=> 'client.one@aware.com',
			'first_name' 	=> 'Client', 
			'last_name' 	=> 'One',
			'user_email' 	=> 'clientone@aware.com',
			'user_pass' 	=> 'password',
		);
		return $clients;

	}

	public function seed_clients() {
		$clients = self::clients();
		foreach( $clients as $client ) {
			$id = wp_insert_user( $client );
		}
	}

} 
?>

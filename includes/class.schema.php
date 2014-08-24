<?php
namespace aware;

class schema {

	public function tables() {
		$tables = array();
		$tables = 'aware_client_inboxes';
	}

	public function fields() {
		$fields['aware_client_inboxes'] = array( 'client_id', 'INT' );
		$fields['aware_client_inboxes'] = array( 'message_id', 'INT' );
		$fields['aware_client_inboxes'] = array( 'message_type_id', 'INT' );
		$fields['aware_client_inboxes'] = array( 'viewed', 'BOOL' );
	}

}
?>

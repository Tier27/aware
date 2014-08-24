<?php
namespace aware;

class Schema {

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

	public static function createEvents()
	{
		global $wpdb;

		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}

		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE {$wpdb->collate}";
		}

		$table_name = $wpdb->prefix . "aware_events";
		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  name varchar(255) DEFAULT '',
		  UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	public static function dropEvents()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "aware_events";
		$wpdb->query("DROP TABLE $table_name");
	}

	public function create($name, $function)
	{
		global $wpdb;
		$table = new Table($wpdb->prefix . AWARE_PREFIX . $name);
		$function($table);
		$table->prepare();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $table->sql );
	}

	public function drop($name)
	{
		global $wpdb;
		$wpdb->query("DROP TABLE ".$wpdb->prefix . AWARE_PREFIX . $name);
	}

}
?>

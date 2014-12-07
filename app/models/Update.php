<?php
namespace aware;

class Update {

	public function __construct($atts = array())
	{
		if( empty($atts) ) $atts = $_POST;
		$this->project_id = $atts['project_id'];
		$this->subject = stripslashes($atts['subject']);
		$this->content = stripslashes($atts['content']);
	}

	public function insert()
	{
		global $wpdb;
		print_r( $this );
		$wpdb->insert('wp_aware_updates', (array)$this);
		$this->id = $wpdb->insert_id;
	}

	public static function create($data = array())
	{
		$update = new Update($data);
		$update->insert();
		return $update;
	}

	public static function all()
	{
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM wp_aware_threads");
	}

	public static function inbox()
	{
		global $wpdb;
		$recipient = get_current_user_id();
		return $wpdb->get_results("SELECT * FROM wp_aware_threads WHERE inbound = $recipient ORDER BY created_at DESC");
	}

	public static function find($id)
	{
		global $wpdb;
		$query = " SELECT * FROM wp_aware_updates u ";
		$query .= " WHERE t.id = $id ";
		$row = $wpdb->get_row($query, "ARRAY_A");
		$update = static::cast($row);
		return $update;
	}

	public function date()
	{
		return date('M d, Y', strtotime($this->created_at));
	}

	public static function cast($atts)
	{
		if( !is_array( $atts ) ) $atts = (array)$atts;
		$update = new Update($atts);
		$update->id = $atts['id'];
		$update->created_at = $atts['created_at'];
		return $update;
	}

	public function recipients()
	{
	}

	public function _update($key, $value)
	{
		global $wpdb;
		$query = "UPDATE wp_aware_updates SET $key='$value' WHERE id=$this->id";
		$wpdb->query($query);
		return $this;
	}

	public static function update($id, $key, $value)
	{
		global $wpdb;
		$query = "UPDATE wp_aware_updates SET $key='$value' WHERE id=$id";
		$wpdb->query($query);
	}

	public static function deactivate($id)
	{
	}

	public function isActive()
	{
	}

	public function attach($clients)
	{
		global $wpdb;
		foreach( $clients as $client )
		{
			$wpdb->insert('wp_aware_client_updates', array('update_id' => $this->id, 'client_id' => $client));
		}
	}

	public static function convert($__)
	{
		$___ = array();
		foreach( $__ as $_ ){
			$___[] = static::cast($_);
		}
		return $___;
	}

}

?>

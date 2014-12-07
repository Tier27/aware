<?php
namespace aware;

class User {

	public function __construct($id = 0, $data = array())
	{
		if( $id == 0 ) return $this;
		global $wpdb;
		if( !empty( $data ) ) 
		{
			$this->data = $data;
		} else {
			$this->data = $wpdb->get_row("SELECT * FROM wp_users WHERE ID=$id");
		}
		$this->meta = get_user_meta($this->data->ID);
	}

	public function firstName()
	{
		return $this->meta['first_name'][0];
	}

	public function lastName()
	{
		return $this->meta['last_name'][0];
	}

	public function fullName()
	{
		return $this->firstName() . ' ' . $this->lastName();
	}

	public static function all()
	{
		global $wpdb;
		$clients = array();
		$users = $wpdb->get_results("SELECT * FROM wp_users");
		foreach( $users as $user )
		{
			$clients[] = new Client(1, $user);
		}
		return $clients;
	}

	public static function isAdmin()
	{
		$user_id = get_current_user_id();
		$admin_id = Settings::adminID();
		return ( $user_id == $admin_id );
	}

	public static function IDbyEmail($email)
	{
		global $wpdb;
		$user_id = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email='$email'");
		return $user_id;
	}

	public static function delete($data)
	{
		if( is_array($data) ) 
			$id = User::IDbyEmail($data['email']);
		else 
			$id = $data;
		require_once ABSPATH . 'wp-admin/includes/user.php';
		return \wp_delete_user($id);
	}

	public static function findByClient()
	{

	}

}

?>

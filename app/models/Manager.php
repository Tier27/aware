<?php
namespace aware;

class Manager {

	public function __construct($user_id, $data = array())
	{
		$this->user_id = $user_id;
		if( !empty($data) )
		{
			$this->first_name = $data['first-name'];
			$this->last_name = $data['last-name'];
			$this->notes = $data['notes'];
		}
	}

	public function fullName()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	public function insert()
	{
		global $wpdb;
		$wpdb->insert('wp_aware_managers', (array)$this);
		$this->id = $wpdb->insert_id;
	}

	public static function create($user_id, $data = array())
	{
		if( empty($data) ) $data = $_POST;
		$_ = new Manager($user_id, $data);
		$_->insert();
		return $_;
	}

	public static function prepare($data)
	{
        $args = array(
            'user_login' => $data['email'],
            'first_name' => $data['first-name'],
            'last_name' => $data['last-name'],
            'user_email' => $data['email'],
            'user_pass' => $data['password'],
            'role' => 'manager',
        );
        $user_id = wp_insert_user( $args );
		return $user_id;
	}

	public static function all()
	{
		global $wpdb;
		$query = "SELECT m.id, m.user_id, u.ID, u.display_name, m.first_name, m.last_name, m.notes, u.user_email as email FROM wp_aware_managers m JOIN wp_users u ON m.user_id = u.ID";
		$users = $wpdb->get_results($query);
		$_ = array();
		foreach( $users as $user ) $_[] = Manager::cast((array)$user);
		return $_;
	}

	public static function cast($atts)
	{
		if( !is_array( $atts ) ) $atts = (array)$atts;
		$_ = new Manager($atts['ID']);
		foreach( $atts as $key => $value ) $_->$key = $value;
		return $_;
	}

	public static function update($id, $key, $value)
	{
		global $wpdb;
		$query = "UPDATE wp_aware_managers SET $key='$value' WHERE user_id=$id";
		$wpdb->query($query);
	}

	public static function find($id)
	{
		global $wpdb;
		$query = "SELECT m.id, m.user_id, u.ID, u.display_name, m.first_name, m.last_name, m.notes, u.user_email as email FROM wp_aware_managers m JOIN wp_users u ON m.user_id = u.ID";
		$user = $wpdb->get_row($query);
		$_ = Manager::cast((array)$user);
		return $_;
	}

	public static function findByUser($user_id)
	{
		global $wpdb;
		$query = "SELECT m.id, m.user_id, u.ID, u.display_name, m.first_name, m.last_name, m.notes, u.user_email as email FROM wp_aware_managers m JOIN wp_users u ON m.user_id = u.ID WHERE m.user_id = $user_id";
		$user = $wpdb->get_row($query);
		$_ = Manager::cast((array)$user);
		return $_;
	}

	public static function options($selected = null, $name = 'manager', $id_type = 'user')
	{
    	?><select name="<?php echo $name; ?>">
      		<option value="0">No client</option><?php
			$managers = static::all();
      		foreach( $managers as $manager ) : 
				if( $id_type == 'user' ) 
					$id = $manager->user_id;
				else
					$id = $manager->id;
      			?><option value="<?php echo $id; ?>" <?php if( $id == $selected ) : ?>selected="selected"<?php endif; ?>><?php echo $manager->fullName(); ?></option><?php
      	endforeach; 
    	?></select><?php
	}

	public function clients()
	{
		global $wpdb;
		$query = "SELECT * FROM wp_aware_clients WHERE manager_id = $this->id";
		$_ = $wpdb->get_results($query);
		return Client::convert($_);
	}

	public static function ID($user_id)
	{
		global $wpdb;
		$query = "SELECT id FROM wp_aware_managers WHERE user_id = $user_id";
		return $wpdb->get_var($query);
	}

	public static function userID($client_id)
	{
		if( $client_id == 0 ) return Settings::adminID();
		global $wpdb;
		$query = "SELECT user_id FROM wp_aware_managers WHERE id = $client_id";
		return $wpdb->get_var($query);
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

<?php
namespace aware;

class Client {

	public function __construct($user_id, $data = array())
	{
		$this->user_id = $user_id;
		if( !empty($data) )
		{
			$this->first_name = $data['first-name'];
			$this->last_name = $data['last-name'];
			$this->manager_id = $data['manager'];
			$this->emails = $data['emails'];
			$this->notes = $data['notes'];
		}
		/*
		if( $id == 0 ) return $this;
		global $wpdb;
		if( !empty( $data ) ) 
		{
			$this->data = $data;
		} else {
			$this->data = $wpdb->get_row("SELECT * FROM wp_users WHERE ID=$id");
		}
		$this->meta = get_user_meta($this->data->ID);
		*/
	}

	public function fullName()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	public static function _all()
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

	public function insert()
	{
		global $wpdb;
		$wpdb->insert('wp_aware_clients', (array)$this);
		$this->id = $wpdb->insert_id;
	}

	public static function create($user_id, $data = array())
	{
		if( empty($data) ) $data = $_POST;
		$client = new Client($user_id, $data);
		$client->insert();
		return $client;
	}

	public static function prepare($data)
	{
        $args = array(
            'user_login' => $data['email'],
            'first_name' => $data['first-name'],
            'last_name' => $data['last-name'],
            'user_email' => $data['email'],
            'user_pass' => $data['password'],
            'role' => 'client',
        );
        $user_id = wp_insert_user( $args );
		return $user_id;
	}

	public static function all()
	{
		global $wpdb;
		$query = "SELECT c.id, c.user_id, u.ID, u.display_name, c.first_name, c.last_name, c.manager_id, c.emails, c.notes, u.user_email as email FROM wp_aware_clients c JOIN wp_users u ON c.user_id = u.ID";
		$users = $wpdb->get_results($query);
		$clients = array();
		foreach( $users as $user ) $clients[] = Client::cast((array)$user);
		return $clients;
	}

	public static function cast($atts)
	{
		if( !is_array( $atts ) ) $atts = (array)$atts;
		$client = new Client($atts['ID']);
		foreach( $atts as $key => $value ) $client->$key = $value;
		return $client;
	}

	public static function update($id, $key, $value)
	{
		global $wpdb;
		$query = "UPDATE wp_aware_clients SET $key='$value' WHERE user_id=$id";
		echo $query;
		$wpdb->query($query);
	}

	public function updates()
	{
		global $wpdb;
		$query = "SELECT * FROM wp_aware_updates u JOIN wp_aware_client_updates cu ON u.id = cu.update_id WHERE cu.client_id = $this->id";
		$results = $wpdb->get_results($query);
		$updates = array();
		foreach( $results as $update ) $updates[] = Update::cast((array)$update);
		return $updates;
	}

	public static function find($id)
	{
		global $wpdb;
		$query = "SELECT c.id, c.user_id, u.ID, u.display_name, c.first_name, c.last_name, c.manager_id, c.emails, c.notes, u.user_email as email FROM wp_aware_clients c JOIN wp_users u ON c.user_id = u.ID WHERE c.id = $id";
		$user = $wpdb->get_row($query);
		$client = Client::cast((array)$user);
		return $client;
	}

	public static function findByUser($user_id)
	{
		global $wpdb;
		$query = "SELECT c.id, c.user_id, u.ID, u.display_name, c.first_name, c.last_name, c.notes, u.user_email as email FROM wp_aware_clients c JOIN wp_users u ON c.user_id = u.ID WHERE c.user_id = $user_id";
		$user = $wpdb->get_row($query);
		$client = Client::cast((array)$user);
		return $client;
	}

	public static function checkboxes($key = 'id', $checked = 0)
	{
          $clients = static::all();
          foreach( $clients as $client ) :
          ?><input type="checkbox" name="clients[]" id="client-<?php echo $client->id; ?>" value="<?php echo $client->id; ?>" <?php if( $client->$key == $checked ) echo "checked=\"checked\""; ?>><label for="client-<?php echo $client->id; ?>"><?php echo $client->fullName(); ?></label><br><?php
          endforeach; 
	}

	public static function options($selected = null, $name = 'client', $id_type = 'user')
	{
    	?><select name="<?php echo $name; ?>">
      		<option value="0">No client</option><?php
			$clients = static::all();
      		foreach( $clients as $client ) : 
				if( $id_type == 'user' ) 
					$id = $client->user_id;
				else
					$id = $client->id;
      			?><option value="<?php echo $id; ?>" <?php if( $id == $selected ) : ?>selected="selected"<?php endif; ?>><?php echo $client->fullName(); ?></option><?php
      	endforeach; 
    	?></select><?php
	}

	public function projects()
	{
		global $wpdb;
		$query = "SELECT * FROM wp_aware_projects WHERE client_id = $this->id";
		$_ = $wpdb->get_results($query);
		return Project::convert($_);
		//$projects = array();
		//foreach( $_projects as $project ) $projects[] = Project::cast((array)$project);
		//return $projects;
	}

	public static function ID($user_id)
	{
		global $wpdb;
		$query = "SELECT id FROM wp_aware_clients WHERE user_id = $user_id";
		return $wpdb->get_var($query);
	}

	public static function userID($client_id)
	{
		if( $client_id == 0 ) return Settings::adminID();
		global $wpdb;
		$query = "SELECT user_id FROM wp_aware_clients WHERE id = $client_id";
		return $wpdb->get_var($query);
	}

	public function events()
	{
		global $wpdb;
		$query = "SELECT e.* FROM wp_aware_clients c JOIN wp_aware_projects p JOIN wp_aware_events e ON c.id = p.client_id AND p.id = e.project_id WHERE client_id = $this->id";
		$_events = $wpdb->get_results($query);
		$events = array();
		foreach( $_events as $event ) $events[] = Event::cast((array)$event);
		return $events;
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

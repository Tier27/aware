<?php
namespace aware;

class Project {

	public function __construct($data = array())
	{
		if( isset($data['client_id']) ) 
			$this->client_id = $data['client_id'];
		else
			$this->client_id = $data['client'];
		$this->name = $data['name'];

		$this->configureDates($data);

		$this->duration = $data['duration'];
		$this->details = $data['details'];
		$this->notes = $data['notes'];
		if( isset($data['id']) ) $this->id = $data['id'];
		return $this;
	}

	public function insert()
	{
		global $wpdb;
		$wpdb->insert('wp_aware_projects', (array)$this);
		$this->id = $wpdb->insert_id;
	}

	public static function create($data = array())
	{
		if( empty($data) ) $data = $_POST;
		$project = new Project($data);
		$project->insert();
		return $project;
	}

	public static function all()
	{
		global $wpdb;
		$query = "SELECT * FROM wp_aware_projects";
		$_projects = $wpdb->get_results($query);
		$projects = array();
		foreach( $_projects as $project ) $projects[] = Project::cast((array)$project);
		return $projects;
	}

	public static function cast($atts)
	{
		if( !is_array( $atts ) ) $atts = (array)$atts;
		$project = new Project($atts);
		//foreach( $atts as $key => $value ) $project->$key = $value;
		return $project;
	}

	public static function update($id, $key, $value)
	{
		global $wpdb;
		$query = "UPDATE wp_aware_projects SET $key='$value' WHERE id=$id";
		$wpdb->query($query);
	}

	public static function options($selected = null)
	{
    	?><select name="project">
      		<option value="0">No project</option><?php
			$projects = static::all();
      		foreach( $projects as $project ) : 
      			?><option value="<?php echo $project->id; ?>" <?php if( $project->id == $selected ) : ?>selected="selected"<?php endif; ?>><?php echo $project->name; ?></option><?php
      	endforeach; 
    	?></select><?php
	}

	public static function find($id)
	{
		global $wpdb;
		$query = " SELECT * FROM wp_aware_projects a";
		$query .= " WHERE a.id = $id ";
		$row = $wpdb->get_row($query, "ARRAY_A");
		$_ = static::cast($row);
		return $_;
	}

	public function events()
	{
		global $wpdb;
		$query = " SELECT * FROM wp_aware_events a ";
		$query .= " WHERE a.project_id = $this->id ";
		$_ = $wpdb->get_results($query, "ARRAY_A");
		$_ = Event::convert($_);
		return $_;
	}

	public function updates()
	{
		global $wpdb;
		$query = " SELECT * FROM wp_aware_updates a ";
		$query .= " WHERE a.project_id = $this->id ";
		$_ = $wpdb->get_results($query, "ARRAY_A");
		$_ = Update::convert($_);
		return $_;
	}

	public function getPermalink()
	{
		return site_url('/client/project/' . $this->id);
	}

	public static function convert($__)
	{
		$___ = array();
		foreach( $__ as $_ ){
			$___[] = static::cast($_);
		}
		return $___;
	}

	public function _update($key, $value)
	{
		global $wpdb;
		$query = "UPDATE wp_aware_projects SET $key='$value' WHERE id=$this->id";
		$wpdb->query($query);
		return $this;
	}

	public function configureDates($data)
	{
		if( isset( $data['start_date'] ) ) :
			$this->start_date = $data['start_date'];
		else :
			$additional_start_seconds = $data['start-hour'] * 3600 + $data['start-minute'] * 60;
			if( $data['start-suffix'] == 'PM' ) $additional_start_seconds += 12 * 3600;
			$this->start_date = date('Y-m-d H:i:s', strtotime($data['start-date']) + $additional_start_seconds);
		endif;
		if( isset( $data['end_date'] ) ) :
			$this->end_date = $data['end_date'];
		else :
			$additional_end_seconds = $data['end-hour'] * 3600 + $data['end-minute'] * 60;
			if( $data['end-suffix'] == 'PM' ) $additional_end_seconds += 12 * 3600;
			$this->end_date = date('Y-m-d H:i:s', strtotime($data['end-date']) + $additional_end_seconds);
		endif;
	}

}

?>

<?php
namespace aware;

class Event {

	public function __construct($data = array())
	{
		if( isset($data['project_id']) ) 
			$this->project_id = $data['project_id'];
		else
			$this->project_id = $data['project'];
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
		$wpdb->insert('wp_aware_events', (array)$this);
		$this->id = $wpdb->insert_id;
	}

	public static function create($data = array())
	{
		if( empty($data) ) $data = $_POST;
		$event = new Event($data);
		$event->insert();
		return $event;
	}

	public static function all()
	{
		global $wpdb;
		$query = "SELECT * FROM wp_aware_events";
		$_events = $wpdb->get_results($query);
		$events = array();
		foreach( $_events as $event ) $events[] = Event::cast((array)$event);
		return $events;
	}

	public static function cast($atts)
	{
		if( !is_array( $atts ) ) $atts = (array)$atts;
		$event = new Event($atts);
		//foreach( $atts as $key => $value ) $event->$key = $value;
		return $event;
	}

	public static function update($id, $key, $value)
	{
		global $wpdb;
		$query = "UPDATE wp_aware_events SET $key='$value' WHERE id=$id";
		$wpdb->query($query);
	}

	public function starts()
	{
		return date('m/d/Y, g:iA', strtotime($this->start_date));
	}

	public function startDate()
	{
		return date('m/d/Y', strtotime($this->start_date));
	}

	public function linkedStartDate()
	{
		return '<a href="' . site_url('client/calendar/date/' . strtotime($this->start_date)) . '">' . $this->startDate() . '</a>';
	}

	public function startTime()
	{
		return date('g:iA', strtotime($this->endstart_date));
	}
	
	public function ends()
	{
		return date('m/d/Y, g:iA', strtotime($this->end_date));
	}

	public function endDate()
	{
		return date('m/d/Y', strtotime($this->end_date));
	}

	public function linkedEndDate()
	{
		return '<a href="' . site_url('client/calendar/date/' . strtotime($this->end_date)) . '">' . $this->endDate() . '</a>';
	}

	public function endTime()
	{
		return date('g:iA', strtotime($this->end_date));
	}
	
	public static function find($id)
	{
		global $wpdb;
		$query = " SELECT * FROM wp_aware_events a";
		$query .= " WHERE a.id = $id ";
		$row = $wpdb->get_row($query, "ARRAY_A");
		$_ = static::cast($row);
		return $_;
	}

	public function project()
	{
		return Project::find($this->project_id);
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
		$query = "UPDATE wp_aware_events SET $key='$value' WHERE id=$this->id";
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

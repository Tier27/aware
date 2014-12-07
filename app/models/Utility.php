<?php
namespace aware;

class Utility {

	public $start;
	
	public function __construct($action = 'Executed')
	{
		$this->start = self::now();
		$this->action = $action;
	}

	public static function now()
	{
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		return $time;
	}

	public function duration()
	{
		$duration = round((self::now() - $this->start), 8);
		return $duration;
	}

	public function report()
	{
		$duration = $this->duration();
		echo $this->action . ' in '.$duration.' seconds.';
	}

}

?>

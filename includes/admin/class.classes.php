<?php

/**
 * Class to keep track of classes
 *
 * @package     AWARE
 * @subpackage  Classes
 * @copyright   Copyright (c) 2014, Joshua B. Kornreich
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.0.1
 */

class AWAREClasses {

	private $classes;

	public function __construct() {

		$this->classes = array();

	}

	/*
	 *:Adder for class array
	 */

	public function add($class) {

		if( !in_array($class, $this->classes) ) $this->classes[] = $class;
		return $this;

	}

	/*
	 * Getter for class array
	 */

	public function getList() {

		return $this->classes;

	}

}


?>

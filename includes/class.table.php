<?php
namespace aware;

class Table {

	public $sql;

	public $fields;

	public $key;

	public function __construct($name)
	{
		$this->sql = "CREATE TABLE $name ( ";
	}
	
	public function increments($id)
	{
		$this->fields[] = " $id mediumint(9) NOT NULL AUTO_INCREMENT ";
		$this->key = " UNIQUE KEY $id ($id) ";
	}

	public function integer($name)
	{
		$this->fields[] = " `$name` mediumint(9) ";
	}

	public function string($name)
	{
		$this->fields[] = " `$name` varchar(255) ";
	}

	public function mediumText($name)
	{
		$this->fields[] = " `$name` mediumtext ";
	}

	public function date($name)
	{
		$this->fields[] = " `$name` date ";
	}

	public function time($name)
	{
		$this->fields[] = " `$name` timestamp default current_timestamp ";
	}

	public function _time($name)
	{
		$this->fields[] = " `$name` timestamp ";
	}

	public function boolean($name, $default = 0)
	{
		$this->fields[] = " `$name` boolean DEFAULT $default ";
	}

	public function prepare()
	{
		$this->fields[] = $this->key;
		$this->sql .= implode(',', $this->fields);
		echo $this->sql;
		echo "<<BR><BR>";
		$this->close();
	}

	public function close()
	{
		$this->sql .= ' ) ';
	}

}
?>

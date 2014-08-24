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

	public function boolean($name)
	{
		$this->fields[] = " `$name` boolean ";
	}

	public function prepare()
	{
		$this->fields[] = $this->key;
		$this->sql .= implode(',', $this->fields);
		$this->close();
	}

	public function close()
	{
		$this->sql .= ' ) ';
	}

}
?>

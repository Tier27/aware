<?php
namespace aware;

Class Route {
	
	public static function post($url, $function)
	{
		add_rewrite_rule($url, admin_url('admin-ajax.php'));	
	}

}

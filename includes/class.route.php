<?php
namespace aware;

Class Route {
	
	public static function init()
	{
		add_action( 'init', array( __CLASS__, 'aware_rewrites_init' ) );
		add_filter( 'query_vars', array( __CLASS__, 'aware_query_vars' ) );
	}

	public static function post($url, $function)
	{
		add_rewrite_rule($url, admin_url('admin-ajax.php'));	
	}

	public static function get($pattern, $type, $query_var)
	{
		global $rewrite;
		$rewrite->query_vars[] = $query_var;
		$rewrite->rules[] = array('pattern' => $pattern, 'type' => $type, 'query_var' => $query_var);
		return true;

		add_action( 'init', array( __CLASS__, 
		function()
		{
			add_rewrite_rule(
				'client/([0-9]+)/?$',
				"index.php?pagename=client&aware_type=$type&$query_var=" . '$matches[1]',
				$after
			);
		} ) );
		add_action( 'query_vars', array( __CLASS__, function( $query_vars )
		{
			$query_vars[] = 'client_id';
			return $query_vars;
		} ) );
	}

	public static function rewrite($url, $type, $query_var)
	{
	
	}

	public static function inbox()
	{
		echo bloginfo('wpurl') . '/client/inbox';
	}

	public static function to($to)
	{
		header('Location: ' . $to);
	}

}

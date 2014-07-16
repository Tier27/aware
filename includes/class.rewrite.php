<?php

class AWARERewrite {


	public function __construct() {
		add_action( 'init', array( __CLASS__, 'aware_rewrites_init' ) );
		add_filter( 'query_vars', array( __CLASS__, 'aware_query_vars' ) );
	}
	public function aware_rewrites_init(){
		add_rewrite_rule(
			'client/([0-9]+)/?$',
			'index.php?pagename=client&client_id=$matches[1]',
			'top' 
		);
		add_rewrite_rule(
			'^client/([^/]*)/?$',
			'index.php?pagename=client&aware_type=$matches[1]',
			'top'
		);
		add_rewrite_rule(
			'^client/([^/]*)/([0-9]+)/?$',
			'index.php?pagename=client&aware_type=$matches[1]&client_id=$matches[2]',
			'top'
		);
		//flush_rewrite_rules(false);
	}


	public function aware_query_vars( $query_vars ){
		$query_vars[] = 'client_id';
		$query_vars[] = 'aware_type';
		return $query_vars;
	}

}

?>

<?php
namespace aware;

class rewrite {


	public function __construct() {
		add_action( 'init', array( __CLASS__, 'aware_rewrites_init' ) );
		add_filter( 'query_vars', array( __CLASS__, 'aware_query_vars' ) );
	}
	public function aware_rewrites_init( $after = 'top', $flush = true, $return_early = false ){
		if( $return_early ) :
			flush_rewrite_rules(false);
			return;
		endif;
		add_rewrite_rule(
			'client/([0-9]+)/?$',
			'index.php?pagename=client&client_id=$matches[1]',
			$after
		);
		/*
		add_rewrite_rule(
			'^client/([^/]*)/?$',
			'index.php?pagename=client&aware_type=$matches[1]',
			$after
		);
		*/
		add_rewrite_rule(
			'^client/updates/?$',
			'index.php?pagename=client&aware_type=updates',
			$after
		);
		add_rewrite_rule(
			'^client/events/?$',
			'index.php?pagename=client&aware_type=events',
			$after
		);
		add_rewrite_rule(
			'^client/projects/?$',
			'index.php?pagename=client&aware_type=projects',
			$after
		);
		add_rewrite_rule(
			'^client/conversations/?$',
			'index.php?pagename=client&aware_type=conversations',
			$after
		);
		add_rewrite_rule(
			'^client/([^/]*)/([0-9]+)/?$',
			'index.php?pagename=client&aware_type=$matches[1]&client_id=$matches[2]',
			$after
		);
		if( $flush ) flush_rewrite_rules(false);
	}


	public function aware_query_vars( $query_vars ){
		$query_vars[] = 'client_id';
		$query_vars[] = 'aware_type';
		return $query_vars;
	}

}

?>

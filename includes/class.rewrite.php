<?php
namespace aware;

class rewrite {

	public $query_vars;

	public $rules = array();

	public function __construct() {
		add_action( 'init', array( __CLASS__, 'execute' ), 20 );
		add_action( 'init', array( __CLASS__, 'aware_rewrites_init' ), 30 );
		add_filter( 'query_vars', array( __CLASS__, 'aware_query_vars' ) );
	}
	public function aware_rewrites_init( $after = 'top', $flush = true, $return_early = false ){
		return;
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
			'^client/threads/?$',
			'index.php?pagename=client&aware_type=threads',
			$after
		);
		add_rewrite_rule(
			'^client/inbox/?$',
			'index.php?pagename=client&aware_type=inbox',
			$after
		);
		add_rewrite_rule(
			'^client/outbox/?$',
			'index.php?pagename=client&aware_type=outbox',
			$after
		);
		add_rewrite_rule(
			'^client/calendar/?$',
			'index.php?pagename=client&aware_type=calendar',
			$after
		);
		add_rewrite_rule(
			'^client/calendar/date/([0-9]+)/?$',
			'index.php?pagename=client&aware_type=calendar&date_time=$matches[1]',
			$after
		);
		//add_rewrite_rule(
		//	'^client/thread/([0-9]+)/?$',
		//	'index.php?pagename=client&aware_type=thread&thread_id=$matches[1]',
		//	$after
		//);
		//add_rewrite_rule(
		//	'^client/event/([0-9]+)/?$',
		//	'index.php?pagename=client&aware_type=event&event_id=$matches[1]',
		//	$after
		//);
		add_rewrite_rule(
			'^client/([^/]*)/([0-9]+)/?$',
			'index.php?pagename=client&aware_type=$matches[1]&aware_id=$matches[2]',
			$after
		);
		//add_rewrite_rule(
		//	'^client/([^/]*)/([0-9]+)/?$',
		//	'index.php?pagename=client&aware_type=$matches[1]&client_id=$matches[2]',
		//	$after
		//);
		if( $flush ) flush_rewrite_rules(false);
	}

	public function execute()
	{
		global $rewrite;
		foreach( $rewrite->rules as $rule )
		{
			$route = 'index.php?pagename=client&aware_type=' . $rule['type'] . '&' . $rule['query_var'] . '=$matches[1]';
			add_rewrite_rule(
				$rule['pattern'],
				$route,
				'false'
			);
		}
		flush_rewrite_rules(false);
	}

	public function aware_query_vars( $query_vars ){
		global $rewrite;
		foreach( $rewrite->query_vars as $var ) $query_vars[] = $var;
		$query_vars[] = 'thread_id';
		$query_vars[] = 'aware_id';
		$query_vars[] = 'aware_type';
		$query_vars[] = 'date_time';
		return $query_vars;
	}

}

?>

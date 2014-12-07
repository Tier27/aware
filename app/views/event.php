<?php
	global $templates;
	global $client;
	$event_id = get_query_var('event_id');
    $templates->dashboard_event( $event_id ); 
?>

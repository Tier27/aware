<?php 
	namespace aware;
	$aware_type = get_query_var('aware_type');
?>
<?php
	$client_id = get_query_var('client_id');
	$client = get_user_by( 'id', $client_id );
	if( !$client ) :
		//$client = wp_get_current_user();
		$user_id = get_current_user_id();
		$client = Client::findByUser($user_id);
	endif;
	global $templates;
	global $retrieve;
?>

  <div class="small-6 columns section">
  <?php $templates->dashboard_accordion_events( $client ); ?>
  <?php $templates->dashboard_accordion_projects( $client ); ?>
  <?php //$templates->dashboard_communications( $client ); ?>
  </div>

  <div class="small-6 columns section">
  <?php $templates->dashboard_accordion_updates( $client, 3 ); ?>
  <?php $templates->dashboard_conversations( $client, 3 ); ?>
  </div> 



 

<?php //Template name: Client Dashboard ?>
<?php namespace aware; ?>
<?php 
	if( !is_user_logged_in() ) wp_redirect( wp_login_url('/client') );
	$aware_type = get_query_var('aware_type');
	if( !$aware_type || is_int( $aware_type ) ) $aware_type = 'dashboard';
?>
<?php
	if( !$client_id ) $client_id = get_current_user_id();
	global $client;
	$client = Client::findByUser($client_id);
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Client Dashboard</title>
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/foundation.css">
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/custom-style.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="<?php echo AWARE_URL; ?>assets/js/vendor/modernizr.js"></script>
    <?php wp_head(); ?>
</head>
<body>
 
<nav class="top-bar" data-topbar>
  <ul class="title-area">     
    <li class="name">
      <h1><a href="#"><?php echo $client->user_login; ?></a></h1>
    </li>
    <li class="toggle-topbar menu-icon"><a href="#"><span>menu</span></a></li>
  </ul>

<?php View::template('client-nav'); ?>

</nav>
 
<br>

<div class="row">

<?php View::template($aware_type); ?>

</div> <!--/.row-->
 
<footer class="row hide">
  <div class="large-12 columns"><hr>
    <div class="row">
      <div class="large-6 columns">
        <p>Client Dashboard Footer</p>
      </div>
      <div class="large-6 small-12 columns">
        <ul class="inline-list right">
          <li><a href="#">Link 1</a></li>
          <li><a href="#">Link 2</a></li>
          <li><a href="#">Link 3</a></li>
        </ul>
      </div>
    </div> <!--/.row-->
  </div>
</footer> 
 
<script src="<?php echo AWARE_URL; ?>assets/js/foundation.min.js"></script>
<script>
	jQuery(document).foundation();
</script>

</body>
</html>

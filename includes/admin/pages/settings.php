<?php namespace aware; ?>
<head>
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/foundation.css">
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/custom-style.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="<?php echo AWARE_URL; ?>assets/js/vendor/modernizr.js"></script>
</head>

<form id="aware-create-thread">
<div class="row collapse">
  <div class="small-12 medium-10 columns">
    <h3>Settings</h3>

    <h6>Aware Administrator</h6>
	<?php $aware_administrator = Settings::adminID(); ?>
	<select name="aware-administrator">
		<?php foreach( get_users() as $user ) : ?>
		<option value="<?php echo $user->ID; ?>" <?php if( $user->ID == $aware_administrator ) echo "selected=\"selected\""; ?>><?php echo $user->data->display_name; ?></option>
		<?php endforeach; ?>
	</select>

    <h6>Administrative Email</h6>
    <input type="text" name="aware-administrative-email" value="<?php echo get_option('aware_administrative_email');?>">
<!--
    <h6>Client Administrative Email</h6>
    <input type="text" name="aware-client-administrative-email" value="<?php echo get_option('aware_client_administrative_email');?>">
-->
    <h6>Administrative name</h6>
    <input type="text" name="aware-administrative-name" value="<?php echo get_option('aware_administrative_name');?>">
    <h6>Hours of operation</h6>
    <div class="row">
	    <div class="large-2 columns">
	      <label>&nbsp;
		<select class="foundation" name="start-hour">
		    <?php for( $i=1; $i<=12; $i++ ) : ?><option value=<?php echo $i; ?> <?php if( $i == $start_hour ) echo "selected=\"selected\""; ?>><?php echo $i; ?></option><?php endfor; ?>
		</select>
	      </label>
	    </div>
	    <div class="large-2 columns">
	      <label>&nbsp;
		<select class="foundation" name="start-minute">
		    <?php for( $i=0; $i<60; $i++ ) : ?><option value=<?php echo $i; ?> <?php if( $i == $start_minute ) echo "selected=\"selected\""; ?>><?php if( $i < 10 ) echo '0'; echo $i; ?></option><?php endfor; ?>
		</select>
	      </label>
	    </div>
	    <div class="large-2 columns">
	      <label>&nbsp;
		<select class="foundation" name="start-suffix">
			<option value="AM" <?php if( 'AM' == $start_time_suffix ) echo "selected=\"selected\""; ?>>AM</option>
			<option value="PM" <?php if( 'PM' == $start_time_suffix ) echo "selected=\"selected\""; ?>>PM</option>
		</select>
	      </label>
	    </div>
	    <div class="large-2 columns">
	      <label>&nbsp;
		<select class="foundation" name="start-hour">
		    <?php for( $i=1; $i<=12; $i++ ) : ?><option value=<?php echo $i; ?> <?php if( $i == $start_hour ) echo "selected=\"selected\""; ?>><?php echo $i; ?></option><?php endfor; ?>
		</select>
	      </label>
	    </div>
	    <div class="large-2 columns">
	      <label>&nbsp;
		<select class="foundation" name="start-minute">
		    <?php for( $i=0; $i<60; $i++ ) : ?><option value=<?php echo $i; ?> <?php if( $i == $start_minute ) echo "selected=\"selected\""; ?>><?php if( $i < 10 ) echo '0'; echo $i; ?></option><?php endfor; ?>
		</select>
	      </label>
	    </div>
	    <div class="large-2 columns">
	      <label>&nbsp;
		<select class="foundation" name="start-suffix">
			<option value="AM" <?php if( 'AM' == $start_time_suffix ) echo "selected=\"selected\""; ?>>AM</option>
			<option value="PM" <?php if( 'PM' == $start_time_suffix ) echo "selected=\"selected\""; ?>>PM</option>
		</select>
	      </label>
	    </div>
    </div>
    <h6>Development Mode (this allows administrators to act as clients)</h6>
    <div class="large-12 columns">
      <input type="radio" name="aware-development-mode" value="1" <?php if( get_option('aware_development_mode') == 1 ) echo "checked=\"checked\"";?>><label>On</label>
      <input type="radio" name="aware-development-mode" value="0"<?php if( get_option('aware_development_mode') == 0 ) echo "checked=\"checked\"";?> ><label>Off</label>
    </div>
  </div>
  <input name="aware-update-settings" class="button radius tiny" value="Update">
  <input name="aware-seed-clients" class="button radius tiny forest-green" value="Seed Clients">
  <input name="action" value="aware_update_settings" style="display: none;">
  <div class="row">
    <div class="large-12 columns response hidden">
    </div>
  </div>
</div> <!--/.row-->
</form>

<?php
$maintenance = new maintenance( array( 'active' => false ) );
$maintenance->purge_conversations();
$maintenance->purge_updates();
$maintenance->purge_clients();
?>

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

    <h6>AWARE Administrative Email</h6>
    <input type="text" name="aware-administrative-email" value="<?php echo get_option('aware_administrative_email');?>">
    <h6>Client Administrative Email</h6>
    <input type="text" name="aware-client-administrative-email" value="<?php echo get_option('aware_client_administrative_email');?>">
    <h6>Development Mode (this allows administrators to act as clients)</h6>
    <div class="large-12 columns">
      <input type="radio" name="aware-development-mode" value="1" <?php if( get_option('aware_development_mode') == 1 ) echo "checked=\"checked\"";?>><label>On</label>
      <input type="radio" name="aware-development-mode" value="0"<?php if( get_option('aware_development_mode') == 0 ) echo "checked=\"checked\"";?> ><label>Off</label>
    </div>
  </div>
</div> <!--/.row-->
<input name="aware-update-settings" class="button radius tiny" value="Update">
<input name="action" value="admin_update_settings" style="display: none;">
  <div class="row">
    <div class="large-12 columns response hidden">
    </div>
  </div>
</form>


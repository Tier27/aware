<?php namespace aware; ?>
<head>
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/foundation.css">
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/ui.css">
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/custom-style.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="<?php echo AWARE_URL; ?>assets/js/vendor/modernizr.js"></script>
</head>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
     
<?php
	ini_set('display_errors', true);
	View::make('admin-users', array('clients' => Client::all(), 'projects' => Retrieve::projects()));
?>
 
<script>
jQuery(document).foundation({
  accordion: {
    active_class: 'active',
    multi_expand: false,
    toggleable: true
  }
});
</script>

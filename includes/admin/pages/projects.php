    
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
    <script src="js/vendor/modernizr.js"></script>
     
   <br>

  <?php //aware_project_part(); ?> 
  <?php aware_accordion_part_projects(); ?> 
 
<script>
jQuery(document).foundation({
  accordion: {
    // specify the class used for active (or open) accordion panels
    active_class: 'active',
    // allow multiple accordion panels to be active at the same time
    multi_expand: false,
    // allow accordion panels to be closed by clicking on their headers
    // setting to false only closes accordion panels when another is opened
    toggleable: true
  }
});
</script>

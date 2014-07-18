<?php namespace aware; ?>
<head>
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/foundation.css">
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/custom-style.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="<?php echo AWARE_URL; ?>assets/js/vendor/modernizr.js"></script>
</head>
 
<div id="aware">

<ul class="tabs" data-tab>
  <li class="tab-title active"><a href="#panel2-1">Conversations</a></li>
  <li class="tab-title"><a href="#panel2-2">Updates</a></li>
</ul>

<div class="tabs-content">
  <div class="content active" id="panel2-1">
	<?php templates::thread_box(); ?>
  </div>
  <div class="content" id="panel2-2">
	<?php templates::update_box(); ?>
  </div>
</div>
<div id="submission-response"></div>

</div> <!--/#aware-->
 
 
<script src="<?php echo AWARE_URL; ?>assets/js/foundation.min.js"></script>
<script>
	jQuery(document).foundation();
</script>
              
<?php 
?>

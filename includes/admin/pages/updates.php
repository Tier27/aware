<head>
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/foundation.css">
    <link type="text/css" rel="stylesheet" href="<?php echo AWARE_URL; ?>assets/css/custom-style.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="<?php echo AWARE_URL; ?>assets/js/vendor/modernizr.js"></script>
</head>
 
<div id="aware">
<nav class="top-bar" data-topbar>
  <ul class="title-area">     
    <li class="name">
      <h1><a href="#">Client Update</a></h1>
    </li>
    <li class="toggle-topbar menu-icon"><a href="#"><span>menu</span></a></li>
  </ul>

  <?php get_template_part('client-nav'); ?>

</nav>
 
<br>


<div class="row">
  <dl class="sub-nav aware-thread-toolbar">
      <dt>Tools:</dt>
      <dd><a><i class="fa fa-tasks"></i> Forum</a></dd>
      <dd><a><i class="fa fa-refresh"></i> Refresh</a></dd>
      <dd><a><i class="fa fa-pencil"></i> Create</a></dd>
      <dd><a><i class="fa fa-search"></i> Search</a></dd>
      <dd><a><i class="fa fa-cog"></i> Settings</a></dd>
    </dl>
</div> <!--/.row-->

<form id="aware-create-thread">
<div class="row collapse">
  <div class="small-12 medium-10 columns">
    <h3>Create Update</h3>

    <h6>Subject</h6>
    <input type="text" name="title">
    <h6>Client</h6>
    <?php $clients = admin_get_clients() ; ?>
    <select name="client">
    <?php foreach( $clients as $client ) : ?>
      <option value="<?php echo $client->ID; ?>"><?php echo $client->display_name; ?></option>
    <?php endforeach; ?>
    </select>
  </div>
</div> <!--/.row-->

<div class="row">
  <!--Communication Section--> 
  <div class="small-12 medium-10 columns panel section">
      <div class="aware-widget-section">
        

        <div class="button-bar aware-thread-comment-tools">
          <ul class="button-group">
            <li><a href="#" class="small button"><i class="fa fa-bold"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-italic"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-link"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-strikethrough"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-underline"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-picture-o"></i></a></li>
            <li><a href="#" class="small button">Color</a></li>
            <li><a href="#" class="small button">Large</a></li>
            <li><a href="#" class="small button">Small</a></li>
          </ul>
        </div>

          <div class="panel aware-widget-communication-discussion">
            <textarea rows="7" name="content"></textarea>
          </div> <!--/.panel-->

        <input name="aware-create-communication" class="button radius tiny" value="Post update">
        <input name="aware-cancel-communication" class="button radius tiny alert" value="Cancel">
	<input name="action" value="admin_create_update" style="display: none;">

      </div> <!--/.aware-widget-section-->
  </div> <!--/.section-->


</div> <!--/.row-->
</form>
<div id="submission-response"></div>

</div> <!--/#aware-->
 
 
<script src="<?php echo AWARE_URL; ?>assets/js/foundation.min.js"></script>
<script>
	jQuery(document).foundation();
</script>
              
<?php 
?>

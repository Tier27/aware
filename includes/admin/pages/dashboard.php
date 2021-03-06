<?php namespace aware; ?>    
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<ul class="tabs" data-tab>
  <li class="tab-title active"><a href="#panel2-1">Clients</a></li>
  <li class="tab-title"><a href="#panel2-2">Projects</a></li>
  <li class="tab-title"><a href="#panel2-3">Events</a></li>
  <li class="tab-title"><a href="#panel2-4">Communication</a></li>
</ul>
<div class="tabs-content">
  <div class="content active" id="panel2-1">
	<?php View::make('admin-users', array('clients' => Client::all(), 'projects' => Retrieve::projects())); ?>
  </div>
  <div class="content" id="panel2-2">
  	<?php parts::accordion_projects(); ?> 
  </div>
  <div class="content" id="panel2-3">
 	<?php parts::accordion_events(); ?> 
  </div>
  <div class="content" id="panel2-4">
            <?php parts::communication(); ?>
  </div>
</div>
<div class="row">
  <div class="large-12 columns">
    <div class="row">
      <div class="large-12 hide-for-small"></div>
    </div>
    <div class="row">
      <div class="large-12 columns show-for-small">
        <img src="http://placehold.it/1200x700&text=Client Dashboard">
      </div>
    </div><br>

    <div class="row">
      <div class="large-12 columns">
        <div class="row">
             
             
 
             
 
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo AWARE_URL; ?>assets/js/foundation.min.js"></script>
<script>
	jQuery(document).foundation();
</script>
 

<?php namespace aware; ?>

<div class="large-12 medium-12 small-12 columns panel section">
  <h3><i class="fa fa-space-shuttle"></i> Clients</h3>
  <hr>
  <dl class="accordion" data-accordion>
  <?php foreach( $clients as $client ) : 
	$client = Client::cast($client);
  ?>
    <dd class="accordion-navigation">
      <a href="#client-<?php echo $client->ID; ?>"><?php echo get_avatar( $client->ID ); ?> <?php echo $client->fullName(); ?></a>
      <div id="client-<?php echo $client->ID; ?>" class="content">
	<?php View::make('admin-user', array('client' => $client, 'action' => 'update', 'projects' => $projects)); ?>
      </div>
    </dd>
  <?php endforeach; ?>
    <dd class="accordion-navigation">
      <a href="#client-new"><i class="fa fa-plus"></i> Add new</a>
      <div id="client-new" class="content">
        <?php View::make('admin-user-new', array('action' => 'add', 'projects' => $projects)); ?>
      </div>
    </dd>
  </dl>
</div>

<?php namespace aware; ?>

<div class="large-12 medium-12 small-12 columns panel section">
  <h3><i class="fa fa-space-shuttle"></i> Manager</h3>
  <hr>
  <dl class="accordion" data-accordion>
  <?php foreach( $managers as $manager ) : 
	$manager = Manager::cast($manager);
  ?>
    <dd class="accordion-navigation">
      <a href="#manager-<?php echo $manager->ID; ?>"><?php echo get_avatar( $manager->ID ); ?> <?php echo $manager->fullName(); ?></a>
      <div id="manager-<?php echo $manager->ID; ?>" class="content">
	<?php View::make('admin-manager', array('manager' => $manager, 'action' => 'update', 'projects' => $projects)); ?>
      </div>
    </dd>
  <?php endforeach; ?>
    <dd class="accordion-navigation">
      <a href="#manager-new"><i class="fa fa-plus"></i> Add new</a>
      <div id="manager-new" class="content">
        <?php View::make('admin-manager-new', array('action' => 'add', 'projects' => $projects)); ?>
      </div>
    </dd>
  </dl>
</div>

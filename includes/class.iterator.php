<?php
namespace aware;

class Iterator {

	public static function clients() { 
		$clients = Retrieve::clients();
	?>
		<div class="large-12 medium-12 small-12 columns panel section">
		  <h3><i class="fa fa-space-shuttle"></i> Clients</h3><hr>
		  <dl class="accordion" data-accordion>
	<?php
		foreach( $clients as $client ) :
	?>
		  <dd class="accordion-navigation">
		    <a href="#client-<?php echo $client->ID; ?>"><?php echo get_avatar( $client->ID ); ?> <?php echo $client->display_name; ?></a>
		    <div id="client-<?php echo $client->ID; ?>" class="content">
			<?php View::make('admin-user', array('client' => $client, 'action' => 'update')); ?>
		    </div>
		  </dd>
	<?php
		endforeach;
	?>
		  <dd class="accordion-navigation">
		    <a href="#client-new"><i class="fa fa-plus"></i> Add new</a>
		    <div id="client-new" class="content">
		      <?php parts::accordion_form( null, 'add' ); ?>
		    </div>
		  </dd>
		  </dl>
		</div>
	<?php
	}

}

?>

<?php
namespace aware;

class parts {

	public function communication() { global $retrieve; ?>
	      <?php $threads = $retrieve->threads(); ?>
	      <div class="large-12 small-12 columns panel section">
		<h3><i class="fa fa-comments"></i> Communications</h3><hr>
		<?php foreach( $threads as $thread ) : ?>
		<div class="panel communication-widget">
		  <div class="communication-widget-avatar">
		  </div>
		  <div class="communicaiton-widget-description">
		      <p><?php echo $thread->post_title; ?></p>
		  </div>
		</div>
		 <?php endforeach; ?>
	      </div>
	<?php } 

	public function accordion_clients() { global $retrieve;
		$data = $retrieve->clients();
	?>
		<div class="large-12 medium-12 small-12 columns panel section">
		  <h3><i class="fa fa-space-shuttle"></i> Clients</h3><hr>
		  <dl class="accordion" data-accordion>
	<?php
		foreach( $data as $datum ) :
	?>
		  <dd class="accordion-navigation">
		    <a href="#client-<?php echo $datum->ID; ?>"><?php echo get_avatar( $datum->ID ); ?> <?php echo $datum->display_name; ?></a>
		    <div id="client-<?php echo $datum->ID; ?>" class="content">
		      <?php self::accordion_form( $datum ); ?>
		    </div>
		  </dd>
	<?php
		endforeach;
	?>
		  <dd class="accordion-navigation">
		    <a href="#client-new"><i class="fa fa-plus"></i> Add new</a>
		    <div id="client-new" class="content">
		      <?php self::accordion_form( null, 'add' ); ?>
		    </div>
		  </dd>
		  </dl>
		</div>
	<?php
	}

	public function accordion_projects() { global $retrieve;
		$projects = $retrieve->projects();
	?>

	      <div class="large-12 medium-12 small-12 columns panel section">
		<h3><i class="fa fa-space-shuttle"></i> Projects</h3><hr>
		<dl class="accordion" data-accordion>
	<?php
		foreach( $projects as $project ) :
	?>
	  <dd class="accordion-navigation">
	    <a href="#project-<?php echo $project->ID; ?>"><?php echo get_avatar( $project->ID ); ?> <?php echo $project->post_title; ?></a>
	    <div id="project-<?php echo $project->ID; ?>" class="content">
		<?php self::accordion_form_project( $project ); ?>
	    </div>
	  </dd>
	<?php
		endforeach;
	?>
	  <dd class="accordion-navigation">
	    <a href="#project-new"><i class="fa fa-plus"></i> Add new</a>
	    <div id="project-new" class="content">
	      <?php self::accordion_form_project( null, 'add' ); ?>
	    </div>
	  </dd>
		</dl>
	      </div>
	<?php
	}

	public function accordion_events() { global $retrieve;
		$events = $retrieve->events();
	?>

	      <div class="large-12 medium-12 small-12 columns panel section">
		<h3><i class="fa fa-space-shuttle"></i> Events</h3><hr>
		<dl class="accordion" data-accordion>
	<?php
		foreach( $events as $event ) :
	?>
	  <dd class="accordion-navigation">
	    <a href="#event-<?php echo $event->ID; ?>"><?php echo get_avatar( $event->ID ); ?> <?php echo $event->post_title; ?></a>
	    <div id="event-<?php echo $event->ID; ?>" class="content">
		<?php self::accordion_form_event( $event ); ?>
	    </div>
	  </dd>
	<?php
		endforeach;
	?>
	  <dd class="accordion-navigation">
	    <a href="#event-new"><i class="fa fa-plus"></i> Add new</a>
	    <div id="event-new" class="content">
	      <?php self::accordion_form_event( null, 'add' ); ?>
	    </div>
	  </dd>
		</dl>
	      </div>
	<?php
	}

	public function accordion_form( $obj = NULL, $action = 'update' ) { global $retrieve; ?>
	<?php if( $obj != NULL ) $meta = get_user_meta( $obj->ID ); ?>
	<form method="post" action="<?php echo site_url('client/' . $obj->ID); ?>">
	  <div class="row">
	    <div class="large-6 columns">
	      <label>First name
		<input type="text" name="first-name" value="<?php echo $meta["first_name"][0]; ?>"/>
	      </label>
	    </div>
	    <div class="large-6 columns">
	      <label>Last name
		<input type="text" name="last-name" value="<?php echo $meta["last_name"][0]; ?>"/>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-4 columns">
	      <label>Email
		<input type="text" name="email" value="<?php echo $obj->user_email; ?>" />
	      </label>
	    </div>
	    <div class="large-4 columns">
	      <label>Password
		<input type="password" name="password" />
	      </label>
	    </div>
	    <div class="large-4 columns">
	      <label>Client ID
		<input type="text" name="client-id" value="<?php echo $meta["client-id"][0]; ?>"/>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      <?php $managers = $retrieve->managers(); ?>
	      <label>Manager
		<select name="manager">
		  <option value="0">No manager</option>
		  <?php foreach( $managers as $manager ) : ?>
		  <option value="<?php echo $manager->ID; ?>" <?php if( $manager->ID == $meta["manager"][0] ) echo "selected=\"selected\""; ?>><?php echo $manager->display_name ?></option>
		  <?php endforeach; ?>
		</select>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      <label>Projects</label>
	      <?php $projects = $retrieve->projects(); ?>
	      <?php foreach( $projects as $project ) : ?>
	      <input type="checkbox" name="projects[]" value="<?php echo $project->ID; ?>" <?php if( in_array( $project->ID, $meta["projects"]) ) echo "checked=\"checked\""; ?>><label for="checkbox1"><?php echo $project->post_title; ?></label><br>
	      <?php endforeach; ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      <label>Notes (private)
		<textarea name="notes" placeholder="Notes about your client"><?php echo $meta["notes"][0]; ?></textarea>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      <input name="ID" value="<?php echo $obj->ID; ?>" class="hidden">
	      <input name="action" value="admin_<?php echo $action; ?>_client" class="hidden">
	      <input name="aware-<?php echo $action; ?>-client" class="button radius" value="<?php echo ucfirst($action); ?> client">
	      <?php if( $action == 'update' ) : ?><input name="aware-delete-client" class="red button radius" value="Delete client"><?php endif; ?>
	      <?php if( $action == 'update' ) : ?>
		<!--<input type="submit" name="aware-view-client-dashboard" class="orange button radius" value="View Client Dashboard">-->
		<a href="<?php echo site_url('client/' . $obj->ID); ?>" class="button radius">View Client Dashboard</a>
	      <?php endif; ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns response hidden">
	      The client has been updated.
	    </div>
	  </div>
	</form>
	<?php }

	public function accordion_form_project( $project = NULL, $action = 'update' ) { global $retrieve; ?>
	<form>
	  <div class="row">
	    <div class="large-6 columns">
	      <label>Name
		<input type="text" name="name" value="<?php echo $project->post_title; ?>"/>
	      </label>
	    </div>
	  </div>
	<!--
	  <div class="row">
	    <div class="large-12 columns">
	      <?php $clients = $retrieve->clients(); ?>
	      <label>Client
		<select name="client">
		  <option value="0">No client</option>
		  <?php $this_client = get_post_meta( $project->ID, 'client', true ); ?>
		  <?php foreach( $clients as $client ) : ?>
		  <option value="<?php echo $client->ID; ?>" <?php if( $client->ID == $this_client ) echo "selected=\"selected\""; ?>><?php echo $client->display_name ?></option>
		  <?php endforeach; ?>
		</select>
	      </label>
	    </div>
	  </div>
	-->
	  <div class="row">
	    <div class="large-12 columns">
	      <label>Clients</label>
	      <?php $these_clients = get_post_meta( $project->ID, 'clients', true ); ?>
	      <?php foreach( $clients as $client ) : ?>
	      <input type="checkbox" name="clients[]" value="<?php echo $client->ID; ?>" <?php if( in_array( $project->ID, get_user_meta( $client->ID, 'projects' ) ) ) echo "checked=\"checked\""; ?>><label for="checkbox1"><?php echo $client->display_name; ?></label><br>
	      <?php endforeach; ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      <label>Notes (private)
		<textarea name="notes" placeholder="Notes about your project"><?php echo get_post_meta( $project->ID, 'notes', true ); ?></textarea>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      <input name="ID" value="<?php echo $project->ID; ?>" class="hidden">
	      <input name="action" value="admin_<?php echo $action; ?>_project" class="hidden">
	      <input name="aware-<?php echo $action; ?>-project" class="button radius tiny" value="<?php echo ucfirst($action); ?> project">
	      <?php if( $action == 'update' ) : ?><input name="aware-delete-project" class="red button radius tiny" value="Delete project"><?php endif; ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns response hidden">
	      The project has been updated.
	    </div>
	  </div>
	</form>
	<?php }

	public function accordion_form_event( $event = NULL, $action = 'update' ) { global $retrieve; ?>
	<?php
	//To handle the mess that is time conversion
	$start_time = ( $start_time = get_post_meta( $event->ID, 'start_time', true ) ) ? $start_time : time(); 
	$start_date = date('m/d/Y', $start_time);
	$start_hour = date('g', $start_time);
	$start_minute = date('i', $start_time);
	$start_time_suffix = date('A', $start_time);
	$end_time = ( $end_time = get_post_meta( $event->ID, 'end_time', true ) ) ? $end_time : time(); 
	$end_date = date('m/d/Y', $end_time);
	$end_hour = date('g', $end_time);
	$end_minute = date('i', $end_time);
	$end_time_suffix = date('A', $end_time);
	?>
	<form>
	  <div class="row">
	    <div class="large-12 columns">
	      <label>Name
		<input type="text" name="name" value="<?php echo $event->post_title; ?>"/>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-6 columns">
	      <label>Start Date
		<input type="text" class="fdatepicker" name="start-date" value="<?php echo $start_date; ?>"/>
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
	  <div class="row">
	    <div class="large-6 columns">
	      <label>End Date
		<input type="text" class="fdatepicker" name="end-date" value="<?php echo $end_date; ?>"/>
	      </label>
	    </div>
	    <div class="large-2 columns">
	      <label>&nbsp;
		<select class="foundation" name="end-hour">
		    <?php for( $i=1; $i<=12; $i++ ) : ?><option value=<?php echo $i; ?> <?php if( $i == $end_hour ) echo "selected=\"selected\""; ?>><?php echo $i; ?></option><?php endfor; ?>
		</select>
	      </label>
	    </div>
	    <div class="large-2 columns">
	      <label>&nbsp;
		<select class="foundation" name="end-minute">
		    <?php for( $i=0; $i<60; $i++ ) : ?><option value=<?php echo $i; ?> <?php if( $i == $end_minute ) echo "selected=\"selected\""; ?>><?php if( $i < 10 ) echo '0'; echo $i; ?></option><?php endfor; ?>
		</select>
	      </label>
	    </div>
	    <div class="large-2 columns">
	      <label>&nbsp;
		<select class="foundation" name="end-suffix">
			<option value="AM" <?php if( 'AM' == $end_time_suffix ) echo "selected=\"selected\""; ?>>AM</option>
			<option value="PM" <?php if( 'PM' == $end_time_suffix ) echo "selected=\"selected\""; ?>>PM</option>
		</select>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      <label>Projects</label>
	      <?php $projects = $retrieve->projects(); ?>
	      <?php foreach( $projects as $project ) : ?>
	      <input type="checkbox" name="projects[]" value="<?php echo $project->ID; ?>" <?php if( in_array( $event->ID, get_post_meta( $project->ID, 'events' )) ) echo "checked=\"checked\""; ?>><label for="checkbox1"><?php echo $project->post_title; ?></label><br>
	      <?php endforeach; ?>
	    </div>
	  </div>
	  <?php form::textarea( array( 'label' => 'Details', 'name' => 'details', 'placeholder' => 'Details about your event', 'value' => get_post_meta( $event->ID, 'details', true ) ) ); ?>
	  <?php form::textarea( array( 'label' => 'Notes', 'name' => 'notes', 'placeholder' => 'Notes about your event', 'value' => get_post_meta( $event->ID, 'notes', true ) ) ); ?>
	  <div class="row">
	    <div class="large-12 columns">
	      <input name="ID" value="<?php echo $event->ID; ?>" class="hidden">
	      <input name="action" value="admin_<?php echo $action; ?>_event" class="hidden">
	      <input name="aware-<?php echo $action; ?>-event" class="button radius tiny" value="<?php echo ucfirst($action); ?> event">
	      <?php if( $action == 'update' ) : ?><input name="aware-delete-event" class="red button radius tiny" value="Delete event"><?php endif; ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns response hidden">
	      The event has been updated.
	    </div>
	  </div>
	</form>
	<?php }

}

?>

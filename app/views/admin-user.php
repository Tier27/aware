<?php namespace aware; ?>
<?php


?>
<form method="post" action="<?php echo site_url('client/'); ?>">
  <div class="row">
    <div class="large-6 columns">
      <label>First name
	<input type="text" name="first-name" value="<?php echo $client->first_name; ?>"/>
      </label>
    </div>
    <div class="large-6 columns">
      <label>Last name
	<input type="text" name="last-name" value="<?php echo $client->last_name; ?>"/>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-4 columns">
      <label>Email
	<input type="text" name="email" value="<?php echo $client->email; ?>" />
      </label>
    </div>
    <div class="large-4 columns">
      <label>Password
	<input type="password" name="password" />
      </label>
    </div>
  </div>
	<!--
  <div class="row">
    <div class="large-12 columns">
      <label>Project
	<select name="projects[]">
	  <option value="0">No project</option>
<?php print_r( Retrieve::projects() ); ?>
	  <?php foreach( $projects as $project ) : ?>
	  <option value="<?php echo $project->ID; ?>" <?php if( in_array( $project->ID, $client_projects ) ) : ?>selected="selected"<?php endif; ?>><?php echo $project->post_title ?></option>
	  <?php endforeach; ?>
	</select>
      </label>
    </div>
  </div>
	-->
  <div class="row">
    <div class="large-12 columns">
      <label>Manager
	<select name="manager">
	  <option value="0">No manager</option>
	  <?php foreach( Manager::all() as $manager ) : ?>
	  <option value="<?php echo $manager->id; ?>" <?php if( $client->manager_id == $manager->id ) : ?>selected="selected"<?php endif; ?>><?php echo $manager->fullName(); ?></option>
	  <?php endforeach; ?>
	</select>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
    <h6>Email Notifications</h6>
    <div class="large-12 columns">
      <input type="radio" name="emails" value="1" <?php if( $client->emails == 1 ) echo "checked=\"checked\"";?>><label>On</label>
      <input type="radio" name="emails" value="0" <?php if( $client->emails == 0 ) echo "checked=\"checked\"";?>><label>Off</label>
    </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <label>Notes (private)
	<textarea name="notes" placeholder="Notes about your client"><?php echo $client->notes; ?></textarea>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <input name="ID" value="<?php echo $client->ID; ?>" class="hidden">
      <input name="action" value="admin_<?php echo $action; ?>_client" class="hidden">
      <input name="aware-<?php echo $action; ?>-client" class="button radius" value="<?php echo ucfirst($action); ?> client">
      <?php if( $action == 'update' ) : ?>
	<input name="aware-delete-client" class="red button radius" value="Delete client">
	<a href="<?php echo site_url('client/'); ?>" class="button radius">View Client Dashboard</a>
      <?php endif; ?>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns response hidden">
      The client has been updated.
    </div>
  </div>
</form>


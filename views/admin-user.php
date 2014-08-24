<form method="post" action="<?php echo site_url('client/'); ?>">
  <div class="row">
    <div class="large-6 columns">
      <label>First name
	<input type="text" name="first-name" value=""/>
      </label>
    </div>
    <div class="large-6 columns">
      <label>Last name
	<input type="text" name="last-name" value=""/>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-4 columns">
      <label>Email
	<input type="text" name="email" value="" />
      </label>
    </div>
    <div class="large-4 columns">
      <label>Password
	<input type="password" name="password" />
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <label>Manager
	<select name="manager">
	  <option value="0">No manager</option>
	  <?php foreach( $managers as $manager ) : ?>
	  <option value="<?php echo $manager->ID; ?>"><?php echo $manager->display_name ?></option>
	  <?php endforeach; ?>
	</select>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <label>Notes (private)
	<textarea name="notes" placeholder="Notes about your client"></textarea>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <input name="ID" value="" class="hidden">
      <input name="action" value="admin__client" class="hidden">
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


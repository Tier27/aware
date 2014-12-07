<?php namespace aware; ?>
<?php


?>
<form method="post" action="<?php echo site_url('manager/'); ?>">
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
      <label>Clients</label>
	<?php Client::checkboxes(); ?>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <label>Notes (private)
	<textarea name="notes" placeholder="Notes about your manager"></textarea>
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <input name="ID" value="<?php echo $manager->ID; ?>" class="hidden">
      <input name="action" value="admin_<?php echo $action; ?>_manager" class="hidden">
      <input name="aware-<?php echo $action; ?>-manager" class="button radius" value="<?php echo ucfirst($action); ?> manager">
      <?php if( $action == 'update' ) : ?>
	<input name="aware-delete-manager" class="red button radius" value="Delete manager">
      <?php endif; ?>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns response hidden">
      The manager has been updated.
    </div>
  </div>
</form>


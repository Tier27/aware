<?php namespace aware; ?>
	<form>
	  <div class="row">
	    <div class="large-6 columns">
	      <label>Name
		<input type="text" name="name" value="<?php echo $project->name; ?>"/>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-6 columns">
	      <label>Date
		<input type="text" class="fdatepicker" name="date" value="<?php echo $start_date; ?>"/>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      Duration<br>
		<input type="radio" name="duration" value="1"><label>All day (7-3)</label>
		<input type="radio" name="duration" value="2"><label>All day (9-6)</label>
		<input type="radio" name="duration" value="3"><label>Custom</label>
	    </div>
	  </div>
	  <div class="row duration custom hide">
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
	  <div class="row duration custom hide">
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
	      <label>Client</label>
		  <?php Client::options($project->client_id, 'client', 'client'); ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      <label>Details 
		<textarea name="details" placeholder="Details about the project"><?php echo $project->details; ?></textarea>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      <label>Notes (private)
		<textarea name="notes" placeholder="Notes about the project"><?php echo $project->notes; ?></textarea>
	      </label>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
	      <input name="id" value="<?php echo $project->id; ?>" class="hidden">
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

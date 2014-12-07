	<?php
	//To handle the mess that is time conversion
	if( isset($event) ) {
	$start_time = strtotime($event->start_date);//( $start_time = get_post_meta( $event->ID, 'start_time', true ) ) ? $start_time : time(); 
	$start_date = date('m/d/Y', $start_time);
	$start_hour = date('g', $start_time);
	$start_minute = date('i', $start_time);
	$start_time_suffix = date('A', $start_time);
	$end_time = ( $end_time = get_post_meta( $event->ID, 'end_time', true ) ) ? $end_time : time(); 
	$end_date = date('m/d/Y', $end_time);
	$end_hour = date('g', $end_time);
	$end_minute = date('i', $end_time);
	$end_time_suffix = date('A', $end_time);
	} else { 

	}
	?>
	<form>
	  <div class="row">
	    <div class="large-12 columns">
	      <label>Name
		<input type="text" name="name" value="<?php echo $event->name; ?>"/>
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
		<?php \aware\Settings::durations(); ?>
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
	      <label>Project</label>
		  <?php \aware\Project::options($event->project_id); ?>
	    </div>
	  </div>
	  <?php \aware\form::textarea( array( 'label' => 'Details', 'name' => 'details', 'placeholder' => 'Details about your event', 'value' => $event->details ) ); ?>
	  <?php \aware\form::textarea( array( 'label' => 'Notes', 'name' => 'notes', 'placeholder' => 'Notes about your event', 'value' => $event->notes ) ); ?>
	  <div class="row">
	    <div class="large-12 columns">
	      <input name="id" value="<?php echo $event->id; ?>" class="hidden">
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

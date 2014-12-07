<dd class="accordion-navigation">
  <a href="#event-<?php echo $event->id; ?>"><?php echo $event->name; ?></a>
  <div id="event-<?php echo $event->id; ?>" class="content">
	<form>
	  <div class="row">
	    <div class="large-4 columns">
			Starts
	    </div>
	    <div class="large-4 columns">
			<?php echo $event->linkedStartDate(); ?>
	    </div>
	    <div class="large-4 columns">
			<?php echo $event->startTime(); ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-4 columns">
			Ends	
	    </div>
	    <div class="large-4 columns">
			<?php echo $event->linkedEndDate(); ?>
	    </div>
	    <div class="large-4 columns">
			<?php echo $event->endTime(); ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-4 columns">
			Project
	    </div>
	    <div class="large-8 columns">
			<?php echo $event->project()->name; ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
		<p><?php echo $event->details; ?></p>
	    </div>
	  </div>
	</form>
  </div>
</dd>

<dd class="accordion-navigation">
  <a href="#event-<?php echo $event->id; ?>"><?php echo $event->name; ?></a>
  <div id="event-<?php echo $event->id; ?>" class="content">
	<form>
	  <div class="row">
	    <div class="small-4 medium-4 large-4 columns">
			Starts
	    </div>
	    <div class="small-4 medium-4 large-4 columns">
			<?php echo $event->linkedStartDate(); ?>
	    </div>
	    <div class="small-4 medium-4 large-4 columns">
			<?php echo $event->startTime(); ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="small-4 medium-4 large-4 columns">
			Ends	
	    </div>
	    <div class="small-4 medium-4 large-4 columns">
			<?php echo $event->linkedEndDate(); ?>
	    </div>
	    <div class="small-4 medium-4 large-4 columns">
			<?php echo $event->endTime(); ?>
	    </div>
	  </div>
	  <?php if( $without != 'project' ) : ?>
	  <div class="row">
	    <div class="small-4 medium-4 large-4 columns">
			Project
	    </div>
	    <div class="small-8 medium-8 large-8 columns">
			<?php echo $event->project()->name; ?>
	    </div>
	  </div>
	  <?php endif; ?>
	  <div class="row">
	    <div class="small-12 medium-12 large-12 columns">
		<p><?php echo $event->details; ?></p>
	    </div>
	  </div>
	</form>
  </div>
</dd>

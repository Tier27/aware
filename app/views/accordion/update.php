<dd class="accordion-navigation">
  <a href="#update-<?php echo $update->id; ?>"><?php echo $update->subject; ?></a>
  <div id="update-<?php echo $update->id; ?>" class="content">
	<form>
	  <div class="row">
	    <div class="small-12 medium-12 large-12 columns">
			<?php echo $update->content; ?>
	    </div>
	  </div>
	  <?php if( $without != 'project' ) : ?>
	  <div class="row">
	    <div class="small-4 medium-4 large-4 columns">
			Project
	    </div>
	    <div class="small-8 medium-8 large-8 columns">
			<?php echo $update->project()->name; ?>
	    </div>
	  </div>
	  <?php endif; ?>
	</form>
  </div>
</dd>

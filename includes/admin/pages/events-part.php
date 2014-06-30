  <div class="row">
    <div class="large-12 columns">
      <div class="row">
        <div class="large-12 hide-for-small">
      </div>
    </div>
      <div class="row">
        <div class="large-12 columns show-for-small">
          <img src="http://placehold.it/1200x700&text=Client Dashboard">
        </div>
      </div><br>
	    <?php $events = admin_get_events(); ?>
            <div class="large-12 medium-12 small-12 columns panel section">
            <h3><i class="fa fa-calendar"></i> Calendar</h3><hr>
	      <?php foreach( $events as $event ) : ?>
              <div class="panel hide-for-small">
                <h5><a href="#"><?php echo $event->post_title; ?></a></h5>
              </div>
	      <?php endforeach; ?>
              <a href="#" class="right">Go To Calendar »</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 

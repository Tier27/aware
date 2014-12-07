<?php
namespace aware;

class templates {

	public function toolbar() { ?>
<div class="row">
  <dl class="sub-nav aware-thread-toolbar">
      <dt>Tools:</dt>
      <dd><a><i class="fa fa-tasks"></i> Forum</a></dd>
      <dd><a><i class="fa fa-refresh"></i> Refresh</a></dd>
      <dd><a><i class="fa fa-pencil"></i> Create</a></dd>
      <dd><a><i class="fa fa-search"></i> Search</a></dd>
      <dd><a><i class="fa fa-cog"></i> Settings</a></dd>
    </dl>
</div> <!--/.row-->
	<?php } 

	public function client_nav() { global $retrieve; ?>
  <section class="top-bar-section">
    
    <ul class="right">
      <?php if( soft_is_admin() ) : ?>
      <li class="divider"></li>
      <li class="has-dropdown">
        <a href="<?php echo admin_url('admin.php?page=aware_dashboard'); ?>"><i class="fa fa-calendar"></i> AWARE</a>
        <ul class="dropdown">
          <li><a href="<?php echo admin_url( 'admin.php?page=aware_clients' ); ?>">Clients</a></li>
          <li><a href="<?php echo admin_url( 'admin.php?page=aware_projects' ); ?>">Projects</a></li>
          <li><a href="<?php echo admin_url( 'admin.php?page=aware_events' ); ?>">Events</a></li>
          <li><a href="<?php echo admin_url( 'admin.php?page=aware_communications' ); ?>">Communications</a></li>
          <li><a href="<?php echo admin_url( 'admin.php?page=aware_settings' ); ?>">Settings</a></li>
        </ul>
      </li>
      <?php endif; ?>
      <li class="divider"></li>
      <li><a href="<?php echo site_url('client/calendar'); ?>"><i class="fa fa-calendar"></i> Calendar</a></li>
      <li class="divider"></li>
      <li><a href="#"><i class="fa fa-bolt"></i> Notifications</a></li>
      <li class="divider"></li>
      <li class="has-dropdown">
        <a href="<?php echo Route::inbox(); ?>"><i class="fa fa-comments"></i> Inbox</a>
        <ul class="dropdown">
          <?php $args = array( 'client' => get_current_user_id() ); ?>
          <?php foreach( Thread::inbox() as $thread ) : ?>
		  <?php $thread = Thread::cast((array)$thread); ?>
          <li><a href="<?php echo $thread->getPermalink(); ?>"><?php echo $thread->subject; ?></a></li>
	  <?php endforeach; ?>
          <li class="divider"></li>
          <li><a href="<?php echo site_url( 'client/conversations' ); ?>">See all →</a></li>
        </ul>
      </li>
      <li class="has-dropdown">
        <a href="#"><i class="fa fa-comments"></i> Outbox</a>
        <ul class="dropdown">
          <li><a href="<?php echo site_url( 'client/message' ); ?>">Start conversation</a></li>
          <li class="divider"></li>
          <?php $args = array( 'client' => get_current_user_id() ); ?>
          <?php foreach( Thread::outbox() as $thread ) : ?>
		  <?php $thread = Thread::cast((array)$thread); ?>
          <li><a href="<?php echo $thread->getPermalink(); ?>"><?php echo $thread->subject; ?></a></li>
	  <?php endforeach; ?>
          <li class="divider"></li>
          <li><a href="<?php echo site_url( 'client/conversations' ); ?>">See all →</a></li>
        </ul>
      </li>
      <li class="divider"></li>
      <li class="has-dropdown">
        <a href="<?php echo site_url( 'client' ); ?>"><?php echo get_avatar( get_current_user_id() ); ?> Dashboard</a>
        <ul class="dropdown">
          <li><a href="<?php echo wp_logout_url(); ?>">Log out</a></li>
        </ul>
      </li>
    </ul>
  </section>
	<?php }

	public function client_nav_wrapped() { ?>
<nav class="top-bar" data-topbar>
  <ul class="title-area">     
    <li class="name">
      <h1><a href="#">Client Update</a></h1>
    </li>
    <li class="toggle-topbar menu-icon"><a href="#"><span>menu</span></a></li>
  </ul>

  <?php self::client_nav('client-nav'); ?>

</nav>
	<?php }

	public function admin_nav() { global $retrieve; ?>
  <section class="top-bar-section">
    
    <ul class="right">
      <li class="divider"></li>
      <li class="has-dropdown">
        <a href="<?php echo admin_url('admin.php?page=aware_dashboard'); ?>"><i class="fa fa-calendar"></i> AWARE</a>
        <ul class="dropdown">
          <li><a href="<?php echo admin_url( 'admin.php?page=aware_clients' ); ?>">Clients</a></li>
          <li><a href="<?php echo admin_url( 'admin.php?page=aware_projects' ); ?>">Projects</a></li>
          <li><a href="<?php echo admin_url( 'admin.php?page=aware_events' ); ?>">Events</a></li>
          <li><a href="<?php echo admin_url( 'admin.php?page=aware_communications' ); ?>">Communications</a></li>
          <li><a href="<?php echo admin_url( 'admin.php?page=aware_settings' ); ?>">Settings</a></li>
        </ul>
      </li>
      <li class="divider"></li>
      <li><a href="#"><i class="fa fa-calendar"></i> Calendar</a></li>
      <li class="divider"></li>
      <li><a href="#"><i class="fa fa-bolt"></i> Notifications</a></li>
      <li class="divider"></li>
      <li class="has-dropdown">
        <a href="#"><i class="fa fa-comments"></i> Communication</a>
        <ul class="dropdown">
          <li><a href="<?php echo site_url( 'client/message' ); ?>">Start conversation</a></li>
          <li class="divider"></li>
          <?php $args = array( 'author' => get_current_user_id() ); ?>
          <?php foreach( $retrieve->threads( $args ) as $thread ) : ?>
          <li><a href="<?php echo get_the_permalink( $thread->ID ); ?>"><?php echo $thread->post_title; ?></a></li>
	  <?php endforeach; ?>
          <li class="divider"></li>
          <li><a href="<?php echo site_url( 'client/conversation' ); ?>">See all →</a></li>
        </ul>
      </li>
      <li class="divider"></li>
      <li><a href="<?php echo site_url( 'client' ); ?>"><?php echo get_avatar(); ?> Dashboard</a></li>
    </ul>
  </section>
	<?php }

	public function dashboard_events( $client, $limit = -1, $full_page = false ) { global $retrieve;

		$args = array( 'meta_key' => 'client', 'meta_value' => $client->ID );
		$events = $retrieve->events( $args );
		$events = $retrieve->client_events( $client->ID );
?>
  <div class="small-12 columns panel section">
    <h3><i class="fa fa-calendar"></i> Events</h3><hr>
      <div class="aware-widget-section">
        <h5><i class="fa fa-long-arrow-right"></i> Events</h5>
	<?php
	foreach( $events as $event ) : 
	?>
          <div class="panel">
            <h6><a href="<?php echo $event->guid; ?>"><?php echo $event->post_title; ?></a></h6>
          </div> <!--/.panel-->
	<?php
	endforeach; 
	?>
      </div> <!--/.aware-widget-section-->
    <?php if( !$full_page ) : ?>
    <a href="<?php echo site_url('client/events/' . $client->ID); ?>" class="right">See All Events »</a>
    <?php endif; ?>
  </div> <!--/.section-->

	<?php }

	public function dashboard_accordion_events($client, $limit = 3, $full_page = false ) { global $retrieve;
		$events = $retrieve->events();
		$events = $client->events();
	?>

	      <div class="large-12 medium-12 small-12 columns panel section">
		<h3><i class="fa fa-space-shuttle"></i> Events</h3><hr>
		<dl class="accordion" data-accordion>
	<?php
		foreach( $events as $event ) :
			View::template('accordion/event', array('event' => $event));
		endforeach;
	?>
		</dl>
		<br>
    	<?php if( !$full_page ) : ?>
	      <a href="<?php echo site_url('client/events/' . $client->ID); ?>" class="right">See All Events »</a>
	<?php endif; ?>
	      </div>
	<?php
	}

	public function dashboard_event($event_id) { 
		$event = Event::find($event_id);
	?>

	      <div class="large-12 medium-12 small-12 columns panel section">
		<h3><i class="fa fa-space-shuttle"></i> <?php echo $event->name; ?></h3><hr>
		<dl class="accordion" data-accordion>
	  <dd class="accordion-navigation">
	    <a href="#event-<?php echo $event->id; ?>"><?php echo $event->name; ?></a>
	  </dd>
		</dl>
		<br>
	      </div>
	<?php
	}


	public function dashboard_projects( $client, $limit = -1, $full_page = false ) { global $retrieve;

		//$args = array( 'meta_key' => 'client', 'meta_value' => $client->ID );
		$projects = $retrieve->projects( $args );
		$client_projects = get_user_meta( $client->ID, 'projects' );
?>
  <div class="small-12 columns panel section">
    <h3><i class="fa fa-calendar"></i> Projects</h3><hr>

      <div class="aware-widget-section">
	<?php
	foreach( $projects as $project ) : 
	if( in_array( $project->ID, $client_projects ) ) :
	?>
          <div class="panel">
            <h6><a href="<?php echo $project->guid; ?>"><?php echo $project->post_title; ?></a></h6>
          </div> <!--/.panel-->
	<?php
	endif;
	endforeach; 
	?>
      </div> <!--/.aware-widget-section-->
    <?php if( !$full_page ) : ?>
    <a href="<?php echo site_url('client/projects/' . $client->ID); ?>" class="right">See All Projects »</a>
    <?php endif; ?>
  </div> <!--/.section-->

	<?php }

	public function dashboard_accordion_projects($client, $limit = 3, $full_page = false) { global $retrieve;
		$projects = $retrieve->projects();
		$projects = $client->projects();
	?>

	      <div class="large-12 medium-12 small-12 columns panel section">
		<h3><i class="fa fa-space-shuttle"></i> Projects</h3><hr>
		<dl class="accordion" data-accordion>
	<?php
		foreach( $projects as $project ) :
	?>
	  <dd class="accordion-navigation">
	    <a href="#project-<?php echo $project->id; ?>"><?php echo $project->name; ?></a>
	    <div id="project-<?php echo $project->id; ?>" class="content">
		<?php self::accordion_project( $project ); ?>
	    </div>
	  </dd>
	<?php
		endforeach;
	?>
		</dl>
		<br>
    	<?php if( !$full_page ) : ?>
	      <a href="<?php echo site_url('client/projects/' . $client->ID); ?>" class="right">See All Projects »</a>
	<?php endif; ?>
	      </div>
	<?php
	}

	public function dashboard_updates( $client, $limit = -1, $full_page = false ) { global $retrieve; 

		$args = array( 
			'client' => $client->ID,
			'posts_per_page' => $limit,
		 );
          	$threads = $retrieve->updates( $args );
		$threads = $client->updates();
?>

  <div class="small-12 columns panel section">
    <h3><i class="fa fa-space-shuttle"></i> Updates</h3><hr>
      <div class="aware-widget-section">
        <h5><i class="fa fa-long-arrow-right"></i> Recent Updates</h5>
          <?php
		foreach( $threads as $thread ) :
          ?>
          <div class="panel">
            <h6><a href="#"><?php echo $thread->subject; ?></a></h6>
            <p><?php echo $thread->content; ?></p>
          </div> <!--/.panel-->

	  <?php 
		endforeach; 
	  ?>
      </div> <!--/.aware-widget-section-->

    <?php if( !$full_page ) : ?>
    <a href="<?php echo site_url('client/updates/' . $client->ID); ?>" class="right">See All Updates »</a>
    <?php endif; ?>
  </div> <!--/.section-->
	
	<?php }

	public function dashboard_accordion_updates( $client, $limit = -1, $full_page = false ) { global $retrieve; 
		$args = array( 
			'client' => $client->ID,
			'posts_per_page' => $limit,
		 );
		$updates = $client->updates(); 
	?>

	      <div class="large-12 medium-12 small-12 columns panel section">
		<h3><i class="fa fa-space-shuttle"></i> Updates</h3><hr>
		<dl class="accordion" data-accordion>
	<?php
		foreach( $updates as $update ) :
	?>
	  <dd class="accordion-navigation">
	    <a href="#update-<?php echo $update->id; ?>"><?php echo $update->subject; ?>
		<span class="pull-right"><?php echo $update->date(); ?></span>
   	    </a>
	    <div id="update-<?php echo $update->id; ?>" class="content">
		<?php self::accordion_update( $update ); ?>
	    </div>
	  </dd>
	<?php
		endforeach;
	?>
		</dl>
		<br>
    	<?php if( !$full_page ) : ?>
	      <a href="<?php echo site_url('client/updates'); ?>" class="right">See All Updates »</a>
	<?php endif; ?>
	      </div>
	<?php
	}

	public function dashboard_conversations( $client, $limit = -1, $full_page = false ) { global $retrieve; 

		return self::inbox();
	}

	public function inbox() {

		$threads = Thread::inbox();

    ?><div class="small-12 columns panel section">
    <h3><i class="fa fa-space-shuttle"></i> Conversations</h3><hr>
      <div class="aware-widget-section">
        <h5><i class="fa fa-long-arrow-right"></i> Recent Conversations</h5>
          <?php
		foreach( $threads as $thread ) :
		$thread = Thread::cast((array)$thread);
		$author = get_userdata( $thread->sender );
          ?>
          <div class="panel aware-widget-communication-discussion">
            <a href="<?php echo $thread->getPermalink(); ?>"><?php echo $thread->subject ?></a>
	    <span class="pull-right"><?php echo date('m/d/Y, g:ia', strtotime($thread->created_at)); ?></span>
	    <br>
	    <br>
            <h6><?php echo get_avatar( $thread->sender ); ?> <?php echo $author->display_name; ?></h6>
          </div> <!--/.panel-->

	  <?php 
		endforeach; 
	  ?>
      </div> <!--/.aware-widget-section-->

    <?php if( !$full_page ) : ?>
    <a href="<?php echo site_url('client/conversations/' . $client->ID); ?>" class="right">See All Conversations »</a>
    <?php endif; ?>
  </div> <!--/.section-->
	
	<?php }

	public function threads()
	{
		print_r( Thread::inbox() );
	}

	public function dashboard_communications( $client, $limit = -1, $full_page = false ) { global $retrieve; 

?>
  <div class="small-12 columns panel section">
    <h3><i class="fa fa-comments"></i> Communication</h3><hr>
      <div class="aware-widget-section">
        <h5><i class="fa fa-long-arrow-right"></i> Discussions</h5>
          <div class="panel aware-widget-communication-discussion">
            <h6><img class="avatar" src="<?php echo AWARE_URL; ?>assets/img/avatar.png" alt="Avatar"> Ryan Douvlos</h6>
            <p><i class="fa fa-mail-reply"></i>Hey, Josh.  You are looking at the last message you received by <a>Ryan</a></p>
          </div> <!--/.panel-->

          <div class="panel aware-widget-communication-discussion">
            <h6><img class="avatar" src="<?php echo AWARE_URL; ?>assets/img/avatar.png" alt="Avatar"> Bob Graham</h6>
            <p><i class="fa fa-mail-forward"></i>Hey, Josh.  You are looking at the last message you sent to <a>Bob</a></p>
          </div> <!--/.panel-->

      </div> <!--/.aware-widget-section-->

    <a href="#" class="right">Go To Projects »</a>
  </div> <!--/.section-->

	<?php }

	public function reply_message_box($thread) { global $client; ?>

<form id="aware-message-box">
<div class="row">
  <!--Communication Section--> 
  <div class="small-12 medium-12 columns panel section">
      <div class="aware-widget-section">
        

        <div class="button-bar aware-thread-comment-tools">
          <ul class="button-group">
            <li><a href="#" class="small button"><i class="fa fa-bold"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-italic"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-link"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-strikethrough"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-underline"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-picture-o"></i></a></li>
            <li><a href="#" class="small button">Color</a></li>
            <li><a href="#" class="small button">Large</a></li>
            <li><a href="#" class="small button">Small</a></li>
          </ul>
        </div>

          <div class="panel aware-widget-communication-discussion">
            <textarea rows="7" name="content"></textarea>
          </div> <!--/.panel-->


        <input name="aware-post-reply" class="button radius tiny" value="Post reply">
        <input name="aware-cancel-reply" class="button radius tiny alert" value="Cancel">
        <input name="aware-delete-thread" class="button radius tiny alert" value="Delete">
	<input name="parent" value="<?php echo get_the_id(); ?>" style="display: none;">
	<input name="thread" value="<?php echo $thread->id; ?>" style="display: none;">
	<input name="client" value="<?php echo $client->ID; ?>" style="display: none;">
	<input name="recipient" value="<?php echo $thread->otherParty(get_current_user_id())->ID; ?>" style="display: none;">
	<input name="sender" value="<?php echo get_current_user_id(); ?>" style="display: none;">
	<input name="action" value="aware_post_reply" style="display: none;">

      </div> <!--/.aware-widget-section-->
  </div> <!--/.section-->


</div> <!--/.row-->
</form>

</div> <!--/#aware-->

	<?php }

	public function conversation_reply( $reply ) { ?>
          <div class="panel aware-widget-communication-discussion aware-thread aware-thread-reply">
            <div class="row">
              <div class="small-12 columns">
                <h6><a><?php echo get_avatar( get_the_author( $reply->ID ) ); ?> <?php echo get_the_author( $reply->ID ); ?></a></h6>
		<?php echo $reply->post_content; ?>
              </div>
            </div> <!--/.row-->

            <hr />
            <div class="row">
              <div class="small-12 columns">
                <ul class="inline-list pull-right aware-thread-reply-tools">
                  <!--<li><a id="aware-thumbs-up"><i class="fa fa-thumbs-up"></i><span>(0)</span></a></li>-->
                  <li><a><i class="fa fa-reply"></i></a></li>
                </ul>
              </div>
            </div> <!--/.row-->

          </div> <!--/.panel-->
	<?php }

	public function thread_reply( $reply, $thread ) { ?>
          <div class="panel aware-widget-communication-discussion aware-thread aware-thread-reply">
            <div class="row">
              <div class="small-12 columns">
                <h6><a><?php echo get_avatar( get_the_author( $reply->sender ) ); ?> <?php echo $thread->party($reply->sender)->display_name; ?></a></h6>
				<p><?php echo $thread->date(); ?></p>
		<?php echo $reply->content; ?>
              </div>
            </div> <!--/.row-->

            <hr />
            <div class="row">
              <div class="small-12 columns">
                <ul class="inline-list pull-right aware-thread-reply-tools">
                </ul>
              </div>
            </div> <!--/.row-->

          </div> <!--/.panel-->
	<?php }

	public static function in_box()
	{
		return self::dashboard_conversations( wp_get_current_user() );
	}

	public function thread_box() { ?>
<form id="aware-create-thread">
<div class="row collapse">
  <div class="small-12 medium-12 columns">
    <h3>Start a conversation</h3>

    <h6>Subject</h6>
    <input type="text" name="subject">
    <h6>Client</h6>
	<?php Client::options(null, 'recipient'); ?>
  </div>
</div> <!--/.row-->

<div class="row">
  <!--Communication Section--> 
  <div class="small-12 medium-12 columns panel section">
      <div class="aware-widget-section">
        

        <div class="button-bar aware-thread-comment-tools">
          <ul class="button-group">
            <li><a href="#" class="small button"><i class="fa fa-bold"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-italic"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-link"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-strikethrough"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-underline"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-picture-o"></i></a></li>
            <li><a href="#" class="small button">Color</a></li>
            <li><a href="#" class="small button">Large</a></li>
            <li><a href="#" class="small button">Small</a></li>
          </ul>
        </div>

          <div class="panel aware-widget-communication-discussion">
            <textarea rows="7" name="content"></textarea>
          </div> <!--/.panel-->

        <input name="aware-create-communication" class="button radius tiny" value="Start conversation">
        <input name="aware-cancel-communication" class="button radius tiny alert" value="Cancel">
	<input name="action" value="aware_create_thread" style="display: none;">

      </div> <!--/.aware-widget-section-->
  </div> <!--/.section-->


</div> <!--/.row-->
</form>
	<?php }

	public function update_box() { ?>
<form id="aware-create-thread">
<div class="row collapse">
  <div class="small-12 medium-12 columns">
    <h3>Create Update</h3>

    <h6>Subject</h6>
    <input type="text" name="subject">
	  <div class="row">
	    <div class="large-12 columns">
	      <label>Clients</label>
		  <?php Client::checkboxes(); ?>
	    </div>
	  </div>
  </div>
</div> <!--/.row-->

<div class="row">
  <!--Communication Section--> 
  <div class="small-12 medium-12 columns panel section">
      <div class="aware-widget-section">
        

        <div class="button-bar aware-thread-comment-tools hide">
          <ul class="button-group">
            <li><a href="#" class="small button"><i class="fa fa-bold"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-italic"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-link"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-strikethrough"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-underline"></i></a></li>
            <li><a href="#" class="small button"><i class="fa fa-picture-o"></i></a></li>
            <li><a href="#" class="small button">Color</a></li>
            <li><a href="#" class="small button">Large</a></li>
            <li><a href="#" class="small button">Small</a></li>
          </ul>
        </div>

          <div class="panel aware-widget-communication-discussion">
            <textarea rows="7" name="content"></textarea>
          </div> <!--/.panel-->

        <input name="aware-create-communication" class="button radius tiny" value="Post update">
        <input name="aware-cancel-communication" class="button radius tiny alert" value="Cancel">
	<input name="action" value="aware_create_update" style="display: none;">
        <div class="large-12 columns response hidden">
        </div>

      </div> <!--/.aware-widget-section-->
  </div> <!--/.section-->


</div> <!--/.row-->
</form>
	<?php }

	public function accordion_event( $event = NULL, $action = 'update' ) { global $retrieve; ?>
	<form>
	  <div class="row">
	    <div class="large-12 columns">
		Starts <?php echo $event->starts(); ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
		Ends <?php echo $event->ends(); ?>
	    </div>
	  </div>
	  <div class="row">
	    <div class="large-12 columns">
		<p><?php echo $event->details; ?></p>
	    </div>
	  </div>
	</form>
	<?php }

	public function accordion_project( $project = NULL, $action = 'update' ) { global $retrieve; ?>
	<form>
	  <div class="row">
	    <div class="large-12 columns">
		<p><?php echo $project->notes; ?></p>
	    </div>
	  </div>
	</form>
	<?php }

	public function accordion_update( $update = NULL, $action = 'update' ) { global $retrieve; ?>
	<form>
	  <div class="row">
	    <div class="large-12 columns">
		<p><?php echo $update->content; ?></p>
	    </div>
	  </div>
	</form>
	<?php }

}

<?php 
	namespace aware; 
	global $client;
?>
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
      <li class="has-dropdown">
        <a href="<?php echo Route::inbox(); ?>"><i class="fa fa-bolt"></i> Projects</a>
        <ul class="dropdown">
          <?php foreach( $client->projects() as $project ) : ?>
          <li><a href="<?php echo $project->getPermalink(); ?>"><?php echo $project->name; ?></a></li>
	  <?php endforeach; ?>
        </ul>
      </li>
      <li class="divider"></li>
      <li class="has-dropdown">
        <a href="<?php echo Route::inbox(); ?>"><i class="fa fa-bolt"></i> Notifications</a>
        <ul class="dropdown">
          <?php foreach( Thread::unread() as $thread ) : ?>
		  <?php $thread = Thread::cast((array)$thread); ?>
          <li><a href="<?php echo $thread->getPermalink(); ?>"><?php echo $thread->subject; ?></a></li>
	  <?php endforeach; ?>
        </ul>
      </li>
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
          <li><a href="<?php echo site_url( 'client/conversations/inbox' ); ?>">See all →</a></li>
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
          <li><a href="<?php echo site_url( 'client/conversations/outbox' ); ?>">See all →</a></li>
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

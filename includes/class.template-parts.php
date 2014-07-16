<?php

class AWARETemplateParts {

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

	public function client_nav() { ?>
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
          <?php foreach( client_get_threads( $args ) as $thread ) : ?>
          <li><a href="<?php echo get_the_permalink( $thread->ID ); ?>"><?php echo $thread->post_title; ?></a></li>
	  <?php endforeach; ?>
          <li class="divider"></li>
          <li><a href="<?php echo site_url( 'client/conversation' ); ?>">See all →</a></li>
        </ul>
      </li>
      <li class="divider"></li>
      <li><a href="<?php echo site_url( 'client' ); ?>"><?php echo get_avatar(); ?> Dasboard</a></li>
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

	public function admin_nav() { ?>
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
          <?php foreach( client_get_threads( $args ) as $thread ) : ?>
          <li><a href="<?php echo get_the_permalink( $thread->ID ); ?>"><?php echo $thread->post_title; ?></a></li>
	  <?php endforeach; ?>
          <li class="divider"></li>
          <li><a href="<?php echo site_url( 'client/conversation' ); ?>">See all →</a></li>
        </ul>
      </li>
      <li class="divider"></li>
      <li><a href="<?php echo site_url( 'client' ); ?>"><?php echo get_avatar(); ?> Dasboard</a></li>
    </ul>
  </section>
	<?php }

	function dashboard_events( $client, $limit = -1, $full_page = false ) {

	$args = array( 'meta_key' => 'client', 'meta_value' => $client->ID );
	$events = admin_get_events( $args );
?>
  <div class="small-12 columns panel section">
    <h3><i class="fa fa-calendar"></i> Events</h3><hr>
      <div class="aware-widget-section">

<!--
        <h5><i class="fa fa-long-arrow-right"></i> External Calendar View</h5>
          <img class="widget-image" src="<?php echo AWARE_URL; ?>assets/img/eventon-full.jpg" alt="event on full">
-->
      </div> <!--/.aware-widget-section-->

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
    <a href="#" class="right">Go To Events »</a>
    <?php endif; ?>
  </div> <!--/.section-->

	<?php }

	function dashboard_projects( $client ) {

	$args = array( 'meta_key' => 'client', 'meta_value' => $client->ID );
	$projects = admin_get_projects( $args );
?>
  <div class="small-12 columns panel section">
    <h3><i class="fa fa-calendar"></i> Projects</h3><hr>

      <div class="aware-widget-section">
	<?php
	foreach( $projects as $project ) : 
	?>
          <div class="panel">
            <h6><a href="<?php echo $project->guid; ?>"><?php echo $project->post_title; ?></a></h6>
          </div> <!--/.panel-->
	<?php
	endforeach; 
	?>
      </div> <!--/.aware-widget-section-->
  </div> <!--/.section-->

	<?php }

}

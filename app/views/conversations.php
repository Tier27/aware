<?php
	namespace aware;
	$utility = new Utility('Communication panel loaded');
	global $retrieve;
	global $client;
?>
<div class="row collapse">
  <div class="small-10 columns">
    <input type="text" name="aware-search" placeholder="Search conversations">
  </div>
  <div class="small-2 columns">
    <input type="submit" class="button orange postfix" value="Search">
  </div>
</div> <!--/.row-->

  <!--Communication Section--> 
  <div class="small-12 columns panel section">
      <div class="aware-widget-section">
        <h5><i class="fa fa-long-arrow-right"></i> Recent Discussions</h5>

          <?php
		$args = array( 'client' => get_current_user_id(), 'post_parent' => 0 );
          	$threads = $retrieve->threads( $args );
		$threads = Thread::inbox();
		foreach( $threads as $thread ) :
          ?>
          <div class="panel aware-widget-communication-discussion">
            <h6 class="aware-thread-title"><img class="avatar" src="<?php echo AWARE_URL; ?>assets/img/avatar.png" alt="Avatar"> <a href="<?php echo $thread->subject; ?>"><?php echo $thread->subject; ?></a></h6>
<!--
            <p class="aware-thread-topic-details"><i class="fa fa-comment"></i><strong>27</strong> responses.  Last response by <a>Ryan</a>, <strong>2</strong> hours ago. (11:42pm, 06/10/14)</p>
-->
          </div> <!--/.panel-->
	  <?php 
		endforeach;
		//$utility->report();
	  ?>

      </div> <!--/.aware-widget-section-->

    <ul class="pagination">
      <li class="arrow unavailable"><a href="">&laquo;</a></li>
      <li class="current"><a href="">1</a></li>
      <li><a href="">2</a></li>
      <li><a href="">3</a></li>
      <li><a href="">4</a></li>
      <li class="unavailable"><a href="">&hellip;</a></li>
      <li><a href="">12</a></li>
      <li><a href="">13</a></li>
      <li class="arrow"><a href="">&raquo;</a></li>
    </ul>
  </div> <!--/.section-->

  <!--Communication Section--> 
  <div class="small-12 medium-3 medium-offset-1 columns panel section hide">
      <div class="aware-widget-section">
        <h5><i class="fa fa-long-arrow-right"></i> Categories</h5>
          <div class="panel aware-widget-communication-discussion">
            <h6 class="zippy"><a>Category 1</a></h6>
            <p>This is the description for category one.</p>
          </div> <!--/.panel-->

          <div class="panel aware-widget-communication-discussion">
            <h6 class="zippy"><a>Category 2</a></h6>
            <p>This is the description for category two.</p>
          </div> <!--/.panel-->

          <div class="panel aware-widget-communication-discussion">
            <h6 class="zippy"><a>Category 3</a></h6>
            <p>This is the description for category three .</p>
          </div> <!--/.panel-->


      </div> <!--/.aware-widget-section-->

  </div> <!--/.section-->
 

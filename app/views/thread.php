<?php namespace aware; ?>
<?php
	$thread_id = get_query_var('thread_id'); 
	$thread = Thread::find($thread_id);
	if( !$thread->isActive() ) 
		Route::to(home_url('/client'));
	//else
	//	$thread->markAsRead();
?>

<div class="row collapse">
  <div class="small-12 columns text-center hide">
    <ul class="breadcrumbs">
      <li><a href="#">Client Thread</a></li>
      <li><a href="#">Category of Thread</a></li>
    </ul>
  </div>
  <div class="small-12 columns text-center">
    <h2 id="aware-thread-topic-title"><a><?php aware_the_title(); ?></a></h2>
  </div>

  <hr />
</div> <!--/.row-->

<div class="row">

  <!--Thread Post Section--> 
  <div class="small-12 columns panel section">
      <div class="aware-widget-section">
          <div class="panel aware-widget-communication-discussion aware-client-thread-topic">
            
            <div class="row">
              <div class="small-12 medium-2 columns">
                <img src="<?php echo AWARE_URL; ?>assets/img/avatar.png" width="100">
              </div>
              <div class="small-12 medium-10 columns">
                <h3 class="inline"><a><?php echo $thread->sender->display_name; ?></a>
		  <span class="pull-right"><?php echo $thread->date(); ?></span>
		</h3>
                <hr />

                <p><?php echo $thread->subject; ?></p>
                <hr />
              
                <ul class="inline-list pull-right">
                  <!--<li><a id="aware-thumbs-up"><i class="fa fa-thumbs-up"></i><span>(0)</span></a></li>-->
                </ul>
              </div>

            </div> <!--/.row-->

          </div> <!--/.panel-->

      </div> <!--/.aware-widget-section-->
  </div> <!--/.section-->

  <!--Client Thread Reply--> 
  <div class="small-12 medium-12 columns panel section">
      <div class="aware-widget-section">


	<?php foreach( $thread->messages as $message ) templates::thread_reply( $message, $thread ); ?>

      </div> <!--/.aware-widget-section-->

  </div> <!--/.section-->

  <!--Client Thread Sidebar--> 
  <div class="small-12 medium-3 medium-offset-1 columns panel section hide">
      <div class="aware-widget-section">
        <h5><i class="fa fa-long-arrow-right"></i> Related Threads</h5>
          <div class="panel aware-widget-communication-discussion">
            <h6 class="aware-sidebar-category"><a>Related Thread 1</a></h6>
            <p>This is the description for related thread one.</p>
          </div> <!--/.panel-->

          <div class="panel aware-widget-communication-discussion">
            <h6 class="aware-sidebar-category"><a>Related Thread 2</a></h6>
            <p>This is the description for related thread two.</p>
          </div> <!--/.panel-->

          <div class="panel aware-widget-communication-discussion">
            <h6 class="aware-sidebar-category"><a>Related Thread 3</a></h6>
            <p>This is the description for related thread three .</p>
          </div> <!--/.panel-->


      </div> <!--/.aware-widget-section-->

  </div> <!--/.section-->


</div> <!--/.row-->
<?php templates::reply_message_box($thread); ?>
 
 
<script src="<?php echo AWARE_URL; ?>assets/js/foundation.min.js"></script>
<script>
	jQuery(document).foundation();
</script>

</body>
</html>

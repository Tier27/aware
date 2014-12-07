<?php
	namespace aware;
	global $client;
	$project_id = get_query_var('project_id');
	$project = Project::find($project_id);
		
?>

<div class="large-12 medium-12 small-12 columns panel section">
	<h3><i class="fa fa-space-shuttle"></i> <?php echo $project->name; ?></h3><hr>
	<dl class="accordion" data-accordion>
		<dd class="accordion-navigation">
			<a href="#project-<?php echo $project->id; ?>"><?php echo $project->name; ?></a>
		</dd>
	</dl>
	<p><?php echo $project->details; ?></p>
	<br>

	<h4><i class="fa fa-space-shuttle"></i> Updates</h4><hr>
    <dl class="accordion" data-accordion>
	<?php foreach( $project->updates() as $update ) View::template('accordion/update', array('update' => $update, 'without' => 'project')); ?>
    </dl>
	<br>

	<h4><i class="fa fa-space-shuttle"></i> Events</h4><hr>
    <dl class="accordion" data-accordion>
	<?php foreach( $project->events() as $event ) View::template('accordion/event', array('event' => $event, 'without' => 'project')); ?>
    </dl>
	<br>
</div>

<?php
	global $templates;
	global $client;
	$date_time = get_query_var('date_time');
	$first_day = date('N', strtotime(date("Y-m-01")));
$date = strtotime(date('Y-m-d H:i:s') . ' +1 day');
	$f_d = $first_day % 7;
	
?>
<ul class="calendar">
    <li class="title"><?php echo date('F'); ?></li>
    <li class="day-header">

        <div class="small-1 medium-1 large-1 day">
            <span class="show-for-medium-up">Sunday</span>
            <span class="show-for-small">Sun</span>
        </div>

        <div class="small-1 medium-1 large-1 day">
            <span class="show-for-medium-up">Monday</span>
            <span class="show-for-small">Mon</span>
        </div>

        <div class="small-1 medium-1 large-1 day">
            <span class="show-for-medium-up">Tuesday</span>
            <span class="show-for-small">Tue</span>
        </div>

        <div class="small-1 medium-1 large-1 day">
            <span class="show-for-large-up">Wednesday</span>
            <span class="show-for-medium-down">Wed</span>
        </div>

        <div class="small-1 medium-1 large-1 day">
            <span class="show-for-medium-up">Thursday</span>
            <span class="show-for-small">Thu</span>
        </div>

        <div class="small-1 medium-1 large-1 day">
            <span class="show-for-medium-up">Friday</span>
            <span class="show-for-small">Fri</span>
        </div>

        <div class="small-1 medium-1 large-1 day">
            <span class="show-for-medium-up">Saturday</span>
            <span class="show-for-small">Sat</span>
        </div>

    </li>

<?php
	$events = $client->events();
	$projects = $client->projects();
	$t_d = date('j');
	for( $j = 0; $j < 5; $j++ ) :
    ?><li class="week"><?php
	for( $i = 0; $i < 7; $i++ ) :
		$k = 7 * $j + $i;
		$previous_month = '';
		if( $k < $f_d ) $previous_month = 'previous-month';
		$time = strtotime(date("Y-m-01") . " +$k days - $f_d days");
		$date = date('j', $time);
		$today = '';
		$selected = '';
		if( $date_time == $time || ( abs($date_time - $time) < (24 * 60 * 60) ) ) $selected = 'selected';
		if( $k >= $f_d && $date == $t_d ) $today = 'today';
        ?><div class="small-1 medium-1 large-1 day <?php echo $previous_month; ?> <?php echo $today; ?> <?php echo $selected; ?>"><?php echo $date; ?>
			<?php foreach( $events as $event ) {
				$fulldate = date('Y-m-d', $time);
				$starttime = strtotime($event->start_date);
				$now = time();
				$now = strtotime(date('Y-m-d'));
				$endtime = strtotime($event->end_date);
				$eventstartdate = date('Y-m-d',$starttime);
				$eventenddate = date('Y-m-d',$endtime);
				if( $fulldate == $eventstartdate || $fulldate == $eventenddate || ( $starttime < $time && $time < $endtime ) ) :
			?>
			<div class="event"><a href="<?php echo site_url('client/event/' . $event->id); ?>"><?php echo $event->name; ?></a></div>
			<?php endif;
			} ?>
			<?php foreach( $projects as $project ) {
				$fulldate = date('Y-m-d', $time);
				$starttime = strtotime($project->start_date);
				$now = time();
				$now = strtotime(date('Y-m-d'));
				$endtime = strtotime($project->end_date);
				$projectstartdate = date('Y-m-d',$starttime);
				$projectenddate = date('Y-m-d',$endtime);
				if( $fulldate == $projectstartdate || $fulldate == $projectenddate || ( $starttime < $time && $time < $endtime ) ) :
			?>
			<div class="project"><a href="<?php echo site_url('client/project/' . $project->id); ?>"><?php echo $project->name; ?></a></div>
			<?php endif;
			} ?>
		</div><?php
	endfor;
	endfor;
?>

</ul>




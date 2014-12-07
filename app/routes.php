<?php
namespace aware;

//flush_rewrite_rules(false);
Route::post('/aware/post', function()
{
	return true;
});

Route::get('^client/event/([0-9]+)/?$', 'event', 'event_id');

Route::get('^client/project/([0-9]+)/?$', 'project', 'project_id');

Route::get('^client/thread/([0-9]+)/?$', 'thread', 'thread_id');

Route::get('^client/calendar/?$', 'calendar', 'calendar_id');

Route::get('^client/calendar/date/([0-9]+)/?$', 'calendar', 'date_time');

?>

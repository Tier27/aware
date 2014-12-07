<?php
namespace aware;

class Seed {

	public static function clients()
	{
		$clients = array(
			array(
				'first-name' => 'John',
				'last-name' => 'Smith',
				'email' => 'joshua.kornreich@gmail.com',
				'password' => 'password',
				'notes' => 'What are these even for?'
			),
			array(
				'first-name' => 'Jane',
				'last-name' => 'Smith',
				'email' => 'jane.smith@aware.com',
				'password' => 'password',
				'notes' => 'What are these even for?'
			),
			array(
				'first-name' => 'Jack',
				'last-name' => 'Smith',
				'email' => 'jack.smith@aware.com',
				'password' => 'password',
				'notes' => 'What are these even for?'
			),
		);
		
		foreach($clients as $client)
		{
			User::delete($client);
			$user_id = Client::prepare($client);
			
			Client::create($user_id, $client);	
		}
	}

	public static function managers()
	{
		$__ = array(
			array(
				'first-name' => 'Morty',
				'last-name' => 'Simpson',
				'email' => 'josh@tier27.com',
				'password' => 'password',
				'notes' => 'What are these even for?'
			),
		);
		
		foreach($__ as $_)
		{
			User::delete($_);
			$user_id = Manager::prepare($_);
			
			Manager::create($user_id, $_);	
		}
	}

	public static function projects()
	{
		$projects = array(
			array(
				'client_id' => 1,
				'name' => 'AWARE',
				'duration' => '1/4 day',
				'start_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' + 3 days')),
				'end_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' + 7 days')),
				'details' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam commodo condimentum dolor, eu gravida eros maximus vitae. Nulla congue ullamcorper velit. Ut et nibh nisl. Ut tristique mollis consequat. In hac habitasse platea dictumst. Quisque a massa libero. Aenean diam orci, bibendum sed augue id, mattis ultrices magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque condimentum elementum turpis et eleifend. Vivamus vestibulum lorem ut tortor accumsan ornare. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nullam nibh ex, condimentum sed laoreet in, bibendum sed purus. Phasellus ut erat ut purus laoreet dignissim. Duis et risus nec felis scelerisque dictum ac in tellus.',
				'notes' => 'What are these even for?'
			),
			array(
				'client_id' => 1,
				'name' => 'ALERT',
				'duration' => '1/4 day',
				'start_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' + 3 days')),
				'end_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' + 7 days')),
				'details' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam commodo condimentum dolor, eu gravida eros maximus vitae. Nulla congue ullamcorper velit. Ut et nibh nisl. Ut tristique mollis consequat. In hac habitasse platea dictumst. Quisque a massa libero. Aenean diam orci, bibendum sed augue id, mattis ultrices magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque condimentum elementum turpis et eleifend. Vivamus vestibulum lorem ut tortor accumsan ornare. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nullam nibh ex, condimentum sed laoreet in, bibendum sed purus. Phasellus ut erat ut purus laoreet dignissim. Duis et risus nec felis scelerisque dictum ac in tellus.',
				'notes' => 'What are these even for?'
			),
			array(
				'client_id' => 1,
				'name' => 'AGENDA',
				'duration' => '1/4 day',
				'start_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' + 3 days')),
				'end_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' + 7 days')),
				'details' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam commodo condimentum dolor, eu gravida eros maximus vitae. Nulla congue ullamcorper velit. Ut et nibh nisl. Ut tristique mollis consequat. In hac habitasse platea dictumst. Quisque a massa libero. Aenean diam orci, bibendum sed augue id, mattis ultrices magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque condimentum elementum turpis et eleifend. Vivamus vestibulum lorem ut tortor accumsan ornare. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nullam nibh ex, condimentum sed laoreet in, bibendum sed purus. Phasellus ut erat ut purus laoreet dignissim. Duis et risus nec felis scelerisque dictum ac in tellus.',
				'notes' => 'What are these even for?'
			),
		);
		
		foreach($projects as $project)
		{
			Project::create($project);	
		}
	}

	public static function updates()
	{
		$updates = array(
			array(
				'subject' => 'This is a first update',
				'content' => 'Everything is going well.',
				'project_id' => 1,
			),
			array(
				'subject' => 'This is a second update',
				'content' => 'Everything is going swell.',
				'project_id' => 1,
			),
			array(
				'subject' => 'This is a third update',
				'content' => 'Everything is going to hell.',
				'project_id' => 1,
			),
		);
		
		foreach($updates as $_)
		{
			$update = Update::create($_);	
			$update->attach(array(1));
		}
	}

	public static function threads()
	{
		$__ = array(
			array(
				'sender' => Settings::adminID(),
				'recipient' => Client::userID(1),
				'subject' => 'Hi John!',
				'content' => 'How are you doing?',
			),
		);
		
		foreach($__ as $_)
		{
			Message::create($_);	
		}
	}

	public static function events()
	{
		$__ = array(
			array(
				'project_id' => '1',
				'name' => 'First event',
				'start_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' + 3 days')),
				'end_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' + 7 days')),
				'details' => 'Details',
				'notes' => 'Notes',
			),
			array(
				'project_id' => '1',
				'name' => 'Second event',
				'start_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' + 6 days')),
				'end_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' + 8 days')),
				'details' => 'Details',
				'notes' => 'Notes',
			),
			array(
				'project_id' => '1',
				'name' => 'Third event',
				'start_date' => date('Y-m-d H:i:s'),
				'end_date' => date('Y-m-d H:i:s'),
				'details' => 'Details',
				'notes' => 'Notes',
			),
		);
		
		foreach($__ as $_)
		{
			Event::create($_);	
		}
	}

}

?>

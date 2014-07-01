var global_data = {};
var local_data;
jQuery(function($){

	$('input[name="aware-create-communication"]').click(function(){

		//$.post(ajaxurl, { action: 'my_action', whatever: '10' }, function(res) {
		var $form = $(this).closest('form');
		$.post(ajaxurl, $form.serialize(), function(res) {
			console.log(res);
			local_data = $.parseJSON(res);
			$form.slideUp(function(){
				global_data.email_data = local_data;
				console.log(res);
				if( local_data.action == 'redirect' ) window.location.href = local_data.location;
				if( typeof(local_data.response) != 'undefined' ) $('#submission-response').html($('<h3></h3>').html("Thank you! Your request has been handled successfully")).slideDown();
			});
		});

	});
	
	$('input[name="aware-update-settings"]').click(function(){
		var $form = $(this).closest('form');
		$form.find('.response').html('Updating settings...').show();
		$.post(ajaxurl, $form.serialize(), function(res) {
			//alert(res);
			$form.find('.response').html('Settings updated.').show();
			console.log(res);
		});
	});

	$('input[name="aware-update-client"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Updating client...').show();
		$.post(ajaxurl, $form.serialize(), function(res){
			console.log(res);
			location.reload();
			$form.find('.response').html('The client has been updated.').show();
		});
	});

	$('input[name="aware-add-client"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Adding client...').show();
		$.post(ajaxurl, $form.serialize(), function(res){
			console.log(res);
			location.reload();
		});
	});

	$('input[name="aware-delete-client"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Deleting client...').show();
		ID = $form.find('input[name="ID"]').val();
		$.post(ajaxurl, { action: 'admin_delete_client', ID: ID }, function(res){
			location.reload();
		});
	});

	$('input[name="aware-update-project"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Updating project...').show();
		$.post(ajaxurl, $form.serialize(), function(res){
			console.log(res);
			location.reload();
			$form.find('.response').html('The project has been updated.').show();
		});
	});

	$('input[name="aware-add-project"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Adding project...').show();
		$.post(ajaxurl, $form.serialize(), function(res){
			console.log(res);
			location.reload();
		});
	});

	$('input[name="aware-delete-project"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Deleting project...').show();
		ID = $form.find('input[name="ID"]').val();
		$.post(ajaxurl, { action: 'admin_delete_project', ID: ID }, function(res){
			location.reload();
		});
	});

	$('input[name="aware-update-event"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Updating event...').show();
		$.post(ajaxurl, $form.serialize(), function(res){
			console.log(res);
			//location.reload();
			$form.find('.response').html('The event has been updated.').show();
		});
	});

	$('input[name="aware-add-event"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Adding event...').show();
		$.post(ajaxurl, $form.serialize(), function(res){
			console.log(res);
			location.reload();
		});
	});

	$('input[name="aware-delete-event"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Deleting event...').show();
		ID = $form.find('input[name="ID"]').val();
		$.post(ajaxurl, { action: 'admin_delete_event', ID: ID }, function(res){
			location.reload();
		});
	});

});

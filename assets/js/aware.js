var global_data = {};
var local_data;
jQuery(function($){

	$('input[name="aware-create-communication"]').click(function(){

		var $form = $(this).closest('form');
		$.post(ajaxurl, $form.serialize(), function(res) {
			local_data = $.parseJSON(res);
			global_data.email_data = local_data;
			console.log(res);
			if( local_data.action == 'redirect' ) window.location.href = local_data.location;
			if( typeof(local_data.response) != 'undefined' ) $form.find('.response').html("Your message has been posted successfully").show();
		});

	});

	$('input[name="aware-post-reply"]').click(function(){

		var $form = $(this).closest('form');
		$.post(ajaxurl, $form.serialize(), function(res) {
			local_data = $.parseJSON(res);
			global_data.email_data = local_data;
			console.log(res);
			location.reload();
		});

	});
	
	$('input[name="aware-update-settings"]').click(function(){
		var $form = $(this).closest('form');
		$form.find('.response').html('Updating settings...').show();
		$.post(ajaxurl, $form.serialize(), function(res) {
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
			$(window).scrollTop(0);
			$form.find('.response').html('The project has been updated.').show();
		});
	});

	$('input[name="aware-add-project"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Adding project...').show();
		$.post(ajaxurl, $form.serialize(), function(res){
			console.log(res);
			location.reload();
			$(window).scrollTop(0);
		});
	});

	$('input[name="aware-delete-project"]').click(function(){
		$form = $(this).closest('form');
		$accordion = $(this).closest('.accordion-navigation');
		$form.find('.response').html('Deleting project...').show();
		ID = $form.find('input[name="ID"]').val();
		$.post(ajaxurl, { action: 'admin_delete_project', ID: ID }, function(res){
			$accordion.remove();	
			location.reload();
			$(window).scrollTop(0);
		});
	});

	$('input[name="aware-update-event"]').click(function(){
		$form = $(this).closest('form');
		$form.find('.response').html('Updating event...').show();
		$.post(ajaxurl, $form.serialize(), function(res){
			console.log(res);
			$form.find('.response').html('The event has been updated.').show();
			location.reload();
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

	$('input[name="duration"]').click(function(){
		$form = $(this).closest('form');
		if( $(this).val() == 3 ) 
			$form.find('.duration.custom').show();
		else 
			$form.find('.duration.custom').hide();
		if( $(this).val() == 1 ) {
			$form.find('select[name="start-hour"]').val('7');
			$form.find('select[name="start-minute"]').val('0');
			$form.find('select[name="start-suffix"]').val('AM');
			$form.find('select[name="end-hour"]').val('3');
			$form.find('select[name="end-minute"]').val('0');
			$form.find('select[name="end-suffix"]').val('PM');
		}
		if( $(this).val() == 2 ) {
			$form.find('select[name="start-hour"]').val('9');
			$form.find('select[name="start-minute"]').val('0');
			$form.find('select[name="start-suffix"]').val('AM');
			$form.find('select[name="end-hour"]').val('6');
			$form.find('select[name="end-minute"]').val('0');
			$form.find('select[name="end-suffix"]').val('PM');
		}
	});

	$('input[name="date-controller"]').change(function(){
		$form = $(this).closest('form');
		$form.find('input[name="start-date"], input[name="end-date"]').val($(this).val());
	});

});

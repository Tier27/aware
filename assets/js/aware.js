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
		$.post(ajaxurl, $form.serialize(), function(res) {
			console.log(res);
		});
	});

});

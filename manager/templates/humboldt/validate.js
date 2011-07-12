$(document).ready(function() {
	// validate signup form on keyup and submit
	var create_validator = $("#unl_ucbcn_event").validate({
		rules: {
			title: "required",
			__reverseLink_eventdatetime_event_idstarttime_1: "required"
		},
		messages: {
			title: "Enter a Title for your event",
			__reverseLink_eventdatetime_event_idstarttime_1: "Enter a Start Date"
		},
		errorPlacement: function(error, element) {
						error.appendTo( element.parent() );
				}
	});
	
	
	var login_validator = $("#event_login").validate({
		rules: {
			username: "required",
			password: "required"
		},
		messages: {
			username: "Enter your HSU User Name",
			password: "Enter your password"
		}
	});
	

});
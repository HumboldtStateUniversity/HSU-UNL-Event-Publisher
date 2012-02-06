$(document).ready(function() {

// hiding recurs until
var foundin = $('label:contains("Recurs Until")');
$(foundin).hide();
	
//create event view
var create_validator = $("#unl_ucbcn_event").validate({
	rules: {
		title: "required",
		__reverseLink_eventdatetime_event_idstarttime_1: "required", //startdate
		listingcontactname: "required",
		listingcontactemail: "required"  
	},
	messages: {
		title: "Enter a Title for your event",
		__reverseLink_eventdatetime_event_idstarttime_1: "Enter a Start Date",
		listingcontactname: "Please enter a contact name",
		listingcontactemail: "Please enter a contact email" 
	},
//make error output appear after all fields
		errorPlacement: function(error, element) {
						error.appendTo( element.parent() );
				}
	});
	
//manager login	
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

//subscribe to calendar	
	var create_validator = $("#unl_ucbcn_subscription").validate({
		rules: {
			automaticapproval: "required"
		},
		messages: {
			automaticapproval: "Please mark Yes or No"
		},
//make error output appear after all fields
		errorPlacement: function(error, element) {
						error.appendTo( element.parent() );
				}
	});

//create a new calendar
	var create_validator = $("#unl_ucbcn_calendar").validate({
		rules: {
			name: "required",
			shortname: "required"
		},
		messages: {
			name: "Enter a Name for your calendar",
			shortname: "Enter a Short Name for your calendar"
		}
	});

});
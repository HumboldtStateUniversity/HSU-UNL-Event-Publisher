<!doctype html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">

<title>Humboldt State <?php
if ($this->calendar->id != $GLOBALS['_UNL_UCBCN']['default_calendar_id']) {
    echo '| '.$this->calendar->name.' ';
}
?>| Events</title>

		<link rel="stylesheet" type="text/css" media="screen" href="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->uri; ?>templates/mobile/css/main.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->uri; ?>templates/mobile/jqm/jquery.ui.datepicker.mobile.css" />
		
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		
		<script>
		  //reset type=date inputs to text
		  $( document ).bind( "mobileinit", function(){
		    $.mobile.page.prototype.options.degradeInputs.date = true;
		  });	
		</script>				

		<script type="text/javascript" src="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.js"></script>
		<script type="text/javascript" src="<?php echo $this->uri; ?>templates/mobile/jqm/jquery.ui.datepicker.js"></script>
		<script type="text/javascript" src="<?php echo $this->uri; ?>templates/mobile/jqm/jquery.ui.datepicker.mobile.js"></script>

		<script>
		    $(function(){
		      // bind change event to select
		      $('#new-day').submit(function() {			
		          var url = $('#date').val(); // get selected value	
		          if (url) { // require a URL									
		              window.location.href = '<?php echo $this->uri ?>' + url; // redirect
		          }
		          return false;
		      });
		    });
		</script>
		
		
		
</head>


<body id="mobilecal">
		<div data-role="page">

		<div data-role="header" data-title="Humboldt State Events">
			<h1 class="wordmark"><img src="<?php echo $this->uri; ?>templates/mobile/css/wordmark.png" alt="Humboldt State Events" /></h1>
			
				<form data-ajax="false" id="event_search" name="event_search" method="get" action="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'search'=>'search')); ?>">
				<div data-role="fieldcontain">	
		    <input type='search' name='q' id='searchinput' alt='Search for events' value="<?php if (isset($_GET['q'])) { echo htmlentities($_GET['q']); } ?>" />
		    <!--<input type='submit' name='submit' value="Go" />-->
		    <input type='hidden' name='search' value='search' />
				</div>
			</form>
			
			</div>
    
		<div data-role="content">

				<a href="#two" data-role="button" data-rel="dialog">Select a date to view</a>

<?php if (isset($this->right)) { ?>
    <div id="updatecontent" class="three_col right">
    <?php UNL_UCBCN::displayRegion($this->output); ?>
    </div>
<?php } else {
    UNL_UCBCN::displayRegion($this->output);
} ?>
		</div>
		</div>
    <div data-role="footer"><p>Switch to <a rel="external" href="<?php echo $this->output[0]->uri; ?>?template=humboldt">standard site</a></p></div>

		</div>
		<div data-role="page" id="two">
			<div data-role="header"><h1>Select a date to view</h1></div>
			
			<div data-role="content">
			
			<form data-ajax="false" id="new-day" action="destination.html">
				<div data-role="fieldcontain">
					<label for="date" id="date-label">Change Date:</label>
					<input type="date" name="date" id="date" value=""  />
					<input type='submit' name='submit' value="Go" />
				</div>		
			</form>
			</div>
			</div>
</body>
</html>
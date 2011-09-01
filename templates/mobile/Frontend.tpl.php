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

		<link rel="stylesheet" type="text/css" media="screen" href="templates/mobile/jqm/jquery.mobile-1.0b2.min.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="templates/mobile/jqm/jquery.ui.datepicker.mobile.css" />

		
<link rel="alternate" type="application/rss+xml" title="<?php echo $this->calendar->name; ?> Events" href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'format'=>'rss')); ?>" />
</head>


<body id="mobilecal">
		<div data-role="page">

		<div data-role="header" data-title="Humboldt State Events">
			<h1>Humboldt State Events</h1>
			
			
			<form id="new-day" action="index.html">
				<div data-role="fieldcontain">
					<label for="date">Change Date:</label>
					<input type="date" name="date" id="date" value=""  />
					<input type='submit' name='submit' value="Go" />
				</div>		
			</form>			
			
		<form id="event_search" name="event_search" method="get" action="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'search'=>'search')); ?>">
    <input type='text' name='q' id='searchinput' alt='Search for events' value="<?php if (isset($_GET['q'])) { echo htmlentities($_GET['q']); } ?>" />
    <input type='submit' name='submit' value="Search" />
    <input type='hidden' name='search' value='search' />
	</form>
			</div>
    
		<div data-role="content">
<?php if (isset($this->right)) { ?>
    <div id="updatecontent" class="three_col right">
    <?php UNL_UCBCN::displayRegion($this->output); ?>
    </div>
<?php } else {
    UNL_UCBCN::displayRegion($this->output);
} ?>
		</div>

    <div data-role="footer"><p>Switch to <a href="<?php echo $this->output[0]->uri; ?>?template=humboldt">standard site</a></p></div>

		</div>
</body>
</html>
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

<?php if (isset($this->right)) { ?>body { 
	font-family: Helmet, Freesans, sans-serif;
}
img{
	max-width:100%;
}
#date, #date-label{
	display:none;
}
#one .ui-header .ui-title{
	text-align:left;
	padding:0;
	margin:15px 15px 0 15px;
}
#one .ui-header .ui-field-contain{
	margin:10px 15px 18px;
	padding:0;
}
#one .ui-header form{
	margin:0;
}
#one h1 span{
	border: 0;
	clip: rect(0 0 0 0);
	height: 1px;
	margin: -1px;
	overflow: hidden;
	padding: 0;
	position: absolute;
	width: 1px;
}
.wordmark{
	width:auto;
}
.vevent h3{
	margin:0;
}
.vevent{
	margin:10px 0;
	padding:10px 5px;
}
.alt{
	background-color: transparent;
  background-color: rgba(255, 255, 255, 0.4);
}
h4.sec_main {
  font-size:20px;
  font-weight: bold;
	margin:35px 0 0 0;
}
.day-buttons a{

}
a .date, a .location{
	color:#222;
	text-decoration:none;
	font-weight:normal;
}
a .summary{
	text-decoration:underline;
}
.ui-field-contain{
	border-bottom:0;
}

/* jquery mobile overrides */
.ui-header {
	border: 0px solid #bbb;
	border-bottom:1px solid #bbb;
	background:url(handmadepaper.png) repeat #6a9231;
	color: #222;
	font-weight: bold;
	text-shadow: 0 -1px 1px #fff;
	font-family: Helmet, Freesans, sans-serif;
	-moz-box-shadow: inset 0px -3px 5px rgba(0,0,0,.2);
	-webkit-box-shadow: inset 0px -3px 5px rgba(0,0,0,.2);
	box-shadow: inset 0px -3px 5px rgba(0,0,0,.2);
}
#two .ui-header{
	border: 1px solid 		#77a13b;
	background: 			#6a9231;
	color: 					#ffffff;
	font-weight: bold;
	text-shadow: 0 -1px 1px #416114;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#77a13b), to(#597e24)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #77a13b, #597e24); /* Chrome 10+, Saf5.1+ */
	background-image:    -moz-linear-gradient(top, #77a13b, #597e24); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #77a13b, #597e24); /* IE10 */
	background-image:      -o-linear-gradient(top, #77a13b, #597e24); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #77a13b, #597e24);
	font-family: Helmet, Freesans, sans-serif;
	-moz-box-shadow: none;
	-webkit-box-shadow: none;
	box-shadow: none;
}
.ui-content {
	background:url(exclusive_paper.jpg) repeat #597e24;
	color: #222;
	text-shadow: 0 1px 0 	#fff;
	font-weight: normal;
	font-family: Helmet, Freesans, sans-serif;
}
.ui-content .ui-link, .ui-footer .ui-link{
	color:#6a9231;
	text-decoration:none;
}
.ui-footer .ui-link{
	text-decoration:underline;
}
.ui-footer{
	border: 1px solid 		#fff;
	background: 			#fff;
	color: 					#222;
	font-weight: normal;
	text-shadow: 0 1px 0 	#fff;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#fff)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #fff, #fff); /* Chrome 10+, Saf5.1+ */
	background-image:    -moz-linear-gradient(top, #fff, #fff); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #fff, #fff); /* IE10 */
	background-image:      -o-linear-gradient(top, #fff, #fff); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #fff, #fff);
	padding-left:15px;
	font-family: Helmet, Freesans, sans-serif;
}

.ui-shadow-inset {
	-moz-box-shadow: inset 0px 0px 0px rgba(0,0,0,.2);
	-webkit-box-shadow: inset 0px 0px 0px rgba(0,0,0,.2);
	box-shadow: inset 0px 0px 0px rgba(0,0,0,.2);
}
.ui-focus{
	
}
.ui-bar-c{
	border: 1px solid #000;
	background: 		#fff;
	color: 					#3E3E3E;
	font-weight: bold;
	text-shadow: 0 1px 1px 	#fff;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#fff)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #fff, #fff); /* Chrome 10+, Saf5.1+ */
	background-image:    -moz-linear-gradient(top, #fff, #fff); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #fff, #fff); /* IE10 */
	background-image:      -o-linear-gradient(top, #fff, #fff); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #fff, #fff);	
}
.ui-header .ui-body-a{
	color:#222;
	border-color:#ccc;
	background-color:#fff;
}
/* btn */
.new-date{
	margin:0;
}
.ui-btn-up-c, .ui-btn-up-a{
	border: 1px solid #87ac52;
	background: #6a9231;
	font-weight: bold;
	color: #fff;
	text-shadow: 0 1px 1px #416114;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#87ac52), to(#6a9231)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #87ac52, #6a9231); /* Chrome 10+, Saf5.1+ */
	background-image:    -moz-linear-gradient(top, #87ac52, #6a9231); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #87ac52, #6a9231); /* IE10 */
	background-image:      -o-linear-gradient(top, #87ac52, #6a9231); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #87ac52, #6a9231);
}
tbody .ui-btn-up-c{
	border: 1px solid 		#ccc;
	background: 			#eee;
	font-weight: bold;
	color: 					#444;
	text-shadow: 0 1px 1px #f6f6f6;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#fdfdfd), to(#eee)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #fdfdfd, #eee); /* Chrome 10+, Saf5.1+ */
	background-image:    -moz-linear-gradient(top, #fdfdfd, #eee); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #fdfdfd, #eee); /* IE10 */
	background-image:      -o-linear-gradient(top, #fdfdfd, #eee); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #fdfdfd, #eee);	
}
tbody .ui-state-highlight{
	border: 1px solid 		#F7C942;
	background: 			#fadb4e;
	font-weight: bold;
	color: 					#333;
	text-shadow: 0 1px 0 	#fff;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#fceda7), to(#fadb4e)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #fceda7, #fadb4e); /* Chrome 10+, Saf5.1+ */
	background-image:    -moz-linear-gradient(top, #fceda7, #fadb4e); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #fceda7, #fadb4e); /* IE10 */
	background-image:      -o-linear-gradient(top, #fceda7, #fadb4e); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #fceda7, #fadb4e);
}
tbody .ui-state-active{
	border: 1px solid 		#145072;
	background: 			#2567ab;
	font-weight: bold;
	color: 					#fff;
	text-shadow: 0 -1px 1px #145072;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#5f9cc5), to(#396b9e)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #5f9cc5, #396b9e); /* Chrome 10+, Saf5.1+ */
	background-image:    -moz-linear-gradient(top, #5f9cc5, #396b9e); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #5f9cc5, #396b9e); /* IE10 */
	background-image:      -o-linear-gradient(top, #5f9cc5, #396b9e); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #5f9cc5, #396b9e);	
}
.ui-btn-up-c a.ui-link-inherit, .ui-btn-up-a a.ui-link-inherit {
	color: #fff;
}
.ui-btn-hover-c, .ui-btn-hover-a {
	border: 1px solid #bbb;
	background: #6a9231;
	font-weight: bold;
	color: #fff;
	text-shadow: 0 1px 1px #416114;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#9fc26c), to(#6a9231)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #9fc26c, #6a9231); /* Chrome 10+, Saf5.1+ */
	background-image:    -moz-linear-gradient(top, #9fc26c, #6a9231); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #9fc26c, #6a9231); /* IE10 */
	background-image:      -o-linear-gradient(top, #9fc26c, #6a9231); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #9fc26c, #6a9231);
}
.ui-btn-hover-c a.ui-link-inherit, .ui-btn-hover-a a.ui-link-inherit {
	color: #fff;
}
.ui-btn-down-c, .ui-btn-down-a {
	border: 1px solid #bbb;
	background: #6a9231;
	font-weight: bold;
	color: #fff;
	text-shadow: 0 1px 1px #416114;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#9fc26c), to(#6a9231)); /* Saf4+, Chrome */
	background-image: -webkit-linear-gradient(top, #6a9231, #6a9231); /* Chrome 10+, Saf5.1+ */
	background-image:    -moz-linear-gradient(top, #6a9231, #6a9231); /* FF3.6 */
	background-image:     -ms-linear-gradient(top, #6a9231, #6a9231); /* IE10 */
	background-image:      -o-linear-gradient(top, #6a9231, #6a9231); /* Opera 11.10+ */
	background-image:         linear-gradient(top, #6a9231, #6a9231);
}
.ui-btn-down-c a.ui-link-inherit, .ui-btn-down-a a.ui-link-inherit {
	color: #fff;
}
.ui-btn-up-c,
.ui-btn-hover-c,
.ui-btn-down-c {
	font-family: Helvetica, Arial, sans-serif;
	text-decoration: none;
}
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
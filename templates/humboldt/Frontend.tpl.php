<?php
if (!isset($GLOBALS['unl_template_dependents'])) {
	$GLOBALS['unl_template_dependents'] = $_SERVER['DOCUMENT_ROOT'];
}
?>
<!DOCTYPE html>


<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="copyright" content="Humboldt State University" />
<meta name="description" content="Humboldt State University: Learning to make a difference. A State University located in coastal Northern California." />
<meta name="keywords" content="HSU, Humboldt, Humboldt State, Humboldt State University, Humboldt State College, California State University, Arcata, California, academics, redwoods, coast, Humboldt County, Humbolt, lumberjack" />
<link rel="shortcut icon" href="http://www.humboldt.edu/humboldt/favicon.ico" />
<link rel="apple-touch-icon" href="http://www.humboldt.edu/humboldt/apple-touch-icon.png" />


<title>Humboldt State University <?php
if ($this->calendar->id != $GLOBALS['_UNL_UCBCN']['default_calendar_id']) {
    echo '| '.$this->calendar->name.' ';
}
?>| Events</title>
<!--styles & scripts for calendar content-->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->uri; ?>templates/humboldt/frontend_main.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->uri; ?>templates/humboldt/facebook.css" />
<script type="text/javascript" src="<?php echo $this->uri; ?>templates/humboldt/scripts/ajaxCaller.js"></script>
<script type="text/javascript" src="<?php echo $this->uri; ?>templates/humboldt/scripts/frontend.js"></script>
<script type="text/javascript" charset="utf-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $this->uri; ?>templates/humboldt/scripts/jquery.tools.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->uri; ?>templates/humboldt/scripts/easyselectbox.min.js"></script>

<script>
// execute your scripts when the DOM is ready. this is mostly a good habit
$(function() {
	// initialize scrollable
	$(".scrollable").scrollable({
		keyboard:false,
		circular:true
	});
});
//gives us the ability to style select box in a more flexible way
$(function(){
	$('#e').easySelectBox({speed:100});
});
</script>
<link rel="alternate" type="application/rss+xml" title="<?php echo $this->calendar->name; ?> Events" href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'format'=>'rss')); ?>" />
<link rel="search" href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'search'=>'search')); ?>" />
<?php 
if (isset($this->output[0], $this->output[0]->event)
    && $this->output[0]->event instanceof UNL_UCBCN_Event) {
	echo '<meta property="og:title" content="'. $this->output[0]->event->title .'"/>
          <meta property="og:site_name" content="'. $this->calendar->name .'"/> 
          <meta property="og:url" content="'. UNL_UCBCN::getBaseURL().$this->output[0]->url .'"/>
          <meta property="og:description" content="'. $this->output[0]->event->description .'" />';
}
?>
</head>
<body>
<div id="wrap">
			<div id="masthead"> 
				<div id="logowrapper"><a id="logo" href="http://www.humboldt.edu/"> 
					<span class="ir">Humboldt State University</span></a> 
				</div> <!--/logowrapper-->
			</div><!--/masthead-->

	<div id="maincontent">
		<div id="load"></div>
		<div id="banner" class="clearfix">
			<div id="banner-wrap">
				<h1 id="calname" ><a href="<?php echo $this->uri; ? class="maincal">"><span class="ir"><?php echo $this->calendar->name; ?></a></span></h1>
<!--search form-->
		<div id="main_search">
	      <form id="event_search" name="event_search" method="get" action="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'search'=>'search')); ?>">
	          <input type='text' name='q' id='searchinput' alt='Search for events' value="<?php if (isset($_GET['q'])) { echo htmlentities($_GET['q']); } ?>" />
	          <input type='submit' name='submit' value="Go" />
	          <input type='hidden' name='search' value='search' />
	      <p id="search_term">Search smartly: In addition to normal keyword search, you can also search with chronological terms such as 'tomorrow', 'Monday' and etc.
	      <a href="#" title="close search tip">(close message)</a>
	      </p>
	      </form>
	      <p><a href="<?php echo $this->uri; ?>manager" title="Submit an event" class="event_submit top">Submit an event</a></p>
      </div><!--/main-search-->
				
	<!-- featured events start-->
	      <?php	
	           if ($this->uri == 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] OR
	               $this->uri == 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']){ // Only show on main calendar page                      
	                    include_once("featured.php");
	                    print featured();
	              }
	        ?>
		</div><!-- /banner-wrap -->
		</div><!-- /banner -->
          <div id="content-wrap">
          <div id="content-top"></div>
          <div class="column-wrap">
	          <div id="view_nav" class="clearfix">
          
		          <!-- event navigation  -->			
          			<ul id="frontend_view_selector" class="<?php echo $this->view; ?>">    
          			    <li id="todayview"><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id)); ?>">Today</a></li>
          			    <li id="upcomingview"><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'upcoming'=>'upcoming')); ?>">Upcoming</a></li>
          			    <li id="monthview"><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('y'=>date('Y'),'m'=>date('m'),'calendar'=>$this->calendar->id)); ?>">Month</a></li>
          			    <li id="yearview"><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('y'=>date('Y'),                                                                                   'calendar'=>$this->calendar->id)); ?>">Year</a></li>
          			</ul>			
          			<!--Event type select-->
          			<div id="eventtypes">
                        <?php if( $eventlist = UNL_UCBCN_Frontend::getEventTypes()): ?>
          	  <form action="<?php echo UNL_UCBCN_Frontend::formatURL(array('search'=>'search')) ?>" method="get">
                            <select name="e" id="e"> 
                            <?php while ($eventlist->fetch()): ?>
                                <option value='<?php echo "$eventlist->id'>$eventlist->name"; ?></option>\n
                            <?php endwhile; ?>
          
                            </select><input type="submit" value="Go" id="event_type_go">
                        </form>
                        <?php endif; ?>
                       </div><!--/eventtypes-->
                   </div><!--/view_nav-->
     <?php if (isset($this->right)) { ?>
                 
      <div class="col left">
  
		<div id="monthwidget" class="minical">
			<?php UNL_UCBCN::displayRegion($this->right); ?>
		</div><!--/monthwidget-->
		<div id="subscribe">
          <h3>Take events with you</h3>
          	<p>Get HSU events on you calendar or in your rss reader.</p>
          	<ul>
          		<li id="eventical"><a href="https://its-caldev.humboldt.edu/unlcal/upcoming/?format=ics&amp;limit=100" title="ical format" class="icon-ical">Google Cal, iCal, or Outlook</a></li>		
          		<li id="eventrss"><a href="https://its-caldev.humboldt.edu/unlcal/upcoming/?format=rss&amp;limit=100" title="RSS feed" class="icon-rss">RSS</a></li>
          	</ul>
         </div><!--/subscribe-->
     	<div id="othercals">
     	<h3>More Calendars</h3>
     		<ul>
     			<li>&raquo; <a href="http://humboldt.edu/centeractivities" title="CenterActivities calendar">CenterActivities calendar</a></li>
     			<li>&raquo; <a href="http://www.humboldt.edu/humboldt/hsuAcademicCalendar2011-12.pdf" >Academic Calendar 2011-12</a></li>
     			<li>&raquo; <a href="http://humboldt.edu/reg/pdf/CalendarOfActivitiesSUM11.pdf" >Activities & Deadlines – Summer 2011</a></li>
     			<li>&raquo; <a href="http://pine.humboldt.edu/reg/pdf/CalendarOfActivitiesF11.pdf" >Activities & Deadlines – Fall 2011</a></li>
     			<li>&raquo; <a href="http://pine.humboldt.edu/reg/pdf/FinalExam_Fall2011.pdf" >Final Exam Schedule for Fall 2011</a></li>
     			<li>&raquo; <a href="http://www.humboldt.edu/humboldt/images/uploads/greenAndGoldCalendar_2011-2012.pdf" >Green & Gold Calendar 2011-2012</a></li>
     				<li>&raquo; <a href="http://www.northcoastjournal.com/calendar/">Events in the Community</a></li>
     			
     		</ul>
     	</div><!--/othercals-->
		<p><a href="<?php echo $this->uri; ?>manager" title="Submit an event" class="event_submit">Submit an event</a></p>
       </div><!--/col left-->
      <div id="updatecontent" class="three_col right">
			
                <?php UNL_UCBCN::displayRegion($this->output); ?>
              </div>
              
          <?php } else {
              UNL_UCBCN::displayRegion($this->output);
          } ?>
      <!-- InstanceEndEditable -->
      <div class="clear"></div>
      <?php include_once $GLOBALS['unl_template_dependents'].'/wdn/templates_3.0/includes/noscript.html'; ?>
      <!--THIS IS THE END OF THE MAIN CONTENT AREA.-->
</div><!-- /column-wrap -->
</div><!-- /column-top -->

</div><!-- /content-wrap -->


</div><!-- /wrap -->

<div id="site-meta">
	<div id="meta-wrap" class="clearfix">
		<div class="article first">
			<h1>Have Questions or comments?</h1>
			<p><a href="http://humboldt.edu/web/feedback" title="">Drop us a line</a> and we will get back to you as soon as we can.</p>		
		</div><!--/article-->
		<div class="article">
			<h1>About this Calendar</h1>
			<p>If you ware wondering how to <a href="#" title="">submit events, get event content on to your own site or subscribe</a> to certain event feeds our guide should get you going.</p>
		</div><!--/article-->
		<div class="article last">
		<div class="socialnetworks"><a href="http://www.facebook.com/humboldtstatealumni" title="facebook"><img src="http://www.humboldt.edu/humboldt/images2010/facebook.png" alt="Facebook" width="18px" height="18px"></a> <a href="http://www.flickr.com/photos/humboldtstate" title="flickr"><img src="http://www.humboldt.edu/humboldt/images2010/flickr.png" alt="Flickr" width="18px" height="18px"></a> <a href="http://www.linkedin.com/groups?home=&amp;gid=2772964&amp;trk=anet_ug_hm" title="linkedin"><img src="http://www.humboldt.edu/humboldt/images2010/linkedin.png" alt="LinkedIn"></a> <a href="http://www.twitter.com/humboldtstate/" title="twitter"><img src="http://www.humboldt.edu/humboldt/images2010/twitter.png" alt="Twitter" width="18px" height="18px"></a> <a href="http://www.youtube.com/humboldtonline" title="youtube"><img src="http://www.humboldt.edu/humboldt/images2010/youtube.png" alt="Youtube" width="18px" height="18px"></a></div>
			<div class="vcard">
				<a class="url" href="http://www.humboldt.edu"></a>
			
				<div class="org fn">Humboldt State University<br />a California State University</div>
				<div class="adr">
					<div class="street-address">1 Harpst Street</div>
					<span class="locality">Arcata</span>, 
					<span class="region">CA</span>
					<span class="postal-code">95521</span>
			
				</div>
				<div class="tel">(707) 826-3011</div>
				<div class="contact"><a href="http://humboldt.edu/web/feedback">Contact us</a></div>
			</div>						
		</div><!--/article-->

</div><!-- /meta-wrap -->

</div><!-- /site-meta -->
  
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script><script type="text/javascript">
//<![CDATA[
_uacct = "UA-133335-1";
urchinTracker();
//]]>

</script>

</body>
</html>

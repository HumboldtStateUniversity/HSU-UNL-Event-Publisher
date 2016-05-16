<?php
if (!isset($GLOBALS['unl_template_dependents'])) {
	$GLOBALS['unl_template_dependents'] = $_SERVER['DOCUMENT_ROOT'];
}
?>
<!DOCTYPE html>
<html lang="en">
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
          <meta property="og:url" content="'. $this->output[0]->url .'"/>
          <meta property="og:description" content="'. $this->output[0]->event->description .'" />
          <meta property="og:type" content="activity" />
          <meta property="fb:admins" content="52303196" />';
    if (isset($this->output[0]->event->imagedata)){
        echo "\n" . '<meta property="og:image" content="' . $this->uri . '?image&id=' . $this->output[0]->event->id . '" />';
    } else {
        echo "\n" . '<meta property="og:image" content="http://www.humboldt.edu/humboldt/apple-touch-icon.png" />';
    }
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
		<?php 
		    if ($this->calendar->id == '1'){ // Only show on main calendar 
		        echo '<h1 id="calname"><a class="maincal" href="'.$this->uri.'"><span class="ir">'.$this->calendar->name.'</span></a></h1>';
		    } else {
		        echo '<h1 id="calname"><a href="'.$this->uri.'?calendar_id='.$this->calendar->id.'">'.$this->calendar->name.'</a></h1>';
		    }
		?>
                
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
	        <?php if ($this->calendar->id == 1 && $this->view == 'day') echo UNL_UCBCN_Frontend::featured(); ?>
		</div><!-- /banner-wrap -->
		</div><!-- /banner -->
          <div id="content-wrap">
          <div id="content-top"></div>
          <div class="column-wrap" id="top">
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
                            <option disabled="disabled">-- Show Events by Type --</option>
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
          	<p>Get HSU events on your calendar or in your rss reader.</p>
          	<ul>
          		<li id="eventical"><a href="<?php echo $this->uri; ?>upcoming/?format=ics&amp;limit=100" title="ical format" class="icon-ical">Google Cal, iCal, or Outlook</a></li>		
          		<li id="eventrss"><a href="<?php echo $this->uri; ?>upcoming/?format=rss&amp;limit=100" title="RSS feed" class="icon-rss">RSS</a></li>
          	</ul>
         </div><!--/subscribe-->
     	<div id="othercals">
     	<h3>More Calendars</h3>
     		<ul>
     			<li>&raquo; <a href="http://humboldt.edu/centeractivities" title="CenterActivities calendar">CenterActivities calendar</a></li>
     			<li>&raquo; <a href="http://humboldt.edu/centerarts/" title="Center Arts calendar">Center Arts calendar</a></li>

     			<!-- <li>&raquo; <a href="http://www.humboldt.edu/humboldt/2013-14AcademicCalendar.pdf" >Academic Calendar 2013-14</a></li> -->
     			<li>&raquo; <a href="http://www.humboldt.edu/humboldt/hsuAcademicCalendar2014-15-Draft.pdf" >Academic Calendar 2014-15</a></li>
     			<li>&raquo; <a href="http://www.humboldt.edu/sites/default/files/hsuacademiccalendar2015-16.pdf" >Academic Calendar 2015-16</a></li>
     			<li>&raquo; <a href="http://www.humboldt.edu/sites/default/files/hsuacademiccalendar2016-17.pdf" >Academic Calendar 2016-17</a></li>
									
                            <li>&raquo; <a href="http://www.humboldt.edu/reg/pdf/CalendarOfActivitiesS16.pdf" >Activities &amp; Deadlines – Spring 2016</a></li>
							<li>&raquo; <a href="http://www.humboldt.edu/reg/pdf/CalendarOfActivitiesF16.pdf" >Activities &amp; Deadlines – Fall 2016</a></li>
							<li>&raquo; <a href="http://www.humboldt.edu/reg/pdf/FinalExam_Spring2016.pdf">Final Exam Schedule – Spring 2016</a></li>
							
							<li>&raquo; <a href="http://www.humboldt.edu/reg/pdf/FinalExam_Fall2016.pdf">Final Exam Schedule – Fall 2016</a></li>
                                    
     			
				<li>&raquo; <a href="http://www.humboldt.edu/humboldt/images/uploads/greenAndGoldCalendar2014-2015.pdf" >Green &amp; Gold Calendar 2014-2015</a></li>
     			<li>&raquo; <a href="http://www.humboldt.edu/sites/default/files/greenandgoldcalendar2015-2016.pdf" >Green &amp; Gold Calendar 2015-2016</a></li>
				<li>&raquo; <a href="http://www.humboldt.edu/sites/default/files/greenandgoldcalendar2016-2017.pdf" >Green &amp; Gold Calendar 2016-2017</a></li>
							
				<li>&raquo; <a href="http://www.northcoastjournal.com/calendar/">Events in the Community</a></li>
     			
     		</ul>
     	</div><!--/othercals-->
     	
     	<div id="maps">
     		<a href="http://humboldt.edu/maps">Campus Maps</a>   			
     	</div><!--/maps-->

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
      <!--THIS IS THE END OF THE MAIN CONTENT AREA.-->
</div><!-- /column-wrap -->
</div><!-- /column-top -->

</div><!-- /content-wrap -->


</div><!-- /wrap -->

<div id="site-meta">
	<div id="meta-wrap" class="clearfix">
		<div class="article first">
			<h1>Have Questions, comments, or feedback?</h1>			
			<p>We have been working hard on this calendar but it might still be a bit rough around
			the edges. Please <a href="http://humboldt.edu/web/feedback/events">tell us</a> how we can improve your experience.</p>		
		</div><!--/article-->
		<div class="article">
			<h1>About this Calendar</h1>
			<p>If you were wondering how to <a href="http://humboldt.edu/web/event-help">submit events, get event content on to your own site or subscribe</a> to certain event feeds, our guide should get you going.</p>
		</div><!--/article-->
		<div class="article last">
		<div class="socialnetworks"><a href="http://www.facebook.com/humboldtstatealumni" title="facebook"><img src="http://www.humboldt.edu/humboldt/images2010/facebook.png" alt="Facebook" width="18px" height="18px"></a> <a href="http://www.flickr.com/photos/humboldtstate" title="flickr"><img src="http://www.humboldt.edu/humboldt/images2010/flickr.png" alt="Flickr" width="18px" height="18px"></a> <a href="http://www.linkedin.com/groups?home=&amp;gid=2772964&amp;trk=anet_ug_hm" title="linkedin"><img src="http://www.humboldt.edu/humboldt/images2010/linkedin.png" alt="LinkedIn"></a> <a href="http://www.twitter.com/humboldtstate/" title="twitter"><img src="http://www.humboldt.edu/humboldt/images2010/twitter.png" alt="Twitter" width="18px" height="18px"></a> <a href="http://www.youtube.com/humboldtonline" title="youtube"><img src="http://www.humboldt.edu/humboldt/images2010/youtube.png" alt="Youtube" width="18px" height="18px"></a></div>
			<div class="vcard">
				<a class="url" href="http://www.humboldt.edu"></a>
			
				<!--<div class="org fn">Humboldt State University<br />a California State University</div>-->
				<div class="adr">
					<div class="street-address">1 Harpst Street</div>
					<span class="locality">Arcata</span>, 
					<span class="region">CA</span>
					<span class="postal-code">95521</span>
			
				</div>
				<div class="tel">(707) 826-3321</div>
				<div class="contact"><a href="http://humboldt.edu/web/feedback">Contact us</a></div>
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
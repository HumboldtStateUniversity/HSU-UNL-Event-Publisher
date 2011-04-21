<?php
if (!isset($GLOBALS['unl_template_dependents'])) {
	$GLOBALS['unl_template_dependents'] = $_SERVER['DOCUMENT_ROOT'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
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
<!--HSU mainsite stylesheets & scripts-->
<style type="text/css"  media="screen,projection">
@import "http://www.humboldt.edu/humboldt/styles/interior.css";
</style>
<link rel="stylesheet" type="text/css" href="http://www.humboldt.edu/humboldt/styles/print.css" media="print" />
<script src="http://www.humboldt.edu/humboldt/scripts/main.js" type="text/javascript"></script>

<!--styles & scripts for calendar content-->

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->uri; ?>templates/humboldt/frontend_main.css" />
<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="http://www.humboldt.edu/humboldt/styles/ie.css" media="screen" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="http://www.humboldt.edu/humboldt/styles/ie7.css" media="screen" /><![endif]-->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->uri; ?>templates/humboldt/facebook.css" />
<script type="text/javascript" src="<?php echo $this->uri; ?>templates/humboldt/ajaxCaller.js"></script>
<script type="text/javascript" src="<?php echo $this->uri; ?>templates/humboldt/frontend.js"></script>
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
<body class="col4-0">
<div id="wrap">
<div id="container">

<p id="branding"><a href="http://www.humboldt.edu"><img src="http://www.humboldt.edu/humboldt/images/interiorwordmark.jpg" alt="Humboldt State University" /></a></p>

<div id="content">
<div id="contentwrap">
<div id="page-meta">

<span></span>
<h1><?php echo $this->calendar->name; ?> Events</h1>

        <?php include_once $GLOBALS['unl_template_dependents'].'/wdn/templates_3.0/includes/wdnTools.html'; ?>

</div>
<div id="maincontent">
<div id="load"></div>
<!--search form-->
      <form id="event_search" name="event_search" method="get" action="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'search'=>'search')); ?>">
          <input type='text' name='q' id='searchinput' alt='Search for events' value="<?php if (isset($_GET['q'])) { echo htmlentities($_GET['q']); } ?>" />
          <input type='submit' name='submit' value="Search" />
          <input type='hidden' name='search' value='search' />
      <!--<p id="search_term">Search smartly: In addition to normal keyword search, you can also search with chronological terms such as 'tomorrow', 'Monday' and etc.
      <a href="#" title="close search tip">(close message)</a>
      </p>-->
<!-- featured events start-->
      <?php	
              if ($this->calendar->id == 1){ // Only show on main calendar
                      include_once "featured.php";
                      print featured();
              }
        ?>
      </form>

      

          <?php if (isset($this->right)) { ?>
              <div class="col left">

                

  
		<div id="monthwidget"><?php UNL_UCBCN::displayRegion($this->right); ?></div>
<!--Event type select-->
                  <?php
                  $eventlist = UNL_UCBCN_Frontend::factory('eventtype');
                  $eventlist->orderBy('name ASC');
                  if($eventlist->find()){
                      $output = "<form action='".$this->uri . "search/' method='GET'><select name='q'>\n";
                      while ($eventlist->fetch()) {
                          $output .= "<option value='$eventlist->name'>$eventlist->name</option>\n";
                      }
                  $output .= '</select><input type="submit"></form>';
                  echo $output;
                  }
                  ?>
                  Z
              
              </div>
              <div id="updatecontent" class="three_col right">
              <!-- event navigation  -->
            <ul id="frontend_view_selector" class="<?php echo $this->view; ?>">
                <li id="todayview"><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id)); ?>">Today's Events</a></li>
                <li id="monthview"><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('y'=>date('Y'),
                                                                                            'm'=>date('m'),
                                                                                            'calendar'=>$this->calendar->id)); ?>">This Month</a></li>
                <li id="yearview"><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('y'=>date('Y'),
                                                                                          'calendar'=>$this->calendar->id)); ?>">This Year</a></li>
                <li id="upcomingview"><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,
                                                                                              'upcoming'=>'upcoming')); ?>">Upcoming</a></li>
            </ul>
              <?php UNL_UCBCN::displayRegion($this->output); ?>
              </div>
              
          <?php } else {
              UNL_UCBCN::displayRegion($this->output);
          } ?>
      <!-- InstanceEndEditable -->
      <div class="clear"></div>
      <?php include_once $GLOBALS['unl_template_dependents'].'/wdn/templates_3.0/includes/noscript.html'; ?>
      <!--THIS IS THE END OF THE MAIN CONTENT AREA.-->
</div>




</div><!-- /contentwrap -->
</div><!-- /content -->
</div><!-- /container -->
 </div><!-- /wrap -->

<div id="site-meta">
<div id="meta-wrap">


<!-- Google CSE Search Box Begins -->

<form action="http://www.humboldt.edu/humboldt/search/" id="search">
    <input type="hidden" name="cx" value="016116879625100262331:h29hmmqiar8" />
    <input type="hidden" name="cof" value="FORID:11" />
    <input type="text" name="q" size="15" value="Search" />
    <input type="image" src="http://www.humboldt.edu/humboldt//images/submit.gif" value="Go" name="sa" class="submit" alt="go" />
</form>
<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&lang=en"></script>
<!-- Google CSE Search Box Ends -->
        
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
</div>

<ul id="contact">
<li><a href="http://www.humboldt.edu/humboldt/contact">Contact Us.</a></li>
<li>|</li>
<li><a href="mailto:dmca@humboldt.edu">DMCA.</a></li>
</ul>

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

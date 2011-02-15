<?php
if (!isset($GLOBALS['unl_template_dependents'])) {
	$GLOBALS['unl_template_dependents'] = $_SERVER['DOCUMENT_ROOT'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><!-- InstanceBegin template="/Templates/php.fixed.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!--
    Membership and regular participation in the UNL Web Developer Network
    is required to use the UNL templates. Visit the WDN site at 
    http://wdn.unl.edu/. Click the WDN Registry link to log in and
    register your unl.edu site.
    All UNL template code is the property of the UNL Web Developer Network.
    The code seen in a source code view is not, and may not be used as, a 
    template. You may not use this code, a reverse-engineered version of 
    this code, or its associated visual presentation in whole or in part to
    create a derivative work.
    This message may not be removed from any pages based on the UNL site template.
    
    $Id: php.fixed.dwt.php 536 2009-07-23 15:47:30Z bbieber2 $
-->
<link rel="stylesheet" type="text/css" media="screen" href="/wdn/templates_3.0/css/all.css" />
<link rel="stylesheet" type="text/css" media="print" href="/wdn/templates_3.0/css/print.css" />
<script type="text/javascript" src="/wdn/templates_3.0/scripts/all.js"></script>
<?php include_once $GLOBALS['unl_template_dependents'].'/wdn/templates_3.0/includes/browserspecifics.html'; ?>
<?php include_once $GLOBALS['unl_template_dependents'].'/wdn/templates_3.0/includes/metanfavico.html'; ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>UNL <?php
if ($this->calendar->id != $GLOBALS['_UNL_UCBCN']['default_calendar_id']) {
    echo '| '.$this->calendar->name.' ';
}
?>| Events</title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->uri; ?>templates/default/frontend_main.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->uri; ?>templates/default/facebook.css" />
<script type="text/javascript" src="<?php echo $this->uri; ?>templates/default/ajaxCaller.js"></script>
<script type="text/javascript" src="<?php echo $this->uri; ?>templates/default/frontend.js"></script>
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
<!-- InstanceEndEditable -->
</head>
<body class="fixed events">

<p class="skipnav"> <a class="skipnav" href="#maincontent">Skip Navigation</a> </p>
<div id="wdn_wrapper">
    <div id="header"> <a href="http://www.unl.edu/" title="UNL website"><img src="/wdn/templates_3.0/images/logo.png" alt="UNL graphic identifier" id="logo" /></a>
        <h1>University of Nebraska&ndash;Lincoln</h1>
        <?php include_once $GLOBALS['unl_template_dependents'].'/wdn/templates_3.0/includes/wdnTools.html'; ?>
    </div>
    <div id="wdn_navigation_bar">
        <div id="breadcrumbs">
            <!-- WDN: see glossary item 'breadcrumbs' -->
            <!-- InstanceBeginEditable name="breadcrumbs" -->
            <ul>
                <li><a href="http://www.unl.edu/" title="University of Nebraska&ndash;Lincoln">UNL</a></li>
                <?php
                if (!empty($this->calendar->website) && ($this->calendar->id != $GLOBALS['_UNL_UCBCN']['default_calendar_id'])) {
                    echo '<li><a href="'.$this->calendar->website.'">'.$this->calendar->name.'</a></li>';
                }
                ?>
                <li>Events</li>
            </ul>
            <!-- InstanceEndEditable --></div>
        <div id="wdn_navigation_wrapper">
            <div id="navigation"><!-- InstanceBeginEditable name="navlinks" -->
                <!-- InstanceEndEditable --></div>
        </div>
    </div>
    <div id="wdn_content_wrapper">
        <div id="titlegraphic"><!-- InstanceBeginEditable name="titlegraphic" -->
            <h1><?php echo $this->calendar->name; ?> Events</h1>
            <!-- InstanceEndEditable --></div>
        <div id="pagetitle"><!-- InstanceBeginEditable name="pagetitle" --> <!-- InstanceEndEditable --></div>
        <div id="maincontent">
            <!--THIS IS THE MAIN CONTENT AREA; WDN: see glossary item 'main content area' -->
            <!-- InstanceBeginEditable name="maincontentarea" -->
            <div id="load"></div>
            <form id="event_search" name="event_search" method="get" action="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'search'=>'search')); ?>">
                <input type='text' name='q' id='searchinput' alt='Search for events' value="<?php if (isset($_GET['q'])) { echo htmlentities($_GET['q']); } ?>" />
                <input type='submit' name='submit' value="Search" />
                <input type='hidden' name='search' value='search' />
            <p id="search_term">Search smartly: In addition to normal keyword search, you can also search with chronological terms such as 'tomorrow', 'Monday' and etc.
            <a href="#" title="close search tip">(close message)</a>
            </p>
            
            </form>
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
                <?php if (isset($this->right)) { ?>
                    <div class="col left">
                        <div id="monthwidget"><?php UNL_UCBCN::displayRegion($this->right); ?></div>
                        <div class="cal_widget">
                        <h3>Contribute/Learn More</h3>
                        <ul>
                        <li id="login_list"><a id="frontend_login" href="<?php echo $this->manageruri; ?>">Submit an Event</a> </li>
                        <li><a href="http://www1.unl.edu/wdn/wiki/UNL_Calendar_Documentation">Learn More</a></li>
                        <li><a href="http://www1.unl.edu/comments/">Provide Feedback</a> </li>
                        </ul></div>
                        
                            
      <div id="subscribe" onmouseover="if(!g_bH){document.getElementById('droplist').style.display='block';}" onmouseout="if(!g_bH){document.getElementById('droplist').style.display='none';}">
        <span>Subscribe to this calendar</span> 
        <ul id="droplist">
          <li id="eventrss"><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'upcoming'=>'upcoming','format'=>'rss')); ?>&amp;limit=100" title="RSS feed">RSS feed</a></li>
          <li id="eventical"><a href="<?php echo UNL_UCBCN_Frontend::formatURL(array('calendar'=>$this->calendar->id,'upcoming'=>'upcoming','format'=>'ics')); ?>&amp;limit=100" title=".ical format">.ical format</a></li>
          </ul>
      </div>
                    
                    </div>
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
        </div>
        <div id="footer">
            <div id="footer_floater"></div>
            <div class="footer_col">
                <?php include_once $GLOBALS['unl_template_dependents'].'/wdn/templates_3.0/includes/feedback.html'; ?>
            </div>
            <div class="footer_col"><!-- InstanceBeginEditable name="leftcollinks" -->
                <h3>Related Links</h3>
                <ul>
                    <li><a href="http://code.google.com/p/unl-event-publisher/">UNL Event Publisher</a></li>
                </ul>
                <!-- InstanceEndEditable --></div>
            <div class="footer_col"><!-- InstanceBeginEditable name="contactinfo" -->
                <h3>Contacting Us</h3>
                <p><strong>University of Nebraska-Lincoln</strong><br />
                1400 R Street<br />
                Lincoln, NE 68588 <br />
                402-472-7211</p>
                <!-- InstanceEndEditable --></div>
            <div class="footer_col">
                <?php include_once $GLOBALS['unl_template_dependents'].'/wdn/templates_3.0/includes/socialmediashare.html'; ?>
            </div>
            <!-- InstanceBeginEditable name="optionalfooter" --> <!-- InstanceEndEditable -->
            <div id="wdn_copyright"><!-- InstanceBeginEditable name="footercontent" -->
                Yeah, it's open source. &copy; <?php echo date('Y'); ?> University of Nebraska&ndash;Lincoln
            <a href="http://www1.unl.edu/comments/" title="Click here to direct your comments and questions">comments?</a>
                <!-- InstanceEndEditable -->
                <?php include_once $GLOBALS['unl_template_dependents'].'/wdn/templates_3.0/includes/wdn.html'; ?>
                | <a href="http://validator.unl.edu/check/referer">W3C</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS</a> <a href="http://www.unl.edu/" title="UNL Home" id="wdn_unl_wordmark"><img src="/wdn/templates_3.0/css/footer/images/wordmark.png" alt="UNL's wordmark" /></a> </div>
        </div>
    </div>
    <div id="wdn_wrapper_footer"> </div>
</div>
</body>
<!-- InstanceEnd --></html>

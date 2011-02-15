<?php
header('Content-Type:text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><!-- InstanceBegin template="/Templates/document.dwt" codeOutsideHTMLIsLocked="false" -->
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
    
    $Id: document.dwt 536 2009-07-23 15:47:30Z bbieber2 $
-->
<link rel="stylesheet" type="text/css" media="screen" href="/wdn/templates_3.0/css/all.css" />
<link rel="stylesheet" type="text/css" media="print" href="/wdn/templates_3.0/css/print.css" />
<script type="text/javascript" src="/wdn/templates_3.0/scripts/all.js"></script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/wdn/templates_3.0/includes/browserspecifics.html'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/wdn/templates_3.0/includes/metanfavico.html'; ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $this->doctitle; ?></title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" type="text/css" media="screen" href="templates/default/manager_main.css" />
<link rel="stylesheet" type="text/css" media="screen" href="templates/default/dialog/dialog_box.css" />
<script type="text/javascript" src="templates/default/manager.js"></script>
<script type="text/javascript" src="templates/default/dialog/dialog_box.js"></script>
<?php
if (isset($this->user)) {
    echo '<script type="text/javascript">
            try {
                WDN.idm.logoutURL = "'.$this->uri.'?logout=true";
                WDN.idm.displayNotice("'.$this->user->uid.'");
            } catch(e) {}
          </script>';
}
?>
<!-- InstanceEndEditable -->
</head>
<body class="secure fixed document" <?php echo $this->uniquebody; ?>>
<p class="skipnav"> <a class="skipnav" href="#maincontent">Skip Navigation</a> </p>
<div id="wdn_wrapper">
    <div id="header"> <a href="http://www.unl.edu/" title="UNL website"><img src="/wdn/templates_3.0/images/logo.png" alt="UNL graphic identifier" id="logo" /></a>
        <h1>University of Nebraska&ndash;Lincoln</h1>
        <?php if (isset($this->user)) {
            UNL_UCBCN::displayRegion($this->calendarselect);
        } ?>
    </div>
    <div id="wdn_navigation_bar">
        <div id="breadcrumbs">
            <!-- WDN: see glossary item 'breadcrumbs' -->
            <!-- InstanceBeginEditable name="breadcrumbs" -->
            <ul>
                <li><a href="http://www.unl.edu/" title="University of Nebraska&ndash;Lincoln">UNL</a></li>
                <li>Events</li>
            </ul>
            <!-- InstanceEndEditable --></div>
        <div id="wdn_navigation_wrapper">
            <div id="navigation"></div>
        </div>
    </div>
    <div id="wdn_content_wrapper">
        <div id="titlegraphic"><!-- InstanceBeginEditable name="titlegraphic" -->
            <h1><?php
                if (isset($this->calendar)) { 
                    echo $this->calendar->name;
                } else {
                    echo 'UNL\'s Event Publishing System';
                }
                ?></h1>
            <!-- InstanceEndEditable --></div>
        <div id="pagetitle"><!-- InstanceBeginEditable name="pagetitle" --> <!-- InstanceEndEditable --></div>
        <div id="maincontent">
            <!--THIS IS THE MAIN CONTENT AREA; WDN: see glossary item 'main content area' -->
            <!-- InstanceBeginEditable name="maincontentarea" -->
            <div class="col left">
                <?php if (isset($this->user)) { ?>
                <div id="navlinks">
                    <ul>
                        <li id="mycalendar"><a href="<?php echo $this->uri; ?>?" title="My Calendar">Pending Events</a></li>
                        <li id="create"><a href="<?php echo $this->uri; ?>?action=createEvent" title="Create Event">Create Event</a></li>
                        <li id="subscribe"><a href="<?php echo $this->uri; ?>?action=subscribe" title="Subscribe">Subscribe</a></li>
                        <!--  <li id="import"><a href="<?php echo $this->uri; ?>?action=import" title="Import/Export">Import/Export</a></li> -->
                    </ul>
                </div>
                <div class="cal_widget">
                    <h3><span><?php echo date("F jS, Y"); ?></span></h3>
                    <ul>
                    <li class="nobullet">Welcome, <?php echo $this->user->uid; ?></li>
                    <li><a href="<?php echo $this->frontenduri.'?calendar_id='.$this->calendar->id; ?>">Live Calendar</a></li>
                    <li><a href="<?php echo $this->uri; ?>?action=account">Account Info</a></li>
                    <li><a href="<?php echo $this->uri; ?>?action=calendar">Calendar Info</a></li>
                    <li><a href="<?php echo $this->uri; ?>?action=users">Users &amp; Permissions</a></li>
                    <li><a href="<?php echo $this->uri; ?>?logout=true">LogOut</a></li>
                    </ul>
                </div>
                <?php
                }

                if (!empty($this->plugins)) {
                    echo '  <div class="cal_widget"><h3>Plugins</h3><ul>';
                    foreach ($this->plugins as $plugin) {
                        echo '<li><a href="'.$plugin->uri.'">'.$plugin->name.'</a></li>';
                    }
                    echo '</ul></div>';
                }
                ?>
            </div>
            <div class="three_col right">
                <?php
                if (isset($this->user)) { 
                    ?>
                    <form id="event_search" name="event_search" method="get" action="<?php echo $this->uri; ?>">
                        <input type='text' name='q' id='searchinput' value="<?php if (isset($_GET['q'])) { echo htmlentities($_GET['q']); } ?>" />
                        <input type='submit' class="search_submit" name='submit' value="Search" />
                        <input type='hidden' name='action' value='search' />
                    </form>
                <?php }
                UNL_UCBCN::displayRegion($this->output);
                ?>
            </div>
            <!-- InstanceEndEditable -->
            <div class="clear"></div>
            <?php include $_SERVER['DOCUMENT_ROOT'].'/wdn/templates_3.0/includes/noscript.html'; ?>
            <!--THIS IS THE END OF THE MAIN CONTENT AREA.-->
        </div>
        <div id="footer">
            <div id="footer_floater"></div>
            <div id="wdn_copyright"><!-- InstanceBeginEditable name="footercontent" -->
                &copy; <?php echo date('Y'); ?> University of Nebraska&ndash;Lincoln | <a href="http://ucommdev.unl.edu/webdev/wiki/index.php/UNL_Calendar_Documentation">Help</a> | <a href="http://www1.unl.edu/wdn/wiki/UNL_Calendar_FAQs"><abbr title="Frequently asked questions">FAQs</abbr></a> | <a href="http://www1.unl.edu/comments/">Comments?</a>
                <!-- InstanceEndEditable -->
                <?php include $_SERVER['DOCUMENT_ROOT'].'/wdn/templates_3.0/includes/wdn.html'; ?>
                | <a href="http://validator.unl.edu/check/referer">W3C</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS</a> <a href="http://www.unl.edu/" title="UNL Home" id="wdn_unl_wordmark"><img src="/wdn/templates_3.0/css/footer/images/wordmark.png" alt="UNL's wordmark" /></a> </div>
        </div>
    </div>
    <div id="wdn_wrapper_footer"> </div>
</div>
</body>
<!-- InstanceEnd --></html>

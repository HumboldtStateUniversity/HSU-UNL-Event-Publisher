<?php
header('Content-Type:text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo $this->doctitle; ?></title>


        <link rel="stylesheet" type="text/css" media="screen" href="templates/humboldt/manager_main.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="templates/humboldt/dialog/dialog_box.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="templates/humboldt/ui.selectmenu.css">
		<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/smoothness/jquery-ui.css" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>
        <script type="text/javascript" src="templates/humboldt/manager.js"></script>
        <script type="text/javascript" src="templates/humboldt/dialog/dialog_box.js"></script>
		<script src="templates/humboldt/ui.selectmenu.js"></script>

    </head>
    <body class="col4-0" <?php echo $this->uniquebody; ?>>
		<div id="wrap"> 
		<div id="container"> 

		<p id="branding"><a href="http://www.humboldt.edu"><img src="http://www.humboldt.edu/humboldt/images/interiorwordmark.jpg" alt="Humboldt State University" /></a></p> 

		<div id="content"> 
		<div id="contentwrap"> 
		<div id="page-meta"> 

		<span></span>	
	
	
        <div id="titlegraphic" style="clear:both">
            <h1>Calendar/Events</h1>
            <!--<h2>Plan. Publish. Share.</h2>-->
        </div>
		<div id="maincontent">	

                <!--<div id="breadCrumb">
                    <a href="">Calendar</a>
                    <?php
                    if (!empty($this->calendar->website)) {
                        echo ' / <a href="'.$this->calendar->website.'">'.$this->calendar->name.'</a>';
                    }
                    ?>
                </div>-->
                <div id="contentSearch">
					<?php if (isset($this->user)) { ?>
                    <form id="event_search" name="event_search" method="get" action="<?php echo $this->uri; ?>">				<div>
                        <input type='text' name='q' id='searchinput' value="<?php if (isset($_GET['q'])) { echo htmlentities($_GET['q']); } ?>" />
                        <input type='submit' class="search_submit" name='submit' value="Search" />
                        <input type='hidden' name='action' value='search' />
						</div>
                    </form>
					<?php
                } ?>
					<?php
		                if (isset($this->calendar)) {
		                    echo '<p class="currentCal">Current calendar: <strong>' . $this->calendar->name . '</strong></p>';
		                #} else {
		                #    echo 'Event Publishing System';
		                }
		                ?>
                    <?php if (isset($this->user)) {
                        UNL_UCBCN::displayRegion($this->calendarselect);
                    } //End if user
                    ?>
                    <div id="navigation">
	                    <div id="title" class="rightnav">

	                    </div>
                        <!--<h4 id="sec_nav">Navigation</h4>-->
                        <div id="navlinks">
                            <?php
                            if (isset($this->user)) { ?>
                            <ul>
                                <li id="mycalendar"><a href="<?php echo $this->uri; ?>?" title="My Calendar">Pending Events</a></li>
                                <li id="create"><a href="<?php echo $this->uri; ?>?action=createEvent" title="Create Event">Create Event</a></li>
                                <li id="subscribe"><a href="<?php echo $this->uri; ?>?action=subscribe" title="Subscribe">Subscribe</a></li>
                            </ul>
                                <?php
                            } ?>
                        </div>
                        <div id="nav_end"></div>
                        <div id="leftcollinks">
                            <?php if (isset($this->user)) { ?>
                            <div class="cal_widget">
                                <h3><span><?php echo date("F jS, Y"); ?></span></h3>
                                <ul>
                                    <li class="nobullet">Welcome, <?php echo $this->user->uid; ?></li>
                                    <li><a href="<?php echo $this->frontenduri . '?calendar_id='.$this->calendar->id; ?>">Live Calendar</a></li>
                                    <li><a href="<?php echo $this->uri; ?>?action=calendar">Calendar Info</a></li>
                                    <li><a href="<?php echo $this->uri; ?>?action=users">Users &amp; Permissions</a></li>
                                    <li><a href="<?php echo $this->uri; ?>?logout=true">LogOut</a></li>
                                </ul>
                            </div>
                                <?php
                            }

                            if (!empty($this->plugins)) {
                                echo '	<div class="cal_widget"><h3>Plugins</h3><ul>';
                                foreach ($this->plugins as $plugin) {
                                    echo '<li><a href="'.$plugin->uri.'">'.$plugin->name.'</a></li>';
                                }
                                echo '</ul></div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div id="main_right" class="mainwrapper">
                        <div id="maincontent">
                            <!--<div>
                                <h2>Descriptive Text</h2>
                                <p>Fill in whatever information is pertinent to your calendar here.</p>
                            </div>-->
                            <?php if (isset($this->user)){
                            UNL_UCBCN::displayRegion($this->output);}
                            ?>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                </div>


			</div> 




			</div><!-- /contentwrap --> 
			</div><!-- /content --> 
			</div><!-- /container --> 
			 </div><!-- /wrap --> 

			<div id="site-meta"> 
			<div id="meta-wrap"> 


			<!-- Google CSE Search Box Begins --> 

			<!--<form action="http://www.humboldt.edu/humboldt/search/" id="search"> 
			    <input type="hidden" name="cx" value="016116879625100262331:h29hmmqiar8" /> 
			    <input type="hidden" name="cof" value="FORID:11" /> 
			    <input type="text" name="q" size="15" value="Search" /> 
			    <input type="image" src="http://www.humboldt.edu/humboldt//images/submit.gif" value="Go" name="sa" class="submit" alt="go" /> 
			</form> 
			<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&lang=en"></script> -->
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

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
				<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="templates/humboldt/hsu-ie.css" media="screen" /><![endif]--> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>
        <script type="text/javascript" src="templates/humboldt/manager.js"></script>
        <script type="text/javascript" src="templates/humboldt/dialog/dialog_box.js"></script>

    </head>
    <body class="col4-0" <?php echo $this->uniquebody; ?>>
		<div id="wrap"> 
		<div id="container"> 

		<p id="branding"><a href="http://www.humboldt.edu"><img src="http://www.humboldt.edu/humboldt/images/interiorwordmark.jpg" alt="Humboldt State University" /></a></p> 

		<div id="content"> 
		<div id="contentwrap"> 

	


		<div id="maincontent">
			<div id="header">
					<h1><a href="<?php echo $this->frontenduri; ?>">Events Calendar</a></h1>	
			</div>
                <!--<div id="breadCrumb">
                    <a href="">Calendar</a>
                    <?php
                    if (!empty($this->calendar->website)) {
                        echo ' / <a href="'.$this->calendar->website.'">'.$this->calendar->name.'</a>';
                    }
                    ?>
                </div>-->
                <div id="contentSearch">
	



                    <div id="navigation">
	
											<?php
								                if (isset($this->calendar)) {
								                    echo '<div id="calChoose-wrap"><p class="currentCal">Current calendar: <strong>' . $this->calendar->name . '</strong></p>';
								                }
								                ?>
						                    <?php if (isset($this->user)) {
						                        UNL_UCBCN::displayRegion($this->calendarselect);
						                    } //End if user
						                    ?>
																<?php
													                if (isset($this->calendar)) {
													                    echo '</div>';
													                }
													                ?>
						
	
	
											<?php if (isset($this->user)) { ?>
													<div id="search-wrap">
						                    <form id="event_search" name="event_search" method="get" action="<?php echo $this->uri; ?>">				<div>
						                        <input type='text' name='q' id='searchinput' value="<?php if (isset($_GET['q'])) { echo htmlentities($_GET['q']); } ?>" />
						                        <input type='submit' class="search_submit" name='submit' value="Search" />
						                        <input type='hidden' name='action' value='search' />
												</div>
						                    </form>
											</div>
											<?php
						                } ?>
						
						
						
	
                        
                        <div id="nav_end"></div>
                        <div id="leftcollinks">
                            <?php if (isset($this->user)) { ?>
                            <div class="cal_widget">
                                <h3><span><?php echo date("F jS, Y"); ?></span></h3>
                                <ul>
                                    <li class="nobullet welcome">Welcome, <?php echo $this->user->uid; ?></li>
                                    <li><a href="<?php echo $this->frontenduri . '?calendar_id='.$this->calendar->id; ?>">Live Calendar</a></li>
                                    <?php if(UNL_UCBCN::userHasPermission($this->user, 'Calendar Edit', $this->calendar)) : ?>
                                        <li><a href="<?php echo $this->uri; ?>?action=calendar">Calendar Info</a></li>
                                    <?php endif; ?>
                                    <?php if(UNL_UCBCN::userHasPermission($this->user, 'Calendar Change User Permissions', $this->calendar)) : ?>
                                    <li><a href="<?php echo $this->uri; ?>?action=users">Users &amp; Permissions</a></li>
                                    <?php endif; ?>
                                    <?php if(UNL_UCBCN::userHasPermission($this->user, 'Calendar Edit Subscription', $this->calendar)) : ?>				    
                  		    	<li><a href="<?php echo $this->uri; ?>?action=subscribe" title="Subscribe">Subscribe to Calendars</a></li>
                                    <?php endif; ?>
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
													
	                            <?php
	                            if (isset($this->user)) { ?>
															<div id="navlinks">
	                            <ul>
	                                <li id="mycalendar"><a href="<?php echo $this->uri; ?>?" title="View or Edit Existing Events">View or Edit Existing Events</a></li>
	                                <li id="create"><a href="<?php echo $this->uri; ?>?action=createEvent" title="Create an Event">Create an Event</a></li>
	                                <!--<li id="subscribe"><a href="<?php echo $this->uri; ?>?action=subscribe" title="Subscribe">Subscribe</a></li>-->
	                            </ul>
															</div>
	                                <?php
	                            } ?>
	                        
	
	
                            <?php UNL_UCBCN::displayRegion($this->output); ?>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                </div>






			</div><!-- /contentwrap --> 
			</div><!-- /content --> 
			</div><!-- /container --> 
			 </div><!-- /wrap --> 

			<div id="site-meta"> 
			<div id="meta-wrap"> 


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
			</body>
</html>

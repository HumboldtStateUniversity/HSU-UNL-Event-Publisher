<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Copyright" content="Copyright Humboldt State University 2011. All Rights Reserved.">
	<meta name="description" content="Humboldt State University: Learning to make a difference. A State University located in coastal Northern California." />
	<meta name="keywords" content="HSU, Humboldt, Humboldt State, Humboldt State University, Humboldt State College, California State University, Arcata, California, academics, redwoods, coast, Humboldt County, Humbolt, lumberjack" />
	<link rel="shortcut icon" href="http://www.humboldt.edu/humboldt/favicon.ico" />
	<link rel="apple-touch-icon" href="http://www.humboldt.edu/humboldt/apple-touch-icon.png" />
	
        <title><?php echo $this->doctitle; ?></title>


        <link rel="stylesheet" type="text/css" media="screen" href="templates/humboldt/manager_main.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="templates/humboldt/dialog/dialog_box.css" /> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>
        <script type="text/javascript" src="templates/humboldt/manager.js"></script>
        <script type="text/javascript" src="templates/humboldt/dialog/dialog_box.js"></script>
				<script type="text/javascript" src="templates/humboldt/jquery.validate.min.js"></script>
				<script type="text/javascript" src="templates/humboldt/validate.js"></script>

    </head>
    <body <?php echo $this->uniquebody; ?>>
			<div id="wrap">
					<div id="masthead"> 
							<div id="logowrapper"><a id="logo" href="http://www.humboldt.edu/"> 
								<span class="ir">Humboldt State University</span></a> 
							</div> 
						</div>
							<div id="maincontent">
								<div id="load"></div>
								<div id="banner">
									<div id="banner-wrap">
					<h1 id="calname"><a class="maincal" href="<?php echo $this->frontenduri; ?>"><span class="ir"><?php echo $this->calendar->name; ?></span></a></h1>
						
					
					<?php if (isset($this->user)) { ?>
						<p><a href="<?php echo $this->uri; ?>?logout=true" class="logout-btn">Log Out</a></p>
					<?php } ?>
					
					<div class="clear"></div>


					</div><!-- /banner-wrap -->
					</div><!-- /banner -->


</div>

<div id="content-wrap">
<div id="content-top"></div>
<div class="column-wrap">

<div class="col left">
	
											<?php
								                if (isset($this->calendar)) {
								                    echo '<div id="calChoose-wrap"><h3>Current calendar</h3><p class="currentCal"><strong>' . $this->calendar->name . '</strong></p>';
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
														
																<h3>Search Events</h3>
						                    <form id="event_search" name="event_search" method="get" action="<?php echo $this->uri; ?>">				<div>
						                        <input type='text' name='q' id='searchinput' value="<?php if (isset($_GET['q'])) { echo htmlentities($_GET['q']); } ?>" />
						                        <input type='submit' class="search_submit" name='submit' value="Go" />
						                        <input type='hidden' name='action' value='search' />
												</div>
						                    </form>
											</div>
											<?php
						                } ?>
						
						
						
                        <div id="leftcollinks">
                            <?php if (isset($this->user)) { ?>
                            <div class="cal_widget">
                                <h3>Welcome, <?php echo $this->user->uid; ?></h3>
                                <ul>
	
                                    <!--<li class="nobullet welcome"><?php echo date("F jS, Y"); ?></li>-->
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
				    <li><a href="<?php echo $this->uri; ?>?logout=true">Log Out</a></li>
                                </ul>
                            </div>
                            
                            <?php
                            }

                            if (isset($this->user) && UNL_UCBCN::userHasPermission($this->user, 'Calendar Edit', $this->calendar)) {
                                if (!empty($this->plugins)) {
                                    echo '      <div class="cal_widget"><h3>Plugins</h3><ul>';
                                    foreach ($this->plugins as $plugin) {
                                        echo '<li><a href="'.$plugin->uri.'">'.$plugin->name.'</a></li>';
                                    }
                               	    echo '</ul></div>';
                                }
                            }
                            ?>

                        </div>
                    </div>

		<div class="right">
													<div style="width:590px;">
	                            <?php
	                            if (isset($this->user)) { ?>
															<div id="navlinks">
	                            <ul>
	                                <li id="mycalendar"><a href="<?php echo $this->uri; ?>?" title="View or Edit Existing Events">&raquo; View or Edit Existing Events</a></li>
	                                <li id="create"><a href="<?php echo $this->uri; ?>?action=createEvent" title="Create an Event">&raquo; Create an Event</a></li>
	                                <!--<li id="subscribe"><a href="<?php echo $this->uri; ?>?action=subscribe" title="Subscribe">Subscribe</a></li>-->
	                            </ul>
															</div>
	                            <?php
	                            } ?>
	                        
	
	
                            <?php UNL_UCBCN::displayRegion($this->output); ?>
</div>
													</div>

														<div class="clearfix"></div>
													<!--THIS IS THE END OF THE MAIN CONTENT AREA.-->
													</div><!-- /column-wrap -->
													</div><!-- /column-top -->

													</div><!-- /content-wrap -->


													</div><!-- /wrap -->
													</div>
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

													</body>
														</html>

<?php if (isset($this->right)) { ?>
              <div class="col left">
  
		<div id="monthwidget"><?php UNL_UCBCN::displayRegion($this->right); ?></div>
<!--Event type select-->
				<div id="eventtypes">
				<h3>Events by Type</h3>
                  <?php if( $eventlist = UNL_UCBCN_Frontend::getEventTypes()): ?>
                  <form action="<?php $this->uri ?>search/" method="get">
                      <select name="e"> 
                      <?php while ($eventlist->fetch()): ?>
                          <option value='<?php echo "$eventlist->id'>$eventlist->name"; ?></option>\n
                      <?php endwhile; ?>

                      </select><input type="submit" value="Go">
                  </form>
                  <?php endif; ?>
                 </div>
                  <div id="subscribe">
                  	<h3>Subscribe</h3>
                  	<ul>
                  		<li id="eventical"><a href="https://its-caldev.humboldt.edu/unlcal/upcoming/?format=ics&amp;limit=100" title=".ical format">iCal &amp; Outlook</a></li>
                  		<li id="eventrss"><a href="https://its-caldev.humboldt.edu/unlcal/upcoming/?format=rss&amp;limit=100" title="RSS feed">RSS feed</a></li>
                  	</ul>
                  </div>
                  <div class="cal_widget">
                  <!--	<h3>Contribute/Learn More</h3>-->
                  	<ul>
                  		<li id="login_list"><a id="frontend_login" href="manager/">&raquo; Submit an Event</a></li>
                  		<li><a href="#">&raquo; Give us Feedback</a></li>
                  		<li><a href="#">&raquo; Calendar Documentation</a> </li>
                  	</ul>
                  </div>
              
              </div>
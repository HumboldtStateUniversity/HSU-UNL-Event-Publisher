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


<div id="updatecontent" class="three_col right">
	<div class="clear"></div>
		<div class="day_cal">
		<?php
		if (is_a($this->output,'UNL_UCBCN_EventListing')) {
		    if ($dt = strtotime($this->query)) {
		        echo '<h1 class="results">Search results for events dated <span>'.date('F jS',$dt).'</span><a class="permalink" href="'.$this->url.'">(link)</a></h1>';
		    } elseif (isset($this->eventtype)) {
		        echo '<h1 class="results">Event results for <span>' . $this->eventtype_name[0] . '</span><a class="permalink" 
		href="'.$this->url.'">(link)</a></h1>';
		    } else {
		        echo '<h1 class="results">Search results for "<span>'.htmlentities($this->query).'</span>"<a class="permalink" href="'.$this->url.'">(link)</a></h1>';
		    }
		    echo '<h3>'.count($this->output->events).' results</h3>';
		}
		UNL_UCBCN::displayRegion($this->output);
		
		echo '<p id="feeds">
		            <a id="icsformat" title="ics format for search results" href="'.UNL_UCBCN_Frontend::reformatURL($this->url,array('format'=>'ics')).'">ics format for search results</a>
		            <a id="rssformat" title="rss format for search results" href="'.UNL_UCBCN_Frontend::reformatURL($this->url,array('format'=>'rss')).'">rss format for search results</a>
		            </p>';
		
		?>
		
		</div>
</div>

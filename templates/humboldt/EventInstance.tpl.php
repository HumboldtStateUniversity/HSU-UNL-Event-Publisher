<?php

$startu = strtotime($this->eventdatetime->starttime);
$endu = strtotime($this->eventdatetime->endtime);

?>
<div class="event_cal">
<div class='vcalendar'>
	<div class='vevent'>
		<h1 class='summary'><?php echo UNL_UCBCN_Frontend::dbStringToHtml($this->event->title); ?> <a class="permalink" href="<?php echo $this->url; ?>">(link)</a></h1>
		<?php if (isset($this->event->subtitle)) echo '<h2>'.UNL_UCBCN_Frontend::dbStringToHtml($this->event->subtitle).'</h2>'; ?>
		<!--<div id="tabsG">
		  <ul>
		    <li><a href="#" id="event_selected" title="Event Detail"><span>Event Detail</span></a></li>
		
		  </ul>
		</div>-->
		
			
		<div id="event_detail">
<!--			<h2 scope="col" class="date">Event Detail</h2>-->
			<h3 class="date"> <?php echo date('l, F jS',$startu); ?></h3>
		
			<span class="alt"><span class="date"> 
			<?php
			if (isset($this->eventdatetime->starttime)) {
				if (strpos($this->eventdatetime->starttime,'00:00:00')) {
					echo '<abbr class="dtstart" title="'.date(DATE_ISO8601, $startu).'">All day</abbr>';
				} else {
		        	echo '<abbr class="dtstart" title="'.date(DATE_ISO8601, $startu).'">'.date('g:i a', $startu).'</abbr>';
				}
		    } else {
		        echo 'Unknown';
		    }
		    if (isset($this->eventdatetime->endtime) &&
		    	($this->eventdatetime->endtime != $this->eventdatetime->starttime) &&
		    	($this->eventdatetime->endtime > $this->eventdatetime->starttime)) {
		    	if (substr($this->eventdatetime->endtime,0,10) != substr($this->eventdatetime->starttime,0,10)) {
		    	    // Not on the same day
		    	    if (strpos($this->eventdatetime->endtime,'00:00:00')) {
		    	        echo '-<abbr class="dtend" title="'.date(DATE_ISO8601, $endu).'">'.date('l, F jS', $endu).'</abbr>';
		    	    } else {
		    	        echo '-<abbr class="dtend" title="'.date(DATE_ISO8601, $endu).'">'.date('l, F jS g:i a', $endu).'</abbr>';
		    	    }
		    	} else {
 				    	    echo '-<abbr class="dtend" title="'.date(DATE_ISO8601, $endu).'">'.date('g:i a', $endu).'</abbr>';
		    	}
		    }
			?></span>
		</h3>

			<h3 class="date">Description:</h3>	
		<p class='description'>
			<?php echo UNL_UCBCN_Frontend::dbStringToHtml($this->event->description); ?></p>
			<?php
			if (isset($this->eventdatetime->additionalpublicinfo)) {
                echo '<p><strong>Additional Public Info:</strong> '.UNL_UCBCN_Frontend::dbStringToHtml($this->eventdatetime->additionalpublicinfo).'</p>';
            }
			if (isset($this->event->webpageurl)) {
			    echo '<p><strong>For more information visit:</strong> <a class="url" href="'.UNL_UCBCN_Frontend::dbStringToHtml($this->event->webpageurl).'">'.UNL_UCBCN_Frontend::dbStringToHtml($this->event->webpageurl).'</a></p>';
			}
			?>
			<?php if (isset($this->event->imagedata)) { ?>
				<img class="event_description_img" src="<?php echo UNL_UCBCN_Frontend::formatURL(array()); ?>?image&amp;id=<?php echo $this->event->id; ?>" alt="image for event <?php echo $this->event->id; ?>" />
			<?php } ?>	
			

		<div class="alt">
			<p class="date"><strong>Where:</strong></p>
			
				<?php
				if (isset($this->eventdatetime->room)) {
				    echo 'Room: '.UNL_UCBCN_Frontend::dbStringToHtml($this->eventdatetime->room);
				}
				if ($loc = $this->eventdatetime->getLocation()) {
					UNL_UCBCN::displayRegion($loc);
				}
                if (isset($this->eventdatetime->directions)) {
                    echo '<p class="directions"><strong>Directions:</strong> '.UNL_UCBCN_Frontend::dbStringToHtml($this->eventdatetime->directions).'</p>';
                }
				?>
		</div>	

			<h3 class="date">Contact:</h3>
			
			<?php 
			    if (isset($this->event->listingcontactname) ||
					isset($this->event->listingcontactphone) ||
					isset($this->event->listingcontactemail)) {

					if (isset($this->event->listingcontactname)) echo '<div class="n">'.$this->event->listingcontactname.'</div>';
					if (isset($this->event->listingcontactphone)) echo '<div class="tel">'.$this->event->listingcontactphone.'</div>';
					if (isset($this->event->listingcontactemail)) echo '<div class="mailto">'.$this->event->listingcontactemail.'</div>';
				} ?>
	</div>
		

		<?php
			UNL_UCBCN::displayRegion($this->facebookRSVP);
			echo $this->facebook->like($this->url,$this->calendar->id);
		?>
		<p id="feeds">
			<a id="icsformat" href="<?php echo UNL_UCBCN_Frontend::reformatURL($this->url,array('format'=>'ics')); ?>" title="Get event in ical format">
				ics format for <?php echo UNL_UCBCN_Frontend::dbStringToHtml($this->event->title)?></a>
			<a id="rssformat" href="<?php echo UNL_UCBCN_Frontend::reformatURL($this->url,array('format'=>'rss')); ?>" title="Get this event via rss">
				rss format for <?php echo UNL_UCBCN_Frontend::dbStringToHtml($this->event->title)?></a>
			<?php
				if ($this->eventdatetime->location_id) {
					$loc = $this->eventdatetime->getLink('location_id');
					$loc = UNL_UCBCN_Frontend::dbStringToHtml($loc->name);
				} else {
					$loc = "Unknown";
				}

				// Add to Google button URL
				$eventdate = '';
				if (isset($this->eventdatetime->starttime)) {
					$starttime = strtotime($this->eventdatetime->starttime);
					$eventdate .= date('Ymd', $starttime);

					if (strpos($this->eventdatetime->starttime,'00:00:00')) {
						// all day event
						$eventdate .= "/" . date('Ymd', strtotime("+1 day", $starttime));
					} else {
						// has start time
						$eventdate .= "T" . gmdate('Hi', $starttime) . '00Z';
						if (isset($this->eventdatetime->endtime)) {
							// has end time
							$endtime = strtotime($this->eventdatetime->endtime);
							$eventdate .= "/" . date('Ymd', $endtime);
							$eventdate .= "T" . gmdate('Hi', $endtime) . '00Z';
						}
					}
				}
				$googleurl = "http://www.google.com/calendar/event?action=TEMPLATE" .
							"&text=" . UNL_UCBCN_Frontend::dbStringToHtml($this->event->title) .
							"&location=" . $loc .
							"&dates=" . $eventdate .
							"&details=" . UNL_UCBCN_Frontend::dbStringToHtml($this->event->description) .
							"&sprop=website:" . $this->url;

			?>
			<a id="googlecal" href="<?php echo $googleurl; ?>" title="Add to google calendar">Add to Google</a>
			
			<a id="twittershare" href="http://twitter.com/home?status=Check out this event <?php echo $this->url; ?>" title="Click to share this post on Twitter">Share on Twitter</a>
		</p>
		
		</div>
	</div>
</div>

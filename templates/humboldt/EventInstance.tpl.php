<?php

$startu = strtotime($this->eventdatetime->starttime);
$endu = strtotime($this->eventdatetime->endtime);

?>
<div class="event_cal">
<div class='vcalendar'>
	<div class='vevent'>
		<h1 class='summary'><?php echo date('l, F jS',$startu); ?></h1>
		<!--<div id="tabsG">
		  <ul>
		    <li><a href="#" id="event_selected" title="Event Detail"><span>Event Detail</span></a></li>
		
		  </ul>
		</div>-->
		
			
		<div id="event_detail">
<!--			<h2 scope="col" class="date">Event Detail</h2>-->
			<h2><?php echo UNL_UCBCN_Frontend::dbStringToHtml($this->event->title); ?> <a class="permalink" href="<?php echo $this->url; ?>" title="Permanent link for this event"><span class="ir">[permalink]</span></a></h2>
			<?php if (isset($this->event->subtitle)) echo '<h3>'.UNL_UCBCN_Frontend::dbStringToHtml($this->event->subtitle).'</h3>'; ?>
		
			<p class="alt">
			<strong>Time:</strong> 
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
			?></p>

			<h3 class="date">Description:</h3>	
		<p class='description'>
			<?php echo UNL_UCBCN_Frontend::dbStringToHtml($this->event->description); ?></p>
			<?php
			if (isset($this->eventdatetime->additionalpublicinfo)) {
                echo '<p><strong>Additional Public Info:</strong> '.UNL_UCBCN_Frontend::dbStringToHtml($this->eventdatetime->additionalpublicinfo).'</p>';
            }
			if (isset($this->event->webpageurl)) {
			    echo '<p><strong>Want more info? </strong> <a class="url" href="'.UNL_UCBCN_Frontend::dbStringToHtml($this->event->webpageurl).'">Visit the event website.</a></p>';
			}
			?>
			<?php if (isset($this->event->imagedata)) { ?>
				<img class="event_description_img" src="<?php echo UNL_UCBCN_Frontend::formatURL(array()); ?>?image&amp;id=<?php echo $this->event->id; ?>" alt="image for event <?php echo $this->event->id; ?>" />
			<?php } ?>	
			<?php if (isset($this->event->price)) echo '<p><strong>Price:</strong>'.UNL_UCBCN_Frontend::dbStringToHtml($this->event->price).'</p>'; ?>
			
			<div class="date"><p><strong>Location:</strong>	</p>		
				<?php
				if (isset($this->eventdatetime->room)) {
				    echo 'Room: '.UNL_UCBCN_Frontend::dbStringToHtml($this->eventdatetime->room);
				}
				if ($loc = $this->eventdatetime->getLocation()) {
					UNL_UCBCN::displayRegion($loc);
				}
                if (isset($this->eventdatetime->directions)) {
                    echo '<span class="directions"><strong>Directions:</strong> '.UNL_UCBCN_Frontend::dbStringToHtml($this->eventdatetime->directions).'</span>';
                }
				?></div>

			<p class="date"><strong>Contact:</strong>
			
			<?php 
			    if (isset($this->event->listingcontactname) ||
					isset($this->event->listingcontactphone) ||
					isset($this->event->listingcontactemail)) {

					if (isset($this->event->listingcontactname)) echo '<span class="n">'.$this->event->listingcontactname.'</span><br />';
					if (isset($this->event->listingcontactphone)) echo '<span class="tel">'.$this->event->listingcontactphone.'</span><br />';
					if (isset($this->event->listingcontactemail)) echo '<span class="mailto"><a href="mailto:'.$this->event->listingcontactemail.'">'.$this->event->listingcontactemail.'</a></span><br />';
				} ?>
	</div>
		
	<div id="facebook_wrap">
		<?php
			UNL_UCBCN::displayRegion($this->facebookRSVP);
			echo $this->facebook->like($this->url,$this->calendar->id);
		?>
		</div>
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
							"&text=" . htmlentities(UNL_UCBCN_Frontend::dbStringToHtml($this->event->title)) .
							"&location=" . htmlentities($loc) .
							"&dates=" . $eventdate .
							"&details=" . htmlentities(UNL_UCBCN_Frontend::dbStringToHtml($this->event->description)) .
							"&sprop=website:" . $this->url;

			?>
			<a id="googlecal" href="<?php echo $googleurl; ?>" title="Add to google calendar">Add to Google</a>
			
			<a id="twittershare" href="http://twitter.com/home?status=Check out this event <?php echo $this->url; ?>" title="Click to share this post on Twitter">Share on Twitter</a>
		</p>
		
		</div>
	</div>
</div>

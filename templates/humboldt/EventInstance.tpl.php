<?php

$startu = strtotime($this->eventdatetime->starttime);
$endu = strtotime($this->eventdatetime->endtime);

?>
<div class="event_cal">
<div class='vcalendar'>
	<div class='vevent'>
		<h1 class='summary'><?php echo UNL_UCBCN_Frontend::dbStringToHtml($this->event->title); ?> <a class="permalink" href="<?php echo $this->url; ?>">(link)</a></h1>
		<?php if (isset($this->event->subtitle)) echo '<h2>'.UNL_UCBCN_Frontend::dbStringToHtml($this->event->subtitle).'</h2>'; ?>
		<div id="tabsG">
		  <ul>
		    <li><a href="#" id="event_selected" title="Event Detail"><span>Event Detail</span></a></li>
		
		  </ul>
		</div>
		<table>
		<thead>
			<tr>
				<th scope="col" class="date">Event Detail</th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td class="date">Date:</td>
			<td><?php echo date('l, F jS',$startu); ?></td>
		</tr>
		<tr class="alt"><td class="date">Time:</td>	
			<td><?php
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
			?></td>
		</tr>
		<tr>
			<td class="date">Description:</td>	
			<td><p class='description'>
			<?php echo UNL_UCBCN_Frontend::dbStringToHtml($this->event->description); ?></p>
			<?php
			if (isset($this->eventdatetime->additionalpublicinfo)) {
                echo '<p>Additional Public Info: '.UNL_UCBCN_Frontend::dbStringToHtml($this->eventdatetime->additionalpublicinfo).'</p>';
            }
			if (isset($this->event->webpageurl)) {
			    echo 'Website: <a class="url" href="'.UNL_UCBCN_Frontend::dbStringToHtml($this->event->webpageurl).'">'.UNL_UCBCN_Frontend::dbStringToHtml($this->event->webpageurl).'</a>';
			}
			?>
			<?php if (isset($this->event->imagedata)) { ?>
				<img class="event_description_img" src="<?php echo UNL_UCBCN_Frontend::formatURL(array()); ?>?image&amp;id=<?php echo $this->event->id; ?>" alt="image for event <?php echo $this->event->id; ?>" />
			<?php } ?>	
			</td>
		</tr>
		<tr class="alt">
			<td class="date">Location:</td>
			<td>
				<?php
				if (isset($this->eventdatetime->room)) {
				    echo 'Room: '.UNL_UCBCN_Frontend::dbStringToHtml($this->eventdatetime->room);
				}
				if ($loc = $this->eventdatetime->getLocation()) {
					UNL_UCBCN::displayRegion($loc);
				}
                if (isset($this->eventdatetime->directions)) {
                    echo '<p class="directions">Directions: '.UNL_UCBCN_Frontend::dbStringToHtml($this->eventdatetime->directions).'</p>';
                }
				?>
			</td>
		</tr>
		<tr>
			<td class="date">Contact:</td>
			<td>
			<?php 
			    if (isset($this->event->listingcontactname) ||
					isset($this->event->listingcontactphone) ||
					isset($this->event->listingcontactemail)) {

					if (isset($this->event->listingcontactname)) echo '<div class="n">'.$this->event->listingcontactname.'</div>';
					if (isset($this->event->listingcontactphone)) echo '<div class="tel">'.$this->event->listingcontactphone.'</div>';
					if (isset($this->event->listingcontactemail)) echo '<div class="mailto">'.$this->event->listingcontactemail.'</div>';
				} ?>
			</td>
		</tr>
		</tbody>
		</table>
        <?php
            UNL_UCBCN::displayRegion($this->facebookRSVP);
            echo $this->facebook->like($this->url,$this->calendar->id);
            echo '<p id="feeds">
			<a id="icsformat" href="'.UNL_UCBCN_Frontend::reformatURL($this->url,array('format'=>'ics')).'">ics format for '.UNL_UCBCN_Frontend::dbStringToHtml($this->event->title).'</a>
			<a id="rssformat" href="'.UNL_UCBCN_Frontend::reformatURL($this->url,array('format'=>'rss')).'">rss format for '.UNL_UCBCN_Frontend::dbStringToHtml($this->event->title).'</a>
			</p>'; ?>
		</div>
	</div>
</div>
<?php
$startu = strtotime($this->eventdatetime->starttime);
$endu = strtotime($this->eventdatetime->endtime);
?>
<item>
	<title><?php echo UNL_UCBCN_Frontend::dbStringToHtml($this->event->title); ?></title>
	<link><?php echo $this->url; ?></link>
	<description>
		<?php
		echo '&lt;div&gt;'.UNL_UCBCN_Frontend::dbStringToHtml(strip_tags($this->event->description)).'&lt;/div&gt;';
		if (isset($this->event->subtitle)) echo '&lt;div&gt;'.UNL_UCBCN_Frontend::dbStringToHtml($this->event->subtitle).'&lt;/div&gt;';
		echo '&lt;small&gt;'.date('l, F jS', $startu).'&lt;/small&gt;';
		
		if (isset($this->eventdatetime->starttime)) {
			if (strpos($this->eventdatetime->starttime,'00:00:00')) {
				echo ' | &lt;small&gt;&lt;abbr class="dtstart" title="'.date(DATE_ISO8601, $startu).'"&gt;All day&lt;/abbr&gt;&lt;/small&gt;';
			} else {
	        	echo ' | &lt;small&gt;&lt;abbr class="dtstart" title="'.date(DATE_ISO8601, $startu).'"&gt;'.date('g:i a', $startu).'&lt;/abbr&gt;&lt;/small&gt;';
			}
	    } else {
	        echo 'Unknown';
	    }
	    if (isset($this->eventdatetime->endtime) &&
	    	($this->eventdatetime->endtime != $this->eventdatetime->starttime) &&
	    	($this->eventdatetime->endtime > $this->eventdatetime->starttime)) {
	    	echo '-&lt;small&gt;&lt;abbr class="dtend" title="'.date(DATE_ISO8601, $endu).'"&gt;'.date('g:i a', $endu).'&lt;/abbr&gt;&lt;/small&gt;';
	    }
		if ($this->eventdatetime->location_id) {
		    $loc = $this->eventdatetime->getLink('location_id');
			echo ' | &lt;small&gt;'.UNL_UCBCN_Frontend::dbStringToHtml($loc->name);
			if (isset($this->eventdatetime->room)) {
			    echo ' Room:'.UNL_UCBCN_Frontend::dbStringToHtml($this->eventdatetime->room);
			}
			echo '&lt;/small&gt;';
		} ?>
	</description>
	<pubDate><?php echo date('r',strtotime($this->event->datecreated)); ?></pubDate>
	<guid><?php echo $this->url; ?></guid>
</item>

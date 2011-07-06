<?php
function featured() {
    $output = '';
    $xmlUrl = "https://its-caldev.humboldt.edu/unlcal/upcoming/?format=xml&limit=18";
    $xmlStr = file_get_contents($xmlUrl);
    $xmlObj = simplexml_load_string($xmlStr);
    $xmlObj = json_decode(json_encode($xmlObj),1); //convert xml object to array

    foreach ($xmlObj['Event'] as $event){
        if ($event['EventStatus'] == 'featured') {
            $events[] = $event;
		}
    } 

	if (count($events)) {

		// generate the div
		$output .= '<div id="featuredEvents">';
		$output .= '<h2 id="featuredEventsTitle">Featured Events</h2>';
		$output .='<a class="prev browse">previous</a><a class="next browse">next</a>';
		$output .= '<div class="scrollable">';
		$output .= '<div class="items">';
		$count = 0;
		foreach ($events as $event) {
		$count++;
		    $time = strtotime($event['DateTime']['StartDate']);
		    $formattedTime = date('M j', $time);

		    if ($count == 1) {
		        $output .= '<div>';
		    }
		    $output .= '<div class="event_detail">';

		    if (!is_array($event['WebPages']['WebPage'][0]))
		        $eventURL = $event['WebPages']['WebPage']['URL'];
		    else
		        // event has external webpage, so make sure to use the internal link
		        $eventURL = $event['WebPages']['WebPage'][0]['URL'];

		    if (!empty($event['Images']['Image']['URL'])) {
		        $output .= '<div class="imagecrop"><a href="' . $eventURL . '">
				<img src="' . $event['Images']['Image']['URL'] . '" 
				alt="' . $event['EventTitle'] . '" /></a></div>';
		    }

		    $output .= '<span>' . $formattedTime . '</span>';
		    $output .= '<a href="' . $eventURL . '">' . 
				$event['EventTitle'] . '</a></div><!-- /event_detail -->';

		    if ($count == 3) {
		        $output .= '</div><!--/generic container-->';
		        $count = 0;
		    }

		}

		if ($count > 0) { // if there are less than 3 events on last page, div wasn't closed in for loop
		$output .= '</div><!--/generic container-->';
		}

		$output .= '</div><!-- /items --></div><!-- /scrollable --></div><!-- /featuredEvents-->';
	}
    return $output;
}

?>


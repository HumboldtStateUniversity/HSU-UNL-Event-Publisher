<?php
function featured() {
    $output = '';
    $xmlUrl = "https://its-caldev.humboldt.edu/unlcal/featured/upcoming/?format=xml";
    $xmlStr = file_get_contents($xmlUrl);
    $xmlObj = simplexml_load_string($xmlStr);
    $xmlObj = json_decode(json_encode($xmlObj),1); //convert xml object to array

    // make sure the last row has 3 events
    $events = array_chunk($xmlObj['Event'], 3); // split into groups of 3
    if (count($events) > 1 && count(end($events)) < 3){   // if the last row has less than 3
        $secondlast = $events[count($events)-2];          // second-to-last row
        $offset = count(end($events));                 // offset index for where to grab from
        $toadd = array_slice($secondlast, $offset); // the array to prepend to last row
        $events[count($events)-1] = array_merge($toadd, $events[count($events)-1]);
    }

    // merge the groups of 3 back into a single array
    $combined = array();
    for($x = 0; $x < count($events); $x++) {
        for($y = 0; $y < 3; $y++) {
            $combined[] = $events[$x][$y];
        }
    }
    $events = $combined;

    // generate the div
    $output .= '<div id="featuredEvents">';
    $output .= '<h2 id="featuredEventsTitle">Featured Events</h2>';
    $output .='<a class="prev browse">previous</a><a class="next browse">next</a>';
    $output .= '<div class="scrollable">';
    $output .= '<div class="items">';
    $count = 1;
    foreach ($events as $event) {
        $time = strtotime($event['DateTime']['StartDate']);
        $formattedTime = date('M j', $time);

        if ($count == 1) {
            $output .= '<div>';
        }
        $output .= '<div class="event_detail">';

        if (!empty($event['Images']['Image']['URL'])) {
            $output .= '<img src="' . $event['Images']['Image']['URL'] . '" alt="' . $event['EventTitle'] . '" 
width"260" height="200" />';
        }

        $output .= '<span>' . $formattedTime . '</span>';
        $output .= '<a href="' . $event['WebPages']['WebPage']['URL'] . '">' . 
$event['EventTitle'] . '</a></div><!-- /event_detail -->';

        if ($count == 3) {
            $output .= '</div><!--/generic container-->';
            $count = 0;
        }
        $count++;
    }
    $output .= '</div><!-- /items --></div><!-- /scrollable --></div><!-- /featuredEvents-->';
    return $output;
}

?>


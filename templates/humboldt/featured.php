<?php
function featured() {
    $output = '';
    $xmlUrl = "https://its-caldev.humboldt.edu/unlcal/featured/upcoming/?format=xml&limit=5";
    $xmlStr = file_get_contents($xmlUrl);
    $xmlObj = simplexml_load_string($xmlStr);

    $output .= '<div id="featuredEvents">';
    $output .= '<h2 id="featuredEventsTitle">Featured Events</h2>';
    foreach ($xmlObj->Event as $item) {
        $time = strtotime($item->DateTime->StartDate);
        $formattedTime = date('M j', $time);
        $output .= '<div class="event_detail"><span>' . $formattedTime . '</span>';
        $output .= '<a href="' . $item->WebPages->WebPage->URL . '">' . $item->EventTitle . '</a></div>';
        //if (!empty($item->Images->Image->URL)) {
        //    $output .= '<img src="' . $item->Images->Image->URL . '" />';
        //}
    }
    $output .= '</div>';
    return $output;
}
?>

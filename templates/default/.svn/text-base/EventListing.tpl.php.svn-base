<?php if ($this->type == 'ongoing') {
    echo '<h4 class="'.$this->type.'">Ongoing Events:</h4>';
} ?>
<table class='<?php echo $this->type; ?>'>
<thead>
<tr>
<th scope="col" class="date">Time</th>
<th scope="col" class="title">Event Title</th>
</tr>
</thead>
<tbody class="vcalendar">
<?php
$oddrow = false;
foreach ($this->events as $e) {
    
    $startu = strtotime($e->eventdatetime->starttime);
    $endu = strtotime($e->eventdatetime->endtime);
    
    $row = '<tr class="vevent';
    if ($oddrow) {
        $row .= ' alt';
    }
    $row .= '">';
    $oddrow = !$oddrow;
    $row .=    '<td class="date">';
    if ($this->type == 'ongoing') {
        $row .= '<abbr class="dtstart" title="'.date('c', $startu).'">'.date('M jS', $startu).'</abbr>';
        $row .= '-<abbr class="dtend" title="'.date('c', $endu).'">'.date('M jS', $endu).'</abbr>';
    } elseif ($this->type == 'upcoming' || $this->type == 'search') {
        if (strpos($e->eventdatetime->starttime,'00:00:00')) {
            $row .= '<abbr class="dtstart" title="'.date('c', $startu).'">'.date('M jS', $startu).'</abbr>';
        } else {
            $row .= '<abbr class="dtstart" title="'.date('c', $startu).'">'.date('g:i a M jS', $startu).'</abbr>';
        }
    } else {
        if (isset($e->eventdatetime->starttime)) {
            if (strpos($e->eventdatetime->starttime,'00:00:00')) {
                $row .= '<abbr class="dtstart" title="'.date('c', $startu).'">All day</abbr>';
            } else {
                $row .= '<abbr class="dtstart" title="'.date('c', $startu).'">'.date('g:i a', $startu).'</abbr>';
            }
        } else {
            $row .= 'Unknown';
        }
        if (isset($e->eventdatetime->endtime) &&
            ($e->eventdatetime->endtime != $e->eventdatetime->starttime) &&
            ($e->eventdatetime->endtime > $e->eventdatetime->starttime)) {
            if (substr($e->eventdatetime->endtime,0,10) != substr($e->eventdatetime->starttime,0,10)) {
                // Not on the same day
                if (strpos($e->eventdatetime->endtime,'00:00:00')) {
                    $row .= '-<abbr class="dtend" title="'.date(DATE_ISO8601, $endu).'">'.date('M jS', $endu).'</abbr>';
                } else {
                    $row .= '-<abbr class="dtend" title="'.date(DATE_ISO8601, $endu).'">'.date('M jS g:i a', $endu).'</abbr>';
                }
            } else {
                $row .= '-<abbr class="dtend" title="'.date(DATE_ISO8601, $endu).'">'.date('g:i a', $endu).'</abbr>';
            }
        }
    }
    $row .= '</td>' .
            '<td><a class="url summary" href="'.UNL_UCBCN_Frontend::dbStringToHtml($e->url).'">'.UNL_UCBCN_Frontend::dbStringToHtml($e->event->title).'</a>';
    if (isset($e->eventdatetime->location_id) && $e->eventdatetime->location_id) {
        $l = $e->eventdatetime->getLink('location_id');
        $row .= ' <span class="location">';
        if (isset($l->mapurl)) {
            $row .= '<a class="mapurl" href="'.UNL_UCBCN_Frontend::dbStringToHtml($l->mapurl).'">'.UNL_UCBCN_Frontend::dbStringToHtml($l->name).'</a>';
        } else {
            $row .= UNL_UCBCN_Frontend::dbStringToHtml($l->name);
        }
        $row .= '</span>';
    }
    if ($this->type != 'ongoing') {
        $row .=    '<blockquote class="description">'.UNL_UCBCN_Frontend::dbStringToHtml($e->event->description).'</blockquote>';
    }
    $row .= $e->facebook->like($e->url,$e->calendar->id);
    $row .= '</td></tr>';
    
    echo $row;
}

 ?>

</tbody>
</table>


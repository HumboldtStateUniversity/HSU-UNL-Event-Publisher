<?php
/**
 * icalendar output for a single vent instance.
 */

$startu = strtotime($this->eventdatetime->starttime);
$endu = strtotime($this->eventdatetime->endtime);

$out = array();
$out[] = 'BEGIN:VEVENT';
//$out[] = 'SEQUENCE:5';
if (isset($this->eventdatetime->starttime)) {
    if (strpos($this->eventdatetime->starttime,'00:00:00')) {
        $out[] = 'DTSTART;VALUE=DATE:'.date('Ymd', $startu);
    } else {
           $out[] = 'DTSTART;TZID=US/Central:'.date('Ymd\THis', $startu);
    }
}
$out[] = 'UID:'.$this->eventdatetime->id.'@'.$_SERVER['SERVER_NAME'];
$out[] = 'DTSTAMP:'.date('Ymd\THis',strtotime($this->event->datecreated));
$out[] = 'SUMMARY:'.strip_tags($this->event->title);
$out[] = 'DESCRIPTION:'.preg_replace("/\r\n|\n|\r/", '\n', strip_tags($this->event->description));
if (isset($this->eventdatetime->location_id) && $this->eventdatetime->location_id) {
    $l = $this->eventdatetime->getLink('location_id');
    $loc =  'LOCATION:'.$l->name;
    if (isset($this->eventdatetime->room)) {
        $loc .=  ' Room '.$this->eventdatetime->room;
    }
    $out[] = $loc;
}
$out[] = 'URL:'.$this->url;
if (isset($this->eventdatetime->endtime)
    && $endu > $startu) {
    if (strpos($this->eventdatetime->endtime,'00:00:00')) {
        $out[] = 'DTEND;VALUE=DATE:'.date('Ymd', $endu);
    } else {
           $out[] = 'DTEND;TZID=US/Central:'.date('Ymd\THis', $endu);
    }
}
$out[] = 'END:VEVENT';
echo implode("\n",$out)."\n";
?>
<div class="calendar clear"></div>
<div class="day_cal">

<?php
$day = new Calendar_Day($this->year,$this->month,$this->day);
?>



<?php
    $prev = $day->prevDay('object');
    echo '<div class="day-buttons" data-role="controlgroup" data-type="horizontal"><a data-role="button" data-icon="arrow-l" rel="external" class="url prev" href="'.UNL_UCBCN_Frontend::formatURL(array(    'd'=>$prev->thisDay(),
                                                            'm'=>$prev->thisMonth(),
                                                            'y'=>$prev->thisYear(),
                                                            'calendar'=>$this->calendar->id)).'">Previous</a> ';
    $next = $day->nextDay('object');
    echo '<a data-role="button" data-icon="arrow-r" data-iconpos="right" rel="external" class="url next" href="'.UNL_UCBCN_Frontend::formatURL(array(    'd'=>$next->thisDay(),
                                                            'm'=>$next->thisMonth(),
                                                            'y'=>$next->thisYear(),
                                                            'calendar'=>$this->calendar->id)).'">Next</a></div>';

    UNL_UCBCN::displayRegion($this->output);

?>

<h4 class="sec_main">
<?php
echo date('l, F jS',$day->getTimeStamp());
?>
</h4>
</div>
<div class="calendar clear"></div>
<div class="day_cal">
<h4 class="sec_main">
<?php
$day = new Calendar_Day($this->year,$this->month,$this->day);
echo date('l, F jS',$day->getTimeStamp());
?>
</h4>

<?php
    $prev = $day->prevDay('object');
    echo '<a rel="external" class="url prev" href="'.UNL_UCBCN_Frontend::formatURL(array(    'd'=>$prev->thisDay(),
                                                            'm'=>$prev->thisMonth(),
                                                            'y'=>$prev->thisYear(),
                                                            'calendar'=>$this->calendar->id)).'">Previous Day</a> ';
    $next = $day->nextDay('object');
    echo '<a rel="external" class="url next" href="'.UNL_UCBCN_Frontend::formatURL(array(    'd'=>$next->thisDay(),
                                                            'm'=>$next->thisMonth(),
                                                            'y'=>$next->thisYear(),
                                                            'calendar'=>$this->calendar->id)).'">Next Day</a></p>';

    UNL_UCBCN::displayRegion($this->output);

?>
</div>
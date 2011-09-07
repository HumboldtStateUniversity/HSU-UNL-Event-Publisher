		<div data-role="content">

<div class="calendar clear"></div>
<div class="day_cal">

	<h4 class="sec_main">
	<?php
	$day = new Calendar_Day($this->year,$this->month,$this->day);
	echo date('l, F jS',$day->getTimeStamp());
	?>
	</h4>
	
<?php
UNL_UCBCN::displayRegion($this->output);
?>
</div>
		</div>
		
		</div>
    <div data-role="footer" data-position="fixed">
			<div data-role="navbar">
				<ul>			
					<?php
					
							$day = new Calendar_Day($this->year,$this->month,$this->day);

					    $prev = $day->prevDay('object');
					    echo '<li><a rel="external" data-icon="arrow-l" data-iconpos="top" href="'.UNL_UCBCN_Frontend::formatURL(array(    'd'=>$prev->thisDay(),
					                                                            'm'=>$prev->thisMonth(),
					                                                            'y'=>$prev->thisYear(),
					                                                            'calendar'=>$this->calendar->id)).'">Previous Day</a></li>';
					    $next = $day->nextDay('object');
					    echo '<li><a rel="external" data-icon="arrow-r" data-iconpos="top" href="'.UNL_UCBCN_Frontend::formatURL(array(    'd'=>$next->thisDay(),
					                                                            'm'=>$next->thisMonth(),
					                                                            'y'=>$next->thisYear(),
					                                                            'calendar'=>$this->calendar->id)).'">Next Day</a></li>';

					?>
					<li><a href="#two" data-rel="dialog" data-icon="grid" data-iconpos="top">Select Date</a></li>
					<li><a href="#three" data-rel="dialog" data-icon="search" data-iconpos="top">Search</a></li>
				</ul>
			</div>
			</div>
		</div>
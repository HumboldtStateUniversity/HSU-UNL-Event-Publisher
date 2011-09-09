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
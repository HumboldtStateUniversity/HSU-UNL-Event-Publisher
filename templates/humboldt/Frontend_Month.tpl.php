<div class="month_cal" id="month_viewcal">
<table class="wp-calendar">
	<caption><?php echo $this->caption; ?></caption>
   <thead>
	<tr>
		<th abbr="Sunday" scope="col" title="Sunday">Sun</th>
		<th abbr="Monday" scope="col" title="Monday">Mon</th>
		<th abbr="Tuesday" scope="col" title="Tuesday">Tue</th>
		<th abbr="Wednesday" scope="col" title="Wednesday">Wed</th>
		<th abbr="Thursday" scope="col" title="Thursday">Thu</th>
		<th abbr="Friday" scope="col" title="Friday">Fri</th>
		<th abbr="Saturday" scope="col" title="Saturday">Sat</th>
	</tr>
	</thead>
	<tbody>
		<?php
		    UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend_Day','Frontend_Day_monthday');
		    foreach ($this->weeks as $week) {
		        echo '<tr>';
		        foreach ($week as $day) {
		            $class = '';
		            if (is_object($day) && get_class($day) == 'UNL_UCBCN_Frontend_Day') {
			            if ($day->month < $this->month) {
			                $class = 'prev';
			            } elseif ($day->month > $this->month) {
			                $class = 'next';
			            }
		            }
		            echo '<td class="'.$class.'">';
		            UNL_UCBCN::displayRegion($day);
		            echo '</td>';
		        }
		        echo '</tr>';
		    }
		?>
	</tbody>
</table>
<a href="#" id="monthfullview" onclick="fullview()">View all events</a>
</div>
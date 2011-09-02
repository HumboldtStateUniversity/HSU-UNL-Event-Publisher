<table class="wp-calendar">
	<caption><?php echo '<span><a href="'.$prev.'" id="prev_month" title="View events for '.Calendar_Util_Textual::thisMonthName($PMonth).' '.$PMonth->thisYear().'">&lt;&lt; </a></span>
  <span class="monthvalue" id="'.Calendar_Util_Textual::thisMonthName($Month).'">'.Calendar_Util_Textual::thisMonthName($Month).'</span>
  <span class="yearvalue">'.$Month->thisYear().'</span>
  <span><a href="'.$next.'" id="next_month" title="View events for '.Calendar_Util_Textual::thisMonthName($NMonth).' '.$NMonth->thisYear().'"> &gt;&gt;</a></span>' ?></caption>
   <thead>
	<tr>
		<th abbr="Sunday" scope="col" title="Sunday">S</th>
		<th abbr="Monday" scope="col" title="Monday">M</th>
		<th abbr="Tuesday" scope="col" title="Tuesday">T</th>
		<th abbr="Wednesday" scope="col" title="Wednesday">W</th>
		<th abbr="Thursday" scope="col" title="Thursday">Th</th>
		<th abbr="Friday" scope="col" title="Friday">F</th>
		<th abbr="Saturday" scope="col" title="Saturday">S</th>
	</tr>
	</thead>
	<tbody>
		<?php echo $this->tbody; ?>
	</tbody>
</table>

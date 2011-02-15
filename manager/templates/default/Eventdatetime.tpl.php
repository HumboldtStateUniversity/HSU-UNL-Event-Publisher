<?php
	//print_r($this);
	//print_r(UNL_UCBCN_Eventdatetime::getLocation($this->location_id));
	$location = UNL_UCBCN_Eventdatetime::getLocations();
?>

<label for='eventdatetime[location_id]' class='required'>Location</label>
<select name='eventdatetime[location_id]' id='eventdatetime[location_id]'>
	<?php
	while($location->fetch()){
		$selected = ($location->id == $this->location_id)? "selected='selected'" : "";
		echo "<option value='{$location->id}' {$selected}>{$location->name}</option>";
	}
	?>
</select><br />
<label for='eventdatetime[room]'>Room</label>
<input type='text' name='eventdatetime[room]' id='eventdatetime[room]' value="<?php echo $this->room ?>"/><br />
<label for='eventdatetime[hours]'>Hours</label>
<input type='text' name='eventdatetime[hours]' id='eventdatetime[hours]' value="<?php echo $this->hours ?>"/><br />

<label for='eventdatetime[directions]'>Directions</label>
<textarea name='eventdatetime[directions]' id='eventdatetime[additionaldirections]'>
	<?php echo $this->directions ?>
</textarea><br />
<label for='eventdatetime[additionalpublicinfo]'>Additional Public Information</label>
<textarea name='eventdatetime[additionalpublicinfo]' id='eventdatetime[additionalpublicinfo]'>
	<?php echo $this->additionalpublicinfo ?>
</textarea><br />

<label for='eventdatetime[starttime]'>Start Date &amp; Time</label>
<input type='text' name='eventdatetime[starttime]' id='eventdatetime[starttime]' value="<?php echo $this->starttime ?>"/><br />
<label for='eventdatetime[endtime]'>End Date &amp; Time</label>
<input type='text' name='eventdatetime[endtime]' id='eventdatetime[endtime]' value="<?php echo $this->endtime ?>"/><br />

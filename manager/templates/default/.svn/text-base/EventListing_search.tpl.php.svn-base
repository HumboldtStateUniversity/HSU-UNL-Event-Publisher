<form action="<?php echo $_SERVER['PHP_SELF'].'?action=search&amp;q='.$_GET['q']; ?>" name="formlist" id="searchlist" method="post">
<div class="eventAction">
	<div class="eventButtonAction">
		<a href="#" class="checkall" onclick="setCheckboxes('searchlist',true); return false">Check All</a>
		<a href="#" class="uncheckall" onclick="setCheckboxes('searchlist',false); return false">Uncheck All</a>
	</div>
	<fieldset class="eventFieldsetAction">
	<legend>Action</legend>
	<label for="action">Action</label>
	<select id="action" onfocus="manager.list = 'search'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
	    <option>Select action...</option>
	    <option value="posted"    disabled="disabled">Add to Posted</option>
	    <option value="pending"   disabled="disabled">Move to Pending</option>
	    <option value="recommend" disabled="disabled">Recommend</option>
	    <option value="delete"    disabled="disabled">Delete</option>
	</select>
	</fieldset>
</div>
<table class="eventlisting">
<thead>
<tr>
<th scope="col" class="select">Select</th>
<th scope="col" class="title">Event Title</th>
<th scope="col" class="edit">Edit</th>
<th scope="col" class="delete">Delete</th>
</tr>
</thead>
<tbody>
<?php
$oddrow = false;
foreach ($this->events as $event) {
	$edt = UNL_UCBCN::factory('eventdatetime');
	$edt->event_id = $event['id'];
	$edt->orderBy('starttime DESC');
	$instances = $edt->find();
	?>
		<tr <?php echo ' id="row'.$event['id'].'" onclick="highlightLine(this,'.$event['id'].');"'; if ($oddrow) echo ' class="alt"'; ?>>
			<td class="select">
				<?php
				if ($event['calendarhasevent']===false) {
					echo '<input type="checkbox" name="event'.$event['id'].'" onclick="checknegate('.$event['id'].')" />';
				} else {
				    echo $event['calendarhasevent'];
				} ?>
			</td>
			<td class="title"><span class='title' style="float:left;"><?php echo htmlspecialchars($event['title']); ?></span>
				<div id='instances_<?php echo $event['id']; ?>' class="instances">
				<ul>
				<?php
					while ($edt->fetch()) {
						if (isset($event['recurrence_id'])) {
							$rec = UNL_UCBCN::factory('recurringdate');
							$rec->event_id = $event['id'];
							$rec->recurrence_id = $event['recurrence_id'];
							$rec->find(true);
							$starttime = $rec->recurringdate . substr($edt->starttime, 11);
							$starttime = strtotime($starttime);
						} else {
					    	$starttime = strtotime($edt->starttime);
						}
					    if (date('Y', $starttime) == date('Y')) {
					        // Date is in current year.
						    $datestring = date('M jS', $starttime);
						    if (substr($edt->starttime, 11) != '00:00:00') {
						        $datestring .= date(' g:ia', $starttime);
						    }
					    } else {
					        $datestring = date('M jS, Y', $starttime);
					    }
					    echo '<li><abbr class="dtstart" title="'.date(DATE_ISO8601, $starttime).'">'.$datestring.'</abbr></li>';
					}
				?>
				</ul>
				</div>
			</td>
			<td class="edit">
				<?php
				if ($event['usercaneditevent']) {
					if ($edt->recurringtype == 'none') {
						echo '<a href="?action=createEvent&amp;id='.$event['id'].'">Edit</a></td>';
					} else {
						echo "<a onclick='showConfirmationDialog(\"{$event['id']}\", \"{$event['recurrence_id']}\");'>Edit</a>";
					}
				} ?>
			</td>
			<td class="delete">
				<?php
				if ($event['usercandeleteevent']) {
					echo '<a onclick="return confirm(\'Are you sure you wish to delete '.addslashes(htmlentities($event['title'])).'?\');" href="'.$_SERVER['PHP_SELF'].'?action=search&amp;q='.$_GET['q'].'&amp;delete='.$event['id'].'&amp;rec_id='.$event['recurrence_id'].'">Delete</a></td>';
				} ?>
			</td>
		</tr>
	<?php
	$oddrow = !$oddrow;
	} ?>
</tbody>
</table>
<div class="eventAction">
	<div class="eventButtonAction">
		<a href="#" class="checkall" onclick="setCheckboxes('searchlist',true); return false">Check All</a>
		<a href="#" class="uncheckall" onclick="setCheckboxes('searchlist',false); return false">Uncheck All</a>
	</div>
	<fieldset class="eventFieldsetAction">
	<legend>Action</legend>
	<label for="action">Action</label>
	<select name="action" onfocus="manager.list = 'search'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
	    <option>Select action...</option>
	    <option value="posted"    disabled="disabled">Add to Posted</option>
	    <option value="pending"   disabled="disabled">Move to Pending</option>
	    <option value="recommend" disabled="disabled">Recommend</option>
	    <option value="delete"    disabled="disabled">Delete</option>
	</select>
	</fieldset>
</div>
<input class="btnsubmit" id="moveto_pending" type="submit" name="pending" value="Add to Pending" />
<input class="btnsubmit" id="moveto_posted" type="submit" name="posted" value="Add to Posted" />
</form>
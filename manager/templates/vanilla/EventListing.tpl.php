<form action="?list=<?php echo $this->status; ?>" id="formlist" name="formlist" method="post">
<div class="eventAction">
	<div class="eventButtonAction">
		<a href="#" class="checkall" onclick="setCheckboxes('formlist',true); return false">Check All</a>
		<a href="#" class="uncheckall" onclick="setCheckboxes('formlist',false); return false">Uncheck All</a>
	</div>
	<br />
	<fieldset class="eventFieldsetAction">
	<label for="action">Action</label>
	<select name="action" onfocus="manager.list = '<?php echo $this->status; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
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
<th scope="col" class="title"><a href="?list=<?php echo @$_GET['list']; ?>&amp;orderby=title">Event Title</a></th>
<th scope="col" class="date"><a href="?list=<?php echo @$_GET['list']; ?>&amp;orderby=starttime">Date</a></th>
<th scope="col" class="edit">Edit</th>
</tr>
</thead>
<tbody>
<?php
$oddrow = false;
foreach ($this->events as $e) {
	$rec = (isset($e->recurrence_id)) ? 'rec'.$e->recurrence_id : '';
	$row = '<tr id="row'.$e->id.'"';
	if (isset($_GET['new_event_id']) && $_GET['new_event_id']==$e->id) {
		if ($oddrow){
		$row .= ' class="updated alt"';
		} else{
		$row .= ' class="updated"';
		}
	} elseif ($oddrow) {
		$row .= ' class="alt"';
	}
	$row .= ' onclick="highlightLine(this,'.$e->id.');">';
	$oddrow = !$oddrow;
	$row .=	'<td class="select"><input type="checkbox" onclick="checknegate('.$e->id.')" name="event'.$e->id.$rec.'" />' .
			'<td class="title">'.$e->title.'</td>' .
			'<td class="date">';
	if (isset($e->recurrence_id)) {
		$rec = UNL_UCBCN::factory('recurringdate');
		$rec->event_id = $e->id;
		$rec->recurrence_id = $e->recurrence_id;
		$rec->find(true);
	}
	$edt = UNL_UCBCN::factory('eventdatetime');
	$edt->event_id = $e->id;
	$edt->orderBy('starttime DESC');
	$instances = $edt->find();
	if ($instances) {
		$row .= '<ul>';
			while ($edt->fetch()) {
			    if (isset($e->recurrence_id)) {
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
			    $row .= '<li><abbr class="dtstart" title="'.date(DATE_ISO8601, $starttime).'">'.$datestring.'</abbr></li>';
			}
		$row .= '</ul>';
    } else {
            $row .= 'Unknown';
    }
	$row .= '</td>' .
			'<td class="edit">';
	if (UNL_UCBCN::userCanEditEvent($_SESSION['_authsession']['username'],$e)) {
		if (isset($e->recurrence_id)) {
		    $row .= "<a onclick='showConfirmationDialog(\"{$e->id}\", \"{$e->recurrence_id}\");'>Edit</a>";
		} else {
			$row .= '<a href="?action=createEvent&amp;id='.$e->id.'">Edit</a>';
		}
	}
	$row .=		'</td></tr>';
	echo $row;
} ?>
</tbody>
</table>
<div class="eventAction">
	<div class="eventButtonAction">
		<a href="#" class="checkall" onclick="setCheckboxes('formlist',true); return false">Check All</a>
		<a href="#" class="uncheckall" onclick="setCheckboxes('formlist',false); return false">Uncheck All</a>
	</div>
	<br />
	<fieldset class="eventFieldsetAction">
	<label for="action">Action</label>
	<select name="action" onfocus="manager.list = '<?php echo $this->status; ?>'; return manager.updateActionMenus(this)" onchange="return manager.actionMenuChange(this)">
	    <option>Select action...</option>
	    <option value="posted"    disabled="disabled">Add to Posted</option>
	    <option value="pending"   disabled="disabled">Move to Pending</option>
	    <option value="recommend" disabled="disabled">Recommend</option>
	    <option value="delete"    disabled="disabled">Delete</option>
	</select>
	</fieldset>
</div>
<div style='visibility: hidden'>
<input class="btnsubmit" id="delete_event" type="submit" name="delete" onclick="return confirm('Are you sure? \n\nYour event is not automatically deleted from the master calendar. To delete, contact College Communications, communications@cornellcollege.edu, with the name and date of the deleted event.');" value="Delete" />
<?php if ($this->status=='posted' || $this->status=='archived') { ?>
<input class="btnsubmit" id="moveto_pending" type="submit" name="pending" value="Move to Pending" />
<?php } elseif ($this->status=='pending') { ?>
<input class="btnsubmit" id="moveto_posted" type="submit" name="posted" value="Add to Posted" />
<?php } ?>
</div>
</form>

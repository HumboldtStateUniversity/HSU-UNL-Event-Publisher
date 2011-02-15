<h3>Recommend <span class="title">'<?php echo $this->events[0]->title; ?>'</span> for other calendars:</h3>
<form action="<?php echo $this->manager->uri; ?>?action=recommend" method="post">
<?php
foreach ($this->events as $event) {
    echo '<input type="hidden" name="event'.$event->id.'" value="true" />';
}
require_once 'HTML/Table.php';
require_once 'UNL/UCBCN/Calendar_has_event.php';
$t = new HTML_Table(array('id'=>'recommend_cals'));
$t->addRow(array('Calendars You Can Send This Event To'),array('colspan'=>'3'), 'TH');

$t->addRow(array('Calendar', 'Pending', 'Posted'), null, 'TH');
foreach ($this->calendars as $calendar_id=>$permissions) {
    $calendar = UNL_UCBCN_Manager::factory('calendar');
    $calendar->get($calendar_id);
    $elid        = 'cal'.$calendar->id;
    $posted      = '';
    $pending     = '';
    $curr_status = false;
    if (count($this->events) == 1) {
        $curr_status = UNL_UCBCN_Calendar_has_event::calendarHasEvent($calendar, $this->events[0]);
    }
    if ($curr_status === false) {
        if (isset($permissions['Event Post'])) {
            $posted = HTML_QuickForm::createElement('radio', $elid, null, null, 'Event Post');
            $posted = $posted->toHtml();
        }
        if (isset($permissions['Event Send Event to Pending Queue'])) {
            $pending = HTML_QuickForm::createElement('radio', $elid, null, null, 'Event Send Event to Pending Queue');
            $pending = $pending->toHtml();
        }
    } else {
        if ($curr_status == 'pending') {
            $pending = 'X';
        } else {
            $posted = 'X';
        }
    }
    $t->addRow(array("<label for='{$elid}'>".$calendar->name.'</label>', $pending, $posted));
}

if (count($this->recommendable)) {
    $t->addRow(array('Calendars Accepting Reccomendations'),array('colspan'=>'3'), 'TH');
    $t->addRow(array('Calendar', 'Pending', 'Posted'), null, 'TH');
    
    foreach ($this->recommendable as $calendar_id) {
        $calendar = UNL_UCBCN_Manager::factory('calendar');
        $calendar->get($calendar_id);
        $elid        = 'cal'.$calendar->id;
        $posted      = '';
        $pending     = '';
        $curr_status = false;
        if (count($this->events) == 1) {
            $curr_status = UNL_UCBCN_Calendar_has_event::calendarHasEvent($calendar, $this->events[0]);
        }
        if ($curr_status === false) {
            if (isset($permissions['Event Send Event to Pending Queue'])) {
                $pending = HTML_QuickForm::createElement('radio', $elid, null, null, 'Event Send Event to Pending Queue');
                $pending = $pending->toHtml();
            }
        } else {
            if ($curr_status == 'pending') {
                $pending = 'X';
            } else {
                $posted = 'X';
            }
        }
        $t->addRow(array("<label for='{$elid}'>".$calendar->name.'</label>', $pending, $posted));
    }
}

echo $t->toHtml();
?>
<input type="submit" value="Go" />
</form>
<script type="text/javascript">
try {stripe('recommend_cals');} catch(e) {}
</script>
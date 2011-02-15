<?php
/**
 * Basic functions related to the event submission form.
 *
 * PHP version 5
 *
 * @category  Events
 * @package   UNL_UCBCN_Manager
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
require_once 'UNL/UCBCN/Manager/jscalendar.php';

/**
 * This class generates a form for an Event.
 *
 * @category  Events
 * @package   UNL_UCBCN_Manager
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Manager_EventForm
{
    /**
     * Constructs the form.
     *
     * @param UNL_UCBCN_Manager $manager The manager object requesting the form.
     */
    function __construct(UNL_UCBCN_Manager $manager)
    {
        $this->manager =& $manager;
    }
    
    /**
     * Returns an HTML form for an event.
     *
     * @param int $id The ID of the event to convert to a form.
     *
     * @return string | object HTML on success UNL_UCBCN_Error on failure.
     */
    function toHtml($id=null)
    {
        $events = UNL_UCBCN_Manager::factory('event');
        if (isset($id) && $id > 0) {
            if (!$events->get($id)) {
                return new UNL_UCBCN_Error('Error, the event with that record was not found!');
            }
        }
        $form = new HTML_QuickForm('unl_ucbcn_event', 'post', $this->manager->uri.'?action=createEvent');
        $fb   =& UNL_UCBCN_Manager_FormBuilder::create($events, false, 'UCBCN_QuickForm', 'UNL_UCBCN_Manager_FormBuilder');
        $form->setDefaults(array(
                    'uidcreated'        => $this->manager->user->uid,
                    'uidlastupdated'    => $this->manager->user->uid));
        $fb->useForm($form);
        $form =& $fb->getForm($this->manager->uri.'?action=createEvent');
        
        if (isset($events->id)) {
            $form->insertElementBefore(HTML_QuickForm::createElement('header', 'eventlocationheader', 'Event Location, Date and Time'), 'optionaldetailsheader');
            $form->insertElementBefore(HTML_QuickForm::createElement('static', 'datestimes', $this->getRelatedLocationDateAndTimes($events)),
                'optionaldetailsheader');
            if (isset($_REQUEST['rec']) && isset($_REQUEST['recid'])) {
                $form->addElement(HTML_QuickForm::createElement('hidden', 'rec', $_REQUEST['rec']));
                $form->addElement(HTML_QuickForm::createElement('hidden', 'recid', $_REQUEST['recid']));
            }
        }
        
        if ($form->isSubmitted() && $form->validate()) {
            // Form has passed the client/server validation and can be inserted.
            /* If this is an update, first check to see if the current user has permission to edit
             * events for the calendar the event was originally posted on.
             */
            $result = $form->process(array(&$fb, 'processForm'), false);
            if ($result) {
                // EVENT Has been added... now check permissions and add to selected calendars.
                $add = $this->addToCalendar($events);
                if ($add===true) {
                    $this->manager->localRedirect($this->manager->uri.'?list=posted&new_event_id='.$events->id);
                } else {
                    // Probably a permissions error.
                    return $add;
                }
            }
            $form->freeze();
            $form->removeElement('__submit__');
        }
        $renderer = new HTML_QuickForm_Renderer_Tableless();
        $renderer->addStopFieldsetElements(array(
                                                    '__submit__'
                                                    ));
        $form->accept($renderer);
        return $renderer->toHtml();
    }
    
    /**
     * Adds an event to the calendar currently in the manager member variable calendar.
     *
     * @param UNL_UCBCN_Event $event The event to add to the calendar.
     *
     * @return bool
     */
    function addToCalendar(UNL_UCBCN_Event $event)
    {
        $che =& UNL_UCBCN::calendarHasEvent($this->manager->calendar, $event);
        if ($che===false) {
            // This calendar does not already have this event.
            switch (true) {
            case $this->manager->userHasPermission($this->manager->user, 'Event Post', $this->manager->calendar):
                $this->manager->addCalendarHasEvent($this->manager->calendar, $event, 'posted', $this->manager->user, 'create event form');
                break;
            case $this->manager->userHasPermission($this->manager->user, 'Event Send Event to Pending Queue', $this->manager->calendar):
                $this->manager->addCalendarHasEvent($this->manager->calendar, $event, 'pending', $this->manager->user, 'create event form');
                break;
            default:
                return new UNL_UCBCN_Error('Sorry, you do not have permission to post an event, or send an event to the Calendar "'.$this->manager->calendar->name.'".');
            }
        } else {
            $che->uidlastupdated  = $this->manager->user->uid;
            $che->datelastupdated = date('Y-m-d H:i:s');
            $che->update();
        }
        return true;
    }
    
    /**
     * Returns a html table of event date time and locations
     *
     * @param UNL_UCBCN_Event $event The event to get related eventdatetime objects for.
     *
     * @return string html
     */
    function getRelatedLocationDateAndTimes(UNL_UCBCN_Event $event)
    {
        if (isset($_REQUEST['rec']) && isset($_REQUEST['recid'])) {
            $recinfo = '&rec='.$_REQUEST['rec'].'&recid='.$_REQUEST['recid'];
        } else {
            $recinfo = '';
        }
        $edt = UNL_UCBCN::factory('eventdatetime');
        $edt->selectAdd('UNIX_TIMESTAMP(starttime) AS starttimeu, UNIX_TIMESTAMP(endtime) AS endtimeu, UNIX_TIMESTAMP(recurs_until) AS recurs_untilu');
        $edt->event_id = $event->id;
        $edt->orderBy('starttime DESC');
        $instances = $edt->find();
        if ($instances) {
            include_once 'HTML/Table.php';
            $table = new HTML_Table(array('class'=>'eventlisting'));
            $table->addRow(array('Start Time', 'End Time', 'Recurs Until', 'Location', 'Edit', 'Delete'), null, 'TH');
            $instances = 0;
            while ($edt->fetch()) {
                $etime = '';
                $stime = '';
                $rutime = '';
                if (isset($edt->location_id)
                    && $l = $edt->getLink('location_id')) {
                    $location = $l->name;
                } else {
                    $location = 'Unknown';
                }
                if ($instances) {
                    $delete = '<a onclick="return confirm(\'Are you sure?\');" href="'.$this->manager->uri.'?action=eventdatetime&delete='.$edt->id.'">Delete</a>';
                } else {
                    $delete = '';
                }
                // Fix eventdatetime information if this is a recurring event
                if (isset($_REQUEST['recid'])) {
                    $rec = UNL_UCBCN::factory('recurringdate');
                    $rec->event_id = $edt->event_id;
                    $rec->recurrence_id = $_REQUEST['recid'];
                    $rec->ongoing = 'FALSE';
                    $rec->find(true);
                    $rtime = $rec->recurringdate;
                    $rec = UNL_UCBCN::factory('recurringdate');
                    $rec->event_ID = $edt->event_id;
                    $rec->recurrence_id = 0;
                    $rec->ongoing = 'FALSE';
                    $rec->find(true);
                    $starttime = $rtime.' '.substr($edt->starttime, 11);
                    $diff = strtotime($rtime) - strtotime($rec->recurringdate);
                    $edt->starttime = $starttime;
                    $edt->starttimeu = strtotime($starttime);
                    $edt->endtime = date('Y-m-d', strtotime(substr($edt->endtime, 0, 10)) + $diff).' '.substr($edt->endtime, 11);
                    $edt->endtimeu = strtotime($edt->endtime);
                }
                if (substr($edt->starttime, 11) != '00:00:00') {
                    $stime .= '<li>'.date('M jS g:ia', $edt->starttimeu).'</li>';
                } else {
                    $stime .= '<li>'.date('M jS', $edt->starttimeu).'</li>';
                }
                if (substr($edt->endtime, 11) != '00:00:00') {
                    $etime .= '<li>'.date('M jS g:ia', $edt->endtimeu).'</li>';
                } elseif ($edt->endtime != '0000-00-00 00:00:00') {
                    $etime .= '<li>'.date('M jS', $edt->endtimeu).'</li>';
                }
                if ($edt->recurs_until != null && substr($edt->recurs_until, 11) != '00:00:00') {
                	$rutime .= '<li>'.date('M jS g:ia', $edt->recurs_untilu).'</li>';
                } elseif ($edt->recurs_until != null && $edt->recurs_until != '00:00:00') {
                	$rutime .= '<li>'.date('M jS', $edt->recurs_untilu).'</li>';
                }
                //$rutime .= '<li>'.date('M jS', $edt->recurs_untilu).'</li>';
                $instances = $table->addRow(array($stime,
                                    $etime,
                                    $rutime,
                                    $location,
                                    '<a href="'.$this->manager->uri.'?action=eventdatetime&id='.$edt->id.$recinfo.'">Edit</a>',
                                    $delete));
            }
            if (!isset($_REQUEST['rec']) && !isset($_REQUEST['recid'])) {
                $table->addRow(array('<a class="subsectionlink" href="'.$this->manager->uri.'?action=eventdatetime&event_id='.$event->id.'">Add additional location, date and time.</a>'));
            }
            $table->setColAttributes(4, 'class="edit"');
            $table->setColAttributes(5, 'class="delete"');
            return $table->toHtml();
        } else {
            return 'Could not find any related event date and times.';
        }
    }
}

?>
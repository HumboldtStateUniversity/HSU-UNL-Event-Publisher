<?php
/**
 * This class contains the information needed for viewing the list of upcoming
 * events within the calendar system.
 * 
 * PHP version 5
 * 
 * @category  Events
 * @package   UNL_UCBCN_Frontend
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License 
 * @version   CVS: $id$
 * @link      http://code.google.com/p/unl-event-publisher/
 */

require_once 'UNL/UCBCN/Frontend.php';
require_once 'UNL/UCBCN/Frontend/MonthWidget.php';
require_once 'UNL/UCBCN/EventListing.php';

/**
 * A list of upcoming events for a calendar.
 * 
 * @category  Events
 * @package   UNL_UCBCN_Frontend
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License 
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Frontend_Upcoming extends UNL_UCBCN
{
    /**
     * Calendar UNL_UCBCN_Calendar Object
     * 
     * @var UNL_UCBCN_Calendar
     */
    public $calendar;
    
    /**
     * Listing of events on this day.
     * 
     * @var UNL_UCBCN_EventListing
     */
    public $output;
    
    /**
     * URL to the listing of upcoming events.
     * 
     * @var string
     */
    public $url;
    
    /**
     * Message for when no upcoming events are found.
     */
    public $noevents = 'Sorry, no events could be found.';
    
    /**
     * Limit the number of records.
     *
     * @var int
     */
    public $limit = 10;
    
    /**
     * Constructs an upcoming event view for this calendar.
     * 
     * @param array $options Associative array of options.
     */
    public function __construct($options)
    {
        parent::__construct($options);
        if (!isset($this->calendar)) {
            $this->calendar = UNL_UCBCN::factory('calendar');
            if (!$this->calendar->get(1)) {
                return new UNL_UCBCN_Error('No calendar specified or could be found.');
            }
        }
        $this->output = $this->showEventListing();
        $this->url    = $this->getURL();
    }
    
    /**
     * UNL_UCBCN_EventListing of events.
     * 
     * @return UNL_UCBCN_EventListing
     */
    public function showEventListing()
    {
        $options = array('calendar'=> $this->calendar,
                         'limit'   => $this->limit);
        // Fetch the day evenlisting for this day.
        $eventlist = new UNL_UCBCN_EventListing('upcoming', $options);
        
        if (count($eventlist->events)) {
            return $eventlist;
        } else {
            include_once 'UNL/UCBCN/Frontend/NoEvents.php';
            return new UNL_UCBCN_Frontend_NoEvents($this->noevents);
        }
    }
    
    /**
     * Get a permanent URL to this object.
     * 
     * @return string URL to this specific upcoming.
     */
    public function getURL()
    {
        return UNL_UCBCN_Frontend::formatURL(array('upcoming'=>'upcoming',
                                                   'calendar'=>$this->calendar->id));
    }
    
}

?>
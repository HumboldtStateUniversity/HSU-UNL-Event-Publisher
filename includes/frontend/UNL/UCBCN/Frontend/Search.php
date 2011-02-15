<?php
/**
 * Search class for frontend users to search for events.
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
 * @todo      Add searching by eventtype.
 */

require_once 'UNL/UCBCN/Frontend.php';
require_once 'UNL/UCBCN/EventListing.php';

/**
 * Container for search results for the frontend.
 *
 * PHP version 5
 * 
 * @category  Events
 * @package   UNL_UCBCN_Frontend
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License 
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Frontend_Search extends UNL_UCBCN_Frontend
{
    /**
     * Calendar we are searching
     *
     * @var object UNL_UCBCN_Calendar
     */
    public $calendar;
    
    /**
     * Listing of matching events or error.
     * 
     * @var UNL_UCBCN_EventListing
     */
    public $output;
    
    /**
     * actual search string user entered
     *
     * @var string
     */
    public $query;
    
    /**
     * Start time to search
     */
    public $starttime;
    
    /**
     * end time to search
     */
    public $endtime;
    
    /** URL to this search results page. */
    public $url;
    
    /**
     * Constructs this search output.
     * 
     * @param array $options Associative array of options.
     */
    public function __construct($options=array())
    {
        $this->view = 'search';
        $this->setOptions($options);
        $this->url = $this->getURL();
        $this->run();
    }
    
    /**
     * Runs the query on the database from the calendar, currently supports two types of
     * searches - textual or date and time hinted by passing a string supported by the strtotime() function.
     * 
     * Text searches:
     * Title, Description, Location
     * 
     * @return void
     */
    public function run()
    {
        $this->query = trim($this->query);
        if (!empty($this->query)) {
            $mdb2 = $this->calendar->getDatabaseConnection();
            $sql  = 'SELECT DISTINCT eventdatetime.id 
                    FROM event, eventdatetime, calendar_has_event, location, 
                      eventtype, event_has_eventtype
                    WHERE
                        event_has_eventtype.event_id = event.id AND
                        event_has_eventtype.eventtype_id = eventtype.id AND
                        eventdatetime.event_id = event.id AND 
                        calendar_has_event.event_id = event.id AND 
                        calendar_has_event.status != \'pending\' AND 
                        calendar_has_event.calendar_id = '.$this->calendar->id.' AND 
                        eventdatetime.location_id = location.id AND ';
            if (($t = strtotime($this->query)) && ($this->query != 'art')) {
                // This is a time...
                $sql .= 'eventdatetime.starttime LIKE \''.date('Y-m-d', $t).'%\' ORDER BY eventdatetime.starttime';
            } else {
                // Do a textual search.
                $sql .= 
                        '(event.title LIKE \'%'.$mdb2->escape($this->query).'%\' OR '.
                        '(eventtype.name LIKE \'%'.$mdb2->escape($this->query).'%\') OR '.

                        'event.description LIKE \'%'.$mdb2->escape($this->query).'%\' OR '.
                        '(location.name LIKE \'%'.$mdb2->escape($this->query).'%\')) AND '.
                        '(eventdatetime.starttime>=\''.date('Y-m-d').' 00:00:00\' OR '.
                        'eventdatetime.endtime>\''.date('Y-m-d').' 00:00:00\')

                        ORDER BY eventdatetime.starttime ASC';
            }
            $res = $mdb2->query($sql);
            if (!PEAR::isError($res)) {
                $this->output       = new UNL_UCBCN_EventListing();
                $this->output->type = 'search';
                while ($row = $res->fetchRow()) {
                    $this->output->events[] =  new UNL_UCBCN_EventInstance($row[0]);
                }
            } else {
                $this->output = new UNL_UCBCN_Error('Error, the search could not be completed: '.$res->getMessage().'<br />Query:'.htmlentities($sql));
            }
        } else {
            $this->output = 'Enter a search string to search for events.';
        }
    }
    
    /**
     * returns the url to this search page.
     *
     * @return string
     */
    public function getURL()
    {
        return UNL_UCBCN_Frontend::formatURL(array( 'search'  => 'search',
                                                    'q'       => urlencode($this->query),
                                                    'calendar'=>$this->calendar->id));
    }
    
}

?>

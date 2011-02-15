<?php
/**
 * This is the primary viewing interface for the events.
 * This would be the 'model/controller' if you follow that paradigm.
 *
 * This file contains functions used throughout the frontend views.
 *
 * PHP version 5
 *
 * @category  Events
 * @package   UNL_UCBCN_Frontend
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 * @todo      Add new output formats such as serialized PHP, XML, and JSON.
 */


/**
 * This is the basic frontend output class through which all output to the public is
 * generated. This class handles the determination of what view the user requested
 * and what information to send.
 *
 * @category  Events
 * @package   UNL_UCBCN_Frontend
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Frontend extends UNL_UCBCN implements UNL_UCBCN_Cacheable
{
    /**
     * Calendar UNL_UCBCN_Calendar Object
     *
     * @var UNL_UCBCN_Calendar
     */
    public $calendar;
    
    /**
     * Year the user is viewing.
     *
     * @var int
     */
    public $year;
    
    /**
     * Month the user is viewing.
     *
     * @var int
     */
    public $month;
    
    /**
     * Day to show events for
     *
     * @var int
     */
    public $day;
    
    /**
     * Specific eventdatetime_id (if used)
     *
     * @var int
     */
    public $eventdatetime_id = null;
    
    /**
     * URI to the management frontend
     *
     * @var string
     */
    public $uri = '';
    
    /**
     * Format of URI's  querystring|rest
     *
     * @var string
     */
    public $uriformat = 'querystring';
    
    /**
     * URI to the management interface UNL_UCBCN_Manager
     *
     * @var string EG: http://events.unl.edu/manager/
     */
    public $manageruri = '';
    
    /**
     * Right column (usually the month widget)
     *
     * @var string
     */
    public $right;
    
    /**
     * Unique body ID
     *
     * @var string
     */
    public $uniquebody;
    
    /**
     * Main content of the page sent to the client.
     *
     * @var mixed
     */
    public $output;
    
    /**
     * Section Title
     *
     * @var string
     */
    public $sectitle;
    
    /**
     * View to be displayed
     *
     * @var string
     */
    public $view = 'day';
    
    /**
     * format of view
     *
     * @var string
     */
    public $format = 'html';
    
    /**
     * whether this is a top level page or a sub-tab
     * @param $options
     */
    public $top_level = false;
    
    /**
     * Constructor for the frontend.
     *
     * @param array $options Associative array of options for the frontend.
     */
    function __construct($options)
    {
        parent::__construct($options);
        if (!isset($this->calendar)) {
            $this->calendar = UNL_UCBCN_Frontend::factory('calendar');
            if (PEAR::isError($this->calendar)) {
                throw new Exception($this->calendar->message);
            } else {
                if (isset($_GET['calendar_id'])) {
                    $this->calendar->get($_GET['calendar_id']);
                } elseif (!$this->calendar->get($this->default_calendar_id)) {
                    return new UNL_UCBCN_Error('No calendar specified or could be found.');
                }
            }
        }
    }
    
    /**
     * This function is called before the run() function to handle
     * any details prior to populating the data in the object, and
     * sends output headers.
     *
     * @param bool $cache_hit if data is already cached or not.
     *
     * @return void
     */
    function preRun($cache_hit = false)
    {
        // Send headers for CORS support so calendar bits can be pulled remotely
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With');
        if (isset($_SERVER['REQUEST_METHOD'])
            && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // short circuit execution for CORS OPTIONS reqeusts
            exit();
        }
        switch($this->format) {
        case 'ics':
            // We'll be outputting a ics file
            header('Content-type: text/calendar');
            header('Content-Disposition: attachment; filename="events.ics"');
            break;
        case 'json':
        	header('Content-Type:text/plain; charset=UTF-8');
        	break;
        case 'xml':
            header('Content-type: text/xml');
            break;
        case 'rss':
            header('Content-type: application/rss+xml');
            break;
        case 'html':
        case 'hcalendar':
            header('Content-Type:text/html; charset=UTF-8');
            break;
        }
        /*
        if ($cache_hit == true) {
            // cached output is about to be sent to the browser.
        } else {
            // output is not already cached.
        }
        */
    }
    
    /**
     * Runs/builds the frontend object with the display parameters set.
     * This function will populate all of the output and member variables with the
     * data for the current view.
     *
     * @return void
     */
    function run()
    {
        switch($this->view) {
        case 'upcoming':
            if (isset($_GET['limit'])) {
                $limit = intval($_GET['limit']);
            } else {
                $limit = 10;
            }
            $this->output[] = new UNL_UCBCN_Frontend_Upcoming(array(
                                                'dsn'=>$this->dsn,
                                                'calendar'=>$this->calendar,
                                                'limit'=>$limit));
            $this->right    = new UNL_UCBCN_Frontend_MonthWidget(date('Y'), date('m'), $this->calendar);
            break;
        case 'event':
            if (isset($_GET['eventdatetime_id'])) {
                $id = (int) $_GET['eventdatetime_id'];
            }
            $event_id = null;
            if (isset($_GET['event_id'])) {
                $event_id = (int) $_GET['event_id'];
            }
            $this->output[] = $this->getEventInstance($id, $this->calendar, $event_id);
            $this->right    = new UNL_UCBCN_Frontend_MonthWidget($this->year,
                                                              $this->month,
                                                              $this->calendar);
            break;
        default:
        case 'day':
            $this->output[] = new UNL_UCBCN_Frontend_Day(array(
                                        'dsn'     => $this->dsn,
                                        'year'    => $this->year,
                                        'month'   => $this->month,
                                        'day'     => $this->day,
                                        'calendar'=> $this->calendar));
            $this->right    = new UNL_UCBCN_Frontend_MonthWidget($this->year,
                                                              $this->month,
                                                              $this->calendar);
            break;
        case 'week':
            $this->output[] = new UNL_UCBCN_Frontend_Week(array(
                                        'dsn'     => $this->dsn,
                                        'year'    => $this->year,
                                        'month'   => $this->month,
                                        'day'     => $this->day,
                                        'calendar'=> $this->calendar));
            break;
        case 'month':
            $this->output[] = new UNL_UCBCN_Frontend_Month($this->year, $this->month, $this->calendar, $this->dsn);
            break;
        case 'monthwidget':
            UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend', 'Frontend_hcalendar');
            $this->output[] = new UNL_UCBCN_Frontend_MonthWidget($this->year, $this->month, $this->calendar);
            break;
        case 'year':
            $this->output[] = new UNL_UCBCN_Frontend_Year($this->year, $this->calendar);
            break;
        case 'search':
            $q = null;
            if (isset($_GET['q'])) {
                $q = $_GET['q'];
            }
            $this->output[] = new UNL_UCBCN_Frontend_Search(array('calendar'=>$this->calendar, 'query'=>$q));
            break;
        case 'image':
            $this->displayImage();
            break;
        }
        switch($this->format) {
        case 'json':
            UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend', 'Frontend_json');
            break;
        case 'xml':
            UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend', 'Frontend_xml');
            break;
        case 'hcalendar':
            UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend', 'Frontend_hcalendar');
            break;
        case 'ics':
        case 'ical':
        case 'icalendar':
            UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend', 'Frontend_icalendar');
            break;
        case 'rss':
            UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend', 'Frontend_rss');
            break;
        case 'stub':
        	UNL_UCBCN::outputTemplate('UNL_UCBCN_Frontend', 'Frontend_stub');
        	break;
        case 'html':
        default:
            // Standard template works for html.
            break;
        }
    }
    
    /**
     * Gets the specified event instance.
     *
     * @param int                $id       The id of the event instance to get.
     * @param UNL_UCBCN_Calendar $calendar The calendar to get the event for.
     *
     * @return object UNL_UCBCN_EventInstance on success UNL_UCBCN_Error on error.
     */
    function getEventInstance($id, $calendar=null, $event_id=null)
    {
        // Get recurring dates, if any
        if (isset($event_id)) {
            $db  = $this->getDatabaseConnection();
            $sql = 'SELECT recurringdate FROM recurringdate WHERE event_id='.$event_id.';';
            $res = $db->query($sql);
            $rdates = $res->fetchCol();
        }
        $event_instance = new UNL_UCBCN_EventInstance($id, $calendar);
        if (isset($_GET['y'], $_GET['m'], $_GET['d'])) {
            $in_date   = str_replace(array('/',' '), '', $_GET['y'].'-'.$_GET['m'].'-'.$_GET['d']);
            $in_date   = date('Y-m-d', strtotime($in_date));
            $real_date = $date = date('Y-m-d', strtotime($event_instance->eventdatetime->starttime));
 
            // Check if the date is a recurring date for this event
            if (isset($rdates) && in_array($in_date, $rdates)) {
                //$starttime =& $event_instance->eventdatetime->starttime;
                //$starttime = $in_date . substr($starttime, 10);
                $sql = 'SELECT recurringdate, recurrence_id, ongoing FROM recurringdate '.
                       'WHERE event_id='.$event_id.' '.
                   	   'AND recurringdate LIKE \''.$in_date.'\';';
                $res = $db->query($sql);
                $rinfo = $res->fetchRow();
                $event_instance->fixRecurringEvent($event_instance, $rinfo[0], $rinfo[1], $rinfo[2]);
            }
            // Verify the date is correct, otherwise, redirect to the correct location.
            else if ($in_date != $real_date) {
                header('HTTP/1.0 301 Moved Permanently');
                header('Location: '.html_entity_decode($event_instance->url));
                exit;
            }
        }
        return $event_instance;
    }
    
    /**
     * Returns a formatted URL.
     *
     * @param array $values Associative array of the values to add to the URL
     * @param bool  $encode If true and format is querystring, ampersands will be &amp;
     *
     * @return string URL to a frontend which has the data in the format requested.
     */
    function formatURL($values,$encode = true)
    {
        $order = array('calendar','upcoming','search','y','m','d','eventdatetime_id','q', 'event_id');
        global $_UNL_UCBCN;
        $url = '';
        if (isset($_UNL_UCBCN['uri']) && !empty($_UNL_UCBCN['uri'])) {
            $url = $_UNL_UCBCN['uri'];
        }
        switch(UNL_UCBCN_Frontend::uriFormat()) {
        case 'rest':
        case 'REST':
            foreach ($order as $val) {
                if (isset($values[$val])) {
                    if ($val == 'calendar' && isset($_UNL_UCBCN['default_calendar_id'])) {
                        /* A calendar needs to be formmatted into the URL.
                         * We need to take care to not include it if it is the
                         * default calendar.
                         */
                        if (is_numeric($values[$val])) {
                            $cid = $values[$val];
                        } else {
                            $cid = UNL_UCBCN_Frontend::getCalendarID($values[$val]);
                        }
                        if ($cid != $_UNL_UCBCN['default_calendar_id']) {
                            // This is link is not for the default calendar, add it to the url.
                            $url .= UNL_UCBCN_Frontend::getCalendarShortname($cid).'/';
                        }
                    } else {
                        $url .= $values[$val].'/';
                    }
                }
            }
            // Final check for the format (rss, ics, etc).
            if (isset($values['format'])) {
                $url .= '?format='.$values['format'];
            }
            break;
        case 'querystring':
        default:
        	$url .= '?';
            foreach ($order as $val) {
                if (isset($values[$val])) {
                    if ($val == 'calendar' && isset($_UNL_UCBCN['default_calendar_id'])) {
                        if (is_numeric($values[$val])) {
                            $cid = $values[$val];
                        } else {
                            $cid = UNL_UCBCN_Frontend::getCalendarID($values[$val]);
                        }
                        if ($cid != $_UNL_UCBCN['default_calendar_id']) {
                            // This is link is not for the default calendar, add it to the url.
                            $url .= 'calendar_id='.$values[$val];
                        }
                    } else {
                        $url .= $val.'='.$values[$val];
                    }
                    if ($encode == true) {
                        $url .= '&amp;';
                    } else {
                        $url .= '&';
                    }
                }
            }
            // Final check for the format (rss, ics, etc).
            if (isset($values['format'])) {
                $url .= 'format='.$values['format'];
            }
            break;
        }
        return $url;
    }
    
    /**
     * This function is for reformmating URL address. IE, you have the
     * url to the object, but simply want to change the format to ics etc.
     *
     * @param string $url    Url of the form http://
     * @param array  $values Associative array of values to apply. format
     *
     * @return string The URL reformatted to a different output format.
     */
    function reformatURL($url, $values)
    {
        if (isset($values['format'])) {
            switch(UNL_UCBCN_Frontend::uriFormat()) {
            case 'rest':
            case 'REST':
                $url .= '?format='.$values['format'];
                break;
            case 'querystring':
            default:
                $url .= 'format='.$values['format'];
                break;
            }
        }
        return $url;
    }
    
    /**
     * Sets and/or returns the uri format.
     *
     * @param string $set optional string, pass it to set the uriFormat, don't pass it to retrieve.
     *
     * @return string rest or querystring
     */
    function uriFormat($set=null)
    {
        global $_UNL_UCBCN;
        if (isset($set)) {
            switch($set){
            case 'rest':
            case 'REST':
                $format = 'rest';
                break;
            case 'querystring':
            default:
                $format = 'querystring';
                break;
            }
            $_UNL_UCBCN['uriformat'] = $format;
        } else {
            if (isset($_UNL_UCBCN['uriformat'])) {
                $format = $_UNL_UCBCN['uriformat'];
            } else {
                $format = 'querystring';
            }
        }
        return $format;
    }
    
    /**
     * This function attempts to determine the view parameters for the frontend output.
     *
     * @param string $method The HTTP method to use for determining views-GET | POST
     *
     * @return array options to be sent to the constructor.
     */
    function determineView($method='GET')
    {
        $view = array();
        switch ($method) {
        case 'GET':
        case '_GET':
        case 'get':
        default:
            $method = '_GET';
            break;
        case 'post':
        case 'POST':
        case '_POST':
            $method = '_POST';
            break;
        }
        $view['view'] = 'day';
        if (isset($GLOBALS[$method]['y'])&&!empty($GLOBALS[$method]['y'])) {
            $view['year'] = (int)$GLOBALS[$method]['y'];
            $view['view'] = 'year';
        } else {
            $view['year'] = date('Y');
        }
        if (isset($GLOBALS[$method]['m'])&&!empty($GLOBALS[$method]['m'])) {
            $view['view']  = 'month';
            $view['month'] = (int)$GLOBALS[$method]['m'];
        } else {
            $view['month'] = date('m');
        }
        if (isset($GLOBALS[$method]['d'])&&!empty($GLOBALS[$method]['d'])) {
            $view['view'] = 'day';
            $view['day']  = (int)$GLOBALS[$method]['d'];
        } else {
            $view['day'] = date('j');
        }
        if (isset($GLOBALS[$method]['s'])) {
            $view['view'] = 'week';
        }
        if (isset($GLOBALS[$method]['eventdatetime_id'])&&!empty($GLOBALS[$method]['eventdatetime_id'])) {
            $view['view']             = 'event';
            $view['eventdatetime_id'] = $GLOBALS[$method]['eventdatetime_id'];
        }

        if (isset($GLOBALS[$method]['image'])) {
            $view['view'] = 'image';
        }

        if (isset($GLOBALS[$method]['month'])) {
            $view['view'] = 'month';
        }
        if (isset($GLOBALS[$method]['search'])) {
            $view['view'] = 'search';
        }
        if (isset($GLOBALS[$method]['upcoming'])) {
            $view['view'] = 'upcoming';
        }
        if (isset($GLOBALS[$method]['monthwidget'])) {
            $view['view'] = 'monthwidget';
        }
        
        if (isset($GLOBALS[$method]['format'])) {
            $view['format'] = $GLOBALS[$method]['format'];
        } else {
            $view['format'] = 'html';
        }
        return $view;
    }
    
    /**
     * Get's a uniqe key for this object for reference in cache.
     *
     * @return string A unique identifier for this view of the calendar.
     */
    function getCacheKey()
    {
        if ($this->view == 'search' || $this->view == 'upcoming') {
            // Right now we aren't caching search results or upcoming pages.
            return false;
        } else {
            return md5(serialize(array_merge($this->determineView(),
                                             array($this->calendar->id))));
        }
    }
    
    /**
     * Returns a calendar shortname for the calendar with the given ID.
     *
     * @param int $id Calendar ID within the database.
     *
     * @return int on success, false on error.
     */
    function getCalendarShortname($id)
    {
        $c = UNL_UCBCN_Frontend::factory('calendar');
        if ($c->get($id)) {
            return $c->shortname;
        } else {
            return false;
        }
    }
    
    /**
     * Gets the calendar id from a shortname.
     *
     * @param string $shortname The value for the shortname field in the calendar table.
     *
     * @return int id on success, false on error.
     */
    function getCalendarID($shortname)
    {
        $c            = UNL_UCBCN_Frontend::factory('calendar');
        $c->shortname = $shortname;
        if ($c->find() && $c->fetch()) {
            return $c->id;
        } else {
            return false;
        }
    }
    
    /**
     * Get a list of calendars with a given status
     *
     * @param string $status The value of the status in the calendar table
     *
     * @return
     */
    function getCalendarsByStatus($status)
    {
		$c = UNL_UCBCN_Frontend::factory('calendar');
		$c->calendarstatus = $status;
		$c->orderBy('name ASC');
		if($c->find()){
			return $c;
		}else{
			return false;
		}
    }
    
    /**
     * Get a list of event types
     *
     * @return
     */
    function getEventTypes()
    {
		$e = UNL_UCBCN_Frontend::factory('eventtype');
		$e->orderBy('name ASC');
		if($e->find()){
			return $e;
		}else{
			return false;
		}
    }
    
    /**
     * This function converts a string stored in the database to html output.
     * & becomes &amp; etc.
     *
     * @param string $t Normally a varchar string from the database.
     *
     * @return String encoded for output to html.
     */
    function dbStringToHtml($text)
    {
        $text = str_replace(array('&amp;', '&'), array('&', '&amp;'), $text);
        return nl2br($text);
    }
    
    /**
     * This function checks if a calendar has events on the day requested.
     *
     * @param string             $epoch    Unix epoch of the day to check.
     * @param UNL_UCBCN_Calendar $calendar The calendar to check.
     *
     * @return bool true or false
     */
    function dayHasEvents($epoch, $calendar = null)
    {
        
        if (isset($calendar)) {
            $db  =& $calendar->getDatabaseConnection();
            $res =& $db->query('SELECT DISTINCT eventdatetime.id FROM calendar_has_event,eventdatetime
                                WHERE calendar_has_event.calendar_id='.$calendar->id.'
                                AND (calendar_has_event.status =\'posted\'
                                     OR calendar_has_event.status =\'archived\')
                                AND calendar_has_event.event_id = eventdatetime.event_id
                                AND (eventdatetime.starttime LIKE \''.date('Y-m-d', $epoch).'%\'
                                    OR (eventdatetime.starttime<\''.date('Y-m-d 00:00:00', $epoch).'\'
                                        AND eventdatetime.endtime > \''.date('Y-m-d 00:00:00', $epoch).'\'))
                                LIMIT 1');
            if (!PEAR::isError($res)) {
                return $res->numRows();
            } else {
                return new UNL_UCBCN_Error($res->getMessage());
            }
        } else {
            $eventdatetime = UNL_UCBCN_Frontend::factory('eventdatetime');
            $eventdatetime->whereAdd('starttime LIKE \''.date('Y-m-d', $epoch).'%\'');
            return $eventdatetime->find();
        }
    }
    
    /**
     * When the image view is set, the image for a given event will be displayed
     * to the end user.  $_GET['id'] must be set to the event.id which has the image.
     *
     * @return void
     */
    function displayImage()
    {
        if (isset($_GET['id'])) {
            $event = UNL_UCBCN_Frontend::factory('event');
            if ($event->get($_GET['id'])) {
                header('Content-type: '.$event->imagemime);
                echo $event->imagedata;
                exit();
            }
        }
        header('HTTP/1.0 404 Not Found');
        echo '404';
        exit(0);
    }
}
?>
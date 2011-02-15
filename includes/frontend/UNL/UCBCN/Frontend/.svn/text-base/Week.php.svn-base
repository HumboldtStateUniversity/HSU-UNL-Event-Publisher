<?php
/**
 * This class contains the information needed for viewing a month view calendar.
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
require_once 'UNL/UCBCN/Frontend/Day.php';
require_once 'Calendar/Calendar.php';
require_once 'Calendar/Week.php';

/**
 * Constructs a week view for the calendar.
 * 
 * @category  Events
 * @package   UNL_UCBCN_Frontend
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Frontend_Week extends UNL_UCBCN
{
    /**
     * Calendar to show events for UNL_UCBCN_Month object
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
     * Day included in the week the user is viewing.
     * 
     * @var int
     */
    public $day;
    
    /**
     * start day of the week 0 = Sunday
     * 
     * @var int
     */
    public $firstDay = 0;
    
    /**
     * Display ongoing events?
     * 
     * @var bool
     */
    public $ongoing = true;
    
    /**
     * Listing of events on this week. This will be an array of 7 
     * UNL_UCBCN_Frontend_Day objects.
     * 
     * @var array
     */
    public $output;
    
    /**
     * URL to this view.
     * 
     * @var string
     */
    public $url;
    
    /**
     * URL to the next week
     * 
     * @var string
     */
    public $next_url;
    
    /**
     * URL to the previous week
     * 
     * @var string
     */
    public $prev_url;
    
    /**
     * Constructs this week object.
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
        $this->output[] = $this->showWeek();
        $this->url = $this->getURL();
    }
    
    /**
     * Populates the output with the days for the events in this week.
     * 
     * @return void
     */
    public function showWeek()
    {
        $week = new Calendar_Week($this->year,
                                  $this->month,
                                  $this->day,
                                  $this->firstDay);
        $week->build();
        while ($day = $week->fetch()) {
            $this->output[] = new UNL_UCBCN_Frontend_Day(array(
                            'year'=>$day->thisYear(),
                            'month'=>$day->thisMonth(),
                            'day'=>$day->thisDay(),
                            'calendar'=>$this->calendar,
                            'dsn'=>$this->dsn,
                            'ongoing'=>$this->ongoing));
        }
    }

    /**
     * Returns the permanent URL to this specific week.
     * 
     * @return string URL to this week.
     */
    public function getURL()
    {
        return UNL_UCBCN_Frontend::formatURL(array('s'=>$this->firstDay,
                                                   'd'=>$this->day,
                                                   'm'=>$this->month,
                                                   'y'=>$this->year,
                                                   'calendar'=>$this->calendar->id));
    }
}
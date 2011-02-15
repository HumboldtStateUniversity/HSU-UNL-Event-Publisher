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
require_once 'Calendar/Month/Weekdays.php';
require_once 'Calendar/Util/Textual.php';

/**
 * Object for a month view of the calendar.
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
class UNL_UCBCN_Frontend_Month extends UNL_UCBCN
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
     * Contains an array of Frontend_Day objects
     *
     * @var array
     */
    public $weeks = array();
    
    /**
     * This function constructs the month widget and populates the heading,
     * caption, footer and body for the MonthWidget.
     * 
     * @param int                $y        Year
     * @param int                $m        Month
     * @param UNL_UCBCN_Calendar $calendar Calendar to display events from.
     * @param string             $dsn      EG: mysqli://events:events@host/events
     */
    public function __construct($y,$m,$calendar,$dsn)
    {
        $this->year     = (int) $y;
        $this->month    = (int) $m;
        $this->calendar = $calendar;
        $Month          = new Calendar_Month_Weekdays($y, $m, 0);
        $PMonth         = $Month->prevMonth('object'); // Get previous month as object
        $prev           = UNL_UCBCN_Frontend::formatURL(array(
                                                'y'       => $PMonth->thisYear(),
                                                'm'       => $PMonth->thisMonth(),
                                                'calendar'=> $this->calendar->id));
        $NMonth         = $Month->nextMonth('object');
        $next           = UNL_UCBCN_Frontend::formatURL(array(
                                                'y'       => $NMonth->thisYear(),
                                                'm'       => $NMonth->thisMonth(),
                                                'calendar'=> $this->calendar->id));
        
        $this->caption  = '
        <span><a href="'.$prev.'" id="prev_month" title="View events for '.
              Calendar_Util_Textual::thisMonthName($PMonth).' '.$PMonth->thisYear().
              '">&lt;&lt; </a></span>
        <span class="monthvalue" id="'.
              Calendar_Util_Textual::thisMonthName($Month).'"><a href="'.
              UNL_UCBCN_Frontend::formatURL(array('y'       => $Month->thisYear(),
                                                  'm'       => $Month->thisMonth(),
                                                  'calendar'=> $this->calendar->id)).
                                                  '">'.
              Calendar_Util_Textual::thisMonthName($Month).'</a></span>
        <span class="yearvalue"><a href="'.
              UNL_UCBCN_Frontend::formatURL(array('y'       => $Month->thisYear(),
                                                  'calendar'=> $this->calendar->id)).
                                                  '">'.$Month->thisYear().
                                                  '</a></span>
        <span><a href="'.$next.'" id="next_month" title="View events for '.
              Calendar_Util_Textual::thisMonthName($NMonth).' '.$NMonth->thisYear().
              '"> &gt;&gt;</a></span>
        ';
        
        //Determine selected days
        $selectedDays = array();
        $Month->build($selectedDays);
        
        //Update recurring events table
        UNL_UCBCN::factory('recurringdate')->getRecurringDates($Month);
        
        $week = count($this->weeks);
        while ( $Day = $Month->fetch() ) {
            $this->weeks[$week][] = new UNL_UCBCN_Frontend_Day(array(
                                            'dsn'     => $dsn,
                                            'year'    => $Day->thisYear(),
                                            'month'   => $Day->thisMonth(),
                                            'day'     => $Day->thisDay(),
                                            'calendar'=> $this->calendar,
                                            'ongoing' => false,
                                            'noevents'=> ''));
            if ( $Day->isLast() ) {
                $week++;
            }
        }
    }
}

?>
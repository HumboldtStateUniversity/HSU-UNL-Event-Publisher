<?php
/**
 * This class defines a 30 day widget containing information for a given month.
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


/**
 * Class defines a month widget, basically a table with 30 boxes representing the
 * days in the month. Days which have events will be selected.
 *
 * @category  Events
 * @package   UNL_UCBCN_Frontend
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Frontend_MonthWidget extends UNL_UCBCN implements UNL_UCBCN_Cacheable
{

    /**
     * Calendar UNL_UCBCN_Calendar Object
     *
     * @var UNL_UCBCN_Calendar
     */
    public $calendar;
    
    /**
     * Year for this month widget
     *
     * @var int
     */
    public $year;
    
    /**
     * Month for this month widget.
     *
     * @var int
     */
    public $month;
    
    /**
     * Caption for the month widget.
     *
     * @var string
     */
    public $caption;
    
    /**
     * Header for the table
     *
     * @var string
     */
    public $thead;
    
    /**
     * Body for the table
     *
     * @var string
     */
    public $tbody;
    
    /**
     * This function constructs the month widget and populates the heading,
     * caption, footer and body for the MonthWidget.
     *
     * @param int                $y        Year
     * @param int                $m        Month
     * @param UNL_UCBCN_Calendar $calendar Calendar to prepare a month widget for.
     */
    public function __construct($y,$m,$calendar=null)
    {
        $this->year     = intval($y);
        $this->month    = intval($m);
        $this->calendar = $calendar;
    }
    
    /**
     * Returns a string identifying this month widget.
     *
     * @return string A string of the form monthwidget_2006-05-12_1
     */
    public function getCacheKey()
    {
        $str = 'monthwidget_'.$this->year.'-'.$this->month.'-'.date('d');
        if (isset($this->calendar)) {
            $str .= '_'.$this->calendar->id;
        }
        return $str;
    }
    
    /**
     * Runs before any output is sent or object is built.
     *
     * @param bool $cache_hit True if cached output is ready to be sent, or false.
     *
     * @return void
     */
    public function preRun($cache_hit = false)
    {
        // Do stuff here if needed.
    }
    
    /**
     * This function populates the month widget... checks for each
     * day of this month whether the day has events.
     *
     * @return void
     */
    public function run()
    {
        $Month  = new Calendar_Month_Weekdays($this->year, $this->month, 0);
        $PMonth = $Month->prevMonth('object'); // Get previous month as object
        $prev   = UNL_UCBCN_Frontend::formatURL(array('y'       =>$PMonth->thisYear(),
                                                      'm'       =>$PMonth->thisMonth(),
                                                      'calendar'=>$this->calendar->id));
        $NMonth = $Month->nextMonth('object');
        $next   = UNL_UCBCN_Frontend::formatURL(array('y'       =>$NMonth->thisYear(),
                                                      'm'       =>$NMonth->thisMonth(),
                                                      'calendar'=>$this->calendar->id));

        $this->caption = '
        <span><a href="'.$prev.'" id="prev_month" title="View events for '.Calendar_Util_Textual::thisMonthName($PMonth).' '.$PMonth->thisYear().'">&lt;&lt; </a></span>
        <span class="monthvalue" id="'.Calendar_Util_Textual::thisMonthName($Month).'"><a href="'.
                        UNL_UCBCN_Frontend::formatURL(array('y'=>$Month->thisYear(),
                                                            'm'=>$Month->thisMonth(),
                                                            'calendar'=>$this->calendar->id)).'">'.Calendar_Util_Textual::thisMonthName($Month).'</a></span>
        <span class="yearvalue"><a href="'.
                        UNL_UCBCN_Frontend::formatURL(array('y'=>$Month->thisYear(),
                                                            'calendar'=>$this->calendar->id)).'">'.$Month->thisYear().'</a></span>
        <span><a href="'.$next.'" id="next_month" title="View events for '.Calendar_Util_Textual::thisMonthName($NMonth).' '.$NMonth->thisYear().'"> &gt;&gt;</a></span>
        ';

        //Determine selected days
        $Month->build();
        $selected_days    = $this->dailyEventCount($Month);
        $ongoing_events   = $this->findOngoingEvents($Month);
        $recurring_events = UNL_UCBCN::factory('recurringdate')->getRecurringDates($Month, $this->calendar);
        

        while ( $Day = $Month->fetch() ) {
    
            // Build a link string for each day
            $link = UNL_UCBCN_Frontend::formatURL(array('y'=>$Day->thisYear(),
                                                        'm'=>$Day->thisMonth(),
                                                        'd'=>$Day->thisDay(),
                                                        'calendar'=>$this->calendar->id));
            
            $class = '';
            if ($Day->thisMonth('timestamp')<$Month->getTimestamp()) {
                $class = 'prev';
            } elseif ($Day->thisMonth('timestamp')>$Month->getTimestamp()) {
                $class = 'next';
            }
            
            if(date('Y') == $Day->thisYear() && date('m') == $Day->thisMonth() && date('d') == $Day->thisDay()){
                $class .= ' today';
            }
            
            // isFirst() to find start of week
            if ( $Day->isFirst() )
                $this->tbody .= "<tr>\n";
                //UNL_UCBCN_Frontend::dayHasEvents($Day->getTimestamp(),$this->calendar)
            if ( in_array(date('m-d', $Day->getTimestamp()), $selected_days)
                || in_array(date('m-d', $Day->getTimestamp()), $ongoing_events)
                || in_array(date('m-d', $Day->getTimestamp()), $recurring_events) ) {
                $this->tbody .= "<td class='selected {$class}'><a href='$link'>".$Day->thisDay()."</a></td>\n";
            } else if ( $Day->isEmpty() ) {
                $this->tbody .= "<td class='{$class}'>".$Day->thisDay()."</td>\n";
            } else {
                $this->tbody .= "<td class='{$class}'>".$Day->thisDay()."</td>\n";
            }
            
            // isLast() to find end of week
            if ( $Day->isLast() )
                $this->tbody .= "</tr>\n";
        }
    }
    
    /**
     * Determines the days of this month with events.
     *
     * @param Calendar_Month $month Month to find events in.
     *
     * @return an array with values representing the days with events.
     */
    public function dailyEventCount($month)
    {
        $db          =& $this->calendar->getDatabaseConnection();
        $days        = $month->fetchAll();
        $start_bound = date('Y-m-d', $days[1]->getTimestamp());
        $end_bound   = date('Y-m-d', $days[count($days)]->getTimestamp());
        $sql         = "SELECT DATE_FORMAT(eventdatetime.starttime,'%m-%d') AS day,
                               count(*) AS events
		                FROM calendar_has_event,eventdatetime
		                WHERE calendar_has_event.calendar_id={$this->calendar->id}
		                AND (calendar_has_event.status ='posted'
                             OR calendar_has_event.status ='archived')
		                AND calendar_has_event.event_id = eventdatetime.event_id
		                AND (eventdatetime.recurringtype = ''
		                     OR eventdatetime.recurringtype = 'none') 
		                AND eventdatetime.starttime >= '$start_bound 00:00:00'
						AND eventdatetime.starttime <= '$end_bound 23:59:59'
		                GROUP BY day;";
        $res         =& $db->queryCol($sql);
        return $res;
    }
    
    /**
     * This function finds ongoing events for the given month.
     *
     * @param Calendar_Month $month Month to find ongoing events for.
     *
     * @return array
     */
    public function findOngoingEvents($month)
    {
        $db      =& $this->calendar->getDatabaseConnection();
        $queries = array();
        $sql     = "CREATE TABLE IF NOT EXISTS `ongoingcheck` (`d` DATE NOT NULL DEFAULT '".date('Y-m-d')."', PRIMARY KEY ( `d` ))";
        $res     =& $db->query($sql);
        if (!PEAR::isError($res)) {
            while ( $day = $month->fetch() ) {
                $strdate = date('Y-m-d', $day->getTimestamp());
                if (!isset($firstday)) {
                    $firstday = $strdate;
                }
                $lastday = $strdate;
                $sql     = "INSERT INTO ongoingcheck VALUES ('$strdate');";
                $db->query($sql);
            }
            $sql = "SELECT DATE_FORMAT(og.d,'%m-%d') AS day, count(*) AS events
                FROM calendar_has_event,eventdatetime,ongoingcheck AS og
                WHERE calendar_has_event.calendar_id={$this->calendar->id}
                AND (calendar_has_event.status ='posted'
                     OR calendar_has_event.status ='archived')
                AND calendar_has_event.event_id = eventdatetime.event_id
                AND (eventdatetime.starttime < og.d
                     AND eventdatetime.endtime >= og.d)
                AND og.d >= '$firstday' AND og.d <= '$lastday'
                GROUP BY day;";
            $res =& $db->queryCol($sql);
            if (PEAR::isError($res)) {
                return array();
            } else {
                return $res;
            }
        } else {
            return array();
        }
    }
    
}

?>

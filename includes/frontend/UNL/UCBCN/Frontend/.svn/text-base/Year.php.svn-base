<?php
/**
 * This class is for the frontend view for an entire year.
 * 
 * It contains basically 4 rows of 3 months, for a total of 12
 * monthwidgets.
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

require_once 'UNL/UCBCN.php';
require_once 'UNL/UCBCN/Frontend/MonthWidget.php';

/**
 * Generates a year view for the public frontend.
 *
 * @category  Events
 * @package   UNL_UCBCN_Frontend
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License 
 * @link      http://code.google.com/p/unl-event-publisher/ 
 */
class UNL_UCBCN_Frontend_Year extends UNL_UCBCN
{
    /**
     * Year to show events for.
     *
     * @var int
     */
    public $year;
    
    /**
     * Array of month widgets - UNL_UCBCN_Frontend_MonthWidget
     *
     * @var array
     */
    public $monthwidgets = array();
    
    /**
     * Calendar to display year for.
     *
     * @var UNL_UCBCN_Calendar
     */
    public $calendar;
    
    /**
     * Constructor for a year calendar.
     *
     * @param int                $y        Year to render
     * @param UNL_UCBCN_Calendar $calendar Calendar to grab events for.
     */
    public function __construct($y,$calendar)
    {
        $this->year     = $y;
        $this->calendar = $calendar;
        $m              = 1;
        for ($m=1;$m<=12;$m++) {
            $this->monthwidgets[] = new UNL_UCBCN_Frontend_MonthWidget($this->year, $m, $this->calendar);
        }
    }
}
?>
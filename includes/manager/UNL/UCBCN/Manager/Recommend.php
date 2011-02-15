<?php
/**
 * This file handles recommendations for other calendars.
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

/**
 * This class handles recommending an event for other calendars.
 *
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Manager_Recommend
{
    /**
     * Management interface to build a recommend page for.
     *
     * @var UNL_UCBCN_Manager
     */
    public $manager;
    
    /**
     * Events to build a recommendation form for.
     *
     * @var array of UNL_UCBCN_Event
     */
    public $events;
    
    /**
     * Calendars which this user has permission to post events to by permission.
     *
     * @var array
     */
    public $calendars;
    
    /**
     * Calendars which this user can recommend events to.
     * 
     * @var array
     */
    public $recommendable;
    
    public function __construct(UNL_UCBCN_Manager &$manager, $events)
    {
        if (count($events) > 0) {
            $submitted       = false;
            $this->manager   = $manager;
            $this->events    = $events;
            $permissions     = array('Event Post', 'Event Send Event to Pending Queue');
            $cal_rows        = $this->getCalendarsWithPermission($this->manager->user, $permissions);
            $this->calendars = array();
            foreach ($cal_rows as $cal) {
                if (isset($_POST['cal'.$cal[0]]) && $_POST['cal'.$cal[0]] == $cal[1]) {
                    $submitted = true;
                    $calendar  = UNL_UCBCN_Manager::factory('calendar');
                    $calendar->get($cal[0]);
                    switch ($_POST['cal'.$cal[0]]) {
                    case 'Event Post':
                        $status = 'posted';
                        break;
                    case 'Event Send Event to Pending Queue':
                        $status = 'pending';
                        break;
                    default:
                        // Should never enter here.
                        throw new Exception('unknown event status sent');
                    }
                    foreach ($this->events as $event) {
                        $calendar->addEvent($event, $status, $this->manager->user, 'recommended');
                    }
                }
                $this->calendars[$cal[0]][$cal[1]] = 1;
            }
            $recommendable = UNL_UCBCN_Manager::factory('calendar');
            $recommendable->account_id = $this->manager->account->id;
            $recommendable->recommendationswithinaccount = true;
            if ($recommendable->find()) {
                while ($recommendable->fetch()) {
                    if (isset($_POST['cal'.$recommendable->id])) {
                        $submitted = true;
                        foreach ($this->events as $event) {
                            $recommendable->addEvent($event, 'pending', $this->manager->user, 'recommended');
                        }
                    }
                    $this->recommendable[] = $recommendable->id;
                }
            }
            if ($submitted && !empty($this->manager->uri)) {
                // We have processed the recommendations. Redirect.
                $this->manager->localRedirect($this->manager->uri);
                exit();
            }
        } else {
            return new UNL_UCBCN_Error('No events selected!');
        }
    }
    
    /**
     * Returns an array of the allowed permissions by calendar.
     *
     * @param UNL_UCBCN_User $user        User to check.
     * @param array          $permissions Array of permission names to check for.
     * 
     * @return array in the form of $arr[{calendar_id}][{permission.name}] = 1
     */
    public function getCalendarsWithPermission(UNL_UCBCN_User $user, array $permissions)
    {
        $db = $user->getDatabaseConnection();
        $sql = array();
        foreach ($permissions AS $name) {
            $safe_perm = $db->escape($name);
            $sql[] =  "SELECT uhp.calendar_id, p.name AS permission, c.name AS calname
                       FROM user_has_permission AS uhp, permission AS p, calendar AS c
                       WHERE uhp.user_uid = '{$this->manager->user->uid}'
                           AND uhp.permission_id = p.id
                           AND p.name = '$safe_perm'
                           AND uhp.calendar_id = c.id";
        }
        return $db->queryAll(implode(' UNION ',$sql).' ORDER BY calname, calendar_id, permission;');
    }
    
}
?>
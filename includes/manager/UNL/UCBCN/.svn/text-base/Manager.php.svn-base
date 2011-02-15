<?php
/**
 * This class extends the UNL UCBerkeley Calendar backend system to create
 * a management frontend. It handles authentication for the user and allows
 * insertion of event details into the calendar backend.
 * It allows authenticated users to submit new events into the system.
 *
 * PHP version 5
 *
 * @category  Events
 * @package   UNL_UCBCN_Manager
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @author    Alvin Woon <alvinwoon@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */

require_once 'UNL/UCBCN.php';
require_once 'UNL/UCBCN/Manager/FormBuilder.php';
require_once 'HTML/QuickForm.php';
require_once 'Auth.php';
require_once 'UNL/UCBCN/EventListing.php';
// Custom quickform renderer.
require_once 'UNL/UCBCN/Manager/Tableless.php';
require_once 'UNL/UCBCN/Manager/Login.php';
require_once 'UNL/UCBCN/Manager/FormBuilder_Driver.php';
require_once 'HTML/QuickForm/group.php';

/**
 * For pagination of results.
 */
require_once 'Pager/Pager.php';

/**
 * Class which handles all event creation and authentication. This class acts as the basis for the
 * management portion of a university event publisher, through which users will log in and create and manage
 * their calendars.
 *
 * @category  Events
 * @package   UNL_UCBCN_Manager
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @author    Alvin Woon <alvinwoon@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Manager extends UNL_UCBCN
{

    /**
     * Auth object
     *
     * @var object
     */
    public $a;
    
    /**
     * UNL_UCBCN_Account
     *
     * @var object
     */
    public $account;
    
    /**
     * UNL_UCBCN_Calendar this user is managing.
     *
     * @var object
     */
    public $calendar;
    
    /**
     * UNL_UCBCN_User object for the user who is logged in and managing a calendar.
     *
     * @var UNL_UCBCN_User
     */
    public $user;
    
    /**
     * URI to the management frontend
     *
     * @var string
     */
    public $uri = '';
    
    /**
     * URI to the public frontend UNL_UCBCN_Frontend
     *
     * @var string
     */
    public $frontenduri = '';

    /**
     * Indicates which calendars you have access to.
     *
     * @var array|string
     */
    public $calendarselect;
    
    /**
     * Unique body ID
     * @var string
     */
    public $uniquebody;
    
    /**
     * Main content of the page sent to the client.
     * @var mixed
     */
    public $output;
    
    /**
     * Title for the page.
     * @var string
     */
    public $doctitle;
    
    /**
     * Section Title
     * @var string
     */
    public $sectitle;

    /**
     * Registered and running plugins.
     *
     * @var array
     */
    public $plugins = array();
    
    /**
     * Constructor for the UNL_UCBCN_Manager.
     *
     * @param array $options Associative array with options to set for member variables.
     */
    function __construct($options)
    {
        $this->setOptions($options);
        $this->setupDBConn();
        if (!isset($this->a)) {
            throw new Exception('No authentication mechanism defined.');
        }
        if (isset($_GET['logout'])) {
            $this->endSession();
            $this->a->logout();
        }
        $this->a->start();
        if ($this->a->checkAuth()) {
            $this->startSession();
            $this->startupPlugins();
            UNL_UCBCN::archiveEvents($this->calendar);
        }
    }
    
    /**
     * This function initializes all plugins.
     *
     * @return void
     */
    function startupPlugins()
    {
        global $_UNL_UCBCN;
        $ds         = DIRECTORY_SEPARATOR;
        if ('@PHP_DIR@' == '@'.'PHP_DIR@') {
            // We're running from an SVN checkout, and not an install
            $plugin_dir = dirname(__FILE__).$ds.'Manager'.$ds.'Plugins';
        } else {
            $plugin_dir = '@PHP_DIR@'.$ds.'UNL'.$ds.'UCBCN'.$ds.'Manager'.$ds.'Plugins';
        }
        if (is_dir($plugin_dir)) {
            if ($handle = opendir($plugin_dir)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..' && substr($file, -4) == '.php') {
                        include_once $plugin_dir.$ds.$file;
                    }
                }
                closedir($handle);
            }
        }
        if (isset($_UNL_UCBCN['plugins'])) {
            foreach ($_UNL_UCBCN['plugins'] as $plug_class) {
                if (class_exists($plug_class)) {
                    try {
                        $plugin = new $plug_class();
                        $plugin->startup($this, $this->uri.'?action=plugin&amp;p='.$plug_class);
                        $this->plugins[$plug_class] = $plugin;
                    } catch(Exception $e) {
                        echo 'Caught trying to start plugin \''.$plug_class.'\': ',  $e->getMessage(), "\n";
                    }
                }
            }
        }
    }
    
    /**
     * Begins a calendar management session for this user.
     *
     * @return void
     */
    function startSession()
    {
        // User has entered correct authentication details, now find get their user record.
        $this->user              = $this->getUser($this->a->getUsername());
        $this->session           = UNL_UCBCN::factory('session');
        $this->session->user_uid = $this->user->uid;
        if (!$this->session->find()) {
            $this->session->user_uid   = $this->user->uid;
            $this->session->lastaction = date('Y-m-d H:i:s');
            $this->session->insert();
        } else {
            $this->session->fetch();
        }
        $this->account = $this->getAccount($this->user);
        if (isset($_GET['calendar_id']) ||
            (isset($this->user->calendar_id) && ($this->user->calendar_id != 0))) {
            $this->calendar = UNL_UCBCN_Manager::factory('calendar');
            if (isset($_GET['calendar_id'])) {
                $cid = $_GET['calendar_id'];
            } else {
                $cid = $this->user->calendar_id;
            }
            if (!$this->calendar->get($cid)) {
                // Could not get the calendar in the session or $_GET
                $this->calendar = $this->getCalendar($this->user, $this->account, false, '?action=calendar&new=true');
            }
        } else {
            $this->calendar = $this->getCalendar($this->user, $this->account, false, '?action=calendar&new=true');
        }
        if ($this->user->calendar_id != $this->calendar->id) {
            // Set the user's calendar_id to remember their default calendar.
            $this->user->calendar_id = $this->calendar->id;
            $this->user->update();
        }
        $_SESSION['calendar_id'] = $this->calendar->id;
    }
    
    /**
     * Ends a calendar management session for the current user.
     *
     * @return void
     */
    function endSession()
    {
        unset($_SESSION['calendar_id']);
    }
    
    /**
     * Returns login object which will be used for the user to authenticate with.
     *
     * @return object UNL_UCBCN_Manager_Login.
     */
    function showLoginForm()
    {
        return new UNL_UCBCN_Manager_Login();
        
    }
    
    /**
     * Returns a form for entering/editing an event.
     *
     * @param int $id ID of the event to retrieve and generate a form for.
     *
     * @return string HTML form for entering an event into the database.
     */
    function showEventSubmitForm($id = null)
    {
        include_once 'UNL/UCBCN/Manager/EventForm.php';
        $form = new UNL_UCBCN_Manager_EventForm($this);
        return $form->toHtml($id);
    }
    
    /**
     * Returns a html form for importing xml/.ics files.
     *
     * @return string HTML form for uploading a file.
     */
    function showImportForm()
    {
        $form = new HTML_QuickForm('import', 'POST', $this->uri.'?action=import');
        $form->addElement('header', 'importhead', 'Import iCalendar .ics/xml:');
        $form->addElement('file', 'filename', 'Filename');
        $form->addElement('submit', 'Submit', 'Submit');
        $renderer = new HTML_QuickForm_Renderer_Tableless();
        $form->accept($renderer);
        return $renderer->toHtml();
    }
    
    /**
     * This function is the hub for the manager frontend.
     * All output sent to the client is set up here, based
     * on querystring parameters and authentication level.
     *
     * @param string $action A manual action to send to the client.
     *
     * @return none.
     */
    function run($action='')
    {
        $this->doctitle = 'UNL Event Publishing System';
        if (isset($this->user)) {
            // User is authenticated, and an account has been chosen.
            $this->calendarselect[] = $this->showChooseCalendar();
            if (empty($action) && isset($_GET['action'])) {
                $action = $_GET['action'];
            }
            if (isset($_GET['calendar_id']) && $_GET['calendar_id'] == 'new') {
                $action = "newCal";
            }
            switch ($action) {
            case 'newCal':
                //echo "newCal";
                $this->sectitle   = 'Create Additional Calendar';
                $this->output[] = $this->showCalendarForm(true);
                break;
            case 'createEvent':
                $this->uniquebody = 'id="create"';
                $this->sectitle   = 'Create/Edit Event';
                if ($this->userHasPermission($this->user, 'Event Create', $this->calendar)) {
                    if (isset($_GET['id'])) {
                        $id = (int)$_GET['id'];
                    } elseif (isset($_POST['id'])) {
                        $id = (int)$_POST['id'];
                    } else {
                        $id = null;
                    }
                    $this->output[] = $this->showEventSubmitForm($id);
                } else {
                    $this->output = new UNL_UCBCN_Error('Sorry, you do not have permission to create events.');
                }
                break;
            case 'eventdatetime':
                if ($this->userHasPermission($this->user, 'Event Create', $this->calendar)) {
                    $this->output[] = $this->showEventDateTimeForm();
                } else {
                    $this->output = new UNL_UCBCN_Error('Sorry, you do not have permission to create events.');
                }
                break;
            case 'import':
                $this->output[]   = $this->showImportForm();
                $this->uniquebody = 'id="import"';
                $this->sectitle   = 'Import .ics or .xml Event';
                break;
            case 'recommend':
                $this->output[] = $this->showRecommendForm();
                break;
            case 'search':
                UNL_UCBCN::outputTemplate('UNL_UCBCN_EventListing', 'EventListing_search');
                $this->uniquebody = 'id="search"';
                $this->sectitle   = 'Event Search';
                $this->output[]   = $this->showSearchResults();
                break;
            case 'subscribe':
                $this->uniquebody = 'id="subscribe"';
                $this->sectitle   = 'Subscribe to Events';
                $this->output[]   = '<p>Subscriptions allow you to automatically add events to your calendar which match a given set of criteria.
                                    This feature allows the College of Engineering\'s Calendar to automatically add all events posted to the Electrical Engineering calendar.</p>';
                $this->output[]   = $this->showSubscriptions();
                $this->output[]   = $this->showSubscribeForm();
                break;
            case 'account':
                $this->output = array();
                if (isset($_GET['new']) && $_GET['new']=='true') {
                    $this->output[] =    '<p>Welcome to the University Event publishing system!</p>'.
                                        '<p>We\'ve created an account for you, simply enter in the additional details to begin publishing your events!</p>';
                }
                $this->output[] = $this->showAccountForm();
                $this->output[] = '<h3>Calendars Under This Account:</h3>';
                $this->output[] = $this->showCalendars();
                $this->sectitle = 'Edit '.$this->account->name.' Info';
                break;
            case 'permissions':
                $this->sectitle = 'Edit User Permissions for '.$this->calendar->name;
                if (isset($_GET['uid'])) {
                    $uid = $_GET['uid'];
                } else {
                    $uid = null;
                }
                $this->output = $this->showPermissionsForm($uid, $this->calendar);
                break;
            case 'users':
                $this->output[] = '<h3>Users With Access to this Calendar:</h3>';
                $this->output[] = $this->showCalendarUsers();
                if ($this->userHasPermission($this->user, 'Calendar Add User', $this->calendar)) {
                    $this->output[] = $this->showAddUserForm();
                }
                break;
            case 'calendar':
                $this->output = array();
                if (isset($_GET['new']) && $_GET['new']=='true') {
                    $this->output[] =    '<p>Welcome to the University Event publishing system!</p>'.
                                        '<p>We\'ve created a calendar for you, simply enter in the additional details to begin publishing your events!</p>'.
                                        '<p>Your calendar name is the title of your calendar, and will be displayed with all your events.</p>';
                }
                $this->output[] = $this->showCalendarForm();
                $this->output[] = '<p class="clr"><a href="'.$this->uri.'?action=users">Users &amp; Permissions</a></p>';
                $this->sectitle = 'Edit '.$this->calendar->name.' Info';
                break;
            case 'plugin':
                if (isset($_GET['p']) && isset($this->plugins[$_GET['p']])) {
                    $this->plugins[$_GET['p']]->run();
                    $this->output[] = $this->plugins[$_GET['p']]->output;
                } else {
                    $this->output = new UNL_UCBCN_Error('That plugin does not exist.');
                }
                break;
            default:
                $this->uniquebody = 'id="normal"';
                if (isset($_GET['list'])) {
                    $list = $_GET['list'];
                } else {
                    $list = 'pending';
                }
                $orderby = '';
                if (isset($_GET['orderby'])) {
                    $orderby = $_GET['orderby'];
                }
                switch ($list) {
                case 'pending':
                case 'posted':
                case 'archived':
                    $this->sectitle = ucfirst($list).' Events';
                    $this->output[] = $this->showEventListing($list, $orderby);
                    break;
                default:
                    $this->output[] = $this->showEventListing('pending', $orderby);
                    $this->sectitle = 'Pending Events';
                    break;
                }
                break;
            }
        } else {
            // User is not logged in.
            $this->sectitle   = 'Event Manager Login';
            $this->uniquebody = 'id="login"';
            $this->output     = $this->showLoginForm();
        }
        $this->doctitle .= ' | '.$this->sectitle;
    }
    
    /**
     * Returns a form for subscribing to a calendar within the system.
     *
     * @return string HTML form
     */
    function showSubscribeForm()
    {
        $subscription =& UNL_UCBCN_Manager::factory('subscription');
        if (isset($_GET['id'])) {
            $subscription->get($_GET['id']);
        }
        $fb   =& DB_DataObject_FormBuilder::create($subscription);
        $form =& $fb->getForm($this->uri.'?action=subscribe');
        if (isset($subscription->searchcriteria)) {
            $form->setDefaults(array('calendar_id'=>$this->calendar->id,
                                     'searchcriteria'=>$subscription->getCalendars($subscription->searchcriteria)));
        } else {
            $form->setDefaults(array('calendar_id'=>$this->calendar->id));
        }
        $renderer = new HTML_QuickForm_Renderer_Tableless();
        $form->accept($renderer);
        if ($form->validate()) {
            if ((isset($subscription->id) && UNL_UCBCN::userHasPermission($this->user, 'Calendar Edit Subscription', $this->calendar))
                    || UNL_UCBCN::userHasPermission($this->user, 'Calendar Add Subscription', $this->calendar)) {
                $form->process(array(&$fb, 'processForm'), false);
                $form->freeze();
                $form->removeElement('__submit__');
                // Add new subscription.
                return '<p>Your subscription has been added.</p>';
            } else {
                return new UNL_UCBCN_Error('You do not have permission to add/edit subscriptions!');
            }
        } else {
            return $renderer->toHtml();
        }
    }
    
    /**
     * Returns a listing of the subscriptions for the current calendar.
     *
     * @return string html list of subscriptions.
     */
    function showSubscriptions()
    {
        $subscriptions              = UNL_UCBCN_Manager::factory('subscription');
        $subscriptions->calendar_id = $this->calendar->id;
        if ($subscriptions->find()) {
            $list = array('<ul>');
            while ($subscriptions->fetch()) {
                $li = '<li>'.$subscriptions->name;
                // Provide Edit link if the user has permission.
                if (UNL_UCBCN::userHasPermission($this->user, 'Calendar Edit Subscription', $this->calendar)) {
                    $li .= ' <a href="'.$this->uri.'?action=subscribe&amp;id='.$subscriptions->id.'">Edit</a>';
                }
                // Show Delete link if the user has permission to delete.
                if (UNL_UCBCN::userHasPermission($this->user, 'Calendar Delete Subscription', $this->calendar)) {
                    if (isset($_GET['delete']) && $_GET['delete']==$subscriptions->id) {
                        if ($subscriptions->delete()) {
                            $li = '<li>'.$subscriptions->name.' (Deleted)';
                        } else {
                             // error deleting the subscription?
                             $li = '<li>'.$subscriptions->name.' Error, cannot delete.';
                        }
                    } else {
                        $li .= ' <a href="'.$this->uri.'?action=subscribe&amp;delete='.$subscriptions->id.'">Delete</a>';
                    }
                }
                $list[] = $li.'</li>';
            }
            $list[] = '</ul>';
            return implode("\n", $list);
        } else {
            return 'This calendar currently has no subscriptions.';
        }
    }
    
    /**
     * Returns an event listing of search results.
     *
     * @return array of html snippets and events
     */
    function showSearchResults()
    {
        include_once 'UNL/UCBCN/Calendar_has_event.php';
        $q    = (isset($_GET['q']))?$_GET['q']:null;
        $mdb2 =& $this->getDatabaseConnection();
        if (!empty($q)) {
            $events = UNL_UCBCN_Manager::factory('event');
            if ($t = strtotime($q)) {
                // This is a time...
                $events->query('SELECT event.* FROM event, eventdatetime WHERE ' .
                        'eventdatetime.event_id = event.id AND eventdatetime.starttime LIKE \''.date('Y-m-d', $t).'%\'');
                
            } else {
                // Do a textual search.
                $q = $mdb2->escape($q);
                $events->whereAdd('event.title LIKE \'%'.$q.'%\' AND event.approvedforcirculation=1');
                $events->orderBy('event.title');
                $events->find();
            }
            $listing = new UNL_UCBCN_EventListing();
            while ($events->fetch()) {
                $event_removed = false;
                if (isset($_GET['delete'])
                    && ($_GET['delete']==$events->id)
                    && UNL_UCBCN::userHasPermission($this->user, 'Event Delete', $this->calendar)) {
                    if (isset($_GET['rec_id'])) {
                        $rd = UNL_UCBCN::factory('recurringdate');
                        $rd->removeInstance($events->id, $_GET['rec_id']);
                    } else {
                        $this->calendar->removeEvent($events);
                        $removed = true;
                    }
                    if ($events->isOrphan()) {
                        $events->delete();
                    }
                }
                if (!$event_removed) {
                    $this->processPostStatusChange($events);
                    if (UNL_UCBCN::userHasPermission($this->user, 'Event Delete', $this->calendar)
                        && UNL_UCBCN_Calendar_has_event::calendarHasEvent($this->calendar, $events)) {
                        $candelete = true;
                    } else {
                        $candelete = false;
                    } 
                    $listing->events[] = UNL_UCBCN_Event::eventToArray($events, UNL_UCBCN::userCanEditEvent($this->user, $events),
                                                $candelete, UNL_UCBCN_Calendar_has_event::calendarHasEvent($this->calendar, $events));
                }
            }
            if (count($listing->events)) {
                $listing = UNL_UCBCN_Recurringdate::getInstanceEvents($listing);
                return array('<h1 class="num_results">'.count($listing->events).' Result(s)</h1>',$listing);
            } else {
                return '<p>No results found.</p>';
            }
        } else {
            return '';
        }
    }
    
    /**
     * Runs actions on the posted events.
     *
     * @return bool
     */
    public function processPostedEvents()
    {
        $events         = UNL_UCBCN_Manager::getPostedEvents();
        $events_changed = false;
        if (count($events)) {
            foreach ($events as $event) {
                if ($this->processPostStatusChange($event)) {
                    $events_changed = true;
                }
            }
        }
        return $events_changed;
    }
    
    /**
     * This function returns an array of all posted events.
     * Events should be posted in the form event1923 Where 1923 is
     * the ID of the event.
     *
     * @return array(UNL_UCBCN_Event)
     */
    static function getPostedEvents()
    {
        $events = array();
        $recurring_events = array();
        foreach ($_POST as $key=>$value) {
            $matches = array();
            if (preg_match('/event([\d]+)(?:rec)?([\d]+)?/', $key, $matches)) {
                $event = UNL_UCBCN::factory('event');
                if ($event->get($matches[1])) {
                    if (isset($matches[2])) {
                        $event->recurrence_id = $matches[2];
                        unset($_POST[$key]);
                        $recurring_events['event'.$matches[1]] = $value;
                    }
                    $events[] =  $event;
                }
            }
        }
        foreach ($recurring_events as $key=>$value) {
            $_POST[$key] = $value;
        }
        return $events;
    }
    
    /**
     * Handles the posting of an updated event. This will alter the event's status
     * based on what the user chose within the manager interface.
     *
     * @param UNL_UCBCN_Event $event  Event to update.
     * @param string          $source Source of this change in status.
     *
     * @return bool
     */
    function processPostStatusChange($event, $source='search')
    {
        if (isset($_POST['event'.$event->id])) {
            $a_event              = UNL_UCBCN_Manager::factory('calendar_has_event');
            $a_event->calendar_id = $this->calendar->id;
            $a_event->event_id    = $event->id;
            if ($a_event->find()) {
                $a_event->fetch();
                // This event date time combination was selected... find out what they chose.
                if (isset($_POST['delete'])
                    && $this->userHasPermission($this->user, 'Event Delete', $this->calendar)) {
                    if (isset($event->recurrence_id)) {
                        // it is a recurring event
                        $rd = UNL_UCBCN_Manager::factory('recurringdate');
                        $rd->removeInstance($event->id, $event->recurrence_id);
                        return true;
                    }
                    // User has chosen to delete the event selected, and has permission to delete the event.
                    if ($a_event->source == 'create event form') {
                        // This is the calendar the event was originally created on, delete from the entire system.
                        return $event->delete();
                    }
                    // Remove the event from this calendar
                    return $a_event->delete();
                } elseif (isset($_POST['pending'])
                    && $this->userHasPermission($this->user, 'Event Send Event to Pending Queue', $this->calendar)) {
                    $a_event->status = 'pending';
                    return $a_event->update();
                } elseif (isset($_POST['posted'])
                    && $this->userHasPermission($this->user, 'Event Post', $this->calendar)) {
                    $a_event->status = 'posted';
                    return $a_event->update();
                }
            } else {
                if (isset($_POST['pending'])
                    && $this->userHasPermission($this->user, 'Event Send Event to Pending Queue', $this->calendar)) {
                    $a_event->status = 'pending';
                    $a_event->source = $source;
                    return $a_event->insert();
                } elseif (isset($_POST['posted'])
                    && $this->userHasPermission($this->user, 'Event Post', $this->calendar)) {
                    $a_event->status = 'posted';
                    $a_event->source = $source;
                    return $a_event->insert();
                }
            }
        }
        return false;
    }
    
    /**
     * This function generates and returns a permissions form for the given user and calendar.
     *
     * @param string             $uid      User id to edit permissions for.
     * @param UNL_UCBCN_Calendar $calendar Calendar to edit permissions for.
     *
     * @return string HTML form.
     */
    function showPermissionsForm($uid,$calendar)
    {
        if ($this->userHasPermission($this->user, 'Calendar Change User Permissions', $this->calendar)) {
            $msg = '';
            if (!is_object($uid)) {
                $user =& UNL_UCBCN_Manager::factory('user');
                if (isset($uid) && !empty($uid)) {
                    $user->uid = $uid;
                    if ($user->find()) {
                        $user->fetch();
                    } else {
                        $user = $this->createUser($this->account, $uid, $this->user);
                    }
                } elseif ($this->userHasPermission($this->user, 'Calendar Add User', $this->calendar)) {
                    // uid is not set, must be creating a new user..?
                    $msg = 'Please select a new user to grant access to and choose the permissions you wish to grant.';
                } else {
                    return new UNL_UCBCN_Error('You do not have permission to add new users to this calendar.');
                }
            } else {
                $user = $uid;
            }
            $fb =& DB_DataObject_FormBuilder::create($user);
            if (!isset($user->uid)) {
                $fb->enumFields = array('uid');
                $uids           = array();
                foreach (array_values($this->a->listUsers()) as $key=>$val) {
                    $uids[$val['username']] = $val['username'];
                }
                $fb->enumOptions = array('uid'=>$uids);
            }
            $fb->formHeaderText = $user->uid.' Permissions for '.$this->calendar->name;
            $fb->crossLinks     = array(array('table'=>'user_has_permission'));
            $form               = $fb->getForm('?action=permissions&uid='.$user->uid);
            $el                 =& $form->getElement('__crossLink_user_has_permission_user_uid_permission_id');
            $el->setLabel('Permissions');
            $el =& $form->getElement('uid');
            $el->setLabel('User ID');
            $form->setDefaults(array('calendar_id'=>$user->calendar_id, 'account_id'=>$user->account_id));
            $checkall = HTML_QuickForm::createElement('static', 'checkall', null, '<a href="#" class="checkall" id="checkall" onclick="setCheckboxes(\'unl_ucbcn_user\',true); return false">Check All</a>');
            $form->insertElementBefore($checkall, '__crossLink_user_has_permission_user_uid_permission_id');
            $uncheckall = HTML_QuickForm::createElement('static', 'uncheckall', null, '<a href="#" class="uncheckall" id="uncheckall" onclick="setCheckboxes(\'unl_ucbcn_user\',false); return false">Uncheck All</a>');
            $form->insertElementBefore($uncheckall, '__crossLink_user_has_permission_user_uid_permission_id');
            $renderer = new HTML_QuickForm_Renderer_Tableless();
            $form->accept($renderer);
            if ($form->validate()) {
                $form->process(array(&$fb, 'processForm'), false);
                $form->freeze();
                $form->removeElement('__submit__');
                $this->localRedirect($this->uri.'?action=users&new_uid='.$user->uid);
                $msg = '<p>User permissions saved...</p>';
            }
            return $msg.$renderer->toHtml();
        } else {
            return new UNL_UCBCN_Error('You do not have permission to edit permissions for this calendar!');
        }
    }
    
    /**
     * Shows the list of events for the current user.
     *
     * @param string $status  The type of events to return, pending, posted or archived
     * @param string $orderby The SQL ORDER BY statement
     *
     * @return array mixed, navigation list, events currently in the system.
     */
    function showEventListing($status='pending',$orderby='eventdatetime.starttime')
    {
        if (isset($_POST) && $this->processPostedEvents()) {
            // Redirect here.
            $this->localRedirect($this->uri.'?list='.$status);
        }
        $mdb2 = $this->getDatabaseConnection();
        switch($orderby) {
        default:
        case 'eventdatetime.starttime':
        case 'starttime':
            $orderby = 'eventdatetime.starttime DESC';
            break;
        case 'title':
        case 'event.title':
            $orderby = 'event.title';
            break;
        }
        $today = date('Y-m-d');
        switch ($status) {
        case 'posted':
            $addrecurring = "OR (calendar_has_event.status = 'archived'
                                 AND eventdatetime.recurringtype != 'none'
                                 AND eventdatetime.recurs_until >= $today)) ";
            break;
        case 'archived':
        case 'pending':
            $addrecurring = ')';
            break;
        }
        $sql          = 'SELECT DISTINCT event.id FROM calendar_has_event, eventdatetime, event
                        WHERE (calendar_has_event.status = \''.$status.'\'
                        '.$addrecurring.'
                        AND calendar_has_event.event_id = event.id
                        AND eventdatetime.event_id = event.id
                        AND calendar_has_event.calendar_id = '.$this->calendar->id.'
                        ORDER BY '.$orderby;
        $e            = array();
        $paged_result = $this->pagerWrapper($mdb2, $sql, array('totalItems'=>$this->getEventCount($this->calendar, $status)));
        if ($paged_result['totalItems']) {
            $e[]              = $paged_result['links'];
            $listing          = new UNL_UCBCN_EventListing();
            $listing->status  = $status;
            $events           = array();
            $recurring_events = array();
            foreach ($paged_result['data'] as $event_id) {
                $event = UNL_UCBCN_Manager::factory('event');
                if ($event->get($event_id['id'])) {
                    $listing->events[] = $event;
                }
            }
            UNL_UCBCN_Recurringdate::getInstanceEvents($listing);
            $e[] = $listing;
            $e[] = $paged_result['links'];
        } else {
            $e[] = '<p>Sorry, there are no '.$status.' events.</p><p>Perhaps you would like to create some?<br />Use the <a href="?action=createEvent">Create Event interface.</a></p>';
        }
        array_unshift($e, '<ul class="eventsbystatus '.$status.'">' .
                            '<li id="pending_manager"><a href="'.$this->uri.'?list=pending">Pending ('.$this->getEventCount($this->calendar, 'pending').')</a></li>' .
                            '<li id="posted_manager"><a href="'.$this->uri.'?list=posted">Posted ('.$this->getEventCount($this->calendar, 'posted').')</a></li>' .
                            '<li id="archived_manager"><a href="'.$this->uri.'?list=archived">Archived ('.$this->getEventCount($this->calendar, 'archived').')</a></li>' .
                        '</ul>');
        return $e;
    }
    
    /**
     * Returns a form to edit the current acccount.
     *
     * @return string html form.
     */
    public function showAccountForm()
    {
        if (isset($this->account)) {
            $msg = '';
            $fb  = DB_DataObject_FormBuilder::create($this->account);
            if (!PEAR::isError($fb)) {
                $form     = $fb->getForm('?action=account');
                $renderer = new HTML_QuickForm_Renderer_Tableless();
                $form->accept($renderer);
                if ($form->validate()) {
                    $form->process(array(&$fb, 'processForm'), false);
                    $form->freeze();
                    $form->removeElement('__submit__');
                    $msg = '<p>Account info saved...</p>';
                }
                return $msg.$renderer->toHtml();
            } else {
                return new UNL_UCBCN_Error('showAccountForm could not create a formbuilder object! The error it returned was:'.$fb->message);
            }
        } else {
            return $this->showCalendars();
        }
    }
    
    /**
     * This function returns a form for editing the calendar details.
     *
     * @return string HTML form for editing a calendar.
     */
    function showCalendarForm($additional = false)
    {
        if (isset($this->calendar) && $this->userHasPermission($this->user, 'Calendar Edit', $this->calendar)) {
            $msg      = '';
            
            if ($additional) {
                $cal = new UNL_UCBCN_Calendar();
                $cal->uidcreated  = $this->user->uid;
                $cal->uidlastupdated = $this->user->uid;
                $cal->account_id     = $this->account->id;
            } else {
                $cal = $this->calendar;
            }
            $fb       = DB_DataObject_FormBuilder::create($cal);
            if ($additional) {
                $form     = $fb->getForm($this->uri.'?action=newCal&calendar_id='.$cal->id);
            } else {
                $form     = $fb->getForm($this->uri.'?action=calendar&calendar_id='.$cal->id);
            }
            $form->addRule('name','Name is required.','required');
            $form->addRule('shortname','shortname is required.','required');
            $renderer = new HTML_QuickForm_Renderer_Tableless();
            //add cal here.
            $form->accept($renderer);
            
            if ($form->validate()) {
                $valid = true;
                if ($additional) {
                    //verify the shortname;
                    $shortname = $form->getElement('shortname')->getValue();
                    ///ensure that it is valid.
                    $match = preg_match('/[^0-9a-z]/',$shortname);
                    if($match){
                        $valid = false;
                        $msg .= '<p>That short name is not valid.  Must be only numbers and/or lowercase letters...</p>';
                    }
                    ///ensure that it is not already used.
                    $c = new UNL_UCBCN_Calendar();
                    $c->shortname = $shortname;
                    $count = $c->find();
                    if ($count) {
                        $valid = false;
                        $msg .= '<p>That short name is already being used.</p>';
                    }
                }
                if ($valid) {
                    $form->process(array(&$fb, 'processForm'), false);
                    $form->freeze();
                    $form->removeElement('__submit__');
                    //ensure permissions
                    $calendars = UNL_UCBCN::factory('calendar');
                    $calendars->account_id = $this->account->id;
                    $calendars->find();
                    while ($calendars->fetch()) {
                        $calendars->addUser($this->user);
                    }
                    $msg = '<p>Calendar info saved...</p>';
                }
            }
            return $msg.$renderer->toHtml();
        } else {
            return array('<p>You do not have permission to edit the calendar info.</p>',$this->showChooseCalendar());
        }
    }
     
    /**
     * Returns a form for editing an event date & time instance.
     *
     * @return string HTML form for editing an event date & time
     */
    function showEventDateTimeForm()
    {
        include_once 'UNL/UCBCN/Manager/jscalendar.php';
        include_once 'DB/DataObject/FormBuilder/QuickForm/SubForm.php';
        
        $msg = '';
        $edt = UNL_UCBCN_Manager::factory('eventdatetime');
        if (isset($_GET['delete'])) {
            if (UNL_UCBCN::userHasPermission($this->user, 'Event Delete', $this->calendar) && $edt->get($_GET['delete'])) {
                $event_id = $edt->event_id;
                $res      = $edt->delete();
                if ($res) {
                    // Successfully deleted. Return user to edit form.
                    $this->localRedirect($this->uri.'?action=createEvent&id='.$event_id);
                } else {
                    return $res;
                }
            } else {
                return new UNL_UCBCN_Error('You do not have permission to delete events.');
            }
        } else {
            if (isset($_GET['id'])) {
                $edt->get($_GET['id']);
            }
            $fb   = DB_DataObject_FormBuilder::create($edt);
            $form = $fb->getForm($this->uri.'?action=eventdatetime');
            
            if (isset($_GET['event_id']) && !isset($edt->id)) {
                $form->setDefaults(array('event_id'=>$_GET['event_id']));
                $event = UNL_UCBCN_Manager::factory('event');
                if ($event->get($_GET['event_id'])) {
                    $msg = 'New Event Date &amp; Time for '.$event->title;
                }
            } else {
                $event = $edt->getLink('event_id');
                $msg   = 'Editing Event Date &amp; Time for '.$event->title;
            }
            $form->insertElementBefore(HTML_QuickForm::createElement('header', 'eventlocationheader', $msg),
                'location_id');
            if (isset($_REQUEST['rec']) && isset($_REQUEST['recid'])) {
                $form->addElement(HTML_QuickForm::createElement('hidden', 'rec', $_REQUEST['rec']));
                $form->addElement(HTML_QuickForm::createElement('hidden', 'recid', $_REQUEST['recid']));
            }
            $renderer = new HTML_QuickForm_Renderer_Tableless();
            $form->accept($renderer);
            if ($form->validate()) {
                if ($form->process(array(&$fb, 'processForm'), false)) {
                    $this->localRedirect($this->uri.'?list=posted&new_event_id='.$edt->event_id);
                    exit(0);
                }
            }
            return $renderer->toHtml();
        }
    }
    
    /**
     * This form allows the user to recommend an event for other calendars.
     *
     * @return UNL_UCBCN_Manager_Recommend
     */
    public function showRecommendForm()
    {
        include_once 'UNL/UCBCN/Manager/Recommend.php';
        $events = $this->getPostedEvents();
        if (count($events) > 0) {
            $r = new UNL_UCBCN_Manager_Recommend($this, $events);
            return $r;
        } else {
            return new UNL_UCBCN_Error('No event(s) selected to recommend!');
        }
    }
    
    /**
     * This function returns a list of calendars for the current account.
     *
     * @return string Unordered list of calendars.
     */
    function showCalendars()
    {
        $calendars             = UNL_UCBCN_Manager::factory('calendar');
        $calendars->account_id = $this->account->id;
        $calendars->orderBy('name');
        if ($calendars->find()) {
            $l = array('<ul>');
            while ($calendars->fetch()) {
                $li = htmlspecialchars($calendars->name);
                if ($this->userHasPermission($this->user, 'Calendar Edit', $this->calendar)) {
                    $li .= '&nbsp;<a href="'.$this->uri.'?action=calendar&amp;calendar_id='.$calendars->id.'">Edit</a>';
                }
                $l[] = '<li>'.$li.'</li>';
            }
            $l[] = '</ul>';
            return implode("\n", $l);
        } else {
            return new UNL_UCBCN_Error('Error, no calendars exist for the current account!');
        }
    }
    
    /**
     * This function returns a list of users that have 'some'
     * permission to the current calendar.
     *
     * @return string html list of users.
     */
    function showCalendarUsers()
    {
        $oddrow = false;
        if ($this->userHasPermission($this->user, 'Calendar Change User Permissions', $this->calendar)) {
            $permissions_list = array('<table class="eventlisting no_onclick">
                                        <thead>
                                        <tr>
                                        <th scope="col" class="user">User</th>
                                        <th scope="col" class="permission">Permission</th>
                                        <th scope="col" class="action">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>');

            $user_has_permission              = UNL_UCBCN_Manager::factory('user_has_permission');
            $user_has_permission->calendar_id = $this->calendar->id;

            $users = UNL_UCBCN_Manager::factory('user');
            $users->groupBy('uid');
            $users->joinAdd($user_has_permission);
            if ($users->find()) {
                while ($users->fetch()) {
                    if ($this->userHasPermission($this->user, 'Calendar Change User Permissions', $this->calendar)) {
                        $user_li = '<tr';
                        if ($oddrow) {
                            $user_li .= ' class="alt">';
                        } else {
                            $user_li .= '>';
                        }
                        $user_li .= '<td>'.$users->uid.'</td><td><a class="user_perm_edit" href="?action=permissions&amp;uid='.$users->uid.'">Edit</a></td>';
                        if ($this->userHasPermission($this->user, 'Calendar Delete User', $this->calendar) && $users->uid != $this->user->uid) {
                            // This user can delete calendar users.
                            if (isset($_GET['remove'])
                                && isset($_GET['uid'])
                                && ($_GET['uid']==$users->uid)) {
                                // The user has clicked the remove user.
                                $this->calendar->removeUser($users);
                                continue;
                            } else {
                                $user_li .= '<td><a class="user_perm_remove" href="?action=users&amp;uid='.$users->uid.'&amp;remove=true">Remove User</a></td>';
                            }
                        } else {
                            $user_li .= '<td></td>';
                        }
                        $user_li .= '</tr>';
                    } else {
                        $user_li = '<tr><td>'.$users->uid.'</td></tr>';
                    }
                    $permissions_list[] = $user_li;
                    $oddrow             = !$oddrow;
                }
            }
            $permissions_list[] = '</tbody></table>';
            return implode("\n", $permissions_list);
        } else {
            return new UNL_UCBCN_Error('You do not have permission to Change User Permissions for this calendar.');
        }
    }
    
    /**
     * Displays a form to add a user to the system.
     *
     * @return string HTML webform
     */
    function showAddUserForm()
    {
        unset($_GET['action']);
        $form = new HTML_QuickForm('add_user', 'get');
        $form->addElement('header', 'addhead', 'Add New User');
        //$form->addElement('text','name','User Name');
        //$form->addElement('hidden','uid');
        $form->addElement('text', 'uid', 'User Id (like jdoe2):');
        $form->addElement('submit', 'submit', 'Add User');
        $form->addElement('hidden', 'action', 'permissions');
        $renderer = new HTML_QuickForm_Renderer_Tableless();
        $form->accept($renderer);
        return $renderer->toHtml();
    }

    /**
     * This function returns all the calendars this user has access to.
     *
     * @return html form for choosing account
     */
    function showChooseCalendar()
    {
        $db  = UNL_UCBCN::getDatabaseConnection();
        $res =& $db->query('SELECT u.calendar_id, c.name FROM user_has_permission AS u, calendar AS c WHERE
                    u.user_uid=\''.$this->user->uid.'\' AND
                    u.calendar_id = c.id
                    GROUP BY u.calendar_id ORDER BY c.name');
        if (PEAR::isError($res)) {
            return new UNL_UCBCN_Error($res->getMessage());
        }
        $form       = new HTML_QuickForm('cal_choose', 'get');
        $cal_select = HTML_QuickForm::createElement('select', 'calendar_id', '');
        $cal_select->addOption('Choose your calendar', $_SESSION['calendar_id']);
        //Add new cal
        $cal_select->addOption('Add a new calendar', 'new');
        while ($row = $res->fetchRow()) {
            $cal_select->addOption($row[1], $row[0]);
        }
        $form->addElement($cal_select);
        $form->addElement('submit', 'submit', 'Go');
        $renderer = new HTML_QuickForm_Renderer_Tableless();
        $form->accept($renderer);
        $output = $renderer->toHtml();
        return $output;
    }
    
    /**
     * Registers a plugin for use within the manager.
     *
     * <code>
     * UNL_UCBCN_Manager::registerPlugin('UNL_UCBCN_Manager_InDesignExport');
     * </code>
     *
     * @param string $class_name Name of the class of the plugin to register.
     *
     * @return void
     */
    public function registerPlugin($class_name)
    {
        global $_UNL_UCBCN;
        if (array_key_exists('plugins', $_UNL_UCBCN) && is_array($_UNL_UCBCN['plugins'])) {
            $_UNL_UCBCN['plugins'][] = $class_name;
        } else {
            $_UNL_UCBCN['plugins'] = array($class_name);
        }
    }
    
    /**
     * This function handles pagination of a database query.
     *
     * @param object &$db           Database connection
     * @param string $query         SQL to send
     * @param array  $pager_options Options for the pager class
     * @param bool   $disabled      Boolean option
     * @param int    $fetchMode     The type of mode to fetch
     *
     * @return pager object
     */
    protected function pagerWrapper(&$db, $query, $pager_options = array(), $disabled = false, $fetchMode = MDB2_FETCHMODE_ASSOC)
    {
        if (!array_key_exists('totalItems', $pager_options)) {
            //be smart and try to guess the total number of records
            if ($countQuery = $this->_rewriteCountQuery($query)) {
                $totalItems = $db->queryOne($countQuery);
                if (PEAR::isError($totalItems)) {
                    return $totalItems;
                }
            } else {
                //GROUP BY => fetch the whole resultset and count the rows returned
                $res =& $db->queryCol($query);
                if (PEAR::isError($res)) {
                    return $res;
                }
                $totalItems = count($res);
            }
            $pager_options['totalItems'] = $totalItems;
        }
        if (!isset($pager_options['perPage'])) {
            $pager_options['perPage'] = 30;
        }
        $pager                           = Pager::factory($pager_options);
        $page                            = array();
        $page['links']                   = $pager->links;
        $page['totalItems']              = $pager_options['totalItems'];
        $page['page_numbers']            = array(
            'current' => $pager->getCurrentPageID(),
            'total'   => $pager->numPages()
        );
        list($page['from'], $page['to']) = $pager->getOffsetByPageId();
        $page['limit']                   = $page['to'] - $page['from'] +1;
        if (!$disabled) {
            $db->setLimit($pager_options['perPage'], $page['from']-1);
        }
        $page['data'] = $db->queryAll($query, null, $fetchMode);
        if (PEAR::isError($page['data'])) {
            return $page['data'];
        }
        if ($disabled) {
            $page['links']        = '';
            $page['page_numbers'] = array(
                'current' => 1,
                'total'   => 1
            );
        }
        return $page;
    }
    
    /**
     * This function modifies queries to return a paged subset.
     *
     * @param string $sql The SQL to rewrite.
     *
     * @return string
     */
    private function _rewriteCountQuery($sql)
    {
        if (preg_match('/^\s*SELECT\s+\bDISTINCT\b/is', $sql) ||
            preg_match('/\s+GROUP\s+BY\s+/is', $sql) ||
            preg_match('/\s+UNION\s+/is', $sql)) {
            return false;
        }
        $open_parenthesis   = '(?:\()';
        $close_parenthesis  = '(?:\))';
        $subquery_in_select = $open_parenthesis.'.*\bFROM\b.*'.$close_parenthesis;
        $pattern            = '/(?:.*'.$subquery_in_select.'.*)\bFROM\b\s+/Uims';
        if (preg_match($pattern, $sql)) {
            return false;
        }
        $subquery_with_limit_order = $open_parenthesis.'.*\b(LIMIT|ORDER)\b.*'.$close_parenthesis;
        $pattern                   = '/.*\bFROM\b.*(?:.*'.$subquery_with_limit_order.'.*).*/Uims';
        if (preg_match($pattern, $sql)) {
            return false;
        }
        $queryCount         = preg_replace('/(?:.*)\bFROM\b\s+/Uims', 'SELECT COUNT(*) FROM ', $sql, 1);
        list($queryCount, ) = preg_split('/\s+ORDER\s+BY\s+/is', $queryCount);
        list($queryCount, ) = preg_split('/\bLIMIT\b/is', $queryCount);
        return trim($queryCount);
    }
}

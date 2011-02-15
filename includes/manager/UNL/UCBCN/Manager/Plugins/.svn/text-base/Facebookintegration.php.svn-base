<?php
/**
 * Facebook Integration class.
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Michael Fairchild <mfairchild365@gmail.com>
 * @copyright 2010 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 *
 */
require_once 'UNL/UCBCN/Manager/Plugin.php';
require_once 'HTML/QuickForm.php';

/**
 * Facebook Integration Class
 * A plugin class that allows for calendar integration with facebook.
 * It allows the user to log into facebook from the manager and link the 
 * currently selected calendar with the facebook account.
 * 
 * Also give an interface to change variables such as createEvents
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Michael Fairchild <mfairchild365@gmail.com>
 * @copyright 2010 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Manager_FacebookIntegration extends UNL_UCBCN_Manager_Plugin
{
    public $name    = 'Facebook Integration';
    public $version = '0.0.1';
    public $author  = 'Michael Fairchild';
    
    public $manager;
    public $facebookAccount;
    public $uri;
    public $output = array();
    public $facebookConfig;
    public $facebookInterface;
    public $me;
    public $logoutUrl;
    public $loginUrl;
    public $session;
    public $config;
    
    /** startup
     * Initializes all variables for the class on when the class is loaded by the manager.
     * 
     * @param manager &$manager = the manager.
     * @param string   $uri = the uri to the current page.
     * 
     * @return void
     **/
    public function startup(&$manager,$uri) 
    {
        $this->manager =& $manager;
        $this->uri        = $uri;
        $this->facebookAccount = UNL_UCBCN::factory('facebook_accounts');
        $this->facebookAccount->calendar_id = $this->manager->calendar->id;
        if (!$this->facebookAccount->find(true)) {
            $this->facebookAccount->insert();
        }
        $this->config = UNL_UCBCN_FacebookInstance::getConfig();
        $this->facebookInterface = UNL_UCBCN_FacebookInstance::initFacebook($this->config['appID'], $this->config['secret']);
        $this->session = $this->facebookInterface->getSession();
        if ($this->session) {
            try {
                $this->me = $this->facebookInterface->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
            }
        }
        if ($this->me) {
            $this->logoutUrl = $this->facebookInterface->getLogoutUrl();
        } else {
            $this->loginUrl = $this->facebookInterface->getLoginUrl();
        }
        //for facebook login: 
        $this->output[] = "
        <div id='fb-root'></div>
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId   : '{$this->config['appID']}',
                    session : ".json_encode($this->session).", // don't refetch the session when PHP already has it
                    status  : true, // check login status
                    cookie  : true, // enable cookies to allow the server to access the session
                    xfbml   : true // parse XFBML
                });
                // whenever the user logs in, we refresh the page
                FB.Event.subscribe('auth.login', function() {
                    window.location.reload();
                });
                FB.Event.subscribe('auth.logout', function() {
                    window.location.reload();
                });
            };
            (function() {
                var e = document.createElement('script');
                e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
        </script>";
    }

    /** run
     * Function called by plugin system.  Runs the main logic of the plugin.
     * 
     * @return void
     **/
    public function run()
    {
        $this->output[] = '<p class="sec_main">Facebook Integration Manager</p>' .
                '<p>Use this tool to register a calander with a facebook account and to change options.</p>';
        if (UNL_UCBCN_FacebookInstance::getConfig() ) {
            if ($this->me) {
                $this->output[] = "<img src='http://graph.facebook.com/".$this->me['id']."/picture'>";
                $this->output[] = "Welcome, " . $this->me['name'] . " (<a href='{$this->logoutUrl}'>logout of facebook</a>)<br>";
                $url = urlencode(UNL_UCBCN_FacebookInstance::getURL()."&authorize=true");
                $this->output[] = "<a href='index.php?action=plugin&p=UNL_UCBCN_Manager_FacebookIntegration'>Integration Home</a> | <a href='{$this->uri}&edit=true'>Edit Settings for this calendar</a> | <a href='index.php?action=plugin&p=UNL_UCBCN_Manager_FacebookIntegration&FAQ=true'>FAQ</a> | <a href='https://graph.facebook.com/oauth/authorize?client_id={$this->config['appID']}&redirect_uri=$url&scope=rsvp_event,user_events,create_event,offline_access,manage_pages'>Authorize facebook account for this calendar</a><br><hr>";
            } else {
                $url = urlencode(UNL_UCBCN_FacebookInstance::getURL()."&authorize=true");
                $this->output[] = "<a href='index.php?action=plugin&p=UNL_UCBCN_Manager_FacebookIntegration'>Integration Home</a> | <a href='{$this->uri}&edit=true'>Edit Settings for this calendar</a> | <a href='index.php?action=plugin&p=UNL_UCBCN_Manager_FacebookIntegration&FAQ=true'>FAQ</a> | <a href='https://graph.facebook.com/oauth/authorize?client_id={$this->config['appID']}&redirect_uri=$url&scope=rsvp_event,user_events,create_event,offline_access,manage_pages'>Authorize a facebook account for this calendar</a><br>";
            }
        } else {
            $this->output[] = "<a href='index.php?action=plugin&p=UNL_UCBCN_Manager_FacebookIntegration'>Integration Home</a> | <a href='{$this->uri}&edit=true'>Edit Settings for this calendar</a> | <a href='index.php?action=plugin&p=UNL_UCBCN_Manager_FacebookIntegration&FAQ=true'>FAQ</a>";
            $this->output[] = "<h2>Setting up facebook: </h2>";
            $this->output[] = "There are a few things that you need to do before you can use this delightful feature.
                                Please follow these instructions to get started...<br>
                              <ol>
                                  <li>Create a PHP file in your root directory named 'config.inc.php'.  There should already be a file
                                      in that directory called 'config.sample.inc.php'.  For an example of how to use the file that you just made
                                      open that file and read it.  The next few instructions will guide you though editing the 'config.inc.php'
                                  <li>
                                      Go to <a href='http://developers.facebook.com/setup/'>Facebook</a> and create an application.
                                      <ol>
                                          <li>For 'Site name:' Put in the name of this site.</li>
                                          <li>For 'Site URL:' Put in the url for this site: 'http://".$_SERVER["SERVER_NAME"]."/?'</li>
                                      </ol>
                                      </li>
                                  <li>Complete the security check if needed.</li>
                                  <li>A results page will show your App Id and App Secret
                                      <ol>
                                      <li>Copy the 'App ID:' to config.inc.php replacing the value of \$fb_appId (ex: \$fb_appId = xxxxxxxxxx)</li>
                                      <li>Copy the 'App Secret:' to config.inc.php replacing the value of \$fb_secret (ex: \$fb_secret = 'xxxxxxxxxx')</li>
                                      </ol>
                                  </li>
                                  <li>Reload this page.  If you entered the correct values, you can then authorize a facebook account.</li>
                              </ol>";
        }
        if (isset($_POST['submit'])) {
            $this->doEdit();
        }
        $this->showStatus();
        
        if (isset($_GET['authorize'])) {
            $action = 'authorize';
        } else if (isset($_GET['edit'])) {
            $action = 'edit';
        } else if (isset($_GET['FAQ'])) {
            $action = 'faq';
        } else {
            $action = 'start';
        }
        switch( $action )
        {
        case 'start':
            break;
        case 'authorize':
            $this->output[] = $this->facebookAuthorize();
            break;
        case 'faq':
            $this->output[] = $this->showFAQ();
            break;
        case 'edit':
            $this->output[] = $this->showEdit();
            break;
        case 'authorize':
            $this->facebookAuthorize();
            break;
        default:
            //$this->output[] = $this->showChooseDateForm();
        }
    }
    
    /** showFAQ
     * Shows a FAQ about the facebook integration.
     * 
     * @return void
     **/
    public function showFAQ()
    {
        $this->output[] = "<h2>FAQ</h2>";
        $this->output[] = "<ol>
                           <li><strong>Q: How does Facebook Integration work?</strong> A: After you properly set up the integration and enable creation of facebook events, a new facebook event will be created for 
                               for each instance of the event on the authorized facebook account.  Users can then view the event on facebook, RSVP and invite friends.  While viewing the event in this event system
                               a user will be able to RSVP to an event.  Facebook events will only be created after integration has been set up, there for events made prior to the integration will not be added
                               to facebook unless you update each event.</li>
                           <li><strong>Q: Why arn't recurring events Created?</strong> A: Facebook does not currently support recurring events.</li>
                           <li><strong>Q: How do I enable the integration?</strong> A: First you need to create a facebook app, then you need to authorize a facebook account for the calendar that you wish to integrate.</li>
                           <li><strong>Q: Do I need to create a facebook app to enable Like Buttons?</strong> A: No, like buttons do not require a facebook app.</li>
                           <li><strong>Q: How do I set up the Facebook Integration to post to a page, rather than my account?</strong> A: First, go <a href='http://www.facebook.com/pages/create.php'>here</a> to create a facebook page.  
                               Then, go to the Facebook Integration Settings and select the page from the drop down list. Note that you must be logged into the facebook account associated with this calendar in order to select your page.</li>
                           </ol>";
    }
    
    /** facebookAuthorize
     * Saves facebook account information to the DB for a calendar.
     * 
     * @return void
     **/
    public function facebookAuthorize()
    {
        $this->output[] = "<h3>Authorize Facebook Account for this calendar</h3>";
        //DB_DataObject::debugLevel(4);
        unset($this->facebookAccount);
        $this->facebookAccount = UNL_UCBCN::factory('facebook_accounts');
        $this->facebookAccount->calendar_id = $this->manager->calendar->id;
        $this->facebookAccount->find(true);
        $this->facebookAccount->facebook_account = $this->me['id'];
        $this->facebookAccount->access_token = $this->session['access_token'];
        $rows = $this->facebookAccount->update();
        if ($rows) {
            $this->output[] = "Facebook account has been authorized for this calendar.<br>";
            $this->output[] = "Facebook account id: {$this->facebookAccount->facebook_account}<br>";
            $this->output[] = "Facebook access_token: {$this->facebookAccount->access_token}<br><hr>";
        }
    }
    
    /** showStatus
     * Displays the status of the facebook integration.  Show Like Buttons, create events, etc.
     * 
     * @return void
     **/
    function showStatus()
    {
        $this->output[] = "<ul>";
        $this->output[] = "<li>Create events is currently set to: ";
        if ($this->facebookAccount->create_events) {
            $this->output[] = "True</li>";
        } else {
            $this->output[] = "False</li>";
        }
        $this->output[] = "<li>Show Like Buttons is currently set to: ";
        if ($this->facebookAccount->show_like_buttons) {
            $this->output[] = "True</li>";
        } else {
            $this->output[] = "False</li>";
        }
        $this->output[] = "<li>New Events will be added to:";
        if ($this->facebookAccount->page_name) {
            $this->output[] = "<strong> {$this->facebookAccount->page_name} </strong></li>";
        } else {
            $this->output[] = "<strong> The Authroized Account's Profile </strong></li>";
        }
        $this->output[] = "<li><strong>Events will be created: ";
        if ($this->facebookAccount->createEvents()) {
            $this->output[] = "True</li>";
        } else {
            $this->output[] = "False</li>";
        }
        $this->output[] = "</strong></ul><hr>";
    }
    
    /** showEdit
     * Displays editable options for the facebook integraion system.
     * 
     * @return void
     **/
    public function showEdit()
    {
        $url= html_entity_decode($this->uri);
        $form = new HTML_QuickForm('UNL_UCBCN_Manager_FacebookIntegration', 'post', $url);
        $form->addElement('hidden', 'action', 'plugin');
        //Only give options if a facebook app has been added.
        if (UNL_UCBCN_FacebookInstance::getConfig() ) {
            if (isset($this->facebookAccount->facebook_account) && $this->facebookAccount->facebook_account != 0 
                && isset($this->facebookAccount->access_token) && $this->facebookAccount->access_token != 0) {
                try{
                    $createEvents = $form->createElement('advcheckbox', 'createEvents', 'Create Events');
                    $createEvents->setChecked(true);
                    $createEvents->setChecked(false);
                    $form->addElement($createEvents);
                    $s = $form->createElement('select', 'page_name', 'Page: ');
                    $result = $this->facebookInterface->api(
                        '/'.$this->facebookAccount->facebook_account.'/accounts?access_token='.$this->facebookAccount->access_token,
                        array('access_token' => $this->facebookAccount->access_token)
                    );
                    $opts['profile'] = $this->me['name'];
                    for ($i=0; $i<count($result['data']); $i++) {
                        if (isset($result['data'][$i]['name'])) {
                            $opts[$result['data'][$i]['name']] = $result['data'][$i]['name'];
                        }
                    }
                    if (isset($this->facebookAccount->page_name)) {
                        $s->loadArray($opts, $this->facebookAccount->page_name);
                    } else {
                        $s->loadArray($opts, 'profile');
                    }
                    $form->addElement($s);
                } catch (FacebookApiException $e) {
                    error_log($e);
                    $this->output[] = "You have not authorized an account yet.  Please authorize an account
                                        for this calendar to view more options.";
                }
            }
        }
        
        //Show Like Button Options:
        $showLike = $form->createElement('advcheckbox', 'showLike', 'Show Like Buttons');
        if ($this->facebookAccount->show_like_buttons) {
            $showLike->setChecked(true);
        } else {
            $showLike->setChecked(false);
        }
        $form->addElement($showLike);
        $form->addElement('submit', 'submit', 'Submit');
        
        return $form->toHtml();
    }
    
    /** doEdit
     * Preforms edits done by showEdit().
     * 
     * @return void
     **/
    public function doEdit()
    {
        //Only allow edits if a facebook app has been added.
        if (UNL_UCBCN_FacebookInstance::getConfig() ) {
            $this->facebookAccount->create_events = $_POST['createEvents'];
            if ($_POST['page_name'] == 'profile') {
                $newName = "";
            } else {
                $newName = $_POST['page_name'];
            }
            $this->facebookAccount->page_name = $newName;
        }
        $this->facebookAccount->show_like_buttons = $_POST['showLike'];
        $this->facebookAccount->update();
        $this->output[] = "<h2>Settings have been updated</h2>";
    }
    
}

//Register the plugin.
UNL_UCBCN_Manager::registerPlugin('UNL_UCBCN_Manager_FacebookIntegration');
?>
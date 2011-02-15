<?php 
/*Display errors*/
ini_set('display_errors', false);

/*Add a custom include path here if needed....*/
set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__FILE__).'/includes/backend',
    dirname(__FILE__).'/includes/manager',
    dirname(__FILE__).'/includes/frontend',
    dirname(__FILE__).'/includes/xml2json',
    dirname(__FILE__).'/includes/pear')));
    
require_once 'UNL/UCBCN/Autoload.php';

/*Global Settings*/
$dsn                 = "{{DSN}}";     //default = "{{DSN}}"
$default_calendar_id = 1;             //default = 1

/*Front end settings:*/
$frontend_config                        = array();
$frontend_config['dsn']                 = $dsn;                  //default = $dsn
$frontend_config['template']            = 'vanilla';             //default = 'vanilla'
$frontend_config['uri']                 = '';                    //default = ''
$frontend_config['uriformat']           = 'querystring';         //default = 'querystring'
$frontend_config['manageruri']          = 'manager/';            //default = 'manager/'
$frontend_config['default_calendar_id'] = $default_calendar_id;  //default = $default_calendar_id

/*Auth setup.*/
require_once 'Auth.php';
$auth = new Auth('Array',array('users'=>array('admin'=>'admin')),null,false);

/*Manager Settings*/
$manager_config                        = array();
$manager_config['dsn']                 = $dsn;                  //default = $dsn
$manager_config['template']            = 'vanilla';             //default = 'vanilla'
$manager_config['frontenduri']         = '../';                 //default = '../'
$manager_config['a']                   = $auth;                 //default = $auth
$manager_config['default_calendar_id'] = $default_calendar_id;  //default = $default_calendar_id

/*Facebook Settings.(required for event creation.  see
 *  plugin for details)*/
$fb_appId            = null;           //default = null
$fb_secret           = null;           //default = null
require_once dirname(__FILE__).'/includes/facebook/src/facebook.php';


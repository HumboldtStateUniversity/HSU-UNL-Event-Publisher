<?php 
/************
 * 
 * Create a config.inc.php file in this directory if you wish to use the manager independently of the frontend config.
 * 
 ************/

/*Display errors*/
ini_set('display_errors', false);

/*Add a custom include path here if needed....*/
set_include_path(implode(PATH_SEPARATOR, array(
    dirname(dirname(__FILE__)).'/includes/pear',
    dirname(dirname(__FILE__)).'/includes/backend',
    dirname(dirname(__FILE__)).'/includes/frontend',
    dirname(dirname(__FILE__)).'/includes/manager',
    dirname(dirname(__FILE__)).'/includes/facebook')));
    
require_once 'UNL/UCBCN/Autoload.php';

/*Global Settings*/
$dsn                 = "{{DSN}}";     //default = "{{DSN}}"
$default_calendar_id = 1;             //default = 1

/*Auth setup.*/
$auth = new Auth('Array',array('users'=>array('admin'=>'admin')),null,false);

/*Manager Settings*/
$manager_config                        = array();
$manager_config['dsn']                 = $dsn;                  //default = $dsn
$manager_config['template']            = 'vanilla';             //default = 'vanilla'
$manager_config['frontenduri']         = '../';                 //default = '../'
$manager_config['a']                   = $auth;                 //default = $auth
$manager_config['default_calendar_id'] = $default_calendar_id;  //default = $default_calendar_id

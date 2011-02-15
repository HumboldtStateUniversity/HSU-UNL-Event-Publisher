<?php
/**
 * This file instantiates the Event manager interface.
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

//load the config in this dir, otherwise load the frontend config.
$manager_config = dirname(__FILE__).'/config.inc.php';
$config = dirname(dirname(__FILE__)).'/config.inc.php';
if (file_exists($manager_config)) {
    require_once $manager_config;
} else if (file_exists($config)) {
    require_once $config;
} else {
    //No config was found.  Run the Install
    header( "Location: ../install/" ) ;
}

$manager = new UNL_UCBCN_Manager($manager_config);

$manager->run();

UNL_UCBCN::displayRegion($manager);


<?php
/**
 * This is a base class manager plugins must extend and implement.
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

require_once 'UNL/UCBCN/Manager.php';

/**
 * Abstract class plugins must extend and implement.
 * 
 * @category  Events 
 * @package   UNL_UCBCN_Manager
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
abstract class UNL_UCBCN_Manager_Plugin
{
    /**
     * Name for the plugin.
     *
     * @var string
     */
    public $name;
    
    /**
     * Version of the plugin.
     *
     * @var string
     */
    public $version;
    
    /**
     * Name of the author of the plugin.
     *
     * @var string
     */
    public $author;
    
    /**
     * Manager running this plugin.
     *
     * @var UNL_UCBCN_Manager
     */
    public $manager;
    
    /**
     * The URI to this plugin.
     *
     * @var string
     */
    public $uri;
    
    /**
     * This will be called when the plugin is initialized on load of a page.
     *
     * @param UNL_UCBCN_Manager &$manager The manager object currently running.
     * @param string            $uri      The URI assigned to this plugin.
     * 
     * @return void
     */
    abstract function startup(&$manager,$uri);
}
?>
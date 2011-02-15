<?php
/**
 * This class is for the login form.
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
 * Simple object which will be used to display a login.
 * 
 * @category  Events 
 * @package   UNL_UCBCN_Manager
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Manager_Login
{
    /**
     * URL to post the form to.
     *
     * @var string
     */
    public $post_url;
    
    /**
     * Name of the form field for the user.
     *
     * @var string
     */
    public $user_field;
    
    /**
     * Name of the form field for the password
     *
     * @var string
     */
    public $password_field;
    
    /**
     * Constructs the login screen.
     */
    function __construct()
    {
        $this->post_url       = $_SERVER['SCRIPT_FILENAME'];
        $this->user_field     = 'username';
        $this->password_field = 'password';
    }
}
?>
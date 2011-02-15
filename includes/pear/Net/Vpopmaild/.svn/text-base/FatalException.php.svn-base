<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Net_Vpopmaild_FatalException 
 * 
 * PHP Version 5
 * 
 * @category Net
 * @package  Net_Vpopmaild
 * @uses     PEAR_Exception
 * @author   Joe Stump <joe@joestump.net>
 * @license  PHP 3.01  {@link http://www.php.net/license/3_01.txt}
 * @link     http://trac.merchbox.com/trac/Net_Vpopmaild
 */

require_once 'PEAR/Exception.php';

/**
 * Net_Vpopmaild_FatalException
 *
 * A small layer above 
 * {@link http://pear.php.net/manual/en/core.pear.pear-exception.php PEAR_Exception}
 * that allows you to pass PEAR_Error as the message to your exceptions.
 *
 * @category Net
 * @package  Net_Vpopmaild
 * @uses     PEAR_Exception
 * @author   Joe Stump <joe@joestump.net>
 * @license  PHP 3.01  {@link http://www.php.net/license/3_01.txt}
 * @link     http://trac.merchbox.com/trac/Net_Vpopmaild
 */
class Net_Vpopmaild_FatalException extends PEAR_Exception
{
    /**
     * __construct
     *
     * @param mixed $message PEAR_Error or your message
     * @param int   $code    An error code
     *
     * @access      public
     * @return      void
     */
    public function __construct($message, $code = 0)
    {
        if (PEAR::isError($message)) {
            $msg  = $message->getMessage();
            $code = $message->getCode();
        } else {
            $msg = $message;
        }

        $this->message = $msg;
        $this->code    = $code;
    }
}

?>

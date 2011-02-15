<?php
/**
 * PEAR postinstall script for the management portion of the calendar system.
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
require_once 'UNL/UCBCN.php';

/**
 * This class can handle all the post installation tasks for the manager.
 * 
 * @category  Events 
 * @package   UNL_UCBCN_Manager
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Manager_setup_postinstall
{
    /**
     * @var bool Whether to update template files
     */
    public $createFiles;
    
    /**
     * @var bool Whether to create/update index.php file
     */
    public $createIndex;
    
    /**
     * @var bool Whether to create a default account
     */
    public $createAccount;
    
    /**
     * @var string Datasource mysql://events:events@localhost/events
     */
    public $dsn;
    
    /**
     * Initialize the post-install task.
     * 
     * @param object &$config     PEAR_Config Pear configuration object.
     * @param object &$pkg        The object for the installed pear package.
     * @param string $lastversion The previously installed version (if any).
     * 
     * @return bool true
     */
    function init(&$config, &$pkg, $lastversion)
    {
        $this->_config        = &$config;
        $this->_registry      = &$config->getRegistry();
        $this->_ui            = &PEAR_Frontend::singleton();
        $this->_pkg           = &$pkg;
        $this->lastversion    = $lastversion;
        $this->databaseExists = false;
        return true;
    }
    
    /**
     * Optionally used to alter responses from the prompted questions.
     * 
     * @param array  $prompts Array of prompts.
     * @param string $section The section currently being executed.
     * 
     * @return prompts
     */
    function postProcessPrompts($prompts, $section)
    {
        switch ($section) {
        case 'databaseSetup':
            break;
        }
        return $prompts;
    }
    
    /**
     * Hub for the post-install task.
     * 
     * @param array  $answers Associative array of answers from the user.
     * @param string $phase   The phase of the installation.
     * 
     * @return bool true | false
     */
    function run($answers, $phase)
    {
        switch ($phase) {
        case 'questionCreate':
            $this->createFiles   = ($answers['createtemplate']=='yes')?true:false;
            $this->createIndex   = ($answers['createindex']=='yes')?true:false;
            $this->createAccount = ($answers['createaccount']=='yes')?true:false;
            return true;
        case 'fileSetup':
            if ($this->createFiles) {
                   return $this->createFiles($answers);
            } else {
                return true;
            }
        case 'accountSetup':
            if ($this->createAccount) {
                return $this->createAccount($answers);
            } else {
                return true;
            }
        case '_undoOnError' :
            // answers contains paramgroups that succeeded in reverse order
            foreach ($answers as $group) {
                switch ($group) {
                case 'createFiles':
                    break;
                }
            }
            break;
        }
    }
    
    /**
     * This function will create a calendar account if it 
     * does not already exist.
     * 
     * @param array $answers Responses from the user.
     * 
     * @return bool|int
     */
    function createAccount($answers)
    {
        $back               = new UNL_UCBCN(array('dsn'=>$answers['dsn']));
        $account            = UNL_UCBCN::factory('account');
        $account->shortname = $answers['shortname'];
        if (!$account->find()) {
            $op = 'insert';
            
            $account->datecreated = date('Y-m-d H:i:s');
        } else {
            $this->outputData('Account already exists, touching last updated datetime.');
            $account->fetch();
            $op = 'update';
        }
        $account->name        = $answers['name'];
        $account->lastupdated = date('Y-m-d H:i:s');
        return $account->$op();
    }
    
    /**
     * This function creates/upgrades the template files, as well as the sample index page.
     * 
     * @param array $answers Associative array of answers from the user.
     * 
     * @return mixed
     */
    function createFiles($answers)
    {
        // Copy the template files over to the location they answered.
        $docroot      = $answers['docroot'].DIRECTORY_SEPARATOR;
        $templateroot = $docroot.'templates'.DIRECTORY_SEPARATOR.$answers['template'].DIRECTORY_SEPARATOR;
        $datadir      = '@DATA_DIR@'. DIRECTORY_SEPARATOR . 'UNL_UCBCN_Manager' . DIRECTORY_SEPARATOR;
        if ($this->createIndex) {
            if (!is_dir($answers['docroot'].DIRECTORY_SEPARATOR)) {
                mkdir($answers['docroot'].DIRECTORY_SEPARATOR);
            }
            copy($datadir.'index.php', $docroot.'index.php');
        }
        
        $this->dircpy($datadir.'templates', $docroot.'templates', true);
        return self::file_str_replace(array('@DSN@',
                                            //'@URI@',
                                            '@TEMPLATE@'),
                                      array($this->dsn,
                                            //$answers['uri'],
                                            $answers['template']),
                                      array($docroot.'index.php',
                                            $templateroot.'manager_main.css',
                                            $templateroot.'Manager.tpl.php'));
    }
    
    /**
     * Replaces text within files.
     * 
     * @param mixed $search  An array or string of text to search for.
     * @param mixed $replace An array or string of text to replace the matched text with.
     * @param mixed $file    An array or string of filenames to search and replace text in.
     * 
     * @return void
     */
    function file_str_replace($search, $replace, $file)
    {
        $a = true;
        if (is_array($file)) {
            foreach ($file as $f) {
                $a = self::file_str_replace($search, $replace, $f);
                if ($a != true) {
                    return $a;
                }
            }
            return $a;
        } else {
            if (file_exists($file)) {
                $contents = file_get_contents($file);
                $contents = str_replace($search, $replace, $contents);
    
                $fp = fopen($file, 'w');
                
                $a = fwrite($fp, $contents, strlen($contents));
                fclose($fp);
                if ($a) {
                    $this->outputData($file);
                    return true;
                } else {
                    $this->outputData('Could not update ' . $file);
                    return false;
                }
            } else {
                $this->outputData($file.' does not exist!');
                return false;
            }
        }
    }
    
    /**
     * This function copies files from source to destination.
     * 
     * @param string $source    Directory within the filesystem.
     * @param string $dest      Destination directory in the filesystem.
     * @param bool   $overwrite Should this overwrite existing files?
     * 
     * @return void
     */
    function dircpy($source, $dest, $overwrite = false)
    {
        if ($handle = opendir($source)) {        // if the folder exploration is sucsessful, continue
            if (!is_dir($dest)) {
                mkdir($dest);
            }
            while (false !== ($file = readdir($handle))) { // as long as storing the next file to $file is successful, continue
                if ($file != '.' && $file != '..') {
                    $path = $source . DIRECTORY_SEPARATOR . $file;
                    if (is_file($path)) {
                        if (!is_file($dest . DIRECTORY_SEPARATOR . $file) || $overwrite) {
                            if (!copy($path, $dest . DIRECTORY_SEPARATOR . $file)) {
                                $this->outputData('File ('.$path.') could not be copied, likely a permissions problem.');
                            }
                        }
                    } elseif (is_dir($path)) {
                        if (!is_dir($dest . DIRECTORY_SEPARATOR . $file)) {
                            mkdir($dest . DIRECTORY_SEPARATOR . $file); // make subdirectory before subdirectory is copied
                        }
                        $this->dircpy($path, $dest . DIRECTORY_SEPARATOR . $file, $overwrite); //recurse!
                    }
                }
            }
            closedir($handle);
        } else {
            $this->outputData('Could not open '.$source);
            return false;
        }
    }
    
    /**
     * takes in a string and sends it to the client.
     * 
     * @param string $msg Message to send
     * 
     * @return void
     */
    function outputData($msg)
    {
        if (isset($this->_ui)) {
            $this->_ui->outputData($msg);
        } else {
            echo $msg.'<br />'.PHP_EOL;
        }
    }
}
?>
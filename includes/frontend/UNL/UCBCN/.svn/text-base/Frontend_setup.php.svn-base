<?php
/**
 * Contains the post-install script task for installing the frontend.
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN_Frontend
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */

/**
 * This is the post install setup script for the frontend.
 * 
 * It takes in responses to questions and handles updating the default
 * template as well as creating a sample index page.
 * 
 * @category  Events 
 * @package   UNL_UCBCN_Frontend
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 * 
 */
class UNL_UCBCN_Frontend_setup_postinstall
{
    public $createFiles;
    public $createIndex;
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
    public function init(&$config, &$pkg, $lastversion)
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
    public function postProcessPrompts($prompts, $section)
    {
        switch ($section) {
        case 'databaseSetup' :
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
    public function run($answers, $phase)
    {
        switch ($phase) {
        case 'questionCreate':
            $this->createFiles = ($answers['createtemplate']=='yes')?true:false;
            $this->createIndex = ($answers['createindex']=='yes')?true:false;
            return $this->createFiles;
        case 'fileSetup' :
            if ($this->createFiles) {
                   return $this->createFiles($answers);
            } else {
                return true;
            }
        case '_undoOnError' :
            // answers contains paramgroups that succeeded in reverse order
            foreach ($answers as $group) {
                switch ($group) {
                case 'createFiles' :
                    break;
                }
            }
            break;
        }
    }
    
    /**
     * This function creates/upgrades the template files, as well as the sample index page.
     * 
     * @param array $answers Associative array of answers from the user.
     * 
     * @return mixed
     */
    public function createFiles($answers)
    {
        // Copy the template files over to the location they answered.
        $docroot      = $answers['docroot'].DIRECTORY_SEPARATOR;
        $templateroot = $docroot.'templates'.DIRECTORY_SEPARATOR.$answers['template'].DIRECTORY_SEPARATOR;
        $datadir      = '@DATA_DIR@'. DIRECTORY_SEPARATOR . 'UNL_UCBCN_Frontend' . DIRECTORY_SEPARATOR;
        if ($this->createIndex) {
            if (!is_dir($docroot)) {
                mkdir($docroot);
            }
            copy($datadir.'index.php', $docroot.'index.php');
        }
        
        $this->dircpy($datadir.'templates', $docroot.'templates', true);
        return self::file_str_replace(array(
                            '@DSN@',
                            //'@URI@',
                            '@'.'TEMPLATE@',
                            '@'.'DOCROOT@'),
                    array(  $this->dsn,
                            //$answers['uri'],
                            $answers['template'],
                            $docroot),
                    array(  $docroot.'index.php',
                            $templateroot.'Frontend.tpl.php'));
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
    public function file_str_replace($search, $replace, $file)
    {
        $a = true;
        if (is_array($file)) {
            foreach ($file as $f) {
                $a = self::file_str_replace($search, $replace, $f);
                if ($a != true) {
                    return $a;
                }
            }
        } else {
            if (file_exists($file)) {
                $contents = file_get_contents($file);
                $contents = str_replace($search, $replace, $contents);
                $fp       = fopen($file, 'w');
                $a        = fwrite($fp, $contents, strlen($contents));
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
    public function dircpy($source, $dest, $overwrite = false)
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
     * @param string $msg A string of text to send to the user.
     * 
     * @return void
     */
    public function outputData($msg)
    {
        if (isset($this->_ui)) {
            $this->_ui->outputData($msg);
        } else {
            echo $msg.'<br />'.PHP_EOL;
        }
    }
}
?>
<?php

/**
 * This is the standard way of reading in the DB_DataObject configuration file.
 * Make sure to include this in all files using DB_DataObject!
 */

// In case we haven't included any PEAR classes yet, we need at least the base class at this point
require_once('PEAR.php'); 

$config = parse_ini_file('/smb_server/apache_docroot/DB_DataObject_FormBuilder/examples/dataObject.ini',TRUE);
foreach($config as $class=>$values) {
    $options = &PEAR::getStaticProperty($class,'options');
    $options = $values;
    
}
// Additional instruction for FormBuilder - this is a clumsy workaround, but currently neccessary!
$_DB_DATAOBJECT_FORMBUILDER['CONFIG'] = $config['DB_DataObject_FormBuilder'];

?>
--TEST--
DB_DO_FB::create($options, $driver)
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
$fb =& DB_DataObject_FormBuilder::create($do, array('formHeaderText' => 'MOVIE Header',
                                                    'preDefOrder' => array('title', 'genre_id')),
                                         'QuickForm');
var_dump(strtolower(get_class($fb)));
var_dump(strtolower(get_class($fb->_form)));
?>
--EXPECT--
string(25) "db_dataobject_formbuilder"
string(35) "db_dataobject_formbuilder_quickform"

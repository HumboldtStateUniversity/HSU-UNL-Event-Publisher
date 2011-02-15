--TEST--
DB_DO_FB::create
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include dirname(__FILE__).'/config.php';
$do =& DB_DataObject::factory('movie');
$fb =& DB_DataObject_FormBuilder::create($do);
var_dump(strtolower(get_class($fb)));
?>
--EXPECT--
string(25) "db_dataobject_formbuilder"
--TEST--
DB_DO_FB::create($options)
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
$fb =& DB_DataObject_FormBuilder::create($do, array('formHeaderText' => 'MOVIE Header',
                                                    'preDefOrder' => array('title', 'genre_id')));
var_dump(strtolower(get_class($fb)));
var_dump($fb->formHeaderText);
var_dump($fb->preDefOrder);
?>
--EXPECT--
string(25) "db_dataobject_formbuilder"
string(12) "MOVIE Header"
array(2) {
  [0]=>
  string(5) "title"
  [1]=>
  string(8) "genre_id"
}

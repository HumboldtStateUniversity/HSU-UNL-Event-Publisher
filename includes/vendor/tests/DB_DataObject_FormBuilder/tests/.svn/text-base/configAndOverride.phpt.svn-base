--TEST--
Global Config
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$config =& PEAR::getStaticProperty('DB_DataObject_FormBuilder', 'options');
$config = array('formHeaderText' => 'Global option',
                'linkDisplayLevel' => '5',
                'preDefOrder' => 'title,genre',
                'elementTypeMap' => 'text:textType,date:dateType',
                'linkDisplayFields' => array('genre_id', 'movie'));
$do =& DB_DataObject::factory('movie');
$do->fb_textFields = array('notes');
$fb =& DB_DataObject_FormBuilder::create($do, array('linkDisplayLevel' => 4,
                                                    'elementNamePrefix' => 'abcd',
                                                    'elementNamePostfix' => 'efgh'));
$fb->elementNamePostfix = 'ijkl';
$fb->crossLinkSeparator = '<br/><br/>';
$fb->textFields = array('title');
//Text option with no default
var_dump($fb->formHeaderText);
//Text option with default
var_dump($fb->linkDisplayLevel);
//String Array with no keys
var_dump($fb->preDefOrder);
//String Array with keys
var_dump($fb->elementTypeMap);
//Array
var_dump($fb->linkDisplayFields);
var_dump($fb->elementNamePrefix);
var_dump($fb->elementNamePostfix);
var_dump($fb->crossLinkSeparator);
var_dump($fb->textFields);
$fb->populateOptions();
var_dump($fb->textFields);
?>
--EXPECT--
string(13) "Global option"
int(4)
array(2) {
  [0]=>
  string(5) "title"
  [1]=>
  string(5) "genre"
}
array(2) {
  ["text"]=>
  string(8) "textType"
  ["date"]=>
  string(8) "dateType"
}
array(2) {
  [0]=>
  string(8) "genre_id"
  [1]=>
  string(5) "movie"
}
string(4) "abcd"
string(4) "ijkl"
string(10) "<br/><br/>"
array(1) {
  [0]=>
  string(5) "title"
}
array(1) {
  [0]=>
  string(5) "notes"
}
--TEST--
type arrays
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
$do->fb_enumFields = array('enumTest', 'enumTest2');
function getEnumOptions($table, $field) {
    return array('p' => 'gh', 'qwe' => 'qwe');
}
$do->fb_enumOptionsCallback = 'getEnumOptions';
$do->fb_enumOptions = array('enumTest2' => array('aa' => 'aa', 'bb' => 'cc'));
$do->fb_fieldsRequired = array('enumTest');
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
$el =& $form->getElement('enumTest');
var_dump($el->_type);
foreach ($el->_options as $option) {
    var_dump($option['attr']['value']);
    var_dump($option['text']);
}
$el =& $form->getElement('enumTest2');
var_dump($el->_type);
foreach ($el->_options as $option) {
    var_dump($option['attr']['value']);
    var_dump($option['text']);
}
?>
--EXPECT--
string(6) "select"
string(1) "p"
string(2) "gh"
string(3) "qwe"
string(3) "qwe"
string(6) "select"
string(0) ""
string(0) ""
string(2) "aa"
string(2) "aa"
string(2) "bb"
string(2) "cc"
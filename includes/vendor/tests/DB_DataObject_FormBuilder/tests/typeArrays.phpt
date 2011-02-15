--TEST--
type arrays
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
$el =& $form->getElement('id');
var_dump($el->getAttribute('type'));
$el =& $form->getElement('title');
var_dump($el->getAttribute('type'));
var_dump(strtolower(get_class($form->_elements[$form->_elementIndex['dateAcquired']])));
$el =& $form->getElement('genre_id');
var_dump($el->_type);

$do->fb_booleanFields = array('title');
$do->fb_textFields = array('enumTest');
$do->fb_dateFields = array('anotherField');
$do->fb_timeFields = array('dateAcquired');
$do->fb_enumFields = array('enumTest2');
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
$el =& $form->getElement('title');
var_dump($el->getAttribute('type'));
$el =& $form->getElement('enumTest');
var_dump($el->_type);
var_dump(strtolower(get_class($form->_elements[$form->_elementIndex['anotherField']])));
var_dump(strtolower(get_class($form->_elements[$form->_elementIndex['dateAcquired']])));
$el =& $form->getElement('enumTest2');
var_dump($el->_type);

?>
--EXPECT--
string(6) "hidden"
string(4) "text"
string(19) "html_quickform_date"
string(6) "select"
string(8) "checkbox"
string(8) "textarea"
string(19) "html_quickform_date"
string(19) "html_quickform_date"
string(6) "select"

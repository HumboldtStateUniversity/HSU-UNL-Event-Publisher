--TEST--
text elements
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
$el =& $form->getElement('summary');
var_dump($el->_type);
var_dump(strtolower(get_class($el)));
?>
--EXPECT--
string(8) "textarea"
string(23) "html_quickform_textarea"
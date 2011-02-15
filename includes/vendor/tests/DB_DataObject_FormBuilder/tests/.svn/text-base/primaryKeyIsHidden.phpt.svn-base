--TEST--
primary key is hidden
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
$do->fb_hidePrimaryKey = false;
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
$el =& $form->getElement('id');
var_dump($el->getAttribute('type'));
?>
--EXPECT--
string(6) "hidden"
string(4) "text"

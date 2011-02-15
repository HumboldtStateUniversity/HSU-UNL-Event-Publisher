--TEST--
Date Options
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
$do->fb_dateTimeElementFormat = 'Y-m-d H:i:s !';
$do->fb_dateElementFormat = 'Y-m-d !';
$do->fb_timeElementFormat = 'H:i:s !';
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
$el =& $form->getElement('dateAcquired');
var_dump($el->_options['format']);
$el =& $form->getElement('dateField');
var_dump($el->_options['format']);
$el =& $form->getElement('timeField');
var_dump($el->_options['format']);
?>
--EXPECT--
string(13) "Y-m-d H:i:s !"
string(7) "Y-m-d !"
string(7) "H:i:s !"

--TEST--
Date Options Callbacks
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
function dateOptions($field, &$fb) {
    return array('format' => 'Y-m-d !');
}
function timeOptions($field, &$fb) {
    return array('format' => 'H:i:s !');
}
function dateTimeOptions($field, &$fb) {
    return array('format' => 'Y-m-d H:i:s !');
}
$do->fb_dateOptionsCallback = 'dateOptions';
$do->fb_timeOptionsCallback = 'timeOptions';
$do->fb_dateTimeOptionsCallback = 'dateTimeOptions';
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

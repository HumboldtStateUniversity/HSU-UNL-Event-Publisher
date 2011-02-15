--TEST--
create submit
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
var_dump($form->elementExists('title'));

$do->fb_elementNamePrefix = 'pre_';
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
var_dump($form->elementExists('pre_title'));

$do->fb_elementNamePrefix = '';
$do->fb_elementNamePostfix = '_post';
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
var_dump($form->elementExists('title_post'));

$do->fb_elementNamePrefix = 'pre_';
$do->fb_elementNamePostfix = '_post';
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
var_dump($form->elementExists('pre_title_post'));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)

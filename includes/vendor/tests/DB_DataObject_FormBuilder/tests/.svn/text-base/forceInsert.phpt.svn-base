--TEST--
forceInsert
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
$_POST = $_REQUEST = array('title' => 'Alien', 'genre_id' => '5', '_qf__dataobject_movie' => '');
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
$do->title = 'Alien';
$num = $do->find();
if (!$num) {
	die('Alien record not found, please re-build the testing DB
');
} elseif ($num > 1) {
	die('More than 1 Alien record found, please rebuild the testing db
');
}
$do->fetch();
$fb =& DB_DataObject_FormBuilder::create($do);
$fb->forceQueryType(DB_DATAOBJECT_FORMBUILDER_QUERY_FORCEINSERT);
$form =& $fb->getForm();
if ($form->validate()) {
	echo 'Validated
';
	$form->process(array(&$fb, 'processForm'), false);
	$do2 =& DB_DataObject::factory('movie');
	$do2->title = 'Alien';
	$num = $do2->find();
	if ($num) {
		echo 'Found '.$num.'
';
		if ($num == 2) {
			$do->fetch();
			$do->fetch();
			$do->delete();
		}
	}
} else {
	$form->display();
}
?>
--EXPECT--
Validated
Found 2

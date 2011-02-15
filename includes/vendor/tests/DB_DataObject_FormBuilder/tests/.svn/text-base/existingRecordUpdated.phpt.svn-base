--TEST--
existingRecordUpdated
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
$_POST = $_REQUEST = array('title' => 'Alien', 'genre_id' => '5', 'anotherField' => 'TEST','_qf__dataobject_movie' => '');
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
if (!$do->get('title', 'Alien')) {
	die('Alien record not found, please re-build the testing DB
');
}
if ($do->anotherField !== '') {
	die('Alien record field anotherField is filled out, pleas re-build the testing DB
');
}
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
if ($form->validate()) {
	echo 'Validated
';
	$form->process(array(&$fb, 'processForm'), false);
	$do2 =& DB_DataObject::factory('movie');
	if ($do2->get('title', $_POST['title'])) {
		echo 'Found
';
		if ($do2->anotherField !== 'TEST') {
			die('Update failed, anotherField is "'.$do2->anotherField.'"
');
		}
		$do2->anotherField = '';
		$do2->update();
	}
} else {
	$form->display();
}
?>
--EXPECT--
Validated
Found


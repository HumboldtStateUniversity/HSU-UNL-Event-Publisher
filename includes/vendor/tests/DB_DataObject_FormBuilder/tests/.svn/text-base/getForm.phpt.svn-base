--TEST--
getForm
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$do =& DB_DataObject::factory('movie');
$fb =& DB_DataObject_FormBuilder::create($do);
$form =& $fb->getForm();
var_dump(strtolower(get_class($form)));
foreach ($do->table() as $field => $type) {
    if (!$form->elementExists($field)) {
        echo $field.' missing
';
    }
}
?>
--EXPECT--
string(14) "html_quickform"

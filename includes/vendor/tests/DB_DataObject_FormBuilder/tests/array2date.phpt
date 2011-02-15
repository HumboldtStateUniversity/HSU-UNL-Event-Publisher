--TEST--
_array2Date
--SKIPIF--
<?php require_once dirname(__FILE__).'/config.php'; ?>
--FILE--
<?php
include(dirname(__FILE__).'/config.php');
$mydate = array(
                'F'=>'8',
                'd'=>'8',
                'Y'=>'2005',
                'h'=>'2',
                'i'=>'51',
                'a'=>'pm',
                );
echo DB_DataObject_FormBuilder::_array2date($mydate)."\n";
$mydate = array(
                'F'=>'8',
                'd'=>'8',
                'Y'=>'2005',
                'h'=>'12',
                'i'=>'00',
                'a'=>'pm',
                );
echo DB_DataObject_FormBuilder::_array2date($mydate)."\n";
$mydate = array(
                'F'=>'8',
                'd'=>'8',
                'Y'=>'2005',
                'h'=>'12',
                'i'=>'31',
                'a'=>'am',
                );
echo DB_DataObject_FormBuilder::_array2date($mydate)."\n";
?>
--EXPECT--
2005-08-08 14:51
2005-08-08 12:00
2005-08-08 00:31


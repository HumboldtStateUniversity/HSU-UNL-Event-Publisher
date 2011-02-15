<?php
/**
 * Table Definition for manufacturer
 */
require_once 'DB/DataObject.php';

class Manufacturer extends DB_DataObject
{
     var $fb_linkDisplayFields = array('name');

    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'manufacturer';                    // table name
    var $manufacturer_id;                 // int(10)  not_null primary_key unique_key unsigned auto_increment
    var $name;                            // string(80)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Manufacturer',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    // This class does not have the usual 'title' field as globally defined in the
    // dataObject.ini file, thus it needs an overriding property so that selectboxes
    // can be built correctly from this class.
    var $fb_linkDisplayFields = array('name');

}
?>
<?php
/**
 * Table Definition for category
 */
require_once 'DB/DataObject.php';

class Category extends DB_DataObject
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'category';                        // table name
    var $category_id;                     // int(10)  not_null primary_key unique_key unsigned auto_increment
    var $title;                           // string(80)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Category',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    var $fb_linkDisplayFields = array('title');
}
?>
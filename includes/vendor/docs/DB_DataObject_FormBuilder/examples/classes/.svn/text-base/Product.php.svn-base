<?php
/**
 * Table Definition for product
 */
require_once 'DB/DataObject.php';

class Product extends DB_DataObject 
{

    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'product';                         // table name
    var $product_id;                      // int(10)  not_null primary_key unsigned auto_increment
    var $title;                           // string(80)  not_null
    var $article_number;                  // string(40)  
    var $description;                     // blob(65535)  blob
    var $manufacturer_ID;                 // int(10)  not_null multiple_key unsigned
    var $category_ID;                     // int(10)  not_null multiple_key unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('Product',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
?>
<?php

/*************************************************************************
 * As all DataObject-derived classes can be used in exactly the same way,
 * we will use only a single, dynamic script for all the tables
 *************************************************************************/

// Be unforgiving about ANY errors, warnings or notices!
error_reporting(E_ALL);

// Read DataObject and FormBuilder configurations
require_once './config_inc.php';

// Which class to use?
$class = isset($_GET['class']) ? $_GET['class'] : 'Product';

// make the object
require_once 'DB/DataObject.php';
$obj = DB_DataObject::factory($class);

// Maybe an existing record was chosen? If yes, fetch it before making the form!
// (If you don't, a new one will be created on form submit)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $obj->get($_GET['id']);
}

// Include the FormBuilder class definition
require_once 'DB/DataObject/FormBuilder.php';

// Create the FormBuilder object BY REFERENCE and pass the DataObject
$formBuilder =& DB_DataObject_FormBuilder::create($obj);

// Create the form, make sure to always make a REFERENCE to the FormBuilder object!
$form =& $formBuilder->getForm($_SERVER['REQUEST_URI']);

// If the form was posted and the data has passed all rules,
// apply the changes to the database
if ($form->validate()) {
    $form->process(array($formBuilder, 'processForm'), false);
}

/**
 * Although not essential for this example, let's make a quick list of the things
 * that already are in the database. All entries will have a link that enables
 * you to update the records by passing the appropriate ID.
 */
$listObj = new $class;
$list = null;
// Do we have enough info on the object to list all records? We need a field to use for displaying the entry...
if (isset($_DB_DATAOBJECT_FORMBUILDER['CONFIG']['select_display_field']) || isset($listObj->select_display_field)) {
    // look if there are already records in this table
    $num = $listObj->find();
    if ($num > 0) {
        // yes, make a list for later output
        $list = '';
        while ($listObj->fetch()) {
            // look for the display field in the object
            if (isset($listObj->select_display_field)) {
                $titleField = $listObj->select_display_field;
                $title = $listObj->$titleField;
            } else {
                $title = $listObj->$_DB_DATAOBJECT_FORMBUILDER['CONFIG']['select_display_field'];
            }
            // get the primary key
            $keyField = strtolower($class.'_id');
            $id = $listObj->$keyField;
            // make the list entry
            $list .= sprintf("<a href='?class=%s&id=%s'>%s</a><br />", $class, $id, $title);
        }
    }
}



// Some HTML for nice output, inline for simplicity
?>
<html>
  <head>
    <title>DB_DataObject_FormBuilder Example</title>
    <style type="text/css">
      <!--
        body { font-family: Georgia, Verdana, Arial, Helvetica, Sans-Serif; font-size: 1em; }
        h1 { font-size: 1.5em; font-weight: bold; }
        .classes { background: #EFEFEF; padding: 5px; margin: 5px; border: 1px solid black; width: 150px; float: left; }
        .form { margin-left: 200px; width: 300px; }
        .list { margin-left: 200px; background: #EFEFEF; border: 1px dotted grey; width: 300px; }
      -->
    </style>
  </head>
  <body>
    <h1>Table/Class: <?php echo($class); ?></h1>
    <div class='classes'>
      <a href='?class=Category'>Categories</a><br />
      <a href='?class=Manufacturer'>Manufacturers</a><br />
      <a href='?class=Product'>Products</a><br />
    </div>
    <div class='form'><?php $form->display(); ?></div>
    <div class='list'><?php echo($list); ?></div>
  </body>
</html>
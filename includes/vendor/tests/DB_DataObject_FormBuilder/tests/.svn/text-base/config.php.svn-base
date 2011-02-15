<?php
ini_set('include_path', '.'.PATH_SEPARATOR.
        dirname(__FILE__).PATH_SEPARATOR.
        '/home/papercrane/include'.PATH_SEPARATOR.
        ini_get('include_path'));
require_once 'DB/DataObject.php';
require_once 'DB/DataObject/FormBuilder.php';
if (!file_exists(dirname(__FILE__).'/DSN')) {
    echo 'skip You need to put your DB DSN in the file "DSN" and import movie.sql before running these tests';
}
$config = array (
  'DB_DataObject' =>
  array (
         'database' => file_get_contents(dirname(__FILE__).'/DSN'),
         'schema_location' => dirname(__FILE__).'/DataObjects',
         'class_location' => dirname(__FILE__).'/DataObjects',
         'require_prefix' => 'DataObjects/',
         'class_prefix' => 'DataObject_',
         'quote_identifiers' => '1',
         ),
  );
foreach($config as $class => $values) {
    $options =& PEAR::getStaticProperty($class, 'options');
    $options = $values;
}
if (!file_exists($config['DB_DataObject']['schema_location'])) {
    DB_DataObject::debugLevel(0);
    require_once 'DB/DataObject/Generator.php';
    $generator = new DB_DataObject_Generator();
    $generator->start();
}
?>
#!/usr/bin/php
<?php

die("This script will rename select(Display|Order)Fields to link(Display|Order)Fields.\nIt is for use only by those who have used CVS versoions of FormBuilder.php after 1.36 up to 1.51.\nPlease backup your files and configure this script before using.\n");

//set this to the path to your DataObject files
$path = '/path/to/DataObjectFiles/';

//DO NOT EDIT BELOW HERE
if($path[strlen($path) - 1] != '/') {
    $path .= '/';
}

include('File/SearchReplace.php');
$snr = new File_SearchReplace('/select(Display|Order)Fields/', 'link\1Fields', array(), $path);
$snr->setSearchFunction('preg');
$snr->doSearch();
<?php

require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$version_release = '1.0.0RC6';
$version_api = $version_release;
$state = 'beta';
$notes = 'Additions:
- Request #8721 CrossLinks now have a tri-state link which can hide all (thanks to peter at niebling dot name)

Bugs Fixed:
- Form processing will now fail when a reverseLink subForm fails processing
- Bug #8105 The "g" formatter is now available in the default date formatter callbacks (thanks to dsanders at baselinesolutions dot com dot au)
- Bug #6962 Crosslinks with special chars in their names, such as postgres tables, are processed again

Changes:
- The internal ElementTable QuickForm element is no longer used in favor of HTML_QuickForm_ElementGrid
- package.xml 2.0 is now used exclusively, you must have PEAR 1.4.4 or greater installed to install DB_DataObject_FormBuilder
- This package is now licensed under the LGPL';
$summary = 'Class to automatically build HTML_QuickForm objects from a DB_DataObject-derived class';
$description = 'DB_DataObject_FormBuilder will aid you in rapid application development using the packages DB_DataObject and HTML_QuickForm. For having a quick but working prototype of your application, simply model the database, run DataObject&apos;s createTable script over it and write a script that passes one of the resulting objects to the FormBuilder class. The FormBuilder will automatically generate a simple but working HTML_QuickForm object that you can use to test your application. It also provides a processing method that will automatically detect if an insert() or update() command has to be executed after the form has been submitted. If you have set up DataObject&apos;s links.ini file correctly, it will also automatically detect if a table field is a foreign key and will populate a selectbox with the linked table&apos;s entries. There are many optional parameters that you can place in your DataObjects.ini or in the properties of your derived classes, that you can use to fine-tune the form-generation, gradually turning the prototypes into fully-featured forms, and you can take control at any stage of the process.';
$packagefile = './package.xml';
$options = array(
    'filelistgenerator' => 'cvs',
    'changelogoldtonew' => false,
    'simpleoutput'      => true,
    'baseinstalldir'    => '/',
    'packagedirectory'  => './',
    'packagefile'       => $packagefile,
    'clearcontents'     => false,
    'ignore'            => array('package*.php', 'package*.xml'),
    'dir_roles'         => array(
         'docs'     => 'doc',
         'examples' => 'doc',
         'tests'    => 'test',
         'tools'    => 'data',
    ),
);

$package = &PEAR_PackageFileManager2::importOptions($packagefile, $options);
$package->setPackageType('php');
$package->clearDeps();
$package->setPhpDep('4.3.0');
$package->setPearInstallerDep('1.4.4');
$package->addPackageDepWithChannel('required', 'DB_DataObject', 'pear.php.net', '1.8.5');
$package->addPackageDepWithChannel('required', 'HTML_QuickForm', 'pear.php.net', '3.2.4');
$package->addPackageDepWithChannel('optional', 'Date', 'pear.php.net', '1.4.7');
$package->addPackageDepWithChannel('optional', 'HTML_Table', 'pear.php.net', '1.7.5');
$package->addPackageDepWithChannel('optional', 'HTML_QuickForm_ElementGrid', 'pear.php.net', '0.1.0');
$package->addRelease();
$package->generateContents();
$package->setReleaseVersion($version_release);
$package->setAPIVersion($version_api);
$package->setReleaseStability($state);
$package->setAPIStability($state);
$package->setNotes($notes);
$package->setSummary($summary);
$package->setDescription($description);
$package->setLicense('LGPL', 'http://www.gnu.org/licenses/lgpl.txt');
//$package->addGlobalReplacement('package-info', '@package_version@', 'version');

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    $package->writePackageFile();
} else {
    $package->debugPackageFile();
}
?>
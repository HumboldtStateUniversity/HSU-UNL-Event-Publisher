#!/usr/bin/php
<?php
require_once ('File/SearchReplace.php');

//Make a backup of your files before using this script!

//comment out this line once you've configured this script
die("Configure this script and make a backup of your files before using!\nThis script will change your DataObject.ini and createTables.php created files.\nIt will also move your db.formBuilder.ini settings into your DataObjects.\n\n");

//Path to DataObject.ini, or the place where your [DB_DataObject] [DB_DataObject_FormBuilder] settings are
// if you didn't use an ini file (i.e. set the schema_locaiton and such in PHP) this script won't work for you
$doIni = '/path/to/DataObject.ini';

//DO NOT EDIT BELOW HERE

//This is because I'm lazy and I wrote the changes this way originally
$replaceStr = 'select_display_field    => linkDisplayFields
select_order_field      => linkOrderFields
selectDisplayFields => linkDisplayFields
selectOrderFields => linkOrderFields
_linkDisplayFields    => linkDisplayFields
_linkOrderFields      => linkOrderFields
_dateToDatabaseCallback => dateToDatabaseCallback
_dateFromDatabaseCallback => dateFromDatabaseCallback
_elementTypeMap => elementTypeMap
_dateFieldLanguage => dateFieldLanguage
follow_links => linkDisplayLevel
hide_primary_key => hidePrimaryKey
date_element_format => dateElementFormat
db_date_format => dbDateFormat
add_form_header => addFormHeader
form_header_text => formHeaderText
rule_violation_message => ruleViolationMessage
required_rule_message => requiredRuleMessage
from_field => fromField
to_field => toField
toField_1 => toField1
toField_2 => toField2
linkDisplayFields => fb_linkDisplayFields
linkOrderFields => fb_linkOrderFields
_crossLinks => fb_crossLinks
_tripleLinks => fb_tripleLinks
__tripleLink => __tripleLink
__crosslink => __crossLink
preDefOrder => fb_preDefOrder
preDefGroups => fb_preDefGroups
fieldLabels => fb_fieldLabels
textFields => fb_textFields
dateFields => fb_dateFields
preDefElements => fb_preDefElements
fieldsToRender => fb_fieldsToRender
userEditableFields => fb_userEditableFields
preProcess => preProcessForm
postProcess => postProcessForm
createSubmit => fb_createSubmit
submitText => fb_submitText
hidePrimaryKey => fb_hidePrimaryKey
select_add_empty => fb_selectAddEmpty';

$replaceArr = array();
foreach (explode("\n", $replaceStr) as $replace) {
    if (trim($replace)) {
        list($from, $to) = explode('=>', $replace);
        $replaceArr[trim($from)] = trim($to);
    }
}

if (!file_exists($doIni)) {
    die("Can't find ini file $doIni\n");
}
echo 'Fixing '.$doIni."\n";
foreach ($replaceArr as $from => $to) {
    if (substr($to, 0, 3) != 'fb_') {
        $snr = new File_SearchReplace($from, $to, $doIni);
        $snr->setSearchFunction('quick');
        $snr->doSearch();
    }
}
$settings = parse_ini_file($doIni);
$db = substr($settings['database'], strrpos($settings['database'], '/') + 1);
if (!file_exists($settings['schema_location'])) {
    die('Schema loction doesn\'t exist "'.$settings['schema_location']."\"\n");
}
echo 'Fixing DataObject files in '.$settings['schema_location']."\n";
foreach ($replaceArr as $from => $to) {
    $snr = new File_SearchReplace($from, $to, false, $settings['schema_location'].'/', false);
    $snr->setSearchFunction('quick');
    $snr->doSearch();
}
if (file_exists($settings['schema_location'].'/'.$db.'.formBuilder.ini')) {
    echo 'Moving formBuilder.ini settings to DataObject files'."\n";
    $fbIni = parse_ini_file($settings['schema_location'].'/'.$db.'.formBuilder.ini', true);
    foreach ($fbIni as $set => $arr) {
        if ($pos = strpos($set, '__display_fields')) {
            $fb[substr($set, 0, $pos)]['linkDisplayFields'] = $arr;
        }
        if ($pos = strpos($set, '__order_fields')) {
            $fb[substr($set, 0, $pos)]['linkOrderFields'] = $arr;
        }
    }
    foreach ($fb as $table => $opts) {
        $file = $settings['schema_location'].'/'.ucfirst($table).'.php';
        echo '  Adding formBuilder.ini settings to '.$file."\n";
        $str = '';
        foreach ($opts as $name => $opt) {
            $str = '    var $fb_'.$name.' = '.str_replace("\n", str_pad("\n", 16 + strlen($name), ' ', STR_PAD_RIGHT), var_export($opt, true)).";\n";
        }
        $snr = new File_SearchReplace('###END_AUTOCODE', "###END_AUTOCODE\n\n".$str, $file);
        $snr->setSearchFunction('quick');
        $snr->doSearch();
    }
}

?>
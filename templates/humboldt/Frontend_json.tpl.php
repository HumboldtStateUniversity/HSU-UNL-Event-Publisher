<?php
require_once dirname(__FILE__).'/../../includes/xml2json/xml2json.php';

ob_start();

include dirname(__FILE__).'/Frontend_xml.tpl.php';

$data = ob_get_clean();

$jsonContents = xml2json::transformXmlStringToJson($data);
echo $jsonContents;
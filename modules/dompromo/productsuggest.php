<?php

include_once(dirname(__FILE__).'../../../config/config.inc.php');
include_once(dirname(__FILE__).'/dompromo.php');

$dompromo = new dompromo();

function getProductList($mot, $limite) {
		$id_lang = Configuration::get('PS_LANG_DEFAULT');
		$sql = Db::getInstance()->ExecuteS('
				SELECT  id_product, name FROM `'._DB_PREFIX_.'product_lang`
				WHERE id_lang = \''.(int)($id_lang).'\'
				AND name like \''.$mot.'%\' 
				LIMIT 0,'.(int)($limite).'
				');
		return $sql;
}

$input = strtolower( $_GET['input'] );
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 0;
$aResults = getProductList($input, $limit);

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0

if (isset($_REQUEST['json'])) {
	header("Content-Type: application/json");
	echo "{\"results\": [";
	$arr = array();
	for ($i=0;$i<count($aResults);$i++) {
		$arr[] = "{\"id\": \"".$aResults[$i]['id_product']."\", \"value\": \"".$aResults[$i]['name']." (ID:".$aResults[$i]['id_product'].")\", \"info\": \"\"}";
	}
	echo implode(", ", $arr);
	echo "]}";
}

?>

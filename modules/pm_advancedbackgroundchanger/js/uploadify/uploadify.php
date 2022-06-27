<?php

$pm_uploadify_503 = true;
function pmAvoidMaintenanceMode() {
	global $pm_uploadify_503;
	if ($pm_uploadify_503) {
		if (strlen(ob_get_contents()) > 1) ob_clean();
		header("HTTP/1.1 200 OK");
		pmProcessUploadedFile(true);
	}
}
@register_shutdown_function('pmAvoidMaintenanceMode');

function pmProcessUploadedFile($from_maintenance = false) {
	global $pm_uploadify_503;
	if (!empty($_FILES) && isset($_REQUEST['secureKey']) && !empty($_REQUEST['secureKey'])) {
		if (!$from_maintenance) {
			if (!include_once(dirname(__FILE__).'/../../../../config/config.inc.php')) die;
			if (!include_once (dirname(__FILE__).'/../../../../init.php')) die;
			$pm_uploadify_503 = false;
		}
		
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$extension = explode('.', $_FILES['Filedata']['name']);
		$extension = end($extension);
		$extension = strtolower($extension);
		
		$ext_allow = array('gif','jpg','zip','doc','png','bmp','jpeg');
		if (!in_array($extension,$ext_allow)) die;
		
		$secureKey = explode('---', $_REQUEST['secureKey']);
		if (is_array($secureKey) && sizeof($secureKey) == 2) {
			$secureKeyName = $secureKey[0];
			$secureKeyValue = $secureKey[1];
			if (preg_match('#_UPLOAD_SECURE_KEY#', $secureKeyName) && strlen($secureKeyValue) == 16 && (_PS_VERSION_ >= 1.5 ? Configuration::getGlobalValue($secureKeyName) : Configuration::get($secureKeyName)) == $secureKeyValue) {
				$targetPath = realpath(realpath(dirname(__FILE__)) . '/../../uploads/temp') . '/';
				// Delete prev file
				if(isset($_REQUEST['filename']) && $_REQUEST['filename'] && file_exists($targetPath.$_REQUEST['filename']))
					unlink($targetPath.$_REQUEST['filename']);
				$targetFile = str_replace('//','/',$targetPath) . uniqid().'.'.$extension;
				move_uploaded_file($tempFile,$targetFile);
				if (ob_get_length() > 0) ob_clean();
				die(basename($targetFile));
			} else {
				die;
			}
		} else {
			die;
		}
	} else {
		die;
	}
}
if (!empty($_FILES) && isset($_REQUEST['secureKey']) && !empty($_REQUEST['secureKey'])) pmProcessUploadedFile();
?>
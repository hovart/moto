<?php
include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('bannerdestockage.php');

$context = Context::getContext();
$sub_banner = new bannerdestockage();
$slides = array();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $sub_banner->secure_key || !Tools::getValue('action'))
	die(1);

if (Tools::getValue('action') == 'updateSlidesPosition' && Tools::getValue('slides'))
{

	$slides = Tools::getValue('slides');

	foreach ($slides as $position => $id_slide)
	{
		$res = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'bannerdestockage_slides` SET `position` = '.(int)$position.'
			WHERE `id_bannerdestockage_slides` = '.(int)$id_slide
		);

	}
	
	$sub_banner->clearCache();
}


<?php

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('tptnbotbanner.php');

$context = Context::getContext();
$bot_banner = new TptnBotBanner();
$slides = array();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $bot_banner->secure_key || !Tools::getValue('action'))
	die(1);

if (Tools::getValue('action') == 'updateSlidesPosition' && Tools::getValue('slides'))
{

	$slides = Tools::getValue('slides');

	foreach ($slides as $position => $id_slide)
	{
		$res = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'tptnbotbanner_slides` SET `position` = '.(int)$position.'
			WHERE `id_homeslider_slides` = '.(int)$id_slide
		);

	}

	$bot_banner->clearCache();
}
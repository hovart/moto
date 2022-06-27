<?php
include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('tptncolbanner.php');

$context = Context::getContext();
$col_banner = new TptnColBanner();
$slides = array();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $col_banner->secure_key || !Tools::getValue('action'))
	die(1);

if (Tools::getValue('action') == 'updateSlidesPosition' && Tools::getValue('slides'))
{

	$slides = Tools::getValue('slides');

	foreach ($slides as $position => $id_slide)
	{
		$res = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'tptncolbanner_slides` SET `position` = '.(int)$position.'
			WHERE `id_tptncolbanner_slides` = '.(int)$id_slide
		);

	}
	
	$col_banner->clearCache();
}


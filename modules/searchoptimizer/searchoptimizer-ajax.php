<?php
/**
* 2015 Novansis
*
* NOTICE OF LICENSE
*
* Conditions and limitations:
*
* A. This source code file is copyrighted, you cannot remove any copyright notice from this file.  You agree to prevent any unauthorized copying of this file.  Except as expressly provided herein, Novansis does not grant any express or implied right to you under copyrights, trademarks, or trade secret information.
*
* B. You may NOT:  (i) rent or lease the file to any third party; (ii) assign this file or transfer the file without the express written consent of Novansis; (iii) modify, adapt, or translate the file in whole or in part; or (iv) distribute, sublicense or transfer the source code form of any components of the file and derivatives thereof to any third party.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer versions in the future.
*
*  @author    Novansis <info@novansis.com>
*  @copyright 2015 Novansis SL
*  @license   http://www.novansis.com/
*/

header('Content-Type: application/json');

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/searchoptimizer.php');

	$vfko4nemcca0 = new SearchOptimizer();

	$vykqw1uy0bur = null;
	$vygadmpcz5nz = null;
	if (Shop::isFeatureActive())
	{
		$vykqw1uy0bur = Tools::getValue('g');
		$vygadmpcz5nz = Tools::getValue('s');
	}

	if (Tools::getValue('act') == 'w')
		echo Tools::jsonEncode($vfko4nemcca0->w($vykqw1uy0bur, $vygadmpcz5nz, Tools::getValue('letterFilter'), Tools::getValue('lang')));
	elseif (Tools::getValue('act') == 'wtr')
		echo Tools::jsonEncode($vfko4nemcca0->wtr($vykqw1uy0bur, $vygadmpcz5nz, Tools::getValue('lang')));
	elseif (Tools::getValue('act') == 'a')
		echo Tools::jsonEncode($vfko4nemcca0->a($vykqw1uy0bur, $vygadmpcz5nz, Tools::getValue('lang')));
	elseif (Tools::getValue('act') == 'sp')
		echo Tools::jsonEncode($vfko4nemcca0->sp($vykqw1uy0bur, $vygadmpcz5nz, Tools::getValue('results'), Tools::getValue('lang')));
	elseif (Tools::getValue('act') == 'r')
		echo Tools::jsonEncode($vfko4nemcca0->r($vykqw1uy0bur, $vygadmpcz5nz, Tools::getValue('search'), Tools::getValue('lang')));
	elseif (Tools::getValue('act') == 'rv')
		echo Tools::jsonEncode($vfko4nemcca0->rv($vykqw1uy0bur, $vygadmpcz5nz, Tools::getValue('remove')));
	elseif (Tools::getValue('act') == 'rsq')
		echo Tools::jsonEncode($vfko4nemcca0->rsq($vykqw1uy0bur, $vygadmpcz5nz, Tools::getValue('SEARCH_OPTIMIZER_RECORD')));
	elseif (Tools::getValue('act') == 'p')
		echo Tools::jsonEncode($vfko4nemcca0->p($vykqw1uy0bur, $vygadmpcz5nz, Tools::getValue('word'), Tools::getValue('lang')));
?>
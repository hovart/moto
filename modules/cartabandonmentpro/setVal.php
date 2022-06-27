<?php
/**
* 2007-2014 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2014 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

include_once('../../config/config.inc.php');
$token = Tools::getValue('token_cartabandonment');
$token_bdd = Configuration::get('CARTABAND_TOKEN');
$action = Tools::getValue('action');	/* name field in Configuration table */
$chars = explode('_', $action);

if (Tools::strlen($token_bdd) > 3 && isset($token_bdd) && isset($token) && $token_bdd == $token && isset($chars[0]) && $chars[0] == 'CARTABAND')
{
	require_once dirname(__FILE__).'/controllers/AjaxController.class.php';
	$val 	= Tools::getValue('val');		/* value of the configuration */
	AjaxController::call($action, $val);
}
else
{
	echo 'hack ...';
	die;
}
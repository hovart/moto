<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/postfinance.php');

$post_finance = new PostFinance();
$values = $post_finance->getReturnValues();

$live_mode = (int)Configuration::get($post_finance->name.'_LIVE_MODE');
$response_code = $values['NCERROR'];

$order_msg = '';
/* Check 3D Secure */
if (isset($values['STATUS_3D']))
{
	$status_3d = pSQL($values['STATUS_3D']);
	if ($status_3d != 'Y')
		$order_msg .= '3D Secure failure. Returned 3D Secure status :'.$status_3d;
}

if ($live_mode === 0) /* If module is in testing mode */
{
	if (empty($order_msg) && $response_code == '0')
	{
		$order_msg = '***TEST*** : '.$post_finance->l('Validated Payment')."\n";
		$statut = _PS_OS_PAYMENT_;
	}
	else
	{
		$order_msg = '***TEST*** : '.$post_finance->l('Invalid Payment')."\n".$response_code;
		$statut = _PS_OS_ERROR_;
	}
}
elseif ($live_mode === 1) /* If module is in production mode */
{
	if ($response_code == '0' && empty($order_msg))
		$statut = _PS_OS_PAYMENT_;
	else
		$statut = _PS_OS_ERROR_;
}

/* Create an order message for the order page */
$order_msg .= "\n".$post_finance->l('Total paid :').' '.(float)$values['AMOUNT']."\n";
$order_msg .= "\n".$post_finance->l('Credit/debit card brand :').' '.$values['BRAND']."\n";
$order_msg .= "\n".$post_finance->l('Order Status :').' '.$statut."\n";
$order_msg .= $post_finance->checkForError($response_code);
$order_msg .= "\n\n".$post_finance->l('Debug info:')."\n".' POST '.print_r($_POST, true)."\n".'GET '.print_r($_GET, true)."\n";

$id_cart = (int)Tools::substr($values['ORDERID'], 8, strpos($values['ORDERID'], '_('));

$post_finance->validate($id_cart, (float)$values['AMOUNT'], $values['PAYID'], $statut, $order_msg);

$urls = $post_finance->getUrls($id_cart);

Tools::redirectLink($urls['ok']);
<?php
/**
* 2007-2015 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

class flashsaleproflashSaleProductsModuleFrontController extends FrontController
{
	public function initContent()
	{
		parent::initContent();
		$flash_sale = new FlashSalePro();

		$flash_sale->checkExpiredSales();
		$flash_sale_info = $flash_sale->getFlashSaleInfo();
		$this->context->smarty->assign('path', $flash_sale->l('Flash Sale').' - '.$flash_sale_info['name']);

		$flash_sale_items = '';

		if ($flash_sale_info != null)
		{
			$flash_sale_items = $flash_sale->getFlashSaleItems($flash_sale_info['id_flashsalespro']);

			$keys = array(
				'FLASHSALEPRO_TIMER_BG_COLOR',
				'FLASHSALEPRO_TIMER_TEXT_COLOR',
				'FLASHSALEPRO_TIMER_DOT_COLOR'
				);
			$configs = Configuration::getMultiple($keys);

			$image_default = $flash_sale->ps_url.'modules/'.$flash_sale->name.'/views/img/flash_sale_logo.png';
			$this->context->smarty->assign(array(
				'flash_sale_info' => $flash_sale_info,
				'flash_sale_items' => $flash_sale_items,
				'image_default' => $image_default,
				'timer_bg_color' => pSQL($configs['FLASHSALEPRO_TIMER_BG_COLOR']),
				'timer_text_color' => pSQL($configs['FLASHSALEPRO_TIMER_TEXT_COLOR']),
				'timer_dot_color' => pSQL($configs['FLASHSALEPRO_TIMER_DOT_COLOR']),
				'ps_url' => $flash_sale->ps_url,
				'ps_version' => (bool)version_compare(_PS_VERSION_, '1.6', '>')
				));
		}

		$this->template = dirname(__FILE__).'/../../views/templates/front/productList.tpl';
	}
}
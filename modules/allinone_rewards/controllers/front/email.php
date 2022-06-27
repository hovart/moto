<?php
/**
 * All-in-one Rewards Module
 *
 * @category  Prestashop
 * @category  Module
 * @author    Yann BONNAILLIE - ByWEB
 * @copyright 2012-2014 Yann BONNAILLIE - ByWEB (http://www.prestaplugins.com)
 * @license   Commercial license see license.txt
 * Support by mail  : contact@prestaplugins.com
 * Support on forum : Patanock
 * Support on Skype : Patanock13
 */

class Allinone_rewardsEmailModuleFrontController extends ModuleFrontController
{
	public $content_only = true;
	public $display_header = false;
	public $display_footer = false;

	public function initContent()
	{
		// allow to not add the javascript at the end causing JS issue (presta 1.6)
		$this->controller_type = 'modulefront';
		parent::initContent();

		$id_template = (int)MyConf::getIdTemplate('sponsorship', $this->context->customer->id);
		$shop_name = htmlentities(Configuration::get('PS_SHOP_NAME'), NULL, 'utf-8');
		$shop_url = Tools::getShopDomain(true, true).__PS_BASE_URI__.'index.php';

		if (Configuration::get('PS_LOGO_MAIL') !== false && file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO_MAIL', null, null, $this->context->shop->id)))
			$shop_logo = Tools::getShopDomain(true, true)._PS_IMG_.Configuration::get('PS_LOGO_MAIL', null, null, $this->context->shop->id);
		else if (file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, $this->context->shop->id)))
			$shop_logo = Tools::getShopDomain(true, true)._PS_IMG_.Configuration::get('PS_LOGO', null, null, $this->context->shop->id);
		else if (file_exists(_PS_IMG_DIR_.'logo.jpg'))
			$shop_logo = Tools::getShopDomain(true, true)._PS_IMG_.'logo.jpg';

		$discount_gc = $this->module->getDiscountReadyForDisplay((int)MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $id_template), (int)MyConf::get('RSPONSORSHIP_FREESHIPPING_GC', null, $id_template), (float)MyConf::get('RSPONSORSHIP_VOUCHER_VALUE_GC_'.(int)$this->context->currency->id, null, $id_template));

		$iso = Language::getIsoById((int)$this->context->language->id);
		$template = 'sponsorship-invitation-novoucher.html';
		if ((int)MyConf::get('RSPONSORSHIP_DISCOUNT_GC', null, $id_template) == 1) {
			if ((int)MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $id_template) != 0)
				$template = 'sponsorship-invitation.html';
			else
				$template = 'sponsorship-invitation-freeshipping.html';
		}
		if (version_compare(_PS_VERSION_, '1.6', '>='))
			$template = '16-'.$template;

		if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template))
			$file = Tools::file_get_contents(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template);
		else
			$file = Tools::file_get_contents(dirname(__FILE__).'/../../mails/en/'.$template);
		$file = str_replace('{shop_name}', $shop_name, $file);
		$file = str_replace('{shop_url}', $shop_url, $file);
		$file = str_replace('{shop_logo}', $shop_logo, $file);
		$file = str_replace('{firstname}', $this->context->customer->firstname, $file);
		$file = str_replace('{lastname}', $this->context->customer->lastname, $file);
		$file = str_replace('{email}', $this->context->customer->email, $file);
		$file = str_replace('{firstname_friend}', 'XXXXX', $file);
		$file = str_replace('{lastname_friend}', 'xxxxxx', $file);
		$file = str_replace('{link}', $this->context->link->getPageLink('index', true, $this->context->language->id, ''), $file);
		$file = str_replace('{nb_discount}', (int)MyConf::get('RSPONSORSHIP_QUANTITY_GC', null, $id_template), $file);
		$file = str_replace('{discount}', $discount_gc, $file);

		$this->context->smarty->assign(array('sback' => Tools::getValue('sback'), 'content' => $file));
		$this->setTemplate('email.tpl');
	}
}
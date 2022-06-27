<?php
abstract class HTMLTemplate extends HTMLTemplateCore
{
	protected function getLogo()
	{
		$logo = '';

		$physical_uri = Context::getContext()->shop->physical_uri.'img/';
		$language = Language::getLanguage($this->order->id_lang);
		if(Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code'].'-'.$this->order->id_shop) && Shop::IsFeatureActive() && file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code'].'-'.$this->order->id_shop)))
			$logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code'].'-'.$this->order->id_shop);
		elseif(Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code']) && Shop::IsFeatureActive() == false && file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code'])))
			$logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO_INVOICE_LINK_'.$language['language_code']);
		elseif (Configuration::get('PS_LOGO_INVOICE', null, null, (int)$this->order->id_shop) != false && file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO_INVOICE', null, null, (int)$this->order->id_shop)))
			$logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO_INVOICE', null, null, (int)$this->order->id_shop);
		elseif (Configuration::get('PS_LOGO', null, null, (int)$this->order->id_shop) != false && file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, (int)$this->order->id_shop)))
			$logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, (int)$this->order->id_shop);
		return $logo;
	}
}
/* Created by PhpStorm.
 * User: Julien
 * Date: 08/01/14
 * Time: 09:23
 */ 
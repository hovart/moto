<?php
class FrontController extends FrontControllerCore
{
	public function initLogoAndFavicon()
	{
			if (Configuration::get('PS_LOGO_LINK_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id) && Shop::IsFeatureActive())
			{
				$mobile_device = $this->context->getMobileDevice();
				if ($mobile_device && Configuration::get('PS_LOGO_MOBILE_LINK_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id))
				{
					$logo = _PS_IMG_.Configuration::get('PS_LOGO_MOBILE_LINK_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id).'.jpg'.'?'.Configuration::get('PS_IMG_UPDATE_TIME');
					$logoheight = Configuration::get('SHOP_LOGO_MOBILE_LINK_H_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id);
					$logowidth = Configuration::get('SHOP_LOGO_MOBILE_LINK_W_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id);
				}
				elseif ($mobile_device && Configuration::get('PS_LOGO_MOBILE'))
				{
					$logo = _PS_IMG_.Configuration::get('PS_LOGO_MOBILE').'?'.Configuration::get('PS_IMG_UPDATE_TIME');
					$logoheight = Configuration::get('SHOP_LOGO_MOBILE_HEIGHT');
					$logowidth = Configuration::get('SHOP_LOGO_MOBILE_WIDTH');
				}
				elseif (Configuration::get('PS_LOGO_LINK_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id))
				{
					$logo = _PS_IMG_.Configuration::get('PS_LOGO_LINK_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id).'.jpg'.'?'.Configuration::get('PS_IMG_UPDATE_TIME');
					$logoheight = Configuration::get('SHOP_LOGO_LINK_H_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id);
					$logowidth = Configuration::get('SHOP_LOGO_LINK_W_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id);
				}
				else
				{
					$logo = _PS_IMG_.Configuration::get('PS_LOGO').'?'.Configuration::get('PS_IMG_UPDATE_TIME');
					$logoheight = Configuration::get('SHOP_LOGO_HEIGHT');
					$logowidth = Configuration::get('SHOP_LOGO_WIDTH');
				}
			}
			elseif ((Configuration::get('PS_LOGO_LINK_'.Context::getContext()->language->language_code)))
			{
				$mobile_device = $this->context->getMobileDevice();
				if ($mobile_device && Configuration::get('PS_LOGO_MOBILE_LINK_'.Context::getContext()->language->language_code))
				{
					$logo = _PS_IMG_.Configuration::get('PS_LOGO_MOBILE_LINK_'.Context::getContext()->language->language_code).'.jpg'.'?'.Configuration::get('PS_IMG_UPDATE_TIME');
					$logoheight = Configuration::get('SHOP_LOGO_MOBILE_LINK_H_'.Context::getContext()->language->language_code);
					$logowidth = Configuration::get('SHOP_LOGO_MOBILE_LINK_W_'.Context::getContext()->language->language_code);
				}
				elseif ($mobile_device && Configuration::get('PS_LOGO_MOBILE'))
				{
					$logo = _PS_IMG_.Configuration::get('PS_LOGO_MOBILE').'?'.Configuration::get('PS_IMG_UPDATE_TIME');
					$logoheight = Configuration::get('SHOP_LOGO_MOBILE_HEIGHT');
					$logowidth = Configuration::get('SHOP_LOGO_MOBILE_WIDTH');

				}
				elseif (Configuration::get('PS_LOGO_LINK_'.Context::getContext()->language->language_code))
				{
					$logo = _PS_IMG_.Configuration::get('PS_LOGO_LINK_'.Context::getContext()->language->language_code).'.jpg'.'?'.Configuration::get('PS_IMG_UPDATE_TIME');
					$logoheight = Configuration::get('SHOP_LOGO_LINK_H_'.Context::getContext()->language->language_code);
					$logowidth = Configuration::get('SHOP_LOGO_LINK_W_'.Context::getContext()->language->language_code);
				}
				else
				{
					$logo = _PS_IMG_.Configuration::get('PS_LOGO').'?'.Configuration::get('PS_IMG_UPDATE_TIME');
					$logoheight = Configuration::get('SHOP_LOGO_HEIGHT');
					$logowidth = Configuration::get('SHOP_LOGO_WIDTH');
				}
			}
			else
			{
				$mobile_device = $this->context->getMobileDevice();
				if ($mobile_device && Configuration::get('PS_LOGO_MOBILE'))
				{
					$logo = _PS_IMG_.Configuration::get('PS_LOGO_MOBILE').'?'.Configuration::get('PS_IMG_UPDATE_TIME');
					$logoheight = Configuration::get('SHOP_LOGO_MOBILE_HEIGHT');
					$logowidth = Configuration::get('SHOP_LOGO_MOBILE_WIDTH');

				}
				else
				{
					$logo = _PS_IMG_.Configuration::get('PS_LOGO').'?'.Configuration::get('PS_IMG_UPDATE_TIME');
					$logoheight = Configuration::get('SHOP_LOGO_HEIGHT');
					$logowidth = Configuration::get('SHOP_LOGO_WIDTH');
				}
			}


		$favicon_url = _PS_IMG_.Configuration::get('PS_FAVICON');

		if(Shop::IsFeatureActive() && Configuration::get('PS_FAVICON_LINK_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id))
			$favicon_url = _PS_IMG_.Configuration::get('PS_FAVICON_LINK_'.Context::getContext()->language->language_code.'-'.Context::getContext()->shop->id);

		if(Shop::IsFeatureActive()== false && Configuration::get('PS_FAVICON_LINK_'.Context::getContext()->language->language_code))
			$favicon_url = _PS_IMG_.Configuration::get('PS_FAVICON_LINK_'.Context::getContext()->language->language_code);

		return array(
			'favicon_url' => $favicon_url,
			'logo_image_width' => ($logowidth),
			'logo_image_height' => ($logoheight),
			'logo_url' => $logo
		);
	}
}
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 07/01/14
 * Time: 09:45
 */ 
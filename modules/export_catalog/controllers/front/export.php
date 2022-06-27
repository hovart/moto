<?php
/**
 * Catalog in CSV format module
 *
 * @category  Prestashop
 * @category  Module
 * @author    Samdha <contact@samdha.net>
 * @copyright Samdha
 * @license   commercial license see license.txt
 */

class Export_catalogExportModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');

        $this->context = Context::getContext();
        $this->context->controller = $this;

        Tools::setCookieLanguage($this->context->cookie);

        $protocol_link = (Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode()) ? 'https://' : 'http://';
        if (Tools::usingSecureMode()) {
            $use_ssl = true;
        } else {
            $use_ssl = isset($this->ssl) && $this->ssl && Configuration::get('PS_SSL_ENABLED');
        }
        $protocol_content = ($use_ssl) ? 'https://' : 'http://';
        $link = new Link($protocol_link, $protocol_content);
        $this->context->link = $link;

        $cart = new Cart();
        $cart->id_lang = (int)Tools::getValue('id_lang');
        $cart->id_currency = (int)Tools::getValue('id_currency');
        $cart->id_guest = (int)$this->context->cookie->id_guest;
        $cart->id_shop_group = (int)$this->context->shop->id_shop_group;
        $cart->id_shop = $this->context->shop->id;
        $cart->id_address_delivery = 0;
        $cart->id_address_invoice = 0;

        // Needed if the merchant want to give a free product to every visitors
        $this->context->cart = $cart;

        $module = Module::getInstanceByName(Tools::getValue('module'));
        if ($module && $module->active) {
            $module->getContent();
        }
        die();
    }
}

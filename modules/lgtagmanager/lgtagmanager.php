<?php
/**
*  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
*
* @author    Línea Gráfica E.C.E. S.L.
* @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
* @license   https://www.lineagrafica.es/licenses/license_en.pdf https://www.lineagrafica.es/licenses/license_es.pdf https://www.lineagrafica.es/licenses/license_fr.pdf
*/

if (!defined('_PS_VERSION_'))
	exit;

class LGTagManager extends Module
{
	public function __construct()
	{
		$this->name = 'lgtagmanager';
		$this->tab = 'analytics_stats';
		$this->version = '1.0.1';
		$this->author = 'Línea Gráfica';
		$this->displayName = $this->l('Google Tag Manager');
		$this->module_key = 'e3b1114e44a067f8637853ff20ade838';

		parent::__construct();
		$this->bootstrap = true;

		if ($this->id && !Configuration::get('TAG_MANAGER_ID'))
			$this->warning = $this->l('You have not set your Tag Manager ID yet');

		$this->description = $this->l('Integrate Google Tag Manager script into your shop.');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');

		/** Backward compatibility */
		require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
	}

	public function install()
	{
		return (parent::install() && $this->registerHook('header') && $this->registerHook('orderConfirmation') && $this->registerHook('top') && $this->registerHook('displayAfterBody'));
	}

	private function getP()
	{
		$default_lang = $this->context->language->id;
		$lang = Language::getIsoById($default_lang);
		$pl = array('es','fr');
		if (!in_array($lang, $pl))
			$lang = 'en';

		$this->context->controller->addCSS(_MODULE_DIR_.$this->name.'/views/css/publi/style.css');
		$base = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')  ? 'https://'.$this->context->shop->domain_ssl : 'http://'.$this->context->shop->domain);
		if (version_compare(_PS_VERSION_, '1.5.0', '>'))
			$uri = $base.$this->context->shop->getBaseURI();
		else
			$uri = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')  ? 'https://'._PS_SHOP_DOMAIN_SSL_DOMAIN_:'http://'._PS_SHOP_DOMAIN_).__PS_BASE_URI__;

		$path = _PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'publi'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.'index.php';
		$object = Tools::file_get_contents($path);
		$object = str_replace('src="/modules/', 'src="'.$uri.'modules/', $object);

		return $object;
	}

	public function getContent()
	{
		$output = '<h2>'.$this->l('Google Tag Manager').'</h2><br>';
		if (Tools::isSubmit('submitTagManager'))
		{
			Configuration::updateValue('TAG_MANAGER_ID', Tools::getValue('TAG_MANAGER_ID'));
			$output .= '
			<div class="conf confirm alert alert-sucess">
				<img src="../img/admin/ok.gif" alt="" title="" />
				'.$this->l('Settings updated').'
			<br></div>';
		}
		return $this->getP().$output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset class="panel">
				<label>'.$this->l('Your tag manager ID').'</label>
				<div class="margin-form">
					<input type="text" name="TAG_MANAGER_ID" value="'.Tools::safeOutput(Tools::getValue('TAG_MANAGER_ID', Configuration::get('TAG_MANAGER_ID'))).'" />
					<p class="clear">'.$this->l('Example:').' GTM-XXXXXX</p>
				</div>
				<input type="submit" name="submitTagManager" value="'.$this->l('Update ID').'" class="button btn btn-default" />
			</fieldset>
		</form>
		';
		$output .= '	<fieldset class="panel" style="margin-top:15px;">
				<label>'.$this->l('Installation instructions:').'</label>
				<p>'.$this->l('Go to your FTP, enter the folders "/themes/your_theme/", edit the file "header.tpl" and add the following code immediately after the "body" tag: ').' <span style="font-weight:bold;">{hook h =\'displayAfterBody\'}</span></p>
				</fieldset>
		
		';

		return $output;
	}

	public function hookHeader($params)
	{
		if ((method_exists('Language', 'isMultiLanguageActivated') && Language::isMultiLanguageActivated()) || Language::countActiveLanguages() > 1)
			$multilang = (string)Tools::getValue('isolang').'/';
		else
			$multilang = '';

		$defaultMetaOrder = Meta::getMetaByPage('order', $this->context->language->id);
		if (strpos($_SERVER['REQUEST_URI'], __PS_BASE_URI__.'order.php') === 0 || strpos($_SERVER['REQUEST_URI'], __PS_BASE_URI__.$multilang.$defaultMetaOrder['url_rewrite']) === 0)
			$this->context->smarty->assign('pageTrack', '/order/step'.(int)Tools::getValue('step').'.html');

		$this->context->smarty->assign('TAG_MANAGER_ID', Configuration::get('TAG_MANAGER_ID'));
		$this->context->smarty->assign('isOrder', false);

		return $this->display(__FILE__, 'views/templates/front/top-page.tpl');
	}

	public function hookFooter($params)
	{
		// for retrocompatibility
		if (!$this->isRegisteredInHook('header'))
			$this->registerHook('header');

		return $this->hookHeader($params);
	}

	public function hookOrderConfirmation($params)
	{
		// Setting parameters
		$parameters = Configuration::getMultiple(array('PS_LANG_DEFAULT'));

		$order = $params['objOrder'];
		if (Validate::isLoadedObject($order))
		{
			$deliveryAddress = new Address((int)$order->id_address_delivery);

			$conversion_rate = 1;
			if ($order->id_currency != Configuration::get('PS_CURRENCY_DEFAULT'))
			{
				$currency = new Currency((int)$order->id_currency);
				$conversion_rate = (float)$currency->conversion_rate;
			}

			// Order general information
			$trans = array(
				'id' => (int)$order->id,                // order ID - required
				'store' => htmlentities(Configuration::get('PS_SHOP_NAME')), // affiliation or store name
				'total' => Tools::ps_round((float)$order->total_paid / (float)$conversion_rate, 2),        // total - required
				'tax' => '0', // tax
				'shipping' => Tools::ps_round((float)$order->total_shipping / (float)$conversion_rate, 2),    // shipping
				'city' => addslashes($deliveryAddress->city),        // city
				'state' => '',                // state or province
				'country' => addslashes($deliveryAddress->country) // country
			);

			// Product information
			$products = $order->getProducts();
			$items = array();
			foreach ($products as $product)
			{
				$category = Db::getInstance()->getRow(
					'
								SELECT name FROM `'._DB_PREFIX_.'category_lang` , '._DB_PREFIX_.'product 
								WHERE `id_product` = '.(int)$product['product_id'].' AND `id_category_default` = `id_category`
								AND `id_lang` = '.(int)$parameters['PS_LANG_DEFAULT']);

				$items[] = array(
					'OrderId' => (int)$order->id,                                // order ID - required
					'SKU' => addslashes($product['product_id']),        // SKU/code - required
					'Product' => addslashes($product['product_name']),        // product name
					'Category' => addslashes($category['name']),            // category or variation
					'Price' => Tools::ps_round((float)$product['product_price_wt'] / (float)$conversion_rate, 2),    // unit price - required
					'Quantity' => addslashes((float)$product['product_quantity'])    //quantity - required
				);
			}

			$TAG_MANAGER_ID = Configuration::get('TAG_MANAGER_ID');

			$this->context->smarty->assign('items', $items);
			$this->context->smarty->assign('trans', $trans);
			$this->context->smarty->assign('TAG_MANAGER_ID', $TAG_MANAGER_ID);
			$this->context->smarty->assign('isOrder', true);

			return $this->display(__FILE__, '/views/templates/front/top-page.tpl');
		}
	}

	public function hookDisplayAfterBody($params)
	{
		$TAG_MANAGER_ID = Configuration::get('TAG_MANAGER_ID');
		$this->context->smarty->assign('TAG_MANAGER_ID', $TAG_MANAGER_ID);

		return $this->display(__FILE__, '/views/templates/front/after-body.tpl');
	}
}

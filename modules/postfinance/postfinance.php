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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class PostFinance extends PaymentModule
{
	protected $js_path = null;
	protected $css_path = null;
	protected $fonts_path = null;
	protected static $lang_cache;

	public function __construct()
	{
		$this->name = 'postfinance';
		$this->tab = 'payments_gateways';
		$this->version = '1.0.3';
		$this->author = 'Prestashop';
		$this->need_instance = 1;
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';
		parent::__construct();

		/**
		 * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
		 */
		$this->bootstrap = true;

		$this->displayName = $this->l('PostFinance');
		$this->description = $this->l('This module allows you to accept payments made via the credit card gateway of PostFinance.');
		$this->js_path = $this->_path.'views/js/';
		$this->css_path = $this->_path.'views/css/';
		$this->fonts_path = $this->_path.'views/fonts/';
		if (version_compare(_PS_VERSION_, '1.6', '<'))
			$this->getLang();
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
		$this->ps_url = Tools::getCurrentUrlProtocolPrefix().htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__;
		$this->payment_link = 'https://e-payment.postfinance.ch/ncol/prod/orderstandard.asp';
		$this->payment_link_test = 'https://e-payment.postfinance.ch/ncol/test/orderstandard.asp';
		$this->module_key = '1ab8aa4d5292d8319dc431e48a8d06b5';
	}

	public function install()
	{
		$module_token = $this->generateToken();
		Configuration::updateValue($this->name.'_TOKEN', $module_token);
		Configuration::updateValue($this->name.'_LIVE_MODE', false);
		Configuration::updateValue($this->name.'_CUSTOM_PAGE', false);
		Configuration::updateValue($this->name.'_BGCOLOR', '#F0F000');
		Configuration::updateValue($this->name.'_TXTCOLOR', '#000000');
		Configuration::updateValue($this->name.'_TBLBGCOLOR', '#FFFFFF');
		Configuration::updateValue($this->name.'_TBLTXTCOLOR', '#000000');
		Configuration::updateValue($this->name.'_BUTTONBGCOLOR', '#FFFFFF');
		Configuration::updateValue($this->name.'_BUTTONTXTCOLOR', '#000000');
		Configuration::updateValue($this->name.'_LOGOLINK', $this->ps_url.'img/'.Configuration::get('PS_LOGO'));
		Configuration::updateValue($this->name.'_LOGOCHECKBOX', 0);
		Configuration::updateValue($this->name.'_LOGO', 1);

		return parent::install() &&
			$this->registerHook('header') &&
			$this->registerHook('backOfficeHeader') &&
			$this->registerHook('payment') &&
			$this->registerHook('orderConfirmation');
	}

	public function uninstall()
	{
		Configuration::deleteByName($this->name.'_LIVE_MODE');
		Configuration::deleteByName($this->name.'_ID_MERCHANT');
		Configuration::deleteByName($this->name.'_SHA_IN');
		Configuration::deleteByName($this->name.'_SHA_OUT');
		Configuration::deleteByName($this->name.'_CUSTOM_PAGE');
		Configuration::deleteByName($this->name.'_BGCOLOR');
		Configuration::deleteByName($this->name.'_TXTCOLOR');
		Configuration::deleteByName($this->name.'_TBLBGCOLOR');
		Configuration::deleteByName($this->name.'_TBLTXTCOLOR');
		Configuration::deleteByName($this->name.'_BUTTONBGCOLOR');
		Configuration::deleteByName($this->name.'_BUTTONTXTCOLOR');
		Configuration::deleteByName($this->name.'_LOGO');
		Configuration::deleteByName($this->name.'_LOGOLINK');
		Configuration::deleteByName($this->name.'_LOGOCHECKBOX');
		return parent::uninstall();
	}

	/**
	 * Load the configuration form
	 */
	public function getContent()
	{
		$this->loadAsset();
		$submit = Tools::isSubmit('check_submit_pf');

		$alert_config = '';
		if ($submit == 'yes')	/* Configuration */
		{
			Configuration::updateValue($this->name.'_LIVE_MODE', (int)Tools::getValue('live_mode'));
			Configuration::updateValue($this->name.'_ID_MERCHANT', pSQL(Tools::getValue('id_merchant')));
			Configuration::updateValue($this->name.'_SHA_IN', pSQL(Tools::getValue('sha_in_phrase')));
			Configuration::updateValue($this->name.'_SHA_OUT', pSQL(Tools::getValue('sha_out_phrase')));
			Configuration::updateValue($this->name.'_CUSTOM_PAGE', pSQL(Tools::getValue('payment_page_custom')));
			Configuration::updateValue($this->name.'_BGCOLOR', pSQL(Tools::getValue('pf_background_color')));
			Configuration::updateValue($this->name.'_TITLE', pSQL(Tools::getValue('pf_title')));
			Configuration::updateValue($this->name.'_TXTCOLOR', pSQL(Tools::getValue('pf_txt_color')));
			Configuration::updateValue($this->name.'_TBLBGCOLOR', pSQL(Tools::getValue('pf_tbl_bg_color')));
			Configuration::updateValue($this->name.'_TBLTXTCOLOR', pSQL(Tools::getValue('pf_tbl_txt_color')));
			Configuration::updateValue($this->name.'_BUTTONBGCOLOR', pSQL(Tools::getValue('pf_button_bg_color')));
			Configuration::updateValue($this->name.'_BUTTONTXTCOLOR', pSQL(Tools::getValue('pf_button_txt_color')));
			Configuration::updateValue($this->name.'_LOGOLINK', pSQL(Tools::getValue('pf_logo_link')));
			Configuration::updateValue($this->name.'_LOGO', (int)Tools::getValue('pf_logo_img'));
			$use_logo = Tools::getValue('pf_logo_checkbox');
			if ($use_logo == 'on')
				Configuration::updateValue($this->name.'_LOGOCHECKBOX', 1);
			else
				Configuration::updateValue($this->name.'_LOGOCHECKBOX', 0);

			$alert_config = $this->displayConfirmation($this->l('Configuration updated'));
		}
		$keys = array(
			$this->name.'_LIVE_MODE',
			$this->name.'_ID_MERCHANT',
			$this->name.'_SHA_IN',
			$this->name.'_SHA_OUT',
			$this->name.'_CUSTOM_PAGE',
			$this->name.'_TITLE',
			$this->name.'_BGCOLOR',
			$this->name.'_TXTCOLOR',
			$this->name.'_TBLBGCOLOR',
			$this->name.'_TBLTXTCOLOR',
			$this->name.'_BUTTONBGCOLOR',
			$this->name.'_BUTTONTXTCOLOR',
			$this->name.'_LOGOLINK',
			$this->name.'_LOGOCHECKBOX',
			$this->name.'_LOGO'
			);
		$config = Configuration::getMultiple($keys);

		$browser = $this->detectBrowser(); /* Colorpicker isn't fully functional with IE */

		$this->context->smarty->assign(array(
			'is_submit' => $submit,
			'alert_config' => $alert_config,
			'browser' => $browser,
			'config' => $config,
			'shop_logo' => $this->ps_url.'img/'.Configuration::get('PS_LOGO')
			));

		return $this->display(__FILE__, 'views/templates/admin/configuration.tpl');
	}

	public function hookPayment()
	{
		$this->loadFrontAsset();
		$context = Context::getContext();
		$live_mode = (int)Configuration::get($this->name.'_LIVE_MODE');

		/* Get required payment interface link */

		if ($live_mode === 0)
			$link = $this->payment_link_test;
		else
			$link = $this->payment_link;

		$id_logo = (int)Configuration::get($this->name.'_LOGO');

		$context->smarty->assign(array(
				'ps_version' => (bool)version_compare(_PS_VERSION_, '1.6', '>'),
				$this->name.'_link' => $link,
				'payment_text' => $this->l('Pay with').' '.$this->displayName,
				'payment_logo' => trim($this->ps_url.'modules/'.$this->name.'/views/img/payment_logo'.$id_logo.'.png'),
				'params' => $this->getParams()
		));

		return $this->display(__FILE__, 'views/templates/front/hookpayment.tpl');
	}

	public function hookOrderConfirmation($params)
	{
		if ($params['objOrder']->module != $this->name)
			return;

		if ($params['objOrder']->valid ||
			$params['objOrder']->current_state == (int)_PS_OS_PAYMENT_ ||
			$params['objOrder']->current_state == (int)_PS_OS_OUTOFSTOCK_)
			$this->context->smarty->assign(array('status' => 'ok', 'id_order' => $params['objOrder']->id));
		else
		{
			$this->context->smarty->assign('status', 'failed');
			$this->context->smarty->assign('contact_form', 'index.php?controller=contact');
		}

		return $this->display(__FILE__, 'views/templates/front/hookorderconfirmation.tpl');
	}

	/**
	 * Add the CSS & JavaScript files you want to be added on the FO.
	 */
	public function hookHeader()
	{
		$this->context->controller->addJS($this->js_path.'front.js');
		$this->context->controller->addCSS($this->css_path.'front.css');
	}

	/*
	* Required parameters are accumulated to send to the payment page
	*/
	public function getParams()
	{
		$context = Context::getContext();
		$cart = new Cart((int)$context->cart->id);
		$customer = new Customer((int)$cart->id_customer);

		$url = $this->getUrls($cart->id);
		$sha_in = pSQL(Configuration::get($this->name.'_SHA_IN'));
		$invoice_address = new Address((int)$cart->id_address_invoice);
		$invoice_country = new Country((int)$invoice_address->id_country, (int)$context->cart->id_lang);

		$params = array();
		$params['PSPID'] = pSQL(Configuration::get($this->name.'_ID_MERCHANT'));
		$params['ORDERID'] = 'id_cart:'.(int)$cart->id.'_('.date('Y-m-d-H:i:s').')';
		$params['AMOUNT'] = $cart->getOrderTotal() * 100;
		$params['CURRENCY'] = $context->currency->iso_code;
		$params['LANGUAGE'] = $context->language->language_code;
		$params['CN'] = $this->toAscii($customer->firstname).' '.$this->toAscii($customer->lastname);
		$params['EMAIL'] = $customer->email;
		$params['OWNERZIP'] = $invoice_address->postcode;
		$params['OWNERADDRESS'] = $this->toAscii($invoice_address->address1).' '.$this->toAscii($invoice_address->address2);
		$params['OWNERTOWN'] = $this->toAscii($invoice_address->city);
		$params['OWNERCTY'] = $invoice_country->iso_code;
		$params['COM'] = $context->shop->name;
		$params['ACCEPTURL'] = $url['validation'];
		$params['DECLINEURL'] = $url['validation'];
		$params['EXCEPTIONURL'] = $url['validation'];
		$params['BACKURL'] = $url['cancel'];
		$params['CANCELURL'] = $url['cancel'];

		/* Get parameters to customise payment page layout */
		$custom_page = (int)Configuration::get($this->name.'_CUSTOM_PAGE');
		if ($custom_page)
		{
			$keys = array(
					$this->name.'_TITLE',
					$this->name.'_BGCOLOR',
					$this->name.'_TXTCOLOR',
					$this->name.'_TBLBGCOLOR',
					$this->name.'_TBLTXTCOLOR',
					$this->name.'_BUTTONBGCOLOR',
					$this->name.'_BUTTONTXTCOLOR'
				);
			$config = Configuration::getMultiple($keys);
			$params['TITLE'] = $config[$this->name.'_TITLE'];
			$params['BGCOLOR'] = $config[$this->name.'_BGCOLOR'];
			$params['TXTCOLOR'] = $config[$this->name.'_TXTCOLOR'];
			$params['TBLBGCOLOR'] = $config[$this->name.'_TBLBGCOLOR'];
			$params['TBLTXTCOLOR'] = $config[$this->name.'_TBLTXTCOLOR'];
			$params['BUTTONBGCOLOR'] = $config[$this->name.'_BUTTONBGCOLOR'];
			$params['BUTTONTXTCOLOR'] = $config[$this->name.'_BUTTONTXTCOLOR'];
			if (Configuration::get($this->name.'_LOGOCHECKBOX'))
				$params['LOGO'] = Configuration::get($this->name.'_LOGOLINK');
		}
		ksort($params); /* Params must be in alphabetical order */
		$str_to_crypt = '';
		foreach ($params as $key => $v)
			$str_to_crypt .= $key.'='.$v.$sha_in;

		$params['SHASign'] = sha1($str_to_crypt);

		return $params;
	}

	/*
	* All return URLs are returned in one array
	*/
	public function getUrls($id_cart)
	{
		$context = Context::getContext();

		$url = array();
		/* return urls */
		$url['ok'] = $this->ps_url.'index.php?controller=order-confirmation&id_cart='.(int)$id_cart.'&id_module='.pSQL($this->id).'&key='.pSQL($context->customer->secure_key);
		$url['cancel'] = $this->ps_url.'index.php?controller=order&step=3&';
		$url['validation'] = $this->ps_url.'modules/'.$this->name.'/validation.php';
		return $url;
	}

	/*
	* Colorpicker isn't fully functional with IE
	* so this function alerts if the merchant is using IE and extra info is provided
	*/
	public function detectBrowser()
	{
		$browser = 'Other';
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			$browser = 'IE';
		return $browser;
	}

	public function getReturnValues()
	{
		$keys = array('orderID', 'currency', 'amount', 'PM', 'ACCEPTANCE', 'STATUS',
					 'CARDNO', 'ED', 'CN', 'TRXDATE', 'PAYID', 'NCERROR', 'NCERRORPLUS',
					  'BRAND', 'IP', 'BIN', 'CCCTY', 'SHASIGN');
		$values = array();

		foreach ($keys as $key)
		{
			if ($key =='NCERROR' || Tools::getValue($key))
				$values[Tools::strtoupper($key)] = pSQL(Tools::getValue($key));
		}

		$sha_sign_incoming = '';
		if (isset($values['SHASIGN']))
		{
			$sha_sign_incoming = $values['SHASIGN'];
			unset($values['SHASIGN']);
		}
		if (isset($values['CONTROLLER']))
			unset($values['CONTROLLER']);
		foreach ($values as $k => $v)	/*Values can be returned in POST/GET and be empty but these must be removed from the str_to_crypt */
			if (empty($v) && $v != '0')
				unset($values[$k]);

		ksort($values); /* Params must be in alphabetical order */

		$str_to_crypt = '';
		$sha_out = pSQL(Configuration::get($this->name.'_SHA_OUT'));
		foreach ($values as $key => $v)
			$str_to_crypt .= $key.'='.$v.$sha_out;

		$sha_sign = sha1($str_to_crypt);

		if (Tools::strtoupper($sha_sign) != Tools::strtoupper($sha_sign_incoming))
			die('Hack attempt');
		return $values;
	}

	public function validate($id_cart, $total_paid, $id_transaction, $statut, $order_msg)
	{
		$cart = new Cart((int)$id_cart);
		/* If order already exists with this id_cart, update the order status, message & history */
		//$this->checkForExistingOrder($id_cart, $statut, $order_msg, $total_paid, $cart);

		if (!$cart->id)
		{
			Logger::addLog('Cart not valid', 4);
			die('Cart not valid');
		}
		$customer = new Customer((int)$cart->id_customer);

		$this->validateOrder($id_cart,
							$statut,
							$total_paid,
							$this->displayName,
							$order_msg,
							array('transaction_id' => $id_transaction),
							null,
							false,
							$customer->secure_key
							);
	}

	public function checkForExistingOrder($id_cart, $statut, $order_message, $total_paid, $cart)
	{
		$id_order = (int)Order::getOrderByCartId($id_cart);
		if ($id_order)
		{
			if ($statut == _PS_OS_PAYMENT_)
			{
				$order = new Order($id_order);
				$order->total_paid_real = $total_paid;
				$order->update();

				if ($order->getCurrentState() != _PS_OS_PAYMENT_)
				{
					$products = $cart->getProducts();
					foreach ($products as $product)
					{
						Product::updateQuantity($product);
						Hook::updateQuantity($product, $order);
					}
				}

				$history = new OrderHistory();
				$history->id_order = $id_order;
				$history->changeIdOrderState((int)_PS_OS_PAYMENT_, $id_order);
				$history->addWithemail(true, array());

				if (isset($order_message) && !empty($order_message))
				{
					$msg = new Message();
					if (Validate::isCleanHtml($order_message))
					{
						$msg->message = $order_message;
						$msg->id_order = (int)$order->id;
						$msg->private = 1;
						$msg->add();
					}
				}
			}
		}
	}

	/* Takes response code received and compares it against possible errors */
	public function checkForError($response_code)
	{
		$response_msg = '';
		/* Return error message depending on response code in the return values*/
		switch ($response_code)
		{
			case '0':
					$response_msg .= $this->l('Incomplete or invalid');
					break;
			case '1':
					$response_msg .= $this->l('Cancelled by client');
					break;
			case '2':
					$response_msg .= $this->l('Authorisation refused');
					break;
			case '4':
					$response_msg .= $this->l('Order stored');
					break;
			case '41':
					$response_msg .= $this->l('Waiting client payment');
					break;
			case '5':
					$response_msg .= $this->l('Incomplete or invalid');
					break;
			case '51':
					$response_msg .= $this->l('Authorisation waiting');
					break;
			case '52':
					$response_msg .= $this->l('Authorisation not known');
					break;
			case '59':
					$response_msg .= $this->l('Author. to get manually');
					break;
			case '6':
					$response_msg .= $this->l('Authorised and cancelled');
					break;
			case '7':
					$response_msg .= $this->l('Payment deleted');
					break;
			case '8':
					$response_msg .= $this->l('Refund');
					break;
			case '0':
					$response_msg .= $this->l('Incomplete or invalid');
					break;
			default:
					$response_msg .= $this->l('Successful Payment');
		}
		return $response_msg;
	}

	public function toAscii($str)
	{
		$str = str_replace('ä', 'a', $str);
		$str = str_replace('ü', 'u', $str);
		$str = str_replace('ö', 'o', $str);
		$str = str_replace('ß', 'ss', $str);
		return $str;
	}

	public function generateToken()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for ($i = 0; $i < 20; $i++)
			$randstring .= $characters[rand(0, Tools::strlen($characters))];
		return $randstring;
	}

	/* Front-end info is called */
	public function loadFrontAsset()
	{
		$this->context->smarty->assign(array(
				'module_path' => $this->_path,
				'module_name' => $this->name,
				'ps_version' => (bool)version_compare(_PS_VERSION_, '1.6', '>'),
		));
	}

	/**
	* Loads asset resources
	*/
	public function loadAsset()
	{
		$css_compatibility = $js_compatibility = array();
		/* Load CSS */
		$css = array(
			$this->css_path.'bootstrap-select.min.css',
			$this->css_path.'bootstrap-dialog.min.css',
			$this->css_path.'bootstrap.vertical-tabs.min.css',
			$this->css_path.'DT_bootstrap.css',
			$this->css_path.'tooltipster.css',
			$this->css_path.'back.css'
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$css_compatibility = array(
				$this->css_path.'bootstrap.min.css',
				$this->css_path.'bootstrap.extend.css',
				$this->css_path.'bootstrap-responsive.min.css',
				$this->fonts_path.'font-awesome.min.css',
			);
			$css = array_merge($css_compatibility, $css);
		}
		$this->context->controller->addCSS($css, 'all');

		/* Load JS */
		$js = array(
			$this->js_path.'bootstrap-select.min.js',
			$this->js_path.'bootstrap-dialog.js',
			$this->js_path.'jquery.autosize.min.js',
			$this->js_path.'jquery.dataTables.js',
			$this->js_path.'DT_bootstrap.js',
			$this->js_path.'dynamic_table_init.js',
			$this->js_path.'jquery.tooltipster.min.js',
			$this->js_path.'back.js'
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$js_compatibility = array(
				$this->js_path.'bootstrap.min.js'
			);
			$js = array_merge($js_compatibility, $js);
		}
		$this->context->controller->addJS($js);

		/* Clean memory */
		unset($js, $css, $js_compatibility, $css_compatibility);

		$request_uri = $_SERVER['REQUEST_URI'];

		$this->context->smarty->assign(array(
			'module_dir' => $this->_path,
			'module_name' => $this->name,
			'module_path' => $this->_path,
			'module_display' => $this->displayName,
			'module_version' => $this->version,
			'pf_doc_path' => $this->getDocUrl('module', $this->context->language->iso_code),
			'pf_config_doc_path' => $this->getDocUrl('config', $this->context->language->iso_code),
			'pf_img_path' => $this->ps_url.'modules/'.$this->name.'/views/img/',
			'requestUri' => $request_uri,
			'ps_version' => (bool)version_compare(_PS_VERSION_, '1.6', '>')
		));
	}

	public function getDocUrl($type, $iso_code)
	{
		if ($iso_code == 'de' || $iso_code == 'dh')
			return $this->ps_url.'modules/'.$this->name.'/docs/postfinance_'.$type.'_de.pdf';
		else if ($iso_code == 'fr' && $type == 'config')
			return $this->ps_url.'modules/'.$this->name.'/docs/postfinance_'.$type.'_fr.pdf';
		else if ($iso_code == 'it')
			return $this->ps_url.'modules/'.$this->name.'/docs/postfinance_'.$type.'_it.pdf';
		else
			return $this->ps_url.'modules/'.$this->name.'/docs/postfinance_'.$type.'_en.pdf';
	}

	private function getLang()
	{
		if (self::$lang_cache == null && !is_array(self::$lang_cache))
		{
			self::$lang_cache = array();
			if ($languages = Language::getLanguages())
			{
				foreach ($languages as $row)
				{
						$exprow = explode(' (', $row['name']);
						$subtitle = (isset($exprow[1]) ? trim(Tools::substr($exprow[1], 0, -1)) : '');
						self::$lang_cache[$row['iso_code']] = array (
								'title' => trim($exprow[0]),
								'subtitle' => $subtitle
						);
				}
				/* Clean memory */
				unset($row, $exprow, $subtitle, $languages);
			}
		}
	}
}

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
require_once dirname(__FILE__).'/classes/FlashSale.php';
class FlashSalePro extends Module
{
	protected $js_path = null;
	protected $css_path = null;
	protected static $lang_cache;
	/** @var string Module css path (eg. '/shop/modules/module_name/css/') */
	protected $sql_path = null;

	/** SQL files */
	const INSTALL_SQL_FILE = 'install.sql';
	const UNINSTALL_SQL_FILE = 'uninstall.sql';

	public function __construct()
	{
		$this->name = 'flashsalepro';
		$this->tab = 'advertising_marketing';
		$this->version = '3.2.2';
		$this->author = 'Prestashop';
		$this->need_instance = 1;
		parent::__construct();

		$this->bootstrap = true;

		$this->displayName = $this->l('FlashSalePro');
		$this->description = $this->l('Boost the sales of your online shop through PrestaShop Flash Sales module!');
		$this->js_path = $this->_path.'views/js/';
		$this->css_path = $this->_path.'views/css/';
		$this->sql_path = dirname(__FILE__).'/sql/';
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		$this->ps_url = Tools::getCurrentUrlProtocolPrefix().htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__;
		$this->module_key = '4940c07e4d6ff3313d22ffda1040baef';
		$this->ps17 = version_compare(_PS_VERSION_, '1.7.0.0', '>=');
	}

	public function install()
	{
		if (Shop::isFeatureActive())
			Shop::setContext(Shop::CONTEXT_ALL);

		$token_flash_sale = $this->generateToken();
		Configuration::updateValue('FLASHSALEPRO_TOKEN', $token_flash_sale);
		Configuration::updateValue('FLASHSALEPRO_LIVE_MODE', false);
		Configuration::updateValue('FLASHSALEPRO_CORNER_BANNER', 1);
		Configuration::updateValue('FLASHSALEPRO_CORNER_BANNER_TEXT', 'Flash Sale !!!');
		Configuration::updateValue('FLASHSALEPRO_BANNER_TEXT_COLOR', '#000000');
		Configuration::updateValue('FLASHSALEPRO_BANNER_BGCOLOR', '#ffff00');
		Configuration::updateValue('FLASHSALEPRO_TIMER_BG_COLOR', '#333333');
		Configuration::updateValue('FLASHSALEPRO_TIMER_TEXT_COLOR', '#cccccc');
		Configuration::updateValue('FLASHSALEPRO_TIMER_DOT_COLOR', '#333333');
		Configuration::updateValue('FLASHSALEPRO_CORNER_BANNER_FONT', 'Arial');
		Configuration::updateValue('FLASHSALEPRO_LEFT_COLUMN', 1);
		Configuration::updateValue('FLASHSALEPRO_RIGHT_COLUMN', 0);
		Configuration::updateValue('FLASHSALEPRO_PRODUCT_PAGE_RIGHT', 1);
		Configuration::updateValue('FLASHSALEPRO_PRODUCT_PAGE_FOOTER', 1);
		Configuration::updateValue('FLASHSALEPRO_INCLUDE_TAx', 1);
		Configuration::updateValue('FLASHSALEPRO_INCLUDE_GRID_CSS', 0);

		$this->createSpareGroup();
		return parent::install() &&
			$this->registerHook('header') &&
			$this->registerHook('footer') &&
			$this->registerHook('backOfficeHeader') &&
			$this->registerHook('ExtraRight') &&
			$this->registerHook('ExtraLeft') &&
			$this->registerHook('productFooter') &&
			$this->registerHook('displayProductButtons') &&
			$this->registerHook('leftColumn') &&
			$this->registerHook('rightColumn') &&
			$this->registerHook('home') &&
			$this->registerHook('displayTopColumn') &&
			$this->installSQL() &&
			$this->installTab();
	}

	/**
	* Install Tab
	* @return boolean
	*/
	private function installTab()
	{
		$tab = new Tab();
		$tab->id_parent = -1;
		$tab->class_name = 'Admin'.get_class($this);
		$tab->module = $this->name;
		$tab->active = 1;
		$tab->name = array();
		foreach (Language::getLanguages(true) as $lang)
			$tab->name[$lang['id_lang']] = $this->displayName;
		unset($lang);
		return $tab->add();
	}

	/**
	* Install SQL
	* @return boolean
	*/
	private function installSQL()
	{
		// Create database tables from install.sql
		if (!file_exists($this->sql_path.self::INSTALL_SQL_FILE))
			return false;

		if (!$sql = Tools::file_get_contents($this->sql_path.self::INSTALL_SQL_FILE))
			return false;

		$replace = array(
			'PREFIX' => _DB_PREFIX_,
			'ENGINE_DEFAULT' => _MYSQL_ENGINE_
		);
		$sql = strtr($sql, $replace);
		$sql = preg_split("/;\s*[\r\n]+/", $sql);

		foreach ($sql as &$q)
			if ($q && count($q) && !Db::getInstance()->Execute(trim($q)))
				return false;

		// Clean memory
		unset($sql, $q, $replace);

		return true;
	}

	/**
	* Uninstall SQL
	* @return boolean
	*/
	private function uninstallSQL()
	{
		// Create database tables from uninstall.sql
		if (!file_exists($this->sql_path.self::UNINSTALL_SQL_FILE))
			return false;

		if (!$sql = Tools::file_get_contents($this->sql_path.self::UNINSTALL_SQL_FILE))
			return false;

		$replace = array(
			'PREFIX' => _DB_PREFIX_,
			'ENGINE_DEFAULT' => _MYSQL_ENGINE_
		);
		$sql = strtr($sql, $replace);
		$sql = preg_split("/;\s*[\r\n]+/", $sql);

		foreach ($sql as &$q)
			if ($q && count($q) && !Db::getInstance()->Execute(trim($q)))
				return false;
		// Clean memory
		unset($sql, $q, $replace);

		return true;
	}

	public function uninstall()
	{
		$this->deactivateAllFlashSales();
		$this->destroyAllFlashSales();
		Configuration::deleteByName('FLASHSALEPRO_LIVE_MODE');
		Configuration::deleteByName('FLASHSALEPRO_CORNER_BANNER');
		Configuration::deleteByName('FLASHSALEPRO_CORNER_BANNER_TEXT');
		Configuration::deleteByName('FLASHSALEPRO_BANNER_TEXT_COLOR');
		Configuration::deleteByName('FLASHSALEPRO_BANNER_BGCOLOR');
		Configuration::deleteByName('FLASHSALEPRO_TIMER_BG_COLOR');
		Configuration::deleteByName('FLASHSALEPRO_TIMER_TEXT_COLOR');
		Configuration::deleteByName('FLASHSALEPRO_TIMER_DOT_COLOR');
		Configuration::deleteByName('FLASHSALEPRO_CORNER_BANNER_FONT');
		Configuration::deleteByName('FLASHSALEPRO_LEFT_COLUMN');
		Configuration::deleteByName('FLASHSALEPRO_RIGHT_COLUMN');
		Configuration::deleteByName('FLASHSALEPRO_PRODUCT_PAGE_RIGHT');
		Configuration::deleteByName('FLASHSALEPRO_PRODUCT_PAGE_FOOTER');
		Configuration::deleteByName('FLASHSALEPRO_INCLUDE_TAx');
		Configuration::deleteByName('FLASHSALEPRO_INCLUDE_GRID_CSS');
		$this->deleteSpareGroup();
		$this->uninstallTab();
		$this->uninstallSQL();
		return parent::uninstall();
	}

	/**
	* Uninstall Tab
	* @return boolean
	*/
	private function uninstallTab()
	{
		$id_tab = (int)Tab::getIdFromClassName('Admin'.get_class($this));
		if ($id_tab)
		{
			$tab = new Tab($id_tab);
			if (Validate::isLoadedObject($tab))
				return $tab->delete();
			else
				return false;
		}
		else
			return true;
	}

	public function createSpareGroup()
	{
		$group = new Group(null, Configuration::get('PS_LANG_DEFAULT'), null);
		$group->name = 'FlashSalePro Disabled';
		$group->show_prices = 0;
		$group->reduction = 0.00;
		$group->price_display_method = 0;

		$group->add();
		Configuration::updateValue('FLASHSALEPRO_DISABLED_GROUP_ID', $group->id);
	}

	public function deleteSpareGroup()
	{
		$id_group = Configuration::get('FLASHSALEPRO_DISABLED_GROUP_ID');
		$group = new Group($id_group);
		$deleted = $group->delete();
		if ($deleted)
			Configuration::deleteByName('FLASHSALEPRO_DISABLED_GROUP_ID');
	}

	/**
	 * Load the configuration form
	 */
	public function getContent()
	{
		$this->runChecks();
		$this->loadAsset();
		//$controller_name = 'Admin'.get_class($this); // SAV ticket #85490
		$controller_name = 'AdminFlashSalePro';
		$current_id_tab = (int)$this->context->controller->id;
		$controller_url = $this->context->link->getAdminLink($controller_name);

		$context = Context::getContext();
		$lang = $context->employee->id_lang;
		$alert_sale_creation = '';
		$submit_flash_sale = Tools::isSubmit('submittedFormCheck');
		$submit_flash_sale_config = Tools::isSubmit('submitFlashSaleConfig');
		$sale_created = 0;
		$is_submit = 0;

		if ($submit_flash_sale == 'check')
		{
			$is_submit = 1;
			$sale_created = $this->createSpecificPrice();
			if ((int)$sale_created === 1)
				$alert_sale_creation = $this->displayConfirmation($this->l('Flash Sale Created.'));
		}

		if ($submit_flash_sale_config)
		{
			$is_submit = 1;
			Configuration::updateValue('FLASHSALEPRO_LEFT_COLUMN', (int)Tools::getValue('flash_left_column_switch'));
			Configuration::updateValue('FLASHSALEPRO_RIGHT_COLUMN', (int)Tools::getValue('flash_right_column_switch'));
			Configuration::updateValue('FLASHSALEPRO_LEFT_CAT_COLUMN', (int)Tools::getValue('flash_category_left_column_switch'));
			Configuration::updateValue('FLASHSALEPRO_PRODUCT_PAGE_RIGHT', (int)Tools::getValue('flash_product_page_right_switch'));
			Configuration::updateValue('FLASHSALEPRO_PRODUCT_PAGE_FOOTER', (int)Tools::getValue('flash_product_page_footer_switch'));
			Configuration::updateValue('FLASHSALEPRO_CORNER_BANNER', (int)Tools::getValue('corner_banner_switch'));
			Configuration::updateValue('FLASHSALEPRO_CORNER_BANNER_TEXT', pSQL(Tools::getValue('corner_banner_text')));
			Configuration::updateValue('FLASHSALEPRO_BANNER_TEXT_COLOR', pSQL(Tools::getValue('banner_text_color')));
			Configuration::updateValue('FLASHSALEPRO_BANNER_BGCOLOR', pSQL(Tools::getValue('banner_bg_color')));
			Configuration::updateValue('FLASHSALEPRO_TIMER_BG_COLOR', pSQL(Tools::getValue('timer_bg_color')));
			Configuration::updateValue('FLASHSALEPRO_TIMER_TEXT_COLOR', pSQL(Tools::getValue('timer_text_color')));
			Configuration::updateValue('FLASHSALEPRO_TIMER_DOT_COLOR', pSQL(Tools::getValue('timer_dot_color')));
			Configuration::updateValue('FLASHSALEPRO_CORNER_BANNER_FONT', pSQL(Tools::getValue('banner_font')));
			Configuration::updateValue('FLASHSALEPRO_INCLUDE_TAx', pSQL(Tools::getValue('flash_tax_switch')));
			Configuration::updateValue('FLASHSALEPRO_INCLUDE_GRID_CSS', (int)Tools::getValue('flash_add_grid_css'));
		}

		$item_query = 'DELETE FROM `'._DB_PREFIX_.'flashsalespro_temp`'; /* Clear the table that temporarily holds information */
		Db::getInstance()->Execute($item_query);

		$flash_sale_info = $this->getFlashSaleTableInfo($lang);
		$flash_sale_count = count($flash_sale_info);
		$currencies = Currency::getCurrencies();
		$countries = Country::getCountries($lang);
		$groups = Group::getGroups($lang);

		$keys = array(
			'FLASHSALEPRO_LEFT_COLUMN',
			'FLASHSALEPRO_RIGHT_COLUMN',
			'FLASHSALEPRO_LEFT_CAT_COLUMN',
			'FLASHSALEPRO_PRODUCT_PAGE_RIGHT',
			'FLASHSALEPRO_PRODUCT_PAGE_FOOTER',
			'FLASHSALEPRO_CORNER_BANNER',
			'FLASHSALEPRO_CORNER_BANNER_TEXT',
			'FLASHSALEPRO_BANNER_TEXT_COLOR',
			'FLASHSALEPRO_BANNER_BGCOLOR',
			'FLASHSALEPRO_CORNER_BANNER_FONT',
			'FLASHSALEPRO_TIMER_BG_COLOR',
			'FLASHSALEPRO_TIMER_TEXT_COLOR',
			'FLASHSALEPRO_TIMER_DOT_COLOR',
			'FLASHSALEPRO_INCLUDE_TAx',
			'FLASHSALEPRO_INCLUDE_GRID_CSS',
			'PS_LANG_DEFAULT');
		$configs = Configuration::getMultiple($keys);

		$request_uri = $_SERVER['REQUEST_URI'];
		
		$flash_sale_names = $this->getFlashSaleNames();
		$languages = Language::getLanguages();
		$default_language = new Language((int)$configs['PS_LANG_DEFAULT']);

		$this->context->smarty->assign(array(
			'ps_url' => $this->ps_url,
			'is_submit' => $is_submit,
			'alert_sale_creation' => $alert_sale_creation,
			'sale_created_alert' => $sale_created,
			'currencies' => $currencies,
			'languages' => $languages,
			'id_lang_default' => (int)$configs['PS_LANG_DEFAULT'],
			'default_language_iso_code' => $default_language->iso_code,
			'default_currency_sign' => $context->currency->getSign(),
			'countries' => $countries,
			'groups' => $groups,
			'flash_sale_info' => $flash_sale_info,
			'flash_sale_count' => $flash_sale_count,
			'flash_sale_names' => $flash_sale_names,
			'flash_left_column_switch' => (int)$configs['FLASHSALEPRO_LEFT_COLUMN'],
			'flash_right_column_switch' => (int)$configs['FLASHSALEPRO_RIGHT_COLUMN'],
			'flash_category_left_column_switch' => (int)$configs['FLASHSALEPRO_LEFT_CAT_COLUMN'],
			'flash_product_page_right_switch' => (int)$configs['FLASHSALEPRO_PRODUCT_PAGE_RIGHT'],
			'flash_product_page_footer_switch' => (int)$configs['FLASHSALEPRO_PRODUCT_PAGE_FOOTER'],
			'corner_banner_switch' => (int)$configs['FLASHSALEPRO_CORNER_BANNER'],
			'corner_banner_text' => pSQL($configs['FLASHSALEPRO_CORNER_BANNER_TEXT']),
			'banner_text_color' => pSQL($configs['FLASHSALEPRO_BANNER_TEXT_COLOR']),
			'banner_bg_color' => pSQL($configs['FLASHSALEPRO_BANNER_BGCOLOR']),
			'banner_font' => pSQL($configs['FLASHSALEPRO_CORNER_BANNER_FONT']),
			'timer_bg_color' => pSQL($configs['FLASHSALEPRO_TIMER_BG_COLOR']),
			'timer_text_color' => pSQL($configs['FLASHSALEPRO_TIMER_TEXT_COLOR']),
			'timer_dot_color' => pSQL($configs['FLASHSALEPRO_TIMER_DOT_COLOR']),
			'flash_tax_switch' => pSQL($configs['FLASHSALEPRO_INCLUDE_TAx']),
			'flash_add_grid_css' => pSQL($configs['FLASHSALEPRO_INCLUDE_GRID_CSS']),
			'image_default' => $this->ps_url.'modules/'.$this->name.'/views/img/flash_sale_logo.png',
			'module_dir' => $this->_path,
			'requestUri' => $request_uri,
			'module_name' => $this->name,
			'module_path' => $this->_path,
			'module_display' => $this->displayName,
			'current_id_tab' => $current_id_tab,
			'controller_url' => $controller_url,
			'controller_name' => $controller_name,
			'lang' => $lang,
			'module_version' => $this->version,
			'future_timestamp' => strtotime('+5 hours'),
			));

		return $this->display(__FILE__, 'views/templates/admin/configuration.tpl');
	}

	public function hookdisplayTop()
	{
		//return $this->displayLarge();
	}

	public function hookdisplayTopColumn()
	{
		$flash_category_left_column_switch = (int)Configuration::get('FLASHSALEPRO_LEFT_CAT_COLUMN');
		$controller = pSQL(Tools::getValue('controller'));
		
		if ($flash_category_left_column_switch == 1 && $controller == 'category')
			return $this->displayLarge();
	}

	public function hookHome()
	{
		$controller = pSQL(Tools::getValue('controller'));
		if ($controller == 'index')
			return $this->displayLarge();
	}

	public function displayLarge()
	{
		$this->runChecks();
		$flash_sale_info = $this->getFlashSaleInfo();

		$flash_sale_items = '';
		if ($flash_sale_info != null)
		{
			if ($this->passRestrictions((int)$flash_sale_info['id_group_restriction'], (int)$flash_sale_info['id_country_restriction'], (int)$flash_sale_info['id_currency_restriction']))
			{
				$flash_sale_items = $this->getFlashSaleItems($flash_sale_info['id_flashsalespro']);

				if ($flash_sale_info['sale_custom_img_link'] == '')
					$image_default = $this->ps_url.'modules/'.$this->name.'/views/img/flash_sale_logo.png';
				else
					$image_default = $flash_sale_info['sale_custom_img_link'];

				$keys = array(
					'FLASHSALEPRO_CORNER_BANNER',
					'FLASHSALEPRO_CORNER_BANNER_TEXT',
					'FLASHSALEPRO_BANNER_TEXT_COLOR',
					'FLASHSALEPRO_BANNER_BGCOLOR',
					'FLASHSALEPRO_CORNER_BANNER_FONT',
					'FLASHSALEPRO_TIMER_BG_COLOR',
					'FLASHSALEPRO_TIMER_TEXT_COLOR',
					'FLASHSALEPRO_TIMER_DOT_COLOR');
				$configs = Configuration::getMultiple($keys);

				$corner_banner_switch = (int)$configs['FLASHSALEPRO_CORNER_BANNER'];
				$browser = $this->detectBrowser();
				if ($browser === 'IE')
					$corner_banner_switch = 0;

				$this->context->smarty->assign(array(
					'corner_banner_switch' => $corner_banner_switch,
					'corner_banner_text' => pSQL($configs['FLASHSALEPRO_CORNER_BANNER_TEXT']),
					'corner_banner_font' => pSQL($configs['FLASHSALEPRO_CORNER_BANNER_FONT']),
					'banner_text_color' => pSQL($configs['FLASHSALEPRO_BANNER_TEXT_COLOR']),
					'banner_bg_color' => pSQL($configs['FLASHSALEPRO_BANNER_BGCOLOR']),
					'image_default' => $image_default,
					'timer_bg_color' => pSQL($configs['FLASHSALEPRO_TIMER_BG_COLOR']),
					'timer_text_color' => pSQL($configs['FLASHSALEPRO_TIMER_TEXT_COLOR']),
					'timer_dot_color' => pSQL($configs['FLASHSALEPRO_TIMER_DOT_COLOR']),
					'flash_sale_info' => $flash_sale_info,
					'flash_sale_items' => $flash_sale_items,
					'ps_url' => $this->ps_url,
					'ps17' => $this->ps17
				));

				return $this->display(__FILE__, 'views/templates/front/home.tpl');
			}
		}
	}


	public function hookProductFooter()
	{
		$flash_product_page_footer_switch = (int)Configuration::get('FLASHSALEPRO_PRODUCT_PAGE_FOOTER');
		if ($flash_product_page_footer_switch === 1)
			return $this->displayLarge();
	}

	public function hookExtraRight()
	{
		$content_only = (int)Tools::getValue('content_only');
		if ($content_only !== 1)
		{
			$flash_product_page_right_switch = (int)Configuration::get('FLASHSALEPRO_PRODUCT_PAGE_RIGHT');
			if ($flash_product_page_right_switch === 1)
			{
				$this->runChecks();
				$flash_sale_info = $this->getFlashSaleInfo();
				$flash_sale_items = '';
				$image_default = $this->ps_url.'modules/'.$this->name.'/img/flash_sale_logo.png';

				if ($flash_sale_info != null)
				{
					if ($this->passRestrictions((int)$flash_sale_info['id_group_restriction'], (int)$flash_sale_info['id_country_restriction'], (int)$flash_sale_info['id_currency_restriction']))
					{
						$this_product_in_sale = 0;
						$flash_sale_items = $this->getFlashSaleItems($flash_sale_info['id_flashsalespro']);
						$id_products = array();
						foreach ($flash_sale_items as $item)
							array_push($id_products, $item['id_product']);
						$current_product_id = (int)Tools::getValue('id_product');

						if (in_array($current_product_id, $id_products))
							$this_product_in_sale = 1;
						$keys = array(
							'FLASHSALEPRO_TIMER_BG_COLOR',
							'FLASHSALEPRO_TIMER_TEXT_COLOR',
							'FLASHSALEPRO_TIMER_DOT_COLOR');
						$configs = Configuration::getMultiple($keys);

						$this->context->smarty->assign(array(
							'ps_url' => $this->ps_url,
							'flash_sale_info' => $flash_sale_info,
							'flash_sale_items' => $flash_sale_items,
							'image_default' => $image_default,
							'this_product_in_sale' => $this_product_in_sale,
							'timer_bg_color' => pSQL($configs['FLASHSALEPRO_TIMER_BG_COLOR']),
							'timer_text_color' => pSQL($configs['FLASHSALEPRO_TIMER_TEXT_COLOR']),
							'timer_dot_color' => pSQL($configs['FLASHSALEPRO_TIMER_DOT_COLOR']),
							));
						return $this->display(__FILE__, 'views/templates/front/productExtraRight.tpl');
					}
				}
			}
		}
	}

	public function hookLeftColumn()
	{
		$flash_left_column_switch = (int)Configuration::get('FLASHSALEPRO_LEFT_COLUMN');

		if ($flash_left_column_switch === 1)
			return $this->getColumnBanner();
	}

	public function hookRightColumn()
	{
		$flash_right_column_switch = (int)Configuration::get('FLASHSALEPRO_RIGHT_COLUMN');
		if ($flash_right_column_switch === 1)
			return $this->getColumnBanner();
	}

	public function getColumnBanner()
	{
		$flash_product_page_switch = (int)Configuration::get('FLASHSALEPRO_PRODUCT_PAGE_RIGHT');
		$flash_category_switch = (int)Configuration::get('FLASHSALEPRO_LEFT_CAT_COLUMN');
		$controller = pSQL(Tools::getValue('controller'));

		if (($controller != 'flashSaleProducts') && ($controller != 'category' || $flash_category_switch != 1) && ($controller != 'product' || $flash_product_page_switch != 1))
		{
			$this->runChecks();
			$flash_sale_info = $this->getFlashSaleInfo();
			$flash_sale_items = '';
			if ($flash_sale_info != null)
			{
				if ($this->passRestrictions((int)$flash_sale_info['id_group_restriction'], (int)$flash_sale_info['id_country_restriction'], (int)$flash_sale_info['id_currency_restriction']))
				{
					$flash_sale_items = $this->getFlashSaleItems($flash_sale_info['id_flashsalespro']);
					
					$disable_clock = 0;
					if ($flash_sale_info['sale_type'] == 'timed')
					{
						$id_products = array();
						foreach ($flash_sale_items as $item)
							array_push($id_products, $item['id_product']);
						$current_product_id = (int)Tools::getValue('id_product');
						if (in_array($current_product_id, $id_products))
							$disable_clock = 1;
					}

					$keys = array(
							'FLASHSALEPRO_TIMER_BG_COLOR',
							'FLASHSALEPRO_TIMER_TEXT_COLOR',
							'FLASHSALEPRO_TIMER_DOT_COLOR');
					$configs = Configuration::getMultiple($keys);

					$this->context->smarty->assign(array(
						'ps_url' => $this->ps_url,
						'flash_sale_info' => $flash_sale_info,
						'flash_sale_items' => $flash_sale_items,
						'timer_bg_color' => pSQL($configs['FLASHSALEPRO_TIMER_BG_COLOR']),
						'timer_text_color' => pSQL($configs['FLASHSALEPRO_TIMER_TEXT_COLOR']),
						'timer_dot_color' => pSQL($configs['FLASHSALEPRO_TIMER_DOT_COLOR']),
						'disable_clock' => $disable_clock
						));
					return $this->display(__FILE__, 'views/templates/front/leftColumn.tpl');
				}
			}
		}
	}

	public function hookDisplayProductButtons()
	{
		$this->runChecks();
		$flash_sale_info = $this->getFlashSaleInfo();
		$flash_sale_items = '';

		if ($flash_sale_info != null && $flash_sale_info['date_end'] !== '0000-00-00 00:00:00' && $flash_sale_info['sale_type'] === 'timed')
		{
			if ($this->passRestrictions((int)$flash_sale_info['id_group_restriction'], (int)$flash_sale_info['id_country_restriction'], (int)$flash_sale_info['id_currency_restriction']))
			{
				$flash_sale_items = $this->getFlashSaleItems($flash_sale_info['id_flashsalespro']);
				$id_products = array();
				foreach ($flash_sale_items as $item)
					array_push($id_products, $item['id_product']);
				$current_product_id = (int)Tools::getValue('id_product');
				if (in_array($current_product_id, $id_products))
				{
					$keys = array(
							'FLASHSALEPRO_TIMER_BG_COLOR',
							'FLASHSALEPRO_TIMER_TEXT_COLOR',
							'FLASHSALEPRO_TIMER_DOT_COLOR');
					$configs = Configuration::getMultiple($keys);

					$this->context->smarty->assign(array(
						'ps_url' => $this->ps_url,
						'flash_sale_info' => $flash_sale_info,
						'flash_sale_items' => $flash_sale_items,
						'timer_bg_color' => pSQL($configs['FLASHSALEPRO_TIMER_BG_COLOR']),
						'timer_text_color' => pSQL($configs['FLASHSALEPRO_TIMER_TEXT_COLOR']),
						'timer_dot_color' => pSQL($configs['FLASHSALEPRO_TIMER_DOT_COLOR']),
						));
					return $this->display(__FILE__, 'views/templates/front/minitureClock.tpl');
				}
			}
		}
	}

	public function runChecks()
	{
		$this->checkStockOfItemsInSale();
		$this->checkExpiredSales();
		$this->activateTimedSale();
	}

	public function passRestrictions($id_group_restriction, $id_country_restriction, $id_currency_restriction)
	{
		$context = Context::getContext();
		if ((int)$id_group_restriction === 0 || (int)$id_group_restriction === (int)$context->customer->id_default_group)
			if ((int)$id_country_restriction === 0 || (int)$id_country_restriction === (int)$context->country->id)
				if ((int)$id_currency_restriction === 0 || (int)$id_currency_restriction === (int)$context->customer->id_currency || $context->customer->id_currency == null || empty($context->customer->id_currency))
					return true;
		return false;
	}

	public function getFlashSaleTableInfo($id_lang)
	{
		$flash_sale_info = Db::getInstance()->ExecuteS(
			'SELECT fs.id_flashsalespro, fs.sale_type, fs.active, fs.date_start,
			 fs.date_end, fs.id_group_restriction, fs.id_currency_restriction, fs.id_country_restriction, fsn.name FROM '
			._DB_PREFIX_.'flashsalespro fs, '._DB_PREFIX_.'flashsalespro_names fsn
			 WHERE fs.id_flashsalespro = fsn.id_flashsalespro AND fsn.id_lang = '.(int)$id_lang.';');

		$flash_sale_info_plus = array();
		foreach ($flash_sale_info as $info)
		{
			if ($info['id_country_restriction'] != 0)
				$info['country_name'] = Country::getNameById($id_lang, $info['id_country_restriction']);
			else
				$info['country_name'] = $this->l('No Restriction');

			if ($info['id_currency_restriction'] != 0)
			{
				$currency = new Currency($info['id_currency_restriction']);
				$info['currency_name'] = $currency->name;
			}
			else
				$info['currency_name'] = $this->l('No Restriction');

			if ($info['id_group_restriction'] != 0)
			{
				$group = new Group($info['id_group_restriction'], $id_lang);
				$info['group_name'] = $group->name;
			}
			else
				$info['group_name'] = $this->l('No Restriction');
			array_push($flash_sale_info_plus, $info);
		}
		return $flash_sale_info_plus;
	}

	public function createSpecificPrice()
	{
		$context = Context::getContext();
		$flash_sale = new FlashSale();

		$sale_type = pSQL(Tools::getValue('flash_type'));
		if ($sale_type === 'timed')
		{
			$date_start = pSQL(Tools::getValue('flash_sale_date_from')).':00';
			$date_end = pSQL(Tools::getValue('flash_sale_date_to')).':00';
		}
		else
		{
			$date_start = '0000-00-00 00:00:00';
			$date_end = '0000-00-00 00:00:00';
		}
		$end_date_timestamp = strtotime($date_end);

		$id_group_restriction = (int)Tools::getValue('discount_group_restriction');
		if ($id_group_restriction == 0)
			$include_tax = (int)Configuration::get('FLASHSALEPRO_INCLUDE_TAx');
		else
		{
			/* In group, tax excluded is 1, tax included is 0 */
			$group = new Group($id_group_restriction);
			if ($group->price_display_method == 0)
				$include_tax = 1;
			else
				$include_tax = 0;
		}

		$id_group_disabled = (int)Configuration::get('FLASHSALEPRO_DISABLED_GROUP_ID');

		$exclude_product_list = Tools::jsonDecode(Tools::getValue('removed_products_array'));
		$extra = '';
		if (!empty($exclude_product_list) && $exclude_product_list != null)
			$extra = 'AND id_item NOT IN ('.implode(',', $exclude_product_list).')';
		$list_product = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'flashsalespro_temp` WHERE item_type = "product" '.$extra);

		foreach ($list_product as $temp)
			array_push($exclude_product_list, $temp['id_item']);
		$list_category = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'flashsalespro_temp` WHERE item_type = "category";');

		$activate = 0;

		/* Flash Sale Custom Image for Banner */
		$current_file_array = $_FILES['custom_flashsale_img'];

		if ($current_file_array['error'] == 0) /* Custom images for products */
		{
			$img_folder = realpath(dirname(__FILE__).'/views/img/');
			$img_directory = $img_folder.'/'.$_FILES['custom_flashsale_img']['name'];
			$img_directory = str_replace("\0", '', $img_directory);	/* Removing null bytes */

			if (!is_dir($img_folder) || !is_writable($img_folder) || $img_folder != realpath(dirname(__FILE__).'/views/img/'))
				exit($this->l('Error in the directory location or permissions for storing the image.'));
			$image_check = ImageManager::validateUpload($_FILES['custom_flashsale_img'], 5000000);

			if ((($_FILES['custom_flashsale_img']['type'] == 'image/gif')
				|| ($_FILES['custom_flashsale_img']['type'] == 'image/jpeg')
				|| ($_FILES['custom_flashsale_img']['type'] == 'image/jpg')
				|| ($_FILES['custom_flashsale_img']['type'] == 'image/pjpeg')
				|| ($_FILES['custom_flashsale_img']['type'] == 'image/x-png')
				|| ($_FILES['custom_flashsale_img']['type'] == 'image/png'))
				&& $image_check == null)
				move_uploaded_file($_FILES['custom_flashsale_img']['tmp_name'], $img_directory);
			else
				exit($this->l('Error with the file type you have tried to upload.'));
		}
		if (isset($_FILES['custom_flashsale_img']['name']) && !empty($_FILES['custom_flashsale_img']['name']))
			$custom_img_link = $this->ps_url.'modules/flashsalepro/views/img/'.$_FILES['custom_flashsale_img']['name'];
		else
			$custom_img_link = '';

		$flash_params = array();
		$flash_params['id_shop'] = $this->context->shop->id;
		$flash_params['id_group_restriction'] = $id_group_restriction;
		$flash_params['id_currency_restriction'] = (int)Tools::getValue('discount_currency_restriction');
		$flash_params['id_country_restriction'] = (int)Tools::getValue('discount_country_restriction');
		$flash_params['sale_type'] = $sale_type;
		$flash_params['date_start'] = $date_start;
		$flash_params['date_end'] = $date_end;
		$flash_params['end_date_timestamp'] = $end_date_timestamp;
		$flash_params['bg_color'] = pSQL(Tools::getValue('flashsale_bg_color'));
		$flash_params['text_color'] = pSQL(Tools::getValue('flashsale_text_color'));
		$flash_params['font'] = pSQL(Tools::getValue('flashsale_text_font'));
		$flash_params['active'] = 0;
		$flash_params['sale_custom_img_link'] = $custom_img_link;
		$id_flashsalepro = $flash_sale->updateParameters($flash_params);

		if ($id_flashsalepro === 0)
			return 0;

		$name_query = '';
		$languages = Language::getLanguages();
		$id_default_lang = Configuration::get('PS_LANG_DEFAULT');

		foreach ($languages as $lang) /*Insert names in all languages */
		{
			$name = Tools::getValue('flash_sale_name_'.$lang['id_lang']);

			if ($name == '' || $name == null)
				$name = Tools::getValue('flash_sale_name_'.$id_default_lang);
			$name_query = 'INSERT INTO '._DB_PREFIX_.'flashsalespro_names (id_flashsalespro, name, id_lang)
					VALUES ('.(int)$id_flashsalepro[0]['id_flashsalespro'].', "'.pSQL($name).'", '.(int)$lang['id_lang'].') ';
			$result = Db::getInstance()->Execute($name_query);
		}
		if ($result === 0)
			return 0;

		if (!empty($list_product[0]) && isset($list_product[0]))
		{
			foreach ($list_product as $item)
			{
				$product = new Product($item['id_item']);
				$id_prod = $product->id;
				$discount_amount_type = pSQL(Tools::getValue('product_'.$id_prod.'_discount_type'));
				$discount_amount = (float)Tools::getValue('amount_product'.$id_prod);

				$specific_price = new SpecificPrice();
				$specific_price->id_shop = 1;
				$specific_price->id_product = $id_prod;
				$specific_price->from_quantity = 1;
				$specific_price->id_country = (int)Tools::getValue('discount_country_restriction');
				$specific_price->id_group = $id_group_disabled;
				$specific_price->id_customer = 0;
				$specific_price->price = -1;
				$specific_price->reduction_tax = $include_tax;
				$specific_price->id_currency = (int)Tools::getValue('discount_currency_restriction');
				$specific_price->from = $date_start;
				$specific_price->to = $date_end;
				if ($discount_amount_type === 'percentage')
					$specific_price->reduction = $discount_amount / 100;
				else
					$specific_price->reduction = $discount_amount;
				$specific_price->reduction_type = $discount_amount_type;
				$specific_price->add();

				/* Flash Sale Custom Product Image */
				$custom_flashsale_img_flag = 0;

				$current_file_array = $_FILES['custom_flashsale_img_'.$id_prod];

				if ($current_file_array['error'] == 0) /* Custom images for products */
				{
					$img_directory = $img_folder.'/'.$_FILES['custom_flashsale_img_'.$id_prod]['name'];
					$img_directory = str_replace("\0", '', $img_directory);	/* Removing null bytes */
					$image_check = ImageManager::validateUpload($_FILES['custom_flashsale_img_'.$id_prod], 5000000);

					if (!$img_folder || !is_writable($img_folder))
						exit($this->l('Error in the directory location or permissions for storing the image.'));

					if ((($_FILES['custom_flashsale_img_'.$id_prod]['type'] == 'image/gif')
						|| ($_FILES['custom_flashsale_img_'.$id_prod]['type'] == 'image/jpeg')
						|| ($_FILES['custom_flashsale_img_'.$id_prod]['type'] == 'image/jpg')
						|| ($_FILES['custom_flashsale_img_'.$id_prod]['type'] == 'image/pjpeg')
						|| ($_FILES['custom_flashsale_img_'.$id_prod]['type'] == 'image/x-png')
						|| ($_FILES['custom_flashsale_img_'.$id_prod]['type'] == 'image/png'))
						&& $image_check == null)
					{
						move_uploaded_file($_FILES['custom_flashsale_img_'.$id_prod]['tmp_name'], $img_directory);
						$custom_flashsale_img_flag = 1;
					}
					else
						exit($this->l('Error with the file type you have tried to upload. ID Prod : '.$id_prod));
				}
				if (isset($_FILES['custom_flashsale_img_'.$id_prod]['name']) && !empty($_FILES['custom_flashsale_img_'.$id_prod]['name']))
					$custom_flashsale_img_link = $this->ps_url.'modules/flashsalepro/views/img/'.$_FILES['custom_flashsale_img_'.$id_prod]['name'];
				else
					$custom_flashsale_img_link = '';

				$stock_above = -1;
				$stock_below = -1;
				if ($sale_type == 'stock');
				{
					$stock_above = (int)Tools::getValue('flashsale_stock_above_'.$id_prod);
					$stock_below = (int)Tools::getValue('flashsale_stock_below_'.$id_prod);
				}

				$item_query = 'INSERT INTO '._DB_PREFIX_.'flashsalespro_items (
					id_flashsalespro, id_specific_price, discount, discount_type, custom_img_link_flag, custom_img_link, active, stock_above, stock_below)
						VALUES ('.(int)$id_flashsalepro[0]['id_flashsalespro'].', '.(int)$specific_price->id.', '.(float)$discount_amount.', "'.pSQL(trim($discount_amount_type)).'
							", '.(int)$custom_flashsale_img_flag.', "'.pSQL($custom_flashsale_img_link).'", 1, '.(int)$stock_above.', '.(int)$stock_below.') ';

				$result = Db::getInstance()->Execute($item_query);
				if ($result === 0)
					return 0;
			}
		}

		foreach ($list_category as $item)
		{
			$category = new Category($item['id_item']);
			$id_category = $category->id;
			if ($this->isParentCatInList($id_category, $list_category) === 0) /* Parent categories always get priority */
			{
				$products = $this->getProductsFromCategory($id_category, $exclude_product_list);
				$discount_amount_type = pSQL(Tools::getValue('category_'.$id_category.'_discount_type'));
				$discount_amount = (float)Tools::getValue('amount_category'.$id_category);
				foreach ($products as $id_prod)
				{
					$specific_price = new SpecificPrice();
					$specific_price->id_shop = 1;
					$specific_price->id_product = $id_prod;
					$specific_price->from_quantity = 1;
					$specific_price->id_country = (int)Tools::getValue('discount_country_restriction');
					$specific_price->id_group = $id_group_disabled;
					$specific_price->id_customer = 0;
					$specific_price->price = -1;
					$specific_price->reduction_tax = $include_tax;
					$specific_price->id_currency = (int)Tools::getValue('discount_currency_restriction');
					$specific_price->from = $date_start;
					$specific_price->to = $date_end;
					if ($discount_amount_type === 'percentage')
						$specific_price->reduction = $discount_amount / 100;
					else
						$specific_price->reduction = $discount_amount;
					$specific_price->reduction_type = $discount_amount_type;
					$specific_price->add();

					$custom_flashsale_img_flag = 0;
					$custom_flashsale_img_link = '';

					$id_flashsalepro = Db::getInstance()->ExecuteS(
											'SELECT id_flashsalespro FROM '._DB_PREFIX_.'flashsalespro ORDER BY id_flashsalespro DESC LIMIT 1;');

					$item_query = 'INSERT INTO '._DB_PREFIX_.'flashsalespro_items (
									id_flashsalespro, id_specific_price, discount, discount_type, custom_img_link_flag, custom_img_link, active)
								VALUES ('.(int)$id_flashsalepro[0]['id_flashsalespro'].', '.(int)$specific_price->id.', '.(float)$discount_amount.', "'
									.pSQL(trim($discount_amount_type)).'", '.(int)$custom_flashsale_img_flag.', "'.pSQL($custom_flashsale_img_link).'", 1) ';
					$result = Db::getInstance()->Execute($item_query);
					if ($result === 0)
						return 0;
				}
			}
		}
		return 1;
	}

	public function isParentCatInList($id_category, $list_category)
	{
		$category = new Category((int)$id_category);
		$parents = $category->getParentsCategories();
		$temp_cat_ids = array();
		foreach ($list_category as $temp_cat)
			array_push($temp_cat_ids, $temp_cat);
		foreach ($parents as $parent)
			if (in_array($parent['id_parent'], $temp_cat_ids))
				return 1;
		return 0;
	}

	/* Checks stock levels in active Flash Sales and deactivates the items if the current stock level is not in between the stated range. */
	public function checkStockOfItemsInSale()
	{
		$stock_check_query = 'SELECT fs.id_shop, fsi.id_flashsalespro, fsi.id_flashsalespro_item, fsi.id_specific_price,
							 fsi.stock_above, fsi.stock_below, sp.id_product
							 FROM `'._DB_PREFIX_.'flashsalespro` fs INNER JOIN `'._DB_PREFIX_.'flashsalespro_items` fsi ON fs.id_flashsalespro = fsi.id_flashsalespro
							 INNER JOIN `'._DB_PREFIX_.'specific_price` sp ON fsi.id_specific_price = sp.id_specific_price
							 WHERE fs.sale_type = "stock" AND fs.active = 1 AND fsi.active = 1';
		$result_stock_check = Db::getInstance()->ExecuteS($stock_check_query);

		$deactivate_item_query = 'UPDATE '._DB_PREFIX_.'flashsalespro_items 
			SET active = 0 WHERE id_flashsalespro_item IN (';
		$items_to_deactivate = array();
		$sp_to_deactivate = array();
		foreach ($result_stock_check as $sale)
		{
			$quantity_available = StockAvailable::getQuantityAvailableByProduct($sale['id_product'], null, $sale['id_shop']); /* Stock available */
			$stock_handle = $this->checkStock($sale['id_product'], $sale['id_shop']);	/* If stock can be sold when out of stock */
			if ($stock_handle == 0 || $sale['stock_above'] > $quantity_available || $sale['stock_below'] < $quantity_available)
			{
				array_push($items_to_deactivate, $sale['id_flashsalespro_item']);
				array_push($sp_to_deactivate, $sale['id_specific_price']);
			}
		}
		$deactivate_item_query .= implode(array_map('intval', $items_to_deactivate), ', ').')';

		if (count($items_to_deactivate) > 0)
		{
			Db::getInstance()->Execute($deactivate_item_query);
			$this->deactivateFlashSaleItems($sp_to_deactivate);
		}
		$this->checkStockToActivate();
		$this->checkForEmptyFlashSale();
	}

	public function checkStockToActivate()
	{
		$stock_check_query = 'SELECT fs.id_shop, fs.id_group_restriction, fsi.id_flashsalespro, fsi.id_flashsalespro_item, fsi.id_specific_price,
							 fsi.stock_above, fsi.stock_below, sp.id_product
							 FROM `'._DB_PREFIX_.'flashsalespro` fs INNER JOIN `'._DB_PREFIX_.'flashsalespro_items` fsi ON fs.id_flashsalespro = fsi.id_flashsalespro
							 INNER JOIN `'._DB_PREFIX_.'specific_price` sp ON fsi.id_specific_price = sp.id_specific_price
							 WHERE fs.sale_type = "stock" AND fs.active = 1 AND fsi.active = 0';
		$result_stock_check = Db::getInstance()->ExecuteS($stock_check_query);

		$activate_item_query = 'UPDATE '._DB_PREFIX_.'flashsalespro_items 
			SET active = 1 WHERE id_flashsalespro_item IN (';
		$items_to_activate = array();
		$sp_to_activate = array();
		foreach ($result_stock_check as $sale)
		{
			$quantity_available = StockAvailable::getQuantityAvailableByProduct($sale['id_product'], null, $sale['id_shop']); /* Stock available */
			if ($sale['stock_above'] < $quantity_available && $sale['stock_below'] > $quantity_available)
			{
				array_push($items_to_activate, $sale['id_flashsalespro_item']);
				array_push($sp_to_activate, $sale['id_specific_price']);
			}
		}
		$activate_item_query .= implode(array_map('intval', $items_to_activate), ', ').')';

		if (count($items_to_activate) > 0)
		{
			$id_group = (int)$result_stock_check[0]['id_group_restriction'];
			Db::getInstance()->Execute($activate_item_query);
			$this->activateFlashSaleItems($sp_to_activate, $id_group);
		}
	}

	public function checkStock($product_id, $id_shop)
	{
		$general_stock_management = (int)Configuration::get('PS_ORDER_OUT_OF_STOCK');
		/* General flag that checks if shop can sell out of stock products */
		$flag = StockAvailable::outOfStock($product_id, $id_shop);
		/* Flag on specific products that checks if shop can sell the product when out of stock */

		if (($general_stock_management == 1 && $flag == 2) || $flag == 1)	/* Checking if available stock */
			return 1;
		return 0;
	}

	/* Function that checks if all items in a stock controlled Flash Sale are inactive. If they are, the Flash Sale is deactivated. */
	public function checkForEmptyFlashSale()
	{
		$active_item_count_query = 'SELECT fsp1.id_flashsalespro, COUNT(*) AS "items_count", 
									(SELECT COUNT(*) FROM  `'._DB_PREFIX_.'flashsalespro_items` fsp2 
										WHERE fsp2.id_flashsalespro = fsp1.id_flashsalespro 
										AND fsp2.active = 1) 
										AS "active_items" 
										FROM  `'._DB_PREFIX_.'flashsalespro_items` fsp1, `'._DB_PREFIX_.'flashsalespro` fs 
										WHERE fs.active = 1 
										AND fs.id_flashsalespro = fsp1.id_flashsalespro
										GROUP BY fsp1.id_flashsalespro';
		$active_item_count = Db::getInstance()->ExecuteS($active_item_count_query);

		if (!empty($active_item_count))
			foreach ($active_item_count as $sale)
				if ($sale['active_items'] == 0)
					$this->deactivateFlashSale($sale['id_flashsalespro']);
	}

	public function getItemCountFromSale($id_flash_sale)
	{
		$item_count_query = '
							SELECT COUNT(id_flashsalespro) AS "items_count" FROM  `'._DB_PREFIX_.'flashsalespro_items` WHERE
							 id_flashsalespro = '.(int)$id_flash_sale;
		return Db::getInstance()->ExecuteS($item_count_query);
	}

	public function getActiveItemCountFromSale($id_flash_sale)
	{
		$item_count_query = 'SELECT COUNT(id_flashsalespro) AS "items_count" FROM  `'._DB_PREFIX_.'flashsalespro_items` WHERE id_flashsalespro = '.(int)$id_flash_sale.' AND active = 1';
		return Db::getInstance()->ExecuteS($item_count_query);
	}

	public function getProductsFromCategory($id_category, $product_list = null)
	{
		$id_products = array();
		if ($id_category == null)
			return $id_products;
		$flash_sale_specific_price_query = $this->getFlashSaleSpecificPricesID();

		$specific_price_query = 'SELECT id_product FROM `'._DB_PREFIX_.'specific_price` WHERE (`to` > NOW() OR `to` = "0000-00-00 00:00:00")';
		if ($flash_sale_specific_price_query != 1)
			$specific_price_query .= ' AND id_specific_price NOT IN ('.implode(',', array_map('intval', $flash_sale_specific_price_query)).')';
		/* Query to find products that have an active specific price excluding specific prices created in a flash sale */
		$result_products_sp = Db::getInstance()->ExecuteS($specific_price_query);

		/* Exclude inactive products */
		$inactive_query = 'SELECT id_product FROM `'._DB_PREFIX_.'product` WHERE `active` = 0';
		$result_products_inactive = Db::getInstance()->ExecuteS($inactive_query);

		$excluded_products = array();
		foreach ($result_products_sp as $excluded_product)
			array_push($excluded_products, $excluded_product['id_product']);
		foreach ($result_products_inactive as $inactive_product)
			array_push($excluded_products, $inactive_product['id_product']);

		$product_query = 'SELECT id_product FROM `'._DB_PREFIX_.'category_product` 
					WHERE id_category = '.(int)$id_category;
		if (count($result_products_sp) > 0)
			$product_query .= ' AND id_product NOT IN ('.implode(array_map('intval', $excluded_products), ', ').')';
		if (!empty($product_list) && $product_list != null)
			$product_query .= ' AND id_product NOT IN ('.implode(array_map('intval', $product_list), ', ').')';

		$result_products = Db::getInstance()->ExecuteS($product_query);
		foreach ($result_products as $product)
			array_push($id_products, $product['id_product']);

		return $id_products;
	}

	public function getFlashSaleInfo()
	{
		$context = Context::getContext();
		$id_groups_client = Customer::getGroupsStatic($context->customer->id);
		$query = 'SELECT id_flashsalespro, id_shop, id_currency_restriction, id_country_restriction, id_group_restriction, sale_custom_img_link, date_end, end_date_timestamp, sale_type, bg_color, text_color, font, active FROM `'._DB_PREFIX_.'flashsalespro` 
					WHERE (id_group_restriction = 0 OR id_group_restriction IN ('.implode(',', $id_groups_client).')) AND (id_currency_restriction = 0 OR id_currency_restriction = '.(int)$context->currency->id.') AND (id_country_restriction = 0 OR id_country_restriction = '.(int)$context->country->id.') AND active = 1';

		$result = Db::getInstance()->ExecuteS($query);

		if (!empty($result) && $result[0]['active'] == 1)
		{
			$result[0]['lang_code'] = $context->language->iso_code;
			$name = $this->getFlashSaleNames($context->language->id, $result[0]['id_flashsalespro']);

			$result[0]['name'] = $name[0]['name'];
			$result[0]['currency_format'] = 0;

			if (($context->currency->format % 2) != 0)	/* Should the currency symbol go before or after the figure */
				$result[0]['currency_format'] = 1;

			return $result[0];
		}
		else
			return null;
	}

	public function getFlashSale($id_flash_sale)
	{
		$query = 'SELECT * FROM `'._DB_PREFIX_.'flashsalespro` 
					WHERE id_flashsalespro = '.(int)$id_flash_sale;
		$result = Db::getInstance()->getRow($query);
		if (!empty($result))
			return $result;
		else
			return null;
	}

	public function getFlashSaleNames($id_lang = null, $id_flashsale = null)
	{
		$get_names_query = 'SELECT name FROM `'._DB_PREFIX_.'flashsalespro_names`';
		if ($id_lang != null || $id_flashsale != null)
			$get_names_query .= ' WHERE';
		if ($id_lang != null)
			$get_names_query .= ' `id_lang` = '.(int)$id_lang;
		if ($id_lang != null && $id_flashsale != null)
			$get_names_query .= ' AND';
		if ($id_flashsale != null)
			$get_names_query .= ' `id_flashsalespro` = '.(int)$id_flashsale.'';
		$result = Db::getInstance()->ExecuteS($get_names_query);

		/* Solution for error if language added to Prestashop after Flash Sale is created */
		if ($result == null || empty($result))
			$result = Db::getInstance()->ExecuteS('SELECT name FROM `'._DB_PREFIX_.'flashsalespro_names` WHERE `id_flashsalespro` = '.(int)$id_flashsale);
		return $result;
	}

	public function getFlashSaleItems($id_flash_sale)
	{
		$context = Context::getContext();
		$group = new Group($context->customer->id_default_group);

		/* group price display method is 1 when product price option excludes tax
		group price display method is 0 when product price option includes tax */
		$include_tax = null;
		if ((int)$group->price_display_method == 0)
			$include_tax = 1;
		else
			$include_tax = 0;
		
		$items = array();
		$query = 'SELECT * FROM `'._DB_PREFIX_.'flashsalespro_items` 
					WHERE `id_flashsalespro` = '.(int)$id_flash_sale.' AND active = 1';
		$result = Db::getInstance()->ExecuteS($query);

		foreach ($result as $item)
		{
			$cart_currency = $context->currency;
			$default_currency = Currency::getDefaultCurrency();
			$item['discount_type'] = trim($item['discount_type']);
			$specific_price = new SpecificPrice($item['id_specific_price']);

			$item['id_product'] = $specific_price->id_product;
			$product = new Product((int)$specific_price->id_product);
			if (Validate::isLoadedObject($product))
			{
				$category = new Category($product->getDefaultCategory());
				$cat_rewrite = $category->link_rewrite[$context->language->id];
				
				$decimals = 2;
				if ($cart_currency->decimals == 0)
					$decimals = 0;
				$product_price_after_discount = number_format($product->getPrice($include_tax, null, 2), $decimals, '.', '');
				$original_price = number_format($product->getPrice($include_tax, null, 2, null, false, false, 1), $decimals, '.', '');
				$discount = number_format($product->getPrice($include_tax, null, 6, null, true, false, 1), $decimals, '.', '');

				$item['product_name'] = $product->getProductName($product->id);
				$item['product_price'] = $original_price;
				if (trim($item['discount_type']) == 'amount')
				{
					$item['product_price_after_discount'] = number_format($original_price - $item['discount'], $decimals, '.', '');
					$item['discount'] = number_format($item['discount'], $decimals);
				}
				else
					$item['product_price_after_discount'] = number_format($product_price_after_discount, $decimals, '.', '');
				$link = new Link();
				$item['product_link'] = $link->getProductLink($product, null, $cat_rewrite);
				if (empty($item['custom_img_link']))
					$item['custom_img_link'] = $this->getImage((int)$item['id_product']);
				$item['currency_sign'] = $context->currency->sign;

				/* There will be work to do for 1.6.1
				if (trim($item['discount_type']) == 'amount')
					$item['discount'] = $product->getPrice($include_tax, null, 2, null, true, false, 1);*/
				/*if ($cart_currency->id != $default_currency->id && trim($item['discount_type']) == 'amount' && _PS_VERSION_ >= '1.6.1' == 1)
						$item['discount'] = $item['discount'] * $cart_currency->conversion_rate;*/
				if (isset($item['id_product']) && $item['id_product'] != '' && $item['id_product'] != null)
					array_push($items, $item);
			}
		}

		return $items;
	}

	public function getAllFlashSaleItems($id_flash_sale)
	{
		$context = Context::getContext();

		$items = array();
		$query = 'SELECT * FROM `'._DB_PREFIX_.'flashsalespro_items` 
					WHERE `id_flashsalespro` = '.(int)$id_flash_sale;
		$result = Db::getInstance()->ExecuteS($query);

		$query_sale_time = 'SELECT sale_type FROM `'._DB_PREFIX_.'flashsalespro` 
					WHERE `id_flashsalespro` = '.(int)$id_flash_sale;
		$sale_type = Db::getInstance()->getValue($query_sale_time);

		foreach ($result as $item)
		{
			$cart_currency = $context->currency;
			$default_currency = Currency::getDefaultCurrency();

			$specific_price = new SpecificPrice($item['id_specific_price']);

			$item['id_product'] = $specific_price->id_product;
			$product = new Product((int)$specific_price->id_product);
			if (Validate::isLoadedObject($product))
			{
				$product_price_after_discount = $product->getPrice();
				$original_price_with_tax = $product->getPrice(true, null, 6, null, false, false, 1);

				$item['product_name'] = $product->getProductName($product->id);
				$item['product_price'] = number_format($original_price_with_tax, 2);
				$item['product_price_after_discount'] = number_format($product_price_after_discount, 2);
				$link = new Link();
				$item['product_link'] = $link->getProductLink($product);
				if (empty($item['custom_img_link']))
					$item['custom_img_link'] = $this->getImage((int)$item['id_product']);
				$item['currency_sign'] = $context->currency->sign;
				$item['sale_type'] = $sale_type;
				if ($cart_currency->id != $default_currency->id && $item['discount_type'] == 'amount')
						$item['discount'] = $item['discount'] * $cart_currency->conversion_rate;

				if ($item['discount_type'] == 'amount')
					$item['discount'] = number_format($item['discount'], 2);
				array_push($items, $item);
			}
		}
		return $items;
	}

	public function destroyAllFlashSales()
	{
		$flash_sales = Db::getInstance()->executeS('SELECT `id_flashsalespro` FROM `'._DB_PREFIX_.'flashsalespro`');
		foreach ($flash_sales as $sale)
		{
			$flash_sale = new FlashSale($sale['id_flashsalespro']);
			$flash_sale->deleteFlashSale();
		}	
	}

	public function deactivateAllFlashSales()
	{
		$active_sales = Db::getInstance()->executeS('SELECT `id_flashsalespro` FROM `'._DB_PREFIX_.'flashsalespro` WHERE `active` = 1');
		foreach ($active_sales as $sale)
			$this->deactivateFlashSale($sale['id_flashsalespro']);	
	}

	public function deactivateFlashSale($id_flash_sale)
	{
		$disabled_group_id = (int)Configuration::get('FLASHSALEPRO_DISABLED_GROUP_ID');
		$id_specific_prices = Db::getInstance()->executeS('SELECT `id_specific_price` FROM `'._DB_PREFIX_.'flashsalespro_items` WHERE `id_flashsalespro` = '.(int)$id_flash_sale);
		$id_sp = array();
		foreach ($id_specific_prices as $specific_price)
			array_push($id_sp, $specific_price['id_specific_price']);

		$change_price_group_query = 'UPDATE '._DB_PREFIX_.'specific_price 
			SET id_group = "'.(int)$disabled_group_id.'" WHERE id_specific_price IN ('.implode(',', array_map('intval', $id_sp)).')';

		Db::getInstance()->execute($change_price_group_query);

		$update_query = 'UPDATE '._DB_PREFIX_.'flashsalespro 
			SET active = 0 WHERE id_flashsalespro = '.(int)$id_flash_sale;

		Db::getInstance()->execute($update_query);
	}

	public function deactivateFlashSaleItems($id_specific_prices)
	{
		$disabled_group_id = (int)Configuration::get('FLASHSALEPRO_DISABLED_GROUP_ID');

		$change_price_group_query = 'UPDATE '._DB_PREFIX_.'specific_price 
			SET id_group = "'.(int)$disabled_group_id.'" WHERE id_specific_price IN ('.implode(',', array_map('intval', $id_specific_prices)).')';

		Db::getInstance()->execute($change_price_group_query);
	}

	public function activateFlashSaleItems($id_specific_prices, $id_group)
	{
		$change_price_group_query = 'UPDATE '._DB_PREFIX_.'specific_price 
			SET id_group = "'.(int)$id_group.'" WHERE id_specific_price IN ('.implode(',', array_map('intval', $id_specific_prices)).')';

		Db::getInstance()->execute($change_price_group_query);
	}

	public function deactivateFlashSales($flash_sale_ids, $id_flash_sale)
	{
		$disabled_group_id = (int)Configuration::get('FLASHSALEPRO_DISABLED_GROUP_ID');

		$active_sales = Db::getInstance()->executeS('SELECT `id_flashsalespro`, `id_group_restriction`, `id_country_restriction`, `id_currency_restriction` FROM `'._DB_PREFIX_.'flashsalespro` WHERE `id_flashsalespro` IN ('.implode(',', array_map('intval', $flash_sale_ids)).')');
		$clash_flash_sales = $this->getSalesToDeactivate($active_sales, $id_flash_sale);
		if (count($clash_flash_sales) > 0)
		{
			$id_specific_prices = Db::getInstance()->executeS('SELECT `id_specific_price` FROM `'._DB_PREFIX_.'flashsalespro_items` WHERE `id_flashsalespro` IN ('.implode(',', array_map('intval', $clash_flash_sales)).')');

			$id_sp = array(); /* Array of flash_sales that need to be deactivated */
			foreach ($id_specific_prices as $specific_price)
				array_push($id_sp, $specific_price['id_specific_price']);

			$update_query = 'UPDATE '._DB_PREFIX_.'specific_price 
				SET id_group = "'.(int)$disabled_group_id.'" WHERE id_specific_price IN ('.implode(',', array_map('intval', $id_sp)).')';
			Db::getInstance()->execute($update_query);

			$update_query = 'UPDATE '._DB_PREFIX_.'flashsalespro 
				SET active = 0 WHERE id_flashsalespro IN ('.implode(',', array_map('intval', $clash_flash_sales)).')';

			return Db::getInstance()->execute($update_query);
		}
	}

	public function getSalesToDeactivate($active_sales, $id_flash_sale)
	{
		$to_active = Db::getInstance()->getRow('SELECT `id_flashsalespro`, `id_group_restriction`, `id_country_restriction`, `id_currency_restriction` FROM `'._DB_PREFIX_.'flashsalespro` WHERE `id_flashsalespro` = '.(int)$id_flash_sale.';');
		$deactivate = array();

		if ($to_active['id_group_restriction'] == 0/* || $to_active['id_country_restriction'] == 0 || $to_active['id_currency_restriction'] == 0*/)
		{
			foreach ($active_sales as $sale)
				array_push($deactivate, $sale['id_flashsalespro']);

			return $deactivate;
		}
		else
		{
			foreach ($active_sales as $sale)
				if ($to_active['id_group_restriction'] == $sale['id_group_restriction'] || $sale['id_group_restriction'] == 0 /*|| $sale['id_country_restriction'] == 0 || $to_active['id_country_restriction'] == $sale['id_country_restriction'] || $to_active['id_currency_restriction'] == $sale['id_currency_restriction'] || $sale['id_currency_restriction'] == 0*/)
					array_push($deactivate, $sale['id_flashsalespro']);

			return $deactivate;
		}
		return -1;
	}

	public function activateFlashSale($id_flash_sale)
	{
		$id_flashsale_current_active = Db::getInstance()->executeS('SELECT `id_flashsalespro` FROM `'._DB_PREFIX_.'flashsalespro` WHERE `active` = 1;');
		$active = array(); 	/* Array of id_flash_sales that are currently active */
		foreach ($id_flashsale_current_active as $active_sale)
			array_push($active, $active_sale['id_flashsalespro']);
		/* End date of flash_sale to be activated */
		$id_flashsale_date = Db::getInstance()->getValue('SELECT `date_end` FROM `'._DB_PREFIX_.'flashsalespro` WHERE `id_flashsalespro` = '.(int)$id_flash_sale);

		$date = date('Y-m-d H:i:s');
		if ($id_flashsale_date != '0000-00-00 00:00:00')
			if ($id_flashsale_date < $date)
				return -1;

		if ($active != null || !isset($active))
			$this->deactivateFlashSales($active, $id_flash_sale);

		$this->activateSpecificPrices($id_flash_sale);

		$update_query = 'UPDATE '._DB_PREFIX_.'flashsalespro 
			SET active = 1 WHERE id_flashsalespro = '.(int)$id_flash_sale;

		Db::getInstance()->execute($update_query);
		return 1;
	}

	public function activateSpecificPrices($id_flash_sale)
	{
		$id_group_original_query = 'SELECT `id_group_restriction` FROM `'._DB_PREFIX_.'flashsalespro` WHERE `id_flashsalespro` = '.(int)$id_flash_sale;
		$id_group_original = Db::getInstance()->getValue($id_group_original_query);

		$id_specific_prices_query = 'SELECT `id_specific_price` FROM `'._DB_PREFIX_.'flashsalespro_items` WHERE `id_flashsalespro` = '.(int)$id_flash_sale;
		$id_specific_prices = Db::getInstance()->executeS($id_specific_prices_query);

		$id_sp = array();
		foreach ($id_specific_prices as $specific_price)
			array_push($id_sp, $specific_price['id_specific_price']);

		if (!empty($id_sp))
		{
			$change_price_group_query = 'UPDATE '._DB_PREFIX_.'specific_price 
				SET id_group = "'.(int)$id_group_original.'" WHERE id_specific_price IN ('.implode(',', array_map('intval', $id_sp)).')';
			Db::getInstance()->execute($change_price_group_query);
		}
	}

	/**
	* Any back-office header requirements
	*/
	public function hookBackOfficeHeader()
	{
		$module_name = Tools::getValue('module_name');
		if (isset($module_name) && $module_name =='flashsalepro')
		{

		}
	}

	/**
	 * Add the CSS & JavaScript files you want to be added on the FO.
	 */
	public function hookHeader()
	{
		$css = array(
			$this->css_path.'flipclock.css',
			$this->css_path.'slick.css',
			$this->css_path.'front.css'
		);
		$add_grid_bootstrap = (int)Configuration::get('FLASHSALEPRO_INCLUDE_GRID_CSS');
		if ($add_grid_bootstrap)
			array_push($css, $this->css_path.'bootstrap-grid.css');
		$this->context->controller->addCSS($css, 'all');

		$js = array(
			$this->js_path.'flipclock.min.js',
			$this->js_path.'slick.min.js',
			$this->js_path.'front.js'
		);
		$this->context->controller->addJS($js);
		/* Clean memory */
		unset($js, $css);
	}

	/**
	 * Add the CSS & JavaScript files you want to be added on the FO.
	 */
	public function hookFooter()
	{
	}

	public function generateToken()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for ($i = 0; $i < 20; $i++)
			$randstring .= $characters[rand(0, Tools::strlen($characters))];
		return $randstring;
	}

	public function checkExpiredSales()
	{
		$query = 'SELECT id_flashsalespro, date_end FROM '._DB_PREFIX_.'flashsalespro 
					WHERE active = 1 AND date_end < NOW() AND date_end != "0000-00-00 00:00:00"';

		$result = Db::getInstance()->ExecuteS($query);

		if (!empty($result))
		{
			$update_list = array();
			foreach ($result as $row)
				array_push($update_list, $row['id_flashsalespro']);
			$update_list_count = count($update_list);
			if ($update_list_count === 1)
				$update_query = 'UPDATE '._DB_PREFIX_.'flashsalespro 
				SET active = 0 WHERE id_flashsalespro = '.(int)$update_list[0];
			else
				$update_query = 'UPDATE '._DB_PREFIX_.'flashsalespro 
				SET active = 0 WHERE id_flashsalespro IN ('.implode(',', array_map('intval', $update_list)).')';
			$result = Db::getInstance()->execute($update_query);
		}
	}

	public function activateTimedSale()
	{
		if (Module::isEnabled($this->name) == 1)
		{
			$query_get_actives = 'SELECT COUNT(id_flashsalespro) FROM '._DB_PREFIX_.'flashsalespro 
						WHERE `active` = 1';
			$result_actives = Db::getInstance()->getValue($query_get_actives);

			if ((int)$result_actives === 0)
			{

				$id_flash_sale = Db::getInstance()->executeS(
							'SELECT * FROM 
										(SELECT id_flashsalespro FROM '._DB_PREFIX_.'flashsalespro 
											WHERE active = 0 
										    AND date_start < NOW() 
										    AND date_end > NOW() 
										    AND date_start != "0000-00-00 00:00:00" 
										    AND sale_type = "timed"
											ORDER BY id_flashsalespro ASC) as tmp 
										LIMIT 1');
				if ($id_flash_sale != null && isset($id_flash_sale))
				{
					$update_query = 'UPDATE '._DB_PREFIX_.'flashsalespro 
									SET `active` = 1 WHERE `id_flashsalespro` = '.(int)$id_flash_sale[0]['id_flashsalespro'];
					Db::getInstance()->execute($update_query);
					$this->activateSpecificPrices($id_flash_sale[0]['id_flashsalespro']);
				}
			}
		}
	}

	public function getFlashSaleSpecificPricesID()
	{
		$query = 'SELECT id_specific_price FROM '._DB_PREFIX_.'flashsalespro_items';
		$result = Db::getInstance()->ExecuteS($query);
		$specific_price_ids = array();
		foreach ($result as $item)
			array_push($specific_price_ids, $item['id_specific_price']);
		if (empty($specific_price_ids))
			return 1;
		return $specific_price_ids;
	}

	public function getProductInfo($lang, $value)
	{
		$currency = Currency::getDefaultCurrency();
		$symbol = $currency->sign;


		$query_existing_specific_price_products = 'SELECT `id_product` FROM `'._DB_PREFIX_.'specific_price` WHERE `to` > NOW() OR `to` = "0000-00-00 00:00:00"';
		$id_sp_list = $this->getFlashSaleSpecificPricesID();
		if ($id_sp_list != 1)
			$query_existing_specific_price_products .= ' AND `id_specific_price` NOT IN ('.implode(',', array_map('intval', $id_sp_list)).')';

		$rows = array();
		$result = Db::getInstance()->ExecuteS($query_existing_specific_price_products);
		foreach ($result as $row)
			array_push($rows, $row['id_product']);

		$query = 'SELECT DISTINCT pl.id_product, pl.name FROM '._DB_PREFIX_.'product_lang pl JOIN `'._DB_PREFIX_.'product` p ON pl.id_product = p.id_product WHERE pl.id_lang = '.(int)$lang.' AND pl.name LIKE "%'.pSQL($value).'%" AND p.active = 1';
		
		$query_existing_products_in_temp = 'SELECT `id_item` FROM `'._DB_PREFIX_.'flashsalespro_temp` WHERE `item_type` = "product"';
		$existing_temp = Db::getInstance()->ExecuteS($query_existing_products_in_temp);
		$exclude = array();
		foreach ($existing_temp as $temp)
			array_push($exclude, $temp['id_item']);
		
		/*if (!empty($exclude) && $exclude != null)
			$query .= ' AND `id_product` NOT IN ('.implode(',', array_map('intval', $exclude)).')';*/
		$result = Db::getInstance()->ExecuteS($query);

		$products = array();
		foreach ($result as $row)
		{
			$image_link = $this->getImage((int)$row['id_product']);
			$prod = new Product((int)$row['id_product']);
			$available_stock = $prod->getRealQuantity((int)$row['id_product']);
			if (!Validate::isLoadedObject($prod))
				Tools::displayError('Error loading $prod (function (getProductInfo)).');
			$price = number_format($prod->price, 2);
			$row['image'] = $image_link;
			$row['price'] = $price;
			$row['currency_symbol'] = $symbol;
			$row['stock'] = $available_stock;
			if (in_array((int)$row['id_product'], $rows)) /* If specific price exists for this product outside the module */
				$row['existing'] = 1;
			else
				$row['existing'] = 0;
			if (in_array((int)$row['id_product'], $exclude)) /* If product is already selected for this flash sale */
				$row['temp'] = 1;
			else
				$row['temp'] = 0;
			array_push($products, $row);
		}
		return $products;
	}

	public function getCategoryInfo($lang, $value)
	{
		$query = 'SELECT DISTINCT cat.id_category, cat.name, cat.description FROM '._DB_PREFIX_.'category_lang cat 
					WHERE cat.id_lang = '.(int)$lang.' AND cat.name LIKE "%'.pSQL($value).'%"';
		$result = Db::getInstance()->ExecuteS($query);

		$query_existing_categories_in_temp = 'SELECT `id_item` FROM `'._DB_PREFIX_.'flashsalespro_temp` WHERE `item_type` = "category"';
		$existing_temp = Db::getInstance()->ExecuteS($query_existing_categories_in_temp);
		$exclude = array();
		foreach ($existing_temp as $temp)
			array_push($exclude, $temp['id_item']);

		$categories = array();
		foreach ($result as $row)
		{
			if (in_array((int)$row['id_category'], $exclude)) /* If product is already selected for this flash sale */
				$row['existing'] = 1;
			else
				$row['existing'] = 0;

			$row['text_category'] = $this->l('Add all products in this category.');
			$row['stripped_desc'] = strip_tags($row['description']);
			array_push($categories, $row);
		}

		return $categories;
	}

	public function getEditProductBlock($item)
	{
		$context = Context::getContext();
		$query_product = 'SELECT id_product FROM `'._DB_PREFIX_.'specific_price` 
					WHERE id_specific_price = '.(int)$item['id_specific_price'];

		$id_product = Db::getInstance()->getValue($query_product);
		$image_link = $this->getImage($id_product);
		$context->smarty->assign(array(
			'id_product' => $id_product,
			'item' => $item,
			'image_link' => $image_link,
			'name' => Product::getProductName($id_product),
			'currency_symbol' => $context->currency->getSign(),
		));

		$product_html = $context->smarty->fetch(dirname(__FILE__).'/views/templates/admin/tabs/forms/editProductBlock.tpl');
		return $product_html;
	}

	public function getEditProductsBlock($items)
	{
		$items_count = count($items);
		$product_html = '';

		if ($items_count < 4)
		{
			foreach ($items as $item)
				$product_html .= $this->getEditProductBlock($item);
		}
		else
		{
			for ($i = 0; $i < 4; $i++)
				$product_html .= $this->getEditProductBlock($items[$i]);
		}
		return $product_html;
	}

	public function getViewProductsBlock($items)
	{
		$product_html = '';
		foreach ($items as $item)
			$product_html .= $this->getEditProductBlock($item);
		return $product_html;
	}

	public function getEditPaginationHtml($items)
	{
		$context = Context::getContext();
		$items_count = count($items);
		$page_count = $items_count / 4;
		if ($items_count % 4 != 0)
			$page_count++;

		$context->smarty->assign(array(
			'page_count' => (int)$page_count,
		));
		$product_html = $context->smarty->fetch(dirname(__FILE__).'/views/templates/admin/tabs/forms/editPaginationBlock.tpl');
		return $product_html;
	}

	public function getProductInfoEdit($lang, $value)
	{
		$currency = Currency::getDefaultCurrency();
		$symbol = $currency->sign;
		$query_existing_specific_price_products = 'SELECT `id_product` FROM `'._DB_PREFIX_.'specific_price` WHERE `to` > NOW()';
		$id_sp_list = $this->getFlashSaleSpecificPricesID();
		if ($id_sp_list != 1)
			$query_existing_specific_price_products .= ' AND `id_specific_price` NOT IN ('.implode(',', array_map('intval', $id_sp_list)).')';

		$rows = array();
		$result = Db::getInstance()->ExecuteS($query_existing_specific_price_products);
		foreach ($result as $row)
			array_push($rows, $row['id_product']);

		$query = 'SELECT DISTINCT pl.id_product, pl.name FROM '._DB_PREFIX_.'product_lang pl WHERE pl.id_lang = '.(int)$lang.' AND pl.name LIKE "%'.pSQL($value).'%"';
		if (!empty($rows))
			$query .= ' AND pl.id_product NOT IN ('.implode(array_map('intval', $rows), ', ').')';

		$result = Db::getInstance()->ExecuteS($query);

		$products = array();
		foreach ($result as $row)
		{
			$image_link = $this->getImage((int)$row['id_product']);
			$prod = new Product((int)$row['id_product']);
			$available_stock = $prod->getRealQuantity((int)$row['id_product']);
			if (!Validate::isLoadedObject($prod))
				Tools::displayError('Error loading $prod (function (getProductInfo)).');

			$price = number_format($prod->price, 2);
			$row['image'] = $image_link;
			$row['price'] = $price;
			$row['currency_symbol'] = $symbol;
			$row['stock'] = $available_stock;

			array_push($products, $row);
		}
		return $products;
	}

	public function addToTempTable($id_item, $type)
	{
		$temp_query = 'INSERT INTO '._DB_PREFIX_.'flashsalespro_temp (item_type, id_item, discount_type)
					VALUES ("'.pSQL($type).'", '.(int)$id_item.', "amount") ';

		$result = Db::getInstance()->Execute($temp_query);
		return $result;
	}

	public function getImage($p_id, $id_image = null)
	{
		$context = Context::getcontext();
		$query_image_id = '
							SELECT id_image
							FROM '._DB_PREFIX_.'image
							WHERE id_product = '.(int)$p_id;

		if ($id_image != null)
			$query_image_id .= ' AND id_image = '.(int)$id_image;

		$query_image_id .= ' ORDER BY cover DESC';

		$images = Db::getInstance()->ExecuteS($query_image_id);

		$query = 'SELECT link_rewrite FROM '._DB_PREFIX_.'product_lang
			  WHERE id_product = '.(int)$p_id.' AND id_lang = '.(int)$context->language->id;
		$link_rewrite = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($query);

		$link = $context->link;

		if (Configuration::get('PS_LEGACY_IMAGES'))
			$image_link = Tools::getShopDomain(true).'/img/p/'.(int)$p_id.'-'.$images[0]['id_image'].'-large.jpg';
		else
			$image_link = $link->getImageLink($link_rewrite[0]['link_rewrite'], $images[0]['id_image']);

		return $image_link;
	}

	/*public function getImage($id_product, $id_image = null)
	{
		$context = Context::getcontext();
		$id_lang = (int)$context->language->id;
		//$cover_dir = Image::getImages($id_lang, $id_product, $id_combination);

		if (!isset($cover_dir[0]['id_image']))
			$cover_dir = Image::getImages($id_lang, $id_product);

		if (!isset($cover_dir[0]['id_image']))
			return false;
		if ($this->ps17)
			$img_formatted = ImageType::getFormattedName('large');
		else
			$img_formatted = ImageType::getFormatedName('large');
		if (Configuration::get('PS_REWRITING_SETTINGS') == 0)
			return Tools::getCurrentUrlProtocolPrefix().htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8')._PS_IMG_.'p/'.$cover_dir[0]['id_image'].'/'.$cover_dir[0]['id_image'].'-'.$img_formatted.'.jpg';
		else
		{
			$img_dir = implode('/', str_split($cover_dir[0]['id_image']));
			return Tools::getCurrentUrlProtocolPrefix().htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8')._PS_IMG_.'p/'.$img_dir.'/'.$cover_dir[0]['id_image'].'-'.$img_formatted.'.jpg';
		}
	}*/

	/* Queries */
	public function removeItemFromList($id_item, $item_type)
	{
		$temp_query = 'DELETE FROM `'._DB_PREFIX_.'flashsalespro_temp` WHERE item_type = \''.pSQL($item_type).'\' AND id_item = '.(float)$id_item;
		Db::getInstance()->Execute($temp_query);
	}

	public function detectBrowser()
	{
		$browser = 'Other';
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			$browser = 'IE';
		return $browser;
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
			$this->css_path.'bootstrap-nav-wizard.css',
			$this->css_path.'DT_bootstrap.css',
			$this->css_path.'fix.css',
			$this->css_path.'datepicker.css',
			$this->css_path.'flashsalepro.css',
			$this->css_path.'back.css',
			$this->css_path.'sweet-alert.css',
			$this->css_path.'flipclock.css',
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$css_compatibility = array(
				$this->css_path.'bootstrap.min.css',
				$this->css_path.'bootstrap.extend.css',
				$this->css_path.'bootstrap-responsive.min.css',
				$this->css_path.'font-awesome.min.css',
			);
			$css = array_merge($css_compatibility, $css);
		}
		$this->context->controller->addCSS($css, 'all');

		/* Load JS */
		$js = array(
			$this->js_path.'bootstrap-select.min.js',
			$this->js_path.'bootstrap-dialog.js',
			$this->js_path.'jquery.autosize.min.js',
			$this->js_path.'dynamic_table_init.js',
			$this->js_path.'jquery.dataTables.js',
			$this->js_path.'DT_bootstrap.js',
			$this->js_path.$this->name.'.js',
			$this->js_path.'sweet-alert.min.js',
			$this->js_path.'flipclock.min.js',
			__PS_BASE_URI__.'js/jquery/plugins/jquery.colorpicker.js',
			$this->js_path.'back.js'
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$js_compatibility = array(
				$this->js_path.'bootstrap.min.js',
				$this->js_path.'bootstrap-datepicker.js'
			);
			$js = array_merge($js_compatibility, $js);
		}
		$this->context->controller->addJS($js);

		/* Clean memory */
		unset($js, $css, $js_compatibility, $css_compatibility);
	}
}

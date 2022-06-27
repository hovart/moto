<?php
if (!defined('_PS_VERSION_'))
	exit;

require_once(_PS_MODULE_DIR_.'totsplashscreen/classes/totsplashscreenModel.php');
require_once(_PS_MODULE_DIR_.'totsplashscreen/classes/totsplashscreentemplateModel.php');

/**
 * Description of tge
 * @version 1.7
 * @author Guillaume Deloince
 */
class totsplashscreenModule extends Module {

	/**
	 * __construct()
	 */
	public function __construct() {
		$this->name = 'totsplashscreen';
		$this->tab = 'totsplashscreen';
		$this->version = "1.7.2";
		$this->author = '202-ecommerce';
		$this->need_instance = 0;
		parent::__construct();
		$this->displayName = $this->l('totsplashscreen');
		$this->description = $this->l('Popup on your website');
		$this->module_key = '0cce611f009e689d9ff3dccc17ab13ea';

		// Ling
		if (version_compare(_PS_VERSION_, '1.5', '>')) {
			$this->link = 'index.php?controller='.Tools::getValue('controller').'&configure='.$this->name.'&token='.Tools::getValue('token').'&tab_module='.$this->tab.'&module_name='.$this->name;
		} else {
			$this->link = 'index.php?tab='.Tools::getValue('tab').'&configure='.$this->name.'&token='.Tools::getValue('token').'&tab_module='.$this->tab.'&module_name='.$this->name;
		}
	}

	/**
	 * Installation module
	 * @return boolean
	 */
	public function install() {

		$sql = array();
		$sql[] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."totsplashscreen` (
					  `id_totsplashscreen` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) NOT NULL,
					  `id_template` int(11) NOT NULL,
					  `type` enum('none','home','category','product','cms','cart','manufacturer') NOT NULL,
					  `id_type` int(11) NOT NULL,
					  `child_category` tinyint(4) NOT NULL,
					  `date_start` datetime NOT NULL,
					  `date_end` datetime NOT NULL,
					  `nb_jour_avant_reapparition` int(11) NOT NULL,
					  `totsplashscreen_version_cookie` int(11) NOT NULL,
					  `id_shop` int(11) NOT NULL,
					  `id_shop_group` int(11) NOT NULL,
					  PRIMARY KEY (`id_totsplashscreen`)
					) ENGINE=InnoDB  DEFAULT CHARSET=latin1";

		$sql[] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."totsplashscreen_template_lang` (
				  `id_totsplashscreen_template` int(11) NOT NULL,
				  `message` text NOT NULL,
				  `id_lang` int(11) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."totsplashscreen_template` (
				  `id_totsplashscreen_template` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) NOT NULL,
				  `link_fb` varchar(255) NOT NULL,
				  `newsletter` tinyint(4) NOT NULL,
				  `width` int(11) NOT NULL,
				  `height` int(11) NOT NULL,
				  `backgroundColor` varchar(7) NOT NULL,
				  `opacity` int(11) NOT NULL,
				  `permission_mode` tinyint(4) NOT NULL,
				  `permission_redirect` varchar(255) NOT NULL,
				  `image_enter` varchar(255) NOT NULL, 
				  `image_leave` varchar(255) NOT NULL, 
				  PRIMARY KEY (`id_totsplashscreen_template`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1";



		foreach ($sql as $query) {
			if (!Db::getInstance()->Execute($query))
				return false;
		}



		if (parent::install() == false OR !$this->registerHook('top') OR !$this->registerHook('header'))
			return false;
		if (!Configuration::updateValue('totsplashscreen_delai', '30') OR
				!Configuration::updateValue('totsplashscreen_version_cookie', '0') OR
				!Configuration::updateValue('totsplashscreen_fan_page', 'on') OR
				!Configuration::updateValue('totsplashscreen_fan_page_url', 'https://www.facebook.com/pages/Exclu-Mariagecom/103202581933') OR
				!Configuration::updateValue('totsplashscreen_newsletter', 'on') OR
				!Configuration::updateValue('totsplashscreen_text_before', '') OR
				!Configuration::updateValue('totsplashscreen_count_page', '10'))
			return false;
		return true;
	}

	/**
	 * Desinstallation
	 * @return boolean
	 */
	public function uninstall() {
		if (!Configuration::deleteByName('totsplashscreen_delai') OR
				!Configuration::deleteByName('totsplashscreen_version_cookie') OR
				!Configuration::deleteByName('totsplashscreen_fan_page') OR
				!Configuration::deleteByName('totsplashscreen_fan_page_url') OR
				!Configuration::deleteByName('totsplashscreen_newsletter') OR
				!Configuration::deleteByName('totsplashscreen_text_before') OR
				!Configuration::deleteByName('totsplashscreen_count_page'))
			return false;

		$sql = array();
		$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."totsplashscreen_template";
		$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."totsplashscreen_template_lang";
		$sql[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."totsplashscreen";

		foreach ($sql as $query) {
			if (!Db::getInstance()->Execute($query))
				return false;
		}

		return parent::uninstall();
	}

	/**
	 * Recuperation de parametre
	 * Elle permet de gerer les versions utilises, ainsi que leur fonction
	 * @param String Nom du parametre
	 * @return String 
	 */
	protected function getValue($value) {
		$ver = _PS_VERSION_;
		$explode = explode('.', $ver);
		// Si c'est une version inferieur ou egale a  1.3 ou que c'est une version anterieur a 1.4.3
		if ($explode[1] >= 3 || $explode[1] == 4 && $explode[2] > 3) {
			if (isset($_POST[$value]))
				return $_POST[$value];
			else if (isset($_GET[$value]))
				return $_GET[$value];
			else
				return false;
		}
		else // Si c'est une version actuelle
			return Tools::getValue($value);
	}

	/**
	 * Enregistrement Newsletter
	 * @param mail $customerEmail
	 * @return int
	 */
	protected function isNewsletterRegistered($customerEmail) {
		// If ps 1.5
		if (!version_compare(_PS_VERSION_, '1.5', '>')) {
			$SQL = 'SELECT `email` FROM '._DB_PREFIX_.'newsletter WHERE `email` = "'.pSQL($customerEmail).'"';
			$SQL2 = 'SELECT `newsletter` FROM '._DB_PREFIX_.'customer WHERE `email` = \''.pSQL($customerEmail).'\'';
		} else {
			$SQL = 'SELECT `email` FROM '._DB_PREFIX_.'newsletter WHERE `email` = "'.pSQL($customerEmail).'" AND `id_shop` = "'.(int) Shop::getContextShopID().'" AND `id_shop_group` = "'.(int) Shop::getContextShopGroupID().'" ';
			$SQL2 = 'SELECT `newsletter` FROM '._DB_PREFIX_.'customer WHERE `email` = \''.pSQL($customerEmail).'\' AND `id_shop` = "'.(int) Shop::getContextShopID().'" AND `id_shop_group` = "'.(int) Shop::getContextShopGroupID().'" ';
		}
		if (Db::getInstance()->getRow($SQL))
			return 1;
		if (!$registered = Db::getInstance()->getRow($SQL2))
			return -1;
		if ($registered['newsletter'] == '1')
			return 2;
		return 0;
	}

	/**
	 * Enregistrement
	 * @version 1.1
	 * @global array $cookie
	 * @return type
	 */
	protected function newsletterRegistration() {
		// Si le formulaire a  bien ete rempli
		if (!$this->getValue('TOTemail') OR !Validate::isEmail($this->getValue('TOTemail')))
			return $this->error = $this->l('Invalid e-mail address');
		// Inscription
		else {
			// Valeur d'enregistrement
			$registerStatus = $this->isNewsletterRegistered(pSQL($this->getValue('TOTemail')));
			// Si le mail n'a pas ete encore enregistre
			if ($registerStatus > 0)
				return $this->error = $this->l('E-mail address already registered');
			// Si l'utilisateur n'a pas de compte client
			elseif ($registerStatus == -1) {
				// Recuperation de variable globale
				if (!version_compare(_PS_VERSION_, '1.5', '>')) {
					global $cookie;
				} else {
					$cookie = $this->context->cookie;
				}
				// Insertion dans la base de donnees
				// IF PS 1.5
				if (!version_compare(_PS_VERSION_, '1.5', '>')) {
					$MySQLQuery = 'INSERT INTO `'._DB_PREFIX_.'newsletter` (`email`, `newsletter_date_add`, `ip_registration_newsletter`, `http_referer`) VALUES (\''.pSQL($this->getValue('TOTemail')).'\', NOW(), \''.pSQL(Tools::getRemoteAddr()).'\',
					(SELECT c.`http_referer` FROM `'._DB_PREFIX_.'connections` c WHERE c.`id_guest` = '.(int) ($cookie->id_guest).' ORDER BY c.`date_add` DESC LIMIT 1))';
				} else {
					$MySQLQuery = 'INSERT INTO `'._DB_PREFIX_.'newsletter` (`email`, `newsletter_date_add`, `ip_registration_newsletter`, `http_referer`, `id_shop`, `id_shop_group`, `active`) VALUES (\''.pSQL($this->getValue('TOTemail')).'\', NOW(), \''.pSQL(Tools::getRemoteAddr()).'\',
					(SELECT c.`http_referer` FROM `'._DB_PREFIX_.'connections` c WHERE c.`id_guest` = '.(int) ($cookie->id_guest).' ORDER BY c.`date_add` DESC LIMIT 1),"'.(int) Shop::getContextShopID().'", "'.(int) Shop::getContextShopGroupID().'", "1")';
				}
				if (!Db::getInstance()->Execute($MySQLQuery))
					return $this->error = $this->l('Error during subscription');
				$this->sendVoucher(pSQL($this->getValue('TOTemail')));
				// Inscription terminee
				return $this->valid = $this->l('Subscription successful');
			}
			// Si l'utilisateur a un compte client
			elseif ($registerStatus == 0) {
				// IF PS 1.5
				if (!version_compare(_PS_VERSION_, '1.5', '>')) {
					$SQL = 'UPDATE '._DB_PREFIX_.'customer SET `newsletter` = 1, `newsletter_date_add` = NOW(), `ip_registration_newsletter` = \''.pSQL(Tools::getRemoteAddr()).'\' WHERE `email` = \''.pSQL($this->getValue('TOTemail')).'\'';
				} else {
					$SQL = 'UPDATE '._DB_PREFIX_.'customer SET `newsletter` = 1, `newsletter_date_add` = NOW(), `ip_registration_newsletter` = \''.pSQL(Tools::getRemoteAddr()).'\' WHERE `email` = \''.pSQL($this->getValue('TOTemail')).'\' AND `id_shop` = "'.(int) Shop::getContextShopID().'" AND `id_shop_group` = "'.(int) Shop::getContextShopGroupID().'"';
				}
				// Mise a  jour dans la base de donnees
				if (!Db::getInstance()->Execute($SQL))
					return $this->error = $this->l('Error during subscription');
				$this->sendVoucher(pSQL($this->getValue('TOTemail')));
				// Mise a  jour terminee
				return $this->valid = $this->l('Subscription successful');
			}
		}
	}

	/**
	 * Sending Voucher
	 * @version 1.1
	 * @global Object Cookie
	 * @param string Email
	 * @return boolean 
	 */
	protected function sendVoucher($email) {
		if (!version_compare(_PS_VERSION_, '1.5', '>')) {
			global $cookie;
		} else {
			$cookie = $this->context->cookie;
		}

		if ($discount = Configuration::get('NW_VOUCHER_CODE'))
			return Mail::Send((int) $cookie->id_lang, 'newsletter_voucher', Mail::l('Newsletter voucher', (int) $cookie->id_lang), array('{discount}' => $discount), $email, NULL, NULL, NULL, NULL, NULL, '/home/ddauteuil/www/mgd/modules/totsplashscreen'.'/mails/');
		return false;
	}

	/**
	 *  Home page
	 */
	function hookTop($params) {
		if (!version_compare(_PS_VERSION_, '1.5', '>')) {
			global $cookie, $smarty;
			$url = '/';
			$domain = Tools::getShopDomain(true);
			$smarty->assign('url', $url);
		} else {
			$cookie = $this->context->cookie;
			$smarty = $this->smarty;
			$domain = Tools::getShopDomain(true);
			$url = '/';
			$this->context->smarty->assign('url', $url);
		}

		$totsplashscreenCookie = $_COOKIE;
		//Recuperation du splash screen si il en existe un pour cette page
		$page_name = $smarty->getTemplateVars('page_name');
		if ($page_name == 'index') {
			$page_name = 'home';
			$id_type = 0;
		} else if ($page_name == 'order') {
			$page_name = 'cart';
			$id_type = 0;
		} else if ($page_name == 'product')
			$id_type = Tools::getValue('id_product');
		else if ($page_name == 'category')
			$id_type = Tools::getValue('id_category');
		else if ($page_name == 'cms')
			$id_type = Tools::getValue('id_cms');
		else
			$id_type = 0;

		$totsplashscreen = TotSplashScreenModel::findSplashScreen($page_name, $id_type);
		$template = new TotSplashScreenTemplateModel($totsplashscreen['id_template'], $cookie->id_lang);

		// Recuperation des variables de configuration
		$results = array(
			'totsplashscreen_delai' => $totsplashscreen['nb_jour_avant_reapparition'],
			'totsplashscreen_version_cookie' => $totsplashscreen['totsplashscreen_version_cookie'],
			'totsplashscreen_fan_page' => ($template->link_fb != '' ? 'on' : ''),
			'totsplashscreen_fan_page_url' => $template->link_fb,
			'totsplashscreen_newsletter' => ($template->newsletter == 1 ? 'on' : ''),
			'totsplashscreen_count_page' => ($totsplashscreen['type'] == 'none' ? $totsplashscreen['id_type'] : 0),
			'totsplashscreen_text_before' => $template->message,
			'totsplashscreen_backgroundColor' => $template->backgroundColor,
			'totsplashscreen_width' => $template->width,
			'totsplashscreen_height' => $template->height,
			'totsplashscreen_opacity' => $template->opacity,
			'totsplashscreen_opacityIE' => $template->opacity * 100,
			'totsplashscreen_permission_mode' => $template->permission_mode,
			'totsplashscreen_permission_redirect' => $template->permission_redirect,
			'id_totsplashscreen' => $totsplashscreen['id_totsplashscreen'],
			'image_leave' => $template->image_leave != '' ? Tools::getShopDomain(true)._MODULE_DIR_.'/totsplashscreen/upload/'.$template->image_leave : '',
			'image_enter' => $template->image_enter != '' ? Tools::getShopDomain(true)._MODULE_DIR_.'/totsplashscreen/upload/'.$template->image_enter : '',
		);

		$this->_postNewsletterProcess();
		// Si le cookie n'existe pas, ou qu'il existe et qu'il est inferieur a  la version actuelle
		if (isset($totsplashscreen['id_totsplashscreen'])) {
			if (!isset($totsplashscreenCookie['totSplashScreen'.$totsplashscreen['id_totsplashscreen']]) || (isset($totsplashscreenCookie['totSplashScreen'.$totsplashscreen['id_totsplashscreen']]) && $totsplashscreenCookie['totSplashScreen'.$totsplashscreen['id_totsplashscreen']] < $results['totsplashscreen_version_cookie'])) {
				if ((isset($totsplashscreenCookie['totsplashscreen_count_page'.$totsplashscreen['id_totsplashscreen']]) && ($totsplashscreenCookie['totsplashscreen_count_page'.$totsplashscreen['id_totsplashscreen']] - 1) == 0) || $results['totsplashscreen_count_page'] == '0') {
					if ($results['totsplashscreen_count_page'] == '0' && $totsplashscreen['type'] == 'none')
						setcookie('totsplashscreen_count_page'.$totsplashscreen['id_totsplashscreen'], '0', (time() + (3600 * 24 * $results['totsplashscreen_delai'])), $url);

					setcookie('totSplashScreen'.$totsplashscreen['id_totsplashscreen'], $results['totsplashscreen_version_cookie'], (time() + (3600 * 24 * $results['totsplashscreen_delai'])), $url);

					if (Module::isInstalled('blocknewsletter'))
						$install = 'on';
					else
						$install = '';

					// Variables assignees a  smarty
					$smarty->assign('install', $install);
					$smarty->assign('totSplashScreen', $results);
					return $this->display(__FILE__, 'views/templates/hook/totsplashscreen.tpl');
				}
				else {
					$delay = (time() + (3600 * 24 * $results['totsplashscreen_delai']));
					if (!isset($totsplashscreenCookie['totsplashscreen_count_page'.$totsplashscreen['id_totsplashscreen']]) || isset($totsplashscreenCookie['totsplashscreen_count_page'.$totsplashscreen['id_totsplashscreen']]) && !is_numeric($totsplashscreenCookie['totsplashscreen_count_page'.$totsplashscreen['id_totsplashscreen']])) {
						setcookie('totsplashscreen_count_page'.$totsplashscreen['id_totsplashscreen'], (int) $results['totsplashscreen_count_page'], $delay, $url);
					} else if (isset($totsplashscreenCookie['totsplashscreen_count_page'.$totsplashscreen['id_totsplashscreen']])) {
						setcookie('totsplashscreen_count_page'.$totsplashscreen['id_totsplashscreen'], (int) ($totsplashscreenCookie['totsplashscreen_count_page'.$totsplashscreen['id_totsplashscreen']] - 1), $delay, $url);
					}
				}
			}
		}
	}

	/**
	 * Insertion dans le header du css
	 */
	public function hookHeader() {
		// If PS 1.5
		if (!version_compare(_PS_VERSION_, '1.5', '>')) {
			Tools::addCSS($this->_path.strtolower($this->name).".css", "all");
		} else {
			$this->context->controller->addCSS($this->_path.strtolower($this->name).".css", "all");
		}
	}

	/**
	 * Panel admin
	 */
	public function getContent() {
		global $smarty;
		//Add global variable 

		$temp = $this->displayBanner();

		if (version_compare(_PS_VERSION_, '1.4.9', '<')) {
			$smarty->template_dir = '/home/ddauteuil/www/mgd/modules/totsplashscreen'.'/views/templates/back';
		}

		$admin_dir = dirname($_SERVER['PHP_SELF']);
		$lang = new Language(Configuration::get('PS_LANG_DEFAULT'));
		$smarty->assign(array(
			'url' => $this->link,
			'_THEME_CSS_DIR_' => _THEME_CSS_DIR_,
			'path' => $this->_path,
			'iso' => $lang->iso_code,
			'admin_link' => $admin_dir,
			'default_lang' => Configuration::get('PS_LANG_DEFAULT'),
		));

		$this->_postProcess();
		$this->_getProcess();
		if (Tools::getValue('newTotsplashscreenTemplate') || Tools::getValue('editTotsplashscreenTemplate')) {//Affichage du formulaire d'ajout de template
			$temp .= $this->displayFormTemplate(Tools::getValue('editTotsplashscreenTemplate'));
		} else if (Tools::getValue('newTotsplashscreen') || Tools::getValue('editTotsplashscreen')) {
			$temp .= $this->displayFormTotSplashScreen(Tools::getValue('editTotsplashscreen'));
		} else {
			if (version_compare(_PS_VERSION_, '1.4.9', '<')) {
				$temp .= '<link rel="stylesheet" type="text/css" href="'.$this->_path.'totsplashscreen.css" />';
			}
			$temp .= $this->displayHome();
		}

		return $temp;
	}

	protected function displayBanner() {

		$translations = array(
			'by' => $this->l('By'),
			'web' => $this->l('Web agency specialized in ecommerce web sites'),
			'addons' => $this->l('Our modules on addons'),
			'blog' => $this->l('News & advice on our blog')
		);

		$module = array(
			'description' => $this->description,
			'name' => $this->name,
			'displayName' => $this->displayName,
			'_path' => $this->_path
		);

		if (version_compare(_PS_VERSION_, '1.5', '>')) {
			$smarty = $this->context->smarty;
			$lang = $this->context->language;
		} else {
			global $smarty, $cookie;
			$lang = new Language($cookie->id_lang);
		}

		$datas = array(
			'module' => $module,
			'translations' => $translations,
			'lang' => $lang
		);

		$smarty->assign($datas);

		return $this->display(__FILE__, '/views/templates/hook/banner.tpl');
	}

	/** DISPLAY * */
	public function displayHome() {
		global $smarty, $cookie;

		$smarty->assign(array(
			'totsplashscreens' => TotSplashScreenModel::getSplashScreens(),
			'tottemplates' => TotSplashScreenTemplateModel::getTemplates(),
			'iso' => Language::getIsoById((int) ($cookie->id_lang))
		));

		return $this->display(__FILE__, 'views/templates/back/home.tpl');
	}

	public function displayFormTotSplashScreen($idTotSplashScreen) {
		global $smarty;

		if ($idTotSplashScreen > 0) {
			$splashscreen = new TotSplashScreenModel($idTotSplashScreen);
			if ($splashscreen->type == 'product')
				$product = new Product($splashscreen->id_type, false, Configuration::get('PS_LANG_DEFAULT'));
			else
				$product = NULL;


			$splashscreen->date_start = substr($splashscreen->date_start, 0, 10);
			$splashscreen->date_end = substr($splashscreen->date_end, 0, 10);

			$smarty->assign(array(
				'product' => $product,
				'splashscreen' => $splashscreen
			));
		}

		$rootCategory = Category::getRootCategory();
		$arborescence = $rootCategory->recurseLiteCategTree(1000); // Arborescence
		$categories = $this->displayCategorySelect($arborescence['children'], '');
		$templates = TotSplashScreenTemplateModel::getTemplates();
		$lang = new Language(Configuration::get('PS_LANG_DEFAULT'));
		$types = array(
			'none' => $this->l('All pages'),
			'home' => $this->l('Home'),
			'cart' => $this->l('Cart'),
			'category' => $this->l('Category'),
			'product' => $this->l('Product'),
			'cms' => $this->l('cms'),
		);
		$cmss = CMS::listCMS();


		$smarty->assign(array(
			'cmss' => $cmss,
			'types' => $types,
			'categories' => $categories,
			'templates' => $templates,
			'_PS_VERSION_' => _PS_VERSION_,
			'iso' => $lang->iso_code
		));



		return $this->display(__FILE__, 'views/templates/back/formsplashscreen.tpl');
	}

	public function displayFormTemplate($idTemplate) {
		if (!version_compare(_PS_VERSION_, '1.5', '>')) {
			global $cookie, $smarty;
			$url = '/';
		} else {
			$cookie = $this->context->cookie;
			$smarty = $this->context->smarty;
			if (Shop::getContext() == SHOP::CONTEXT_SHOP) {
				$shop = new Shop(Shop::getContextShopID());
				$url = $shop->getBaseURL();
			} else {
				$shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
				$url = $shop->getBaseURL();
			}
		}

		// Recuperation de la variable ISO de la langue
		$iso = Language::getIsoById((int) ($cookie->id_lang));
		// On y insert TinyMCE
		$isoTinyMCE = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en');
		// Dossier en cours
		$ad = dirname($_SERVER["PHP_SELF"]);
		if (!Module::isInstalled('blocknewsletter')) {
			$txt = '<a target="_blank" href="index.php?tab=AdminModules&token='.$this->getValue('token').'&tab_module=front_office_features&module_name=blocknewsletter"><span style="color:red";>'.$this->l('not installed, newsletter subscription will not display').'</span></a>';
		} else { // Sinon on explique qu'il est installe
			$txt = '<span style="color:green";>'.$this->l('is installed').'</span>';
		}

		$country = new Country(Configuration::get('PS_COUNTRY_DEFAULT'));
		$defaultLanguage = (int) (Configuration::get('PS_LANG_DEFAULT'));

		$smarty->assign(array(
			'defaultLanguage' => $defaultLanguage,
			'isoTinyMCE' => $isoTinyMCE,
			'ad' => $ad,
			'_THEME_CSS_DIR_' => _THEME_CSS_DIR_,
			'txt' => $txt,
			'languages' => Language::getLanguages(),
			'iso' => $iso,
		));

		if ($idTemplate > 0) {
			$template = new TotSplashScreenTemplateModel($idTemplate);

			$results = Configuration::getMultiple(array('totsplashscreen_delai', 'totsplashscreen_version_cookie', 'totsplashscreen_fan_page', 'totsplashscreen_fan_page_url', 'totsplashscreen_newsletter', 'totsplashscreen_count_page'));
			// Recuperation des configurations
			$results = Configuration::getMultiple(array('totsplashscreen_delai', 'totsplashscreen_version_cookie', 'totsplashscreen_fan_page', 'totsplashscreen_fan_page_url', 'totsplashscreen_newsletter', 'totsplashscreen_count_page'));
			// Langue par defaut
			$smarty->assign(array(
				'path' => $this->_path,
				'checked' => ($template->link_fb == '') ? '' : 'checked ',
				'style' => ($template->link_fb == '') ? 'style="display:none;"' : '',
				'txt' => $txt,
				'checked_newsletter' => ($template->newsletter ? 'checked' : ''),
				'url_newsletter' => 'index.php?tab=AdminModules&token='.$this->getValue('token').'&tab_module=front_office_features&module_name=blocknewsletter',
				'url_translation' => 'index.php?tab=AdminTranslations&token='.Tools::getAdminTokenLite('AdminTranslations').'&type=modules&lang='.$iso.'#totsplashscreen',
				'url_202' => 'http://www.202-ecommerce.com/'.$this->name.'?sourceid=mod&lang='.(int) $cookie->id_lang,
				'create' => false,
				'thismodule' => $this,
				'totsplashscreen_fan_page' => ($template->link_fb == '') ? '' : $template->link_fb,
				'totsplashscreen_fan_page_url' => $template->link_fb,
				'name' => $template->name,
				'message' => $template->message,
				'width' => $template->width,
				'height' => $template->height,
				'backgroundColor' => $template->backgroundColor,
				'opacity' => $template->opacity,
				'permission_mode' => $template->permission_mode,
				'permission_redirect' => $template->permission_redirect,
				'id' => $idTemplate
			));
		} else {
			$smarty->assign(array(
				'name' => '',
				'checked' => '',
				'checked_newsletter' => '',
				'style' => 'style="display:none"',
				'totsplashscreen_fan_page' => '',
				'totsplashscreen_fan_page_url' => '',
				'totsplashscreen_newsletter' => '',
				'totsplashscreen_count_page' => '',
				'width' => '260',
				'height' => '',
				'backgroundColor' => '#FFFFFF',
				'opacity' => '80',
				'permission_mode' => '',
				'permission_redirect' => '',
				'create' => true,
				'thismodule' => $this
			));
		}
		return $this->display(__FILE__, 'views/templates/back/formsplashscreentemplate.tpl');
	}

	/** POST PROCESS * */
	public function _postProcess() {
		if (Tools::getValue('newTemplateForm')) {
			$this->_postProcessTemplate();
		}
		if (Tools::getValue('idtemplate')) {
			$this->_postProcessTemplate(Tools::getValue('idtemplate'));
		}
		if (Tools::getValue('idSplashScreen')) {
			$this->_postProcessSplashScreen(Tools::getValue('idSplashScreen'));
		}
		if (Tools::getValue('newSplashScreen')) {
			$this->_postProcessSplashScreen();
		}
	}

	public function _postNewsletterProcess() {
		if (!version_compare(_PS_VERSION_, '1.5', '>')) {
			global $cookie, $smarty;
			$url = '/';
			$smarty->assign('url', $url);
		} else {
			$cookie = $this->context->cookie;
			$smarty = $this->smarty;
			$url = Tools::getShopDomain(true);
			$this->context->smarty->assign('url', $url);
		}

		// Si le formulaire a  ete envoye
		if (Tools::isSubmit('TOTsubmitNewsletter')) {
			// Enregistrement a  la newsletter
			$this->newsletterRegistration();
			// Si ca s'est mal passe on retourne l'erreur
			if (isset($this->error)) {
				// On assign a  smarty
				$smarty->assign(array('color' => 'red',
					'totSplashMsg' => $this->error,
					'nw_value' => $this->getValue('TOTemail') ? pSQL($this->getValue('TOTemail')) : false,
					'nw_error' => true,
					'action' => $this->getValue('TOTaction')));
			}
			// Si ca s'est bien passe on indique que c'est bon
			elseif (isset($this->valid)) {
				// Si la configuration de PS doit envoyer un mail, on utilise
				if (Configuration::get('NW_CONFIRMATION_EMAIL') AND $this->getValue('TOTaction') AND (int) ($this->getValue('TOTaction') == 0))
					Mail::Send((int) ($params['cookie']->id_lang), 'newsletter_conf', Mail::l('Newsletter confirmation'), array(), pSQL($this->getValue('TOTemail')), NULL, NULL, NULL, NULL, NULL, '/home/ddauteuil/www/mgd/modules/totsplashscreen'.'/mails/');
				// On assigne a  smarty les valeurs
				$smarty->assign(array('color' => 'green',
					'totSplashMsg' => $this->valid,
					'nw_error' => false));
			}
		}
	}

	public function _postProcessSplashScreen($idSplashScreen = 0) {
		if ($idSplashScreen == 0)
			$splashscreen = new TotSplashScreenModel();
		else
			$splashscreen = new TotSplashScreenModel($idSplashScreen);

		$splashscreen->name = Tools::getValue('name');
		$splashscreen->date_start = (Tools::getValue('date_start') == '') ? '0000-00-00' : Tools::getValue('date_start').'';
		$splashscreen->date_end = (Tools::getValue('date_end') == '') ? '0000-00-00' : Tools::getValue('date_end').'';
		$splashscreen->nb_jour_avant_reapparition = Tools::getValue('nb_jour_avant_reapparition');
		$splashscreen->type = Tools::getValue('type');
		$splashscreen->id_template = Tools::getValue('template');
		if ($splashscreen->type == 'product')
			$splashscreen->id_type = Tools::getValue('addProductAutoComplete');
		else if ($splashscreen->type == 'category')
			$splashscreen->id_type = Tools::getValue('category');
		else if ($splashscreen->type == 'cms')
			$splashscreen->id_type = Tools::getValue('cms');
		else if ($splashscreen->type == 'none')
			$splashscreen->id_type = Tools::getValue('navigation');
		else
			$splashscreen->id_type = 0;

		$splashscreen->save();
	}

	public function _postProcessTemplate($idTemplate = false) {

		$template = new TotSplashScreenTemplateModel($idTemplate);
		

		$template->width = Tools::getValue('width');
		$template->height = Tools::getValue('height');
		$template->name = Tools::getValue('name');
		$template->link_fb = (Tools::getValue('fan_page') == 'on' ? Tools::getValue('fan_page_url') : '');
		$template->newsletter = (Tools::getValue('newsletter') == 'on' ? 1 : 0);
		$template->backgroundColor = Tools::getValue('backgroundColor');
		$template->opacity = Tools::getValue('opacity');
		$template->permission_mode = Tools::getValue('permission_mode');

		$template->permission_redirect = Tools::getValue('permission_redirect');
		$template->message = Tools::getValue('message');
		
		if (Tools::getValue('permission_mode')) {
			if ($_FILES['image_enter']['name'] != '')
				$template->image_enter = $this->uploadImage($_FILES['image_enter']);
			if ($_FILES['image_leave']['name'] != '')
				$template->image_leave = $this->uploadImage($_FILES['image_leave']);
		}
		else {
			$template->image_enter = '';
			$template->image_leave = '';
		}
		$template->save();
	}

	/** GET PROCESS * */
	public function _getProcess() {
		if (Tools::getValue('deleteTemplate')) {
			$template = new TotSplashScreenTemplateModel(Tools::getValue('deleteTemplate'));
			$template->delete();
		}
		if (Tools::getValue('deleteSplashScreen')) {
			$splashscreen = new TotSplashScreenModel(Tools::getValue('deleteSplashScreen'));
			$splashscreen->delete();
		}
		if (Tools::getValue('cleancookie')) {
			$splashscreen = new TotSplashScreenModel(Tools::getValue('cleancookie'));
			$splashscreen->totsplashscreen_version_cookie++;
			$splashscreen->save();
		}
		if (Tools::getValue('cleanmycookie')) {
			if (isset($_COOKIE['totsplashscreen_count_page'.Tools::getValue('cleanmycookie')]))
				setcookie('totsplashscreen_count_page'.Tools::getValue('cleanmycookie'), $_COOKIE['totsplashscreen_count_page'.Tools::getValue('cleanmycookie')], time() - 3600, '/');
			if (isset($_COOKIE['totSplashScreen'.Tools::getValue('cleanmycookie')]))
				setcookie('totSplashScreen'.Tools::getValue('cleanmycookie'), $_COOKIE['totSplashScreen'.Tools::getValue('cleanmycookie')], time() - 3600, '/');
		}

		if (Tools::getValue('viewTotsplashscreenTemplate')) {
			global $smarty;

			$template = new TotSplashScreenTemplateModel(Tools::getValue('viewTotsplashscreenTemplate'), Configuration::get('PS_LANG_DEFAULT'));

			// Recuperation des variables de configuration
			$results = array(
				'totsplashscreen_delai' => 0,
				'totsplashscreen_version_cookie' => 0,
				'totsplashscreen_fan_page' => ($template->link_fb != '' ? 'on' : ''),
				'totsplashscreen_fan_page_url' => $template->link_fb,
				'totsplashscreen_newsletter' => ($template->newsletter == 1 ? 'on' : ''),
				'totsplashscreen_count_page' => 0,
				'totsplashscreen_text_before' => $template->message,
				'totsplashscreen_backgroundColor' => $template->backgroundColor,
				'totsplashscreen_width' => $template->width,
				'totsplashscreen_height' => $template->height,
				'totsplashscreen_opacity' => $template->opacity,
				'totsplashscreen_opacityIE' => $template->opacity * 100,
				'totsplashscreen_permission_mode' => $template->permission_mode,
				'totsplashscreen_permission_redirect' => $template->permission_redirect,
				'id_totsplashscreen' => 0,
				'image_leave' => $template->image_leave != '' ? Tools::getShopDomain(true)._MODULE_DIR_.'/totsplashscreen/upload/'.$template->image_leave : '',
				'image_enter' => $template->image_enter != '' ? Tools::getShopDomain(true)._MODULE_DIR_.'/totsplashscreen/upload/'.$template->image_enter : '',
				'preview' => $template->id > 0 ? true : false,
			);

			if (Module::isInstalled('blocknewsletter'))
				$install = 'on';
			else
				$install = '';

			// Variables assignees a  smarty
			$smarty->assign('install', $install);
			$smarty->assign('totSplashScreen', $results);
			$this->hookHeader();
		}
	}

	protected function displayCategorySelect($child, $prefix) {
		$cats = array();
		$lang = (int) Configuration::get('PS_LANG_DEFAULT');
		foreach ($child as $category) {
			if (is_array($category['name'])) {
				$catname = $category['name'][$lang];
			} else {
				$catname = $category['name'];
			}

			$cats[] = array(
				'id' => $category['id'],
				'name' => $prefix.' '.$catname
			);

			if (count($category['children']) >= 1) {
				$cats = array_merge($cats, $this->displayCategorySelect($category['children'], $prefix.'---'));
			}
		}
		return $cats;
	}

	/**
	 * Upload icon
	 * @version 1.02
	 * @return boolean 
	 */
	protected function uploadImage($file) {
		if (!empty($file)) {
			// temp confirm
			if (!is_uploaded_file($file['tmp_name']))
				return false;
			// Type
			$type = substr(strrchr($file['name'], '.'), 1);
			// Name 
			$name = $this->deleteAccent($file['name']);

			// Path to img
			$path = '/home/ddauteuil/www/mgd/modules/totsplashscreen'.'/upload/'.$name;

			// Upload
			if (!move_uploaded_file($file['tmp_name'], $path)) {
				return false;
			}
			return $name;
		}
		else
			return false;
	}

	/**
	 * Remove accent
	 * @version 1.0
	 * @param String String changed
	 * @return String 
	 */
	protected function deleteAccent($str) {
		return preg_replace("/[^a-zA-Z0-9]/", "", $str);
	}

}

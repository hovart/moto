<?php
/**
 * gsnippetsreviews.php file defines main class of module
 *
 * @author    Business Tech SARL <http://www.businesstech.fr/en/contact-us>
 * @copyright 2003-2017 Business Tech SARL
 * @version   4.3.3
 * @uses      Please read included installation and configuration instructions (PDF format)
 * @see       lib/install
 *              => i-install.php => interface
 *              => install-ctrl_class.php => controller, manage factory with config or sql install object
 *              => install.config classes => manage install / uninstall of config values (register hook)
 *            lib/admin
 *              => i-admin.php => interface
 *              => admin-ctrl_class.php => controller, manage factory with configure or update admin object
 *              => display and update admin classes => manage display of admin form and make action of updating config like (add, edit, delete, update, ... see PHP Doc in class)
 *            lib/hook
 *              => hook-base_class.php => abstract
 *              => hook-ctrl_class.php => controller, manage strategy with hook object. Like this, you can add hook easily with declare a new file class
 *              => hook-home_class.php => manage displaying content on your home page
 *            lib/module-dao_class.php
 *              D A O = Data Access Object => manage all sql queries
 *            lib/module-tools_class.php
 *              declare all transverse functions which are unclassifiable in specific class
 *            lib/warnings_class.php
 *              manage all displaying warnings when module isn't already configured after installation
 * @date      27/07/2017
 */

if (!defined('_PS_VERSION_')) {
	exit(1);
}

class GSnippetsReviews extends Module
{
	/**
	 * @var array $aConfiguration : array of set configuration
	 */
	public static $aConfiguration = array();

	/**
	 * @var int $iCurrentLang : store id of default lang
	 */
	public static $iCurrentLang = null;

	/**
	 * @var int $sCurrentLang : store iso of default lang
	 */
	public static $sCurrentLang = null;

	/**
	 * @var obj $oCookie : store cookie obj
	 */
	public static $oCookie = null;

	/**
	 * @var obj $oModule : obj module itself
	 */
	public static $oModule = array();

	/**
	 * @var string $sQueryMode : query mode - detect XHR
	 */
	public static $sQueryMode = null;

	/**
	 * @var string $sBASE_URI : base of URI in prestashop
	 */
	public static $sBASE_URI = null;

	/**
	 * @var array $aErrors : array get error
	 */
	public $aErrors = null;

	/**
	 * @var int $iShopId : shop id used for 1.5 and for multi shop
	 */
	public static $iShopId = 1;

	/**
	 * @var bool $bCompare16 : get compare version for PS 1.6
	 */
	public static $bCompare16 = false;

	/**
	 * @var bool $bCompare1611 : get compare version for PS 1.6.1.1
	 */
	public static $bCompare1611 = false;

	/**
	 * @var bool $bCompare17 : get compare version for PS 1.7
	 */
	public static $bCompare17 = false;

	/**
	 * @var bool $bCompare1710 : get compare version for PS 1.7.1.0
	 */
	public static $bCompare1710 = false;

	/**
	 * Magic Method __construct assigns few information about module and instantiate parent class
	 */
	public function __construct()
	{
		require_once(dirname(__FILE__) . '/conf/common.conf.php');
		require_once(_GSR_PATH_LIB . 'module-tools_class.php');

		// get shop id
		self::$iShopId = Context::getContext()->shop->id;
		// get current  lang id
		self::$iCurrentLang = Context::getContext()->cookie->id_lang;
		// get current lang iso
		self::$sCurrentLang = BT_GsrModuleTools::getLangIso();
		// get cookie obj
		self::$oCookie = Context::getContext()->cookie;

		$this->name = 'gsnippetsreviews';
		$this->module_key = '4d3d6e69f63e45e3ada7c5d9f8b1b33b';
		$this->tab = 'seo';
		$this->version = '4.3.3';
		$this->author = 'BusinessTech';
		$this->need_instance = 1;
		$this->bootstrap = true;
		$this->controllers = array('account','form','review','reviews');

		parent::__construct();

		$this->displayName      = $this->l('Customer Ratings and Reviews Pro + Google Rich Snippets');
		$this->description      = $this->l('2 in 1 module: Full product and review system + Google Rich Snippets for enhanced Google listings');
		$this->confirmUninstall = $this->l('Are you sure you want to remove it ? Your Customer Ratings and Reviews Pro + Google Rich Snippets will no longer work. Be careful, all your configuration and your data will be lost');

		// check versions
		self::$bCompare16 = version_compare(_PS_VERSION_, '1.6', '>=');
		self::$bCompare1611 = version_compare(_PS_VERSION_, '1.6.1.1', '>=');
		self::$bCompare17 = version_compare(_PS_VERSION_, '1.7', '>=');
		self::$bCompare1710 = version_compare(_PS_VERSION_, '1.7.1.0', '>=');

		// stock itself obj
		self::$oModule = $this;

		// update module version
		$GLOBALS[_GSR_MODULE_NAME . '_CONFIGURATION'][_GSR_MODULE_NAME . '_MODULE_VERSION'] = $this->version;

		// set base of URI
		self::$sBASE_URI = $this->_path;

		// get configuration options
		BT_GsrModuleTools::getConfiguration();

		// get call mode - Ajax or dynamic - used for clean headers and footer in ajax request
		self::$sQueryMode = Tools::getValue('sMode');
	}

	/**
	 * install() method installs all mandatory structure (DB or Files) => sql queries and update values and hooks registered
	 *
	 * @return bool
	 */
	public function install()
	{
		require_once(_GSR_PATH_CONF . 'install.conf.php');
		require_once(_GSR_PATH_LIB_INSTALL . 'install-ctrl_class.php');

		// set return
		$bReturn = true;

		if (!parent::install()
			|| !BT_InstallCtrl::run('install', 'sql', _GSR_PATH_SQL . _GSR_INSTALL_SQL_FILE)
			|| !BT_InstallCtrl::run('install', 'config')
			|| !BT_InstallCtrl::run('install', 'tab')
		) {
			$bReturn = false;
		}
		
		return $bReturn;
	}

	/**
	 * uninstall() method uninstalls all mandatory structure (DB or Files)
	 *
	 * @return bool
	 */
	public function uninstall()
	{
		require_once(_GSR_PATH_CONF . 'install.conf.php');
		require_once(_GSR_PATH_LIB_INSTALL . 'install-ctrl_class.php');
		
		// set return
		$bReturn = true;

		if (!parent::uninstall()
			|| !BT_InstallCtrl::run('uninstall', 'sql', _GSR_PATH_SQL . _GSR_UNINSTALL_SQL_FILE)
			|| !BT_InstallCtrl::run('uninstall', 'config')
			|| !BT_InstallCtrl::run('uninstall', 'tab')
		) {
			$bReturn = false;
		}

		return $bReturn;
	}

	/**
	 * getContent() method manages all data in Back Office
	 *
	 * @return string
	 */
	public function getContent()
	{
		require_once(_GSR_PATH_CONF . 'admin.conf.php');
		require_once(_GSR_PATH_LIB . 'warning_class.php');
		require_once(_GSR_PATH_LIB_ADMIN . 'base-ctrl_class.php');

		try {
			// get controller type
			$sControllerType = (!Tools::getIsset(_GSR_PARAM_CTRL_NAME) || (Tools::getIsset(_GSR_PARAM_CTRL_NAME) && 'admin' == Tools::getValue(_GSR_PARAM_CTRL_NAME)))? (Tools::getIsset(_GSR_PARAM_CTRL_NAME)? Tools::getValue(_GSR_PARAM_CTRL_NAME) : 'admin') : Tools::getValue(_GSR_PARAM_CTRL_NAME);

			// check warnings
			BT_GsrWarning::create()->run('module', 'productcomments', array(), true);

			// instantiate matched controller object
			$oCtrl = BT_GsrBaseCtrl::get($sControllerType);

			// execute good action in admin
			// only displayed with key : tpl and assign in order to display good smarty template
			$aDisplay = $oCtrl->run(array_merge($_GET, $_POST));

			// free memory
			unset($oCtrl);

			if (!empty($aDisplay)) {
				$aDisplay['assign'] = array_merge($aDisplay['assign'], array('oJsTranslatedMsg' => BT_GsrModuleTools::jsonEncode($GLOBALS[_GSR_MODULE_NAME . '_JS_MSG']), 'bAddJsCss' => true));

				// get content
				$sContent = $this->displayModule($aDisplay['tpl'], $aDisplay['assign']);

				if (!empty(self::$sQueryMode)) {
					echo $sContent;
				}
				else {
					return $sContent;
				}
			}
			else {
				throw new Exception('action returns empty content', 110);
			}
		}
		catch (Exception $e) {
			$this->aErrors[] = array('msg' => $e->getMessage(), 'code' => $e->getCode());

			// get content
			$sContent = $this->displayErrorModule();

			if (!empty(self::$sQueryMode)) {
				echo $sContent;
			}
			else {
				return $sContent;
			}
		}
		// exit clean with XHR mode
		if( !empty(self::$sQueryMode)) {
			exit(0);
		}
	}

	/**
	 * hookDisplayHeader() method displays customized module content on header
	 *
	 * @return string
	 */
	public function hookDisplayHeader()
	{
		return (
			$this->_execHook('display', 'header')
		);
	}

	/**
	 * hookDisplayFooter() method displays customized module content on footer
	 *
	 * @return string
	 */
	public function hookDisplayFooter()
	{
		return (
			$this->_execHook('display', 'footer')
		);
	}

	/**
	 * hookDisplayHome() method displays snippets for home content
	 *
	 * @return string
	 */
	public function hookDisplayHome()
	{
		return (
			$this->_execHook('display', 'home')
		);
	}

	/**
	 * hookDisplayTop() method displays snippets for top content
	 *
	 * @return string
	 */
	public function hookDisplayTop()
	{
		return (
			$this->_execHook('display', 'top')
		);
	}

	/**
	 * hookDisplayLeftColumn() method displays snippets for product page on left column
	 *
	 * @return string
	 */
	public function hookDisplayLeftColumn()
	{
		return (
			$this->_execHook('display', 'leftColumn')
		);
	}

	/**
	 * hookDisplayRightColumn() method displays snippets for product page on right column
	 *
	 * @return string
	 */
	public function hookDisplayRightColumn()
	{
		return (
			$this->_execHook('display', 'rightColumn')
		);
	}

	/**
	 * hookDisplayRightColumnProduct() method displays customized module content on extra right of product
	 *
	 * @return string
	 */
	public function hookDisplayRightColumnProduct()
	{
		return (
			$this->_execHook('display', 'displayRightColumnProduct')
		);
	}

	/**
	 * hookDisplayLeftColumnProduct() method displays customized module content in extra left
	 *
	 * @return string
	 */
	public function hookDisplayLeftColumnProduct()
	{
		return (
			$this->_execHook('display', 'displayLeftColumnProduct')
		);
	}

	/**
	 * hookDisplayFooterProduct() method displays customized module content in product footer
	 *
	 * @return string
	 */
	public function hookDisplayFooterProduct()
	{
		return (
			$this->_execHook('display', 'displayFooterProduct')
		);
	}

	/**
	 * hookDisplayProductButtons() method displays customized module content in box "product actions"
	 *
	 * @return string
	 */
	public function hookDisplayProductButtons()
	{
		return (
			$this->_execHook('display', 'displayProductButtons')
		);
	}

	/**
	 * hookDisplayReassurance() method displays customized module content in box "product reassurance"
	 *
	 * @return string
	 */
	public function hookDisplayReassurance()
	{
		return (
			$this->_execHook('display', 'displayReassurance')
		);
	}

	/**
	 * hookDisplayProductTab() method displays customized module content in product tab
	 *
	 * @return string
	 */
	public function hookDisplayProductTab()
	{
		return (
			$this->_execHook('display', 'productTab')
		);
	}

	/**
	 * hookDisplayProductTabContent() method displays customized module content in product tab content
	 *
	 * @return string
	 */
	public function hookDisplayProductTabContent(array $aParams = null)
	{
		return (
			$this->_execHook('display', 'productTabContent', $aParams)
		);
	}
	
	/**
	 * hookOrderConfirmation() method save all product purchased in order to send an email notification
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookOrderConfirmation(array $aParams)
	{
		return '';
	}

	/**
	 * hookOrderConfirmation() method save all product purchased in order to send an email notification
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookDisplayOrderConfirmation(array $aParams)
	{
		return '';
	}

	/**
	 * hookActionValidateOrder() method executes new order hook
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookActionValidateOrder(array $aParams)
	{
		return (
			$this->hookModuleOrderConfirmation($aParams)
		);
	}

	/**
	 * hookActionValidateOrder() method executes new order hook
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookModuleOrderConfirmation(array $aParams)
	{
		return (
			(self::$aConfiguration[_GSR_MODULE_NAME . '_ENABLE_CALLBACK'])? $this->_execHook('action', 'orderConfirmation', $aParams) : ''
		);
	}

	/**
	 * hookCustomerAccount() method displays option for activating callback review
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookCustomerAccount(array $aParams = null)
	{
		return (
			$this->_execHook('display', 'customerAccount', $aParams)
		);
	}

	/**
	 * hookCustomerReminderStatus() method update customer's reminder status
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookCustomerReminderStatus(array $aParams = null)
	{
		return (
			$this->_execHook('action', 'updateReminderStatus', $aParams)
		);
	}

	/**
	 * hookPopin() method displays FB post share & voucher data
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookPopin(array $aParams = null)
	{
		return (
			$this->_execHook('display', 'popin', $aParams)
		);
	}

	/**
	 * hookPopinFb() method displays FB post like
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookPopinFb(array $aParams = null)
	{
		return (
			$this->_execHook('display', 'popinFb', $aParams)
		);
	}

	/**
	 * hookDisplayProductListReviews() method displays product rating in product list page
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookDisplayProductListReviews(array $aParams = null)
	{
		require_once(_GSR_PATH_CONF . 'hook.conf.php');

		if (!empty($aParams)
			&& !empty($aParams['product']['id_product'])
			&& !empty(GSnippetsReviews::$aConfiguration[_GSR_MODULE_NAME . '_DISPLAY_HOOK_REVIEW_STARS'])
		) {
			return (
				$this->_execHook('display', 'productRating', array(
					'id' => $aParams['product']['id_product'],
					'suffix' => rand(0, getrandmax()),
					'cache' => true,
					'cacheId' => $aParams['product']['id_product'],
					'template' => (_GSR_PATH_TPL_NAME . _GSR_TPL_HOOK_PATH . _GSR_TPL_REVIEW_PAGE_LIST))
				)
			);
		}
		return '';
	}

	/**
	 * hookActionProductDelete() method check reviews and delete them when a product is deleted
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookActionProductDelete(array $aParams = null)
	{
		if (!empty($aParams['id_product'])) {
			return (
				$this->_execHook('action', 'productDelete', array('id_product' => $aParams['id_product']))
			);
		}
	}

	/**
	 * hookProductRating() method displays product rating in product list page
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookProductRating(array $aParams = null)
	{
		return (
			$this->_execHook('display', 'productRating', $aParams)
		);
	}

	/**
	 * hookReviewForm() method displays review form
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookReviewForm(array $aParams = null)
	{
		return (
			$this->_execHook('display', 'reviewForm', $aParams)
		);
	}

	/**
	 * hookPostReview() method post review
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookPostReview(array $aParams = null)
	{
		return (
			$this->_execHook('action', 'postReview', $aParams)
		);
	}

	/**
	 * hookReportForm() method display review report form
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookReportForm(array $aParams = null)
	{
		return (
			$this->_execHook('display', 'reportForm', $aParams)
		);
	}

	/**
	 * hookReportReview() method report a review
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookReportReview(array $aParams = null)
	{
		return (
			$this->_execHook('action', 'reportReview', $aParams)
		);
	}

	/**
	 * hookUpdateReview() method update a review
	 *
	 * @param array $aParams
	 * @return string
	 */
	public function hookUpdateReview(array $aParams = null)
	{
		return (
			$this->_execHook('action', 'updateReview', $aParams)
		);
	}

	/**
	 * hookReviewStandalone() method display review standalone
	 *
	 * @see _execHook method
	 * @param array $aParams
	 * @return string
	 */
	public function hookReviewStandalone(array $aParams = null)
	{
		return (
			$this->_execHook('display', 'review', $aParams)
		);
	}

	/**
	 * _execHook() method displays selected hook content
	 *
	 * @param string $sHookType
	 * @param array $aParams
	 * @return string
	 */
	private function _execHook($sHookType, $sAction,  array $aParams = null)
	{
		// include
		require_once(_GSR_PATH_CONF . 'hook.conf.php');
		require_once(_GSR_PATH_LIB_HOOK . 'hook-ctrl_class.php');

		// define
		$aDisplay = array();

		try {
			// use cache or not
			if (!empty($aParams['cache'])
				&& !empty($aParams['template'])
				&& !empty($aParams['cacheId'])
			) {
				$bUseCache = !$this->isCached($aParams['template'], $this->getCacheId($aParams['cacheId']))? false : true;

				if ($bUseCache) {
					$aDisplay['tpl'] = $aParams['template'];
					$aDisplay['assign'] = array();
				}
			}
			else {
				$bUseCache = false;
			}

			// detect cache or not
			if (!$bUseCache) {
				// define which hook class is executed in order to display good content in good zone in shop
				$oHook = new BT_GsrHookCtrl($sHookType, $sAction);

				// displays good block content
				$aDisplay = $oHook->run($aParams);

				// free memory
				unset($oHook);
			}

			// execute good action in admin
			// only displayed with key : tpl and assign in order to display good smarty template
			if (!empty($aDisplay)) {
				return (
					$this->displayModule($aDisplay['tpl'], $aDisplay['assign'], $bUseCache, (!empty($aParams['cacheId'])? $aParams['cacheId'] : null))
				);
			}
			else {
				throw new Exception('Chosen hook returned empty content', 110);
			}
		}
		catch (Exception $e) {
			$this->aErrors[] = array('msg' => $e->getMessage(), 'code' => $e->getCode());

			return (
				$this->displayErrorModule()
			);
		}
	}

	/**
	 * setErrorHandler() method manages module error
	 *
	 * @param string $sTplName
	 * @param array $aAssign
	 */
	public function setErrorHandler($iErrno, $sErrstr, $sErrFile, $iErrLine, $aErrContext)
	{
		switch ($iErrno) {
			case E_USER_ERROR :
				$this->aErrors[] = array('msg' => 'Fatal error <b>' . $sErrstr . '</b>', 'code' => $iErrno, 'file' => $sErrFile, 'line' => $iErrLine, 'context' => $aErrContext);
				break;
			case E_USER_WARNING :
				$this->aErrors[] = array('msg' => 'Warning <b>' . $sErrstr . '</b>', 'code' => $iErrno, 'file' => $sErrFile, 'line' => $iErrLine, 'context' => $aErrContext);
				break;
			case E_USER_NOTICE :
				$this->aErrors[] = array('msg' => 'Notice <b>' . $sErrstr . '</b>', 'code' => $iErrno, 'file' => $sErrFile, 'line' => $iErrLine, 'context' => $aErrContext);
				break;
			default :
				$this->aErrors[] = array('msg' => 'Unknow error <b>' . $sErrstr . '</b>', 'code' => $iErrno, 'file' => $sErrFile, 'line' => $iErrLine, 'context' => $aErrContext);
				break; 
		}
		return (
			$this->displayErrorModule()
		);
	}

	/**
	 * displayModule() method displays view
	 *
	 * @param string $sTplName
	 * @param array $aAssign
	 * @param bool $bUseCache
	 * @param int $iICacheId
	 * @return string html
	 */
	public function displayModule($sTplName, $aAssign, $bUseCache = false, $iICacheId = null)
	{
		if (file_exists(_GSR_PATH_TPL . $sTplName) && is_file(_GSR_PATH_TPL . $sTplName)) {
			// set assign module name
			$aAssign = array_merge($aAssign, array('sModuleName' => Tools::strtolower(_GSR_MODULE_NAME), 'bDebug' => _GSR_DEBUG));

			// use cache
			if (!empty($bUseCache) && !empty($iICacheId)) {
				return (
					$this->display(__FILE__, $sTplName, $this->getCacheId($iICacheId))
				);
			}
			// not use cache
			else {
				Context::getContext()->smarty->assign($aAssign);
				return (
					$this->display(__FILE__, _GSR_PATH_TPL_NAME . $sTplName)
				);
			}
		}
		else {
			throw new Exception('Template "' . $sTplName . '" doesn\'t exists', 120);
		}
	}

	/**
	 * displayErrorModule() method displays view with error
	 *
	 * @param string $sTplName
	 * @param array $aAssign
	 * @return string html
	 */
	public function displayErrorModule()
	{
		Context::getContext()->smarty->assign(
			array(
				'sHomeURI'      => BT_GsrModuleTools::truncateUri(),
				'aErrors'       => $this->aErrors,
				'sModuleName'   => Tools::strtolower(_GSR_MODULE_NAME),
				'bDebug'        => _GSR_DEBUG,
			)
		);

		return (
			$this->display(__FILE__, _GSR_PATH_TPL_NAME . 'admin/' . _GSR_TPL_ERROR)
		);
	}

	/**
	 * updateModule() method updates module as necessary
	 *
	 * @return array
	 */
	public function updateModule()
	{
		require(_GSR_PATH_LIB . 'module-update_class.php');

		// check if update tables
		BT_GsrModuleUpdate::create()->run('tables');

		// check if update fields
		BT_GsrModuleUpdate::create()->run('fields');

		// check if update hooks
		BT_GsrModuleUpdate::create()->run('hooks');

		// check if update templates
		BT_GsrModuleUpdate::create()->run('templates');

		// check if update lang ID
		BT_GsrModuleUpdate::create()->run('langId');

		// check if update shop ID
		BT_GsrModuleUpdate::create()->run('shopId');

		// check if update rating Date
		BT_GsrModuleUpdate::create()->run('ratingDate');

		// check if update rating Date
		BT_GsrModuleUpdate::create()->run('moduleAdminTab');

		return (
			BT_GsrModuleUpdate::create()->getErrors()
		);
	}
}
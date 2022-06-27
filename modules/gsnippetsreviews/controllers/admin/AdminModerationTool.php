<?php
/**
 * AdminModerationTool.php file defines admin tab class of module
 */

if (!defined('_PS_VERSION_')) {
	exit(1);
}

class AdminModerationToolController extends ModuleAdminController
{
	/**
	 * @var int $iCurrentLang : store id of default lang
	 */
	public static $iCurrentLang = null;

	/**
	 * @var string $sBASE_URI : base of URI in prestashop
	 */
	public static $sBASE_URI = null;

	/**
	 * @var string $className : set the class name
	 */
	public $className = 'AdminModerationToolController';

	/**
	 * @var string $multishop_context_group : store context group
	 */
	public $multishop_context_group = null;

	/**
	 * Magic Method __construct assigns few information about module and instantiate parent class
	 */
	public function __construct()
	{
		require_once(_PS_MODULE_DIR_ . 'gsnippetsreviews/conf/common.conf.php');
		require_once(_PS_MODULE_DIR_ . 'gsnippetsreviews/lib/module-tools_class.php');

		// get default lang
		self::$iCurrentLang = Context::getContext()->cookie->id_lang;

		$sIsoLang = BT_GsrModuleTools::getLangIso(self::$iCurrentLang);

		if (isset($GLOBALS[_GSR_MODULE_NAME . '_TABS']['AdminModerationTool']['lang'][$sIsoLang])) {
			$sName = $GLOBALS[_GSR_MODULE_NAME . '_TABS']['AdminModerationTool']['lang'][$sIsoLang];
		}
		else {
			$sName = $GLOBALS[_GSR_MODULE_NAME . '_TABS']['AdminModerationTool']['lang']['en'];
		}

		// set module name
		$this->display = 'view';
		$this->name = $sName;

		// construct
		parent::__construct();
	}


	/**
	 * init() method manages to init the controller by executing the parent method
	 */
	public function init()
	{
		parent::init();
	}


	/**
	 * initContent() method manages to initialize the controller content by executing the parent method
	 */
	public function initContent()
	{
		parent::initContent();
	}


	/**
	 * renderView() method manages all data in Back Office of module's admin controller
	 *
	 * @return string
	 */
	public function renderView()
	{
		// include main class
		require_once(_GSR_PATH_ROOT . 'gsnippetsreviews.php');

		// instantiate
		$oMainClass = new GSnippetsReviews();

		// define which controller to use
		$_POST[_GSR_PARAM_CTRL_NAME] = (!Tools::getIsset(_GSR_PARAM_CTRL_NAME) || (Tools::getIsset(_GSR_PARAM_CTRL_NAME) && _GSR_ADMIN_REVIEW_CTRL == Tools::getValue(_GSR_PARAM_CTRL_NAME)))? (Tools::getIsset(_GSR_PARAM_CTRL_NAME)? Tools::getValue(_GSR_PARAM_CTRL_NAME) : _GSR_ADMIN_REVIEW_CTRL) : Tools::getValue(_GSR_PARAM_CTRL_NAME);

		// use case - display => list of reviews / others actions: change status / modify / delete / sort
		$_POST['sAction'] = (!Tools::getIsset('sAction') || (Tools::getIsset('sAction') && 'review' == Tools::getValue('sAction')))? 'display' : Tools::getValue('sAction') ;

		// set type of request
		$_POST['sType'] = !Tools::getIsset('sType')? 'tabs' : Tools::getValue('sType');

		return (
			$oMainClass->getContent()
		);
	}


	/**
	 * setMedia() method manages to initialize controller's media
	 *
	 * @return string
	 */
	public function setMedia()
	{
		$this->addJqueryPlugin('jquery');
		return parent::setMedia();
	}
}
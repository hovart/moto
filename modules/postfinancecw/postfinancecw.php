<?php
/**
 *  * You are allowed to use this API in your web application.
 *
 * Copyright (C) 2016 by customweb GmbH
 *
 * This program is licenced under the customweb software licence. With the
 * purchase or the installation of the software in your application you
 * accept the licence agreement. The allowed usage is outlined in the
 * customweb software licence which can be found under
 * http://www.sellxed.com/en/software-license-agreement
 *
 * Any modification or distribution is strictly forbidden. The license
 * grants you the installation in one application. For multiuse you will need
 * to purchase further licences at http://www.sellxed.com/shop.
 *
 * See the customweb software licence agreement for more details.
 *
 */
$modulePath = rtrim(_PS_MODULE_DIR_, '/');
require_once $modulePath . '/postfinancecw/lib/loader.php';
require_once 'Customweb/Payment/Authorization/ITransaction.php';
require_once 'Customweb/Core/Url.php';
require_once 'Customweb/Core/DateTime.php';
require_once 'Customweb/Payment/ExternalCheckout/IProviderService.php';
require_once 'Customweb/Payment/ExternalCheckout/IContext.php';
require_once 'Customweb/Database/Migration/Manager.php';
require_once 'Customweb/Core/String.php';
require_once 'Customweb/Core/Exception/CastException.php';
require_once 'Customweb/Util/Invoice.php';

require_once 'PostFinanceCw/Util.php';
require_once 'PostFinanceCw/PaymentMethod.php';
require_once 'PostFinanceCw/ConfigurationApi.php';
require_once 'PostFinanceCw/Entity/ExternalCheckoutContext.php';
require_once 'PostFinanceCw/SmartyProxy.php';
require_once 'PostFinanceCw/Entity/Transaction.php';



require_once $modulePath . '/postfinancecw/lib/PostFinanceCw/TranslationResolver.php';

if (!defined('_PS_VERSION_'))
	exit();

/**
 * PostFinanceCw
 *
 * This class defines all central vars for the PostFinanceCw modules.
 *          			 		  	    
 * 
 * @author customweb GmbH
 */
	/*
 * ##conditional(isSingleModule.equals("true"))####
 * class PostFinanceCw extends PostFinanceCw_PaymentMethod {
 *
 * public $paymentMethodDisplayName = 'PostFinance';
 * public $paymentMethodName = 'AllMethods';
 *
 * ####conditional##
 */
	
class PostFinanceCw extends Module {
	
	/**
	 *
	 * @var PostFinanceCw_ConfigurationApi
	 */
	private $configurationApi = null;
	public $trusted = true;
	const CREATE_PENDING_ORDER_KEY = 'CREATE_PENDING_ORDER';
	private static $recordMailMessages = false;
	private static $recordedMailMessages = array();
	private static $instance = null;
	private static $cancellingCheckIsRunning = false;

	/**
	 * This method init the module.
	 */
	public function __construct(){
		
		// We have to make sure we can reuse the instance later.
		if (self::$instance === null) {
			self::$instance = $this;
		}
		
		$this->name = 'postfinancecw';
		$this->tab = 'checkout';
		$this->version = preg_replace('([^0-9\.a-zA-Z]+)', '', '3.0.17');
		$this->author = 'customweb ltd';
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';
		$this->bootstrap = true;
		
		parent::__construct();
		
		if (Module::isInstalled($this->name)) {
			$migration = new Customweb_Database_Migration_Manager(PostFinanceCw_Util::getDriver(), dirname(__FILE__) . '/updates/', 
					_DB_PREFIX_ . 'postfinancecw_schema_version');
			$migration->migrate();
		}
		
		// The parent construct is required for translations          			 		  	    
		$this->displayName = PostFinanceCw::translate('DISPLAY NAME');
		$this->description = PostFinanceCw::translate('ACCEPTS PAYMENTS MAIN');
		$this->confirmUninstall = PostFinanceCw::translate('DELETE CONFIRMATION');
		
		if (Module::isInstalled('mailhook')) {
			require_once rtrim(_PS_MODULE_DIR_, '/') . '/mailhook/MailMessage.php';
			require_once rtrim(_PS_MODULE_DIR_, '/') . '/mailhook/MailMessageAttachment.php';
			require_once rtrim(_PS_MODULE_DIR_, '/') . '/mailhook/MailMessageEvent.php';
		}
		if (Module::isInstalled($this->name) && !empty($this->id)) {
			$this->checkForCancellingRunningTransaction();
		}
		
		if (!isset($_GET['configure']) && $this->context->controller instanceof AdminModulesController && method_exists('Module', 'isModuleTrusted') &&
				 (!Module::isInstalled($this->name) || !Module::isInstalled('mailhook'))) {
			$this->context->smarty = new PostFinanceCw_SmartyProxy($this->context->smarty);
			if (!isset($GLOBALS['cwrmUnTrustedMs'])) {
				$GLOBALS['cwrmUnTrustedMs'] = array();
			}
			$GLOBALS['cwrmUnTrustedMs'][] = 'postfinancecw';
		}
		
		if (Module::isInstalled($this->name) && !empty($this->id)) {
			$this->checkLicense();
		}
		
		$this->handleChangesForAuthController();
	}
	
	private function checkLicense(){
		require_once 'Customweb/Licensing/PostFinanceCw/License.php';
		$arguments = null;
		return Customweb_Licensing_PostFinanceCw_License::run('9cm4t2mbrniisapj', $this, $arguments);
	}

	public function call_m8s8c5l8uebudhcf() {
		$arguments = func_get_args();
		$method = $arguments[0];
		$call = $arguments[1];
		$parameters = array_slice($arguments, 2);
		if ($call == 's') {
			return call_user_func_array(array(get_class($this), $method), $parameters);
		}
		else {
			return call_user_func_array(array($this, $method), $parameters);
		}
		
		
	}
	private function setWarning($warning){
		$this->warning = $warning;
	}

	private function getName(){
		return $this->name;
	}

	/**
	 * When pending orders are created, the stock may be reduced during the checkout.
	 * When
	 * the customer returns during the payment in the browser to the store, the stock is
	 * reserved for the customer, however he will never complete the payment. Hence we have to give
	 * the customer the option to cancel the running transaction.
	 */
	private function checkForCancellingRunningTransaction(){
		if ($this->isCreationOfPendingOrderActive() && self::$cancellingCheckIsRunning === false) {
			self::$cancellingCheckIsRunning = true;
			$controller = strtolower(Tools::getValue('controller'));
			if (($controller == 'order' || $controller == 'orderopc') && isset($this->context->cart) && !Configuration::get('PS_CATALOG_MODE') &&
					 !$this->context->cart->checkQuantities()) {
				$originalCartId = $this->context->cart->id;
				PostFinanceCw_Util::getDriver()->beginTransaction();
				$cancelledTransactions = 0;
				try {
					$transactions = PostFinanceCw_Entity_Transaction::getTransactionsByOriginalCartId($originalCartId, false);
					foreach ($transactions as $transaction) {
						if ($transaction->getAuthorizationStatus() == Customweb_Payment_Authorization_ITransaction::AUTHORIZATION_STATUS_PENDING) {
							$transaction->forceTransactionFailing();
							$cancelledTransactions++;
						}
					}
					PostFinanceCw_Util::getDriver()->commit();
				}
				catch (Exception $e) {
					$this->context->controller->errors[] = $e->getMessage();
					PostFinanceCw_Util::getDriver()->rollBack();
				}
				if ($cancelledTransactions > 0) {
					$this->context->controller->errors[] = PostFinanceCw::translate(
							"It seems as you have not finished the payment. We have cancelled the running payment.");
				}
			}
			self::$cancellingCheckIsRunning = false;
		}
	}

	public static function getInstance(){
		if (self::$instance === null) {
			self::$instance = new PostFinanceCw();
		}
		
		return self::$instance;
	}

	/**
	 *
	 * @return PostFinanceCw_ConfigurationApi
	 */
	public function getConfigApi(){
		if (empty($this->id)) {
			throw new Exception("Cannot initiate the config api wihtout the module id.");
		}
		
		if ($this->configurationApi == null) {
			$this->configurationApi = new PostFinanceCw_ConfigurationApi($this->id);
		}
		return $this->configurationApi;
	}

	/**
	 * This method installs the module.
	 *
	 * @return boolean if it was successful
	 */
	public function install(){
		
			$this->installController('AdminPostFinanceCwRefund', 'PostFinance Refund');
			$this->installController('AdminPostFinanceCwMoto', 'PostFinance Moto');
			$this->installController('AdminPostFinanceCwForm', 'PostFinance', 1, Tab::getIdFromClassName('AdminParentModules'));
			$this->installController('AdminPostFinanceCwTransaction', 'PostFinance Transactions', 1);

		if (parent::install() && $this->installConfigurationValues() && $this->registerHook('cwExternalCheckoutCollection') &&
				 $this->registerHook('adminOrder') && $this->registerHook('backOfficeHeader') && $this->registerHook('displayHeader') &&
				 $this->registerHook('displayShoppingCartFooter') && $this->registerHook('displayCustomerAccountForm') &&
				 $this->registerHook('displayBeforeShoppingCartBlock') && $this->registerHook('displayPDFInvoice')) {
			return true;
		}
		else {
			return false;
		}
	}

	public function installController($controllerName, $name, $active = 0, $parentId = null){
		if ($parentId === null) {
			$parentId = Tab::getIdFromClassName('AdminOrders');
		}
		
		$tab_controller_main = new Tab();
		$tab_controller_main->active = $active;
		$tab_controller_main->class_name = $controllerName;
		foreach (Language::getLanguages() as $language) {
			//in Presta 1.5 the name length is limited to 32
			if(version_compare(_PS_VERSION_, '1.6') >= 0){
				$tab_controller_main->name[$language['id_lang']] = substr($name, 0, 64);
			}
			else{
				//we have to cut the psp name otherwise, otherwise there could be an issue
				//where we can not distinguish the different controllers as all there visible names are identical
				if(strlen($name) > 32) {
					if(strpos($name, 'PostFinance') !== false){
						$name = str_replace('PostFinance', '', $name);
						$length = strlen($name);
						if($length < 32){
							$pspName = substr('PostFinance', 0, 32-$length);
							$name = $pspName.$name;
						}
					}
				}
				$tab_controller_main->name[$language['id_lang']] = substr($name, 0, 32);
			}
			
		}
		$tab_controller_main->id_parent = $parentId;
		$tab_controller_main->module = $this->name;
		$tab_controller_main->add();
		$tab_controller_main->move(Tab::getNewLastPosition(0));
	}

	public function uninstall(){
		$this->uninstallController('AdminPostFinanceCwRefund');
		$this->uninstallController('AdminPostFinanceCwMoto');
		$this->uninstallController('AdminPostFinanceCwForm');
		$this->uninstallController('AdminPostFinanceCwTransaction');
		
		return parent::uninstall() && $this->uninstallConfigurationValues();
	}

	public function uninstallController($controllerName){
		$tab_controller_main_id = TabCore::getIdFromClassName($controllerName);
		$tab_controller_main = new Tab($tab_controller_main_id);
		$tab_controller_main->delete();
	}

	private function installConfigurationValues(){
		$this->getConfigApi()->updateConfigurationValue('CREATE_PENDING_ORDER', 'inactive');
		$this->getConfigApi()->updateConfigurationValue('OPERATION_MODE', 'test');
		$this->getConfigApi()->updateConfigurationValue('PSPID', '');
		$this->getConfigApi()->updateConfigurationValue('TEST_PSPID', '');
		$this->getConfigApi()->updateConfigurationValue('LIVE_SHA_PASSPHRASE_IN', '');
		$this->getConfigApi()->updateConfigurationValue('LIVE_SHA_PASSPHRASE_OUT', '');
		$this->getConfigApi()->updateConfigurationValue('TEST_SHA_PASSPHRASE_IN', '');
		$this->getConfigApi()->updateConfigurationValue('TEST_SHA_PASSPHRASE_OUT', '');
		$this->getConfigApi()->updateConfigurationValue('HASH_METHOD', 'sha512');
		$this->getConfigApi()->updateConfigurationValue('ORDER_ID_SCHEMA', 'order_{id}');
		$this->getConfigApi()->updateConfigurationValue('TITLE', '');
		$this->getConfigApi()->updateConfigurationValue('ORDER_DESCRIPTION_SCHEMA', 'Order {id}');
		$this->getConfigApi()->updateConfigurationValue('TEMPLATE', 'default');
		$this->getConfigApi()->updateConfigurationValue('TEMPLATE_URL', '');
		$this->getConfigApi()->updateConfigurationValue('SHOP_ID', '');
		$this->getConfigApi()->updateConfigurationValue('API_USER_ID', '');
		$this->getConfigApi()->updateConfigurationValue('API_PASSWORD', '');
		$this->getConfigApi()->updateConfigurationValue('ALIAS_USAGE_MESSAGE', '');
		$this->getConfigApi()->updateConfigurationValue('TRANSACTION_UPDATES', 'inactive');
		$this->getConfigApi()->updateConfigurationValue('EXTERNAL_CHECKOUT_ACCOUNT_CREATION', 'skip_selection');
		
		return true;
	}

	private function uninstallConfigurationValues(){
		$this->getConfigApi()->removeConfigurationValue('CREATE_PENDING_ORDER');
		$this->getConfigApi()->removeConfigurationValue('OPERATION_MODE');
		$this->getConfigApi()->removeConfigurationValue('PSPID');
		$this->getConfigApi()->removeConfigurationValue('TEST_PSPID');
		$this->getConfigApi()->removeConfigurationValue('LIVE_SHA_PASSPHRASE_IN');
		$this->getConfigApi()->removeConfigurationValue('LIVE_SHA_PASSPHRASE_OUT');
		$this->getConfigApi()->removeConfigurationValue('TEST_SHA_PASSPHRASE_IN');
		$this->getConfigApi()->removeConfigurationValue('TEST_SHA_PASSPHRASE_OUT');
		$this->getConfigApi()->removeConfigurationValue('HASH_METHOD');
		$this->getConfigApi()->removeConfigurationValue('ORDER_ID_SCHEMA');
		$this->getConfigApi()->removeConfigurationValue('TITLE');
		$this->getConfigApi()->removeConfigurationValue('ORDER_DESCRIPTION_SCHEMA');
		$this->getConfigApi()->removeConfigurationValue('TEMPLATE');
		$this->getConfigApi()->removeConfigurationValue('TEMPLATE_URL');
		$this->getConfigApi()->removeConfigurationValue('SHOP_ID');
		$this->getConfigApi()->removeConfigurationValue('API_USER_ID');
		$this->getConfigApi()->removeConfigurationValue('API_PASSWORD');
		$this->getConfigApi()->removeConfigurationValue('ALIAS_USAGE_MESSAGE');
		$this->getConfigApi()->removeConfigurationValue('TRANSACTION_UPDATES');
		$this->getConfigApi()->removeConfigurationValue('EXTERNAL_CHECKOUT_ACCOUNT_CREATION');
		
		return true;
	}

	/**
	 * The main method for the configuration page.
	 *
	 * @return string html output
	 */
	public function getContent(){
		$this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/css/admin.css');
		
		$html = '';
		if (isset($_POST['submit_postfinancecw'])) {
			
			if (isset($_POST[self::CREATE_PENDING_ORDER_KEY]) && $_POST[self::CREATE_PENDING_ORDER_KEY] == 'active') {
				$this->registerHook('actionMailSend');
				if (!self::isInstalled('mailhook')) {
					$html .= $this->displayError(
							PostFinanceCw::translate(
									"The module 'Mail Hook' must be activated, when using the option 'create pending order', otherwise the mail sending behavior may be inappropriate."));
				}
			}
			
			$fields = $this->getConfigApi()->convertFieldTypes($this->getFormFields());
			$this->getConfigApi()->processConfigurationSaveAction($fields);
			$html .= $this->displayConfirmation(PostFinanceCw::translate('Settings updated'));
		}
		
		$html .= $this->getConfigurationForm();
		
		return $html;
	}

	private function getConfigurationForm(){
		$link = new Link();
		$fields = $this->getConfigApi()->convertFieldTypes($this->getFormFields());
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get(
				'PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->id = (int) Tools::getValue('id_carrier');
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submit_postfinancecw';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab .
				 '&module_name=' . $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigApi()->getConfigurationValues($fields),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id 
		);
		
		$forms = array(
			array(
				'form' => array(
					'legend' => array(
						'title' => 'PostFinance: ' . PostFinanceCw::translate('Settings'),
						'icon' => 'icon-envelope' 
					),
					'input' => $fields,
					'submit' => array(
						'title' => PostFinanceCw::translate('Save') 
					) 
				) 
			) 
		);
		
		return $helper->generateForm($forms);
	}

	protected function getFormFields(){
		$fields = array(
			0 => array(
				'name' => 'CREATE_PENDING_ORDER',
 				'label' => $this->l("Create Pending Order (Beta Feature)"),
 				'desc' => $this->l("By creating pending orders the module will create a order before the payment is authorized. This not PrestaShop standard and may introduce some issues. However the module can send the order number to , which can reduce the overhead for the reconsilation. To use this feature the 'Mail Hook' module must be activated. This feature is in beta. It may not work."),
 				'default' => 'inactive',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'inactive',
 							'name' => $this->l("Inactive"),
 						),
 						1 => array(
							'id' => 'active',
 							'name' => $this->l("Active"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			1 => array(
				'name' => 'OPERATION_MODE',
 				'label' => $this->l("Operation Mode"),
 				'desc' => $this->l("If the test mode is selected the test PSPID is used and
				the test SHA passphrases.
			"),
 				'default' => 'test',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'test',
 							'name' => $this->l("Test Mode"),
 						),
 						1 => array(
							'id' => 'live',
 							'name' => $this->l("Live Mode"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			2 => array(
				'name' => 'PSPID',
 				'label' => $this->l("Live PSPID"),
 				'desc' => $this->l("The PSPID as given by the
				.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			3 => array(
				'name' => 'TEST_PSPID',
 				'label' => $this->l("Test PSPID"),
 				'desc' => $this->l("The test PSPID as given by the
				.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			4 => array(
				'name' => 'LIVE_SHA_PASSPHRASE_IN',
 				'label' => $this->l("SHA-IN Passphrase"),
 				'desc' => $this->l("Enter the live SHA-IN passphrase. This value must be
				identical to the one in the back-end of
				.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			5 => array(
				'name' => 'LIVE_SHA_PASSPHRASE_OUT',
 				'label' => $this->l("SHA-OUT Passphrase"),
 				'desc' => $this->l("Enter the live SHA-OUT passphrase. This value must be
				identical to the one in the back-end of
				.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			6 => array(
				'name' => 'TEST_SHA_PASSPHRASE_IN',
 				'label' => $this->l("Test Account SHA-IN Passphrase"),
 				'desc' => $this->l("Enter the test SHA-IN passphrase. This value must be
				identical to the one in the back-end of
				.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			7 => array(
				'name' => 'TEST_SHA_PASSPHRASE_OUT',
 				'label' => $this->l("Test Account SHA-OUT Passphrase"),
 				'desc' => $this->l("Enter the test SHA-OUT passphrase. This value must be
				identical to the one in the back-end of
				.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			8 => array(
				'name' => 'HASH_METHOD',
 				'label' => $this->l("Hash calculation method"),
 				'desc' => $this->l("Select the hash calculation method to use. This value
				must correspond with the selected value in the back-end of
				.
			"),
 				'default' => 'sha512',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'sha1',
 							'name' => $this->l("SHA-1"),
 						),
 						1 => array(
							'id' => 'sha256',
 							'name' => $this->l("SHA-256"),
 						),
 						2 => array(
							'id' => 'sha512',
 							'name' => $this->l("SHA-512"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			9 => array(
				'name' => 'ORDER_ID_SCHEMA',
 				'label' => $this->l("Order prefix"),
 				'desc' => $this->l("Here you can insert an order prefix. The prefix allows
				you to change the order number that is
				transmitted to
				. The prefix must contain the tag
				{id}. It will then be replaced
				by the order number (e.g. name_{id}).
			"),
 				'default' => 'order_{id}',
 				'type' => 'text',
 			),
 			10 => array(
				'name' => 'TITLE',
 				'label' => $this->l("Payment Page Title"),
 				'desc' => $this->l("Define here the title which is shown on the payment page. If no title is defined here the default one is used.
			"),
 				'default' => '',
 				'lang' => 'true',
 				'type' => 'textarea',
 			),
 			11 => array(
				'name' => 'ORDER_DESCRIPTION_SCHEMA',
 				'label' => $this->l("Order Description"),
 				'desc' => $this->l("This parameter is sometimes transmitted to the acquirer
				(depending on the acquirer),
				in order to be shown on the account
				statements of the merchant or the customer. The prefix can contain
				the tag
				{id}. It will then be replaced by the order number (e.g. name
				{id}). (Payment Page only)
			"),
 				'default' => 'Order {id}',
 				'type' => 'text',
 			),
 			12 => array(
				'name' => 'TEMPLATE',
 				'label' => $this->l("Dynamic Template"),
 				'desc' => $this->l("With the Dynamic Template you can design the layout of
				the payment page yourself. For the option 'Own template' the URL to
				the
				template file must be entered into the following box.
			"),
 				'default' => 'default',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'default',
 							'name' => $this->l("Use shop template"),
 						),
 						1 => array(
							'id' => 'static',
 							'name' => $this->l("Use static template"),
 						),
 						2 => array(
							'id' => 'custom',
 							'name' => $this->l("Use own template"),
 						),
 						3 => array(
							'id' => 'none',
 							'name' => $this->l("Don't change the layout of the payment page
				"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			13 => array(
				'name' => 'TEMPLATE_URL',
 				'label' => $this->l("Template URL for own template"),
 				'desc' => $this->l("The URL indicated here is rendered as Template. For this
				you must select option 'Use own template'. The URL must point to an
				HTML page
				that contains the string '\$\$\$PAYMENT ZONE\$\$\$'. This part of
				the HTML file is replaced with the form for the credit card input.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			14 => array(
				'name' => 'SHOP_ID',
 				'label' => $this->l("Shop ID"),
 				'desc' => $this->l("Here you can define a Shop ID. This is only necessary if
				you wish to operate several shops with one PSPID. In order to use
				this
				module, an additional module is required.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			15 => array(
				'name' => 'API_USER_ID',
 				'label' => $this->l("API Username"),
 				'desc' => $this->l("You can create an API username in the back-end of
				. The API user is necessary for
				the direct
				communication between the shop and the service of
				.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			16 => array(
				'name' => 'API_PASSWORD',
 				'label' => $this->l("API Password"),
 				'desc' => $this->l("Password for the API user."),
 				'default' => '',
 				'type' => 'text',
 			),
 			17 => array(
				'name' => 'ALIAS_USAGE_MESSAGE',
 				'label' => $this->l("Intended purpose of alias"),
 				'desc' => $this->l("If the Alias Manager is used, the intended purpose is
				shown to the customer on the payment page. Through this the customer
				knows why
				his data is saved.
			"),
 				'default' => '',
 				'lang' => 'true',
 				'type' => 'textarea',
 			),
 			18 => array(
				'name' => 'TRANSACTION_UPDATES',
 				'label' => $this->l("Transaction Updates"),
 				'desc' => $this->l("When the store is not available (network outage, server
				failure or any other outage), when the feedback of
				 is sent, then the transaction
				state is not
				updated. Hence no order confirmation e-mail is sent and
				the order is
				not in the paid state. By
				activating the transaction
				update, such transactions can be authorized later over direct link.
				To use this feature the
				update service must be activated and
				the API
				username and the API password must be set.
			"),
 				'default' => 'inactive',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'active',
 							'name' => $this->l("Active"),
 						),
 						1 => array(
							'id' => 'inactive',
 							'name' => $this->l("Inactive"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			19 => array(
				'name' => 'EXTERNAL_CHECKOUT_ACCOUNT_CREATION',
 				'label' => $this->l("External Checkout: Guest Checkout"),
 				'desc' => $this->l("When an external checkout is active the customer may need to authenticate. If the e-mail address does not exist in the database, should the customer be forced to select how he or she should create the account or should automatically an guest account be created?"),
 				'default' => 'skip_selection',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'force_selection',
 							'name' => $this->l("Force Account Selection"),
 						),
 						1 => array(
							'id' => 'skip_selection',
 							'name' => $this->l("Create Guest Account when possible"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 		);
		
		return $fields;
	}

	public function getPath(){
		return $this->_path;
	}

	public function hookDisplayHeader(){
		// In the one page checkout the CSS files are not loaded. This method adds therefore the missing CSS files on
		// this page.          			 		  	    
		if ($this->context->controller instanceof OrderOpcController) {
			$this->context->controller->addCSS(_MODULE_DIR_ . 'postfinancecw/css/style.css');
		}
	}

	public function hookDisplayShoppingCartFooter(){
		$output = '';
		
		if (!isset($GLOBALS['cwExternalCheckoutDataCollection']) && $this->context->cart->getOrderTotal() > 0 &&
				 (!isset($_POST['step']) || $_POST['step'] != '3')) {
			$checkouts = Hook::exec('cwExternalCheckoutCollection', array(), null, true);
			
			$GLOBALS['cwExternalCheckoutDataCollection'] = array();
			foreach ($checkouts as $checkoutList) {
				foreach ($checkoutList as $checkout) {
					$GLOBALS['cwExternalCheckoutDataCollection'][] = $checkout;
				}
			}
			usort($GLOBALS['cwExternalCheckoutDataCollection'], array(
				$this,
				"sortCheckouts" 
			));
			
			$output = '<div class="cw-external-checkouts row">';
			foreach ($GLOBALS['cwExternalCheckoutDataCollection'] as $checkout) {
				$output .= $checkout['widget'];
			}
			$output .= '</div>';
		}
		$this->context->controller->addCSS(_MODULE_DIR_ . 'postfinancecw/css/style.css');
		
		
		return $output;
	}

	public function hookDisplayBeforeShoppingCartBlock(){
		
		$contextId = Tools::getValue('postfinancecw-context-id', null);
		if ($contextId !== null) {
			$context = $this->loadContext($contextId);
			if ($context->getState() == Customweb_Payment_ExternalCheckout_IContext::STATE_FAILED) {
				$failedMessage = $context->getFailedErrorMessage();
				if (!empty($failedMessage)) {
					$this->smarty->assign(array(
						'externalCheckoutFailedMessage' => $failedMessage 
					));
					return $this->evaluateTemplate('external-checkout-error-message.tpl');
				}
			}
		}
		
		return '';
	}
	
	public function hookDisplayPDFInvoice($object) {
		if(!isset($object['object'])) {
			return;
		}
		$orderInvoice = $object['object'];
		if(!($orderInvoice instanceof OrderInvoice)){
			return;
		}
	
		$transactions = PostFinanceCw_Entity_Transaction::getTransactionsByOrderId($orderInvoice->id_order);
		$transactionObject = null;
		foreach ($transactions as $transaction) {
			if ($transaction->getTransactionObject() !== null && $transaction->getTransactionObject()->isAuthorized()) {
				$transactionObject = $transaction->getTransactionObject();
				break;
			}
		}
		if ($transactionObject === null) {
			return;
		}
		$paymentInformation = $transactionObject->getPaymentInformation();
		$result='';
		if(!empty($paymentInformation)) {
			$result .=  '<div class="postfinancecw-invoice-payment-information" id="postfinancecw-invoice-payment-information">';
			$result .= $paymentInformation;
			$result .= '</div>';
		}
		return $result;
	}
	
	private function handleChangesForAuthController(){
		
		$contextId = null;
		$cookie = $this->context->cookie;
		if ($this->context->controller instanceof AuthController) {
			$contextId = Tools::getValue('postfinancecw-context-id');
		}
		if (empty($contextId) && isset($cookie->postfinancecw_context_id)) {
			$contextId = $cookie->postfinancecw_context_id;
		}
		
		if (!empty($contextId)) {
			$context = $this->loadContext($contextId);
			$this->checkToken($context);
			if ($cookie->logged == 1 && !empty($cookie->email)) {
				unset($cookie->postfinancecw_context_id);
				$context->setCustomerEmailAddress($cookie->email);
				PostFinanceCw_Util::getEntityManager()->persist($context);
				header('Location: ' . $context->getAuthenticationSuccessUrl());
				exit();
			}
			$cookie->postfinancecw_context_id = $contextId;
			$link = new Link();
			$url = $link->getPageLink('authentication', true, null, 
					array(
						'postfinancecw-context-id' => $context->getContextId(),
						'token' => $context->getSecurityToken() 
					));
			$_GET['back'] = $url;
			if (!isset($_POST['email_create'])) {
				$_POST['email_create'] = $context->getAuthenticationEmailAddress();
			}
			if (!isset($_POST['email'])) {
				$_POST['email'] = $context->getAuthenticationEmailAddress();
			}
			if (!isset($_POST['guest_email'])) {
				$_POST['guest_email'] = $context->getAuthenticationEmailAddress();
			}
			
			if ($context->getShippingAddress() !== null) {
				// Data for guest checkout
				if (!isset($_POST['firstname'])) {
					$_POST['firstname'] = $context->getShippingAddress()->getFirstName();
				}
				if (!isset($_POST['lastname'])) {
					$_POST['lastname'] = $context->getShippingAddress()->getLastName();
				}
				if (!isset($_POST['address1'])) {
					$_POST['address1'] = $context->getShippingAddress()->getStreet();
				}
				if (!isset($_POST['postcode'])) {
					$_POST['postcode'] = $context->getShippingAddress()->getPostCode();
				}
				if (!isset($_POST['city'])) {
					$_POST['city'] = $context->getShippingAddress()->getCity();
				}
				if (!isset($_POST['id_country'])) {
					$_POST['id_country'] = Country::getByIso($context->getShippingAddress()->getCountryIsoCode());
				}
				if (!isset($_POST['phone_mobile'])) {
					$_POST['phone_mobile'] = $context->getShippingAddress()->getMobilePhoneNumber();
				}
				if (!isset($_POST['phone'])) {
					$_POST['phone'] = $context->getShippingAddress()->getPhoneNumber();
				}
				
				if (!isset($_POST['customer_firstname'])) {
					$_POST['customer_firstname'] = $_POST['firstname'];
				}
				if (!isset($_POST['customer_lastname'])) {
					$_POST['customer_lastname'] = $_POST['lastname'];
				}
				if (!isset($_POST['customer_address1'])) {
					$_POST['customer_address1'] = $_POST['address1'];
				}
				if (!isset($_POST['customer_postcode'])) {
					$_POST['customer_postcode'] = $_POST['postcode'];
				}
				if (!isset($_POST['customer_city'])) {
					$_POST['customer_city'] = $_POST['city'];
				}
			}
			
			if ($context->getBillingAddress() !== null) {
				
				$_POST['invoice_address'] = 'on';
				$guestInformations = array();
				// Data for guest checkout
				

				if (!isset($_POST['id_gender'])) {
					$_POST['id_gender'] = PostFinanceCw_Util::getGenderId($context->getBillingAddress()->getGender());
				}
				
				$dob = $context->getBillingAddress()->getDateOfBirth();
				if ($dob instanceof DateTime) {
					if (!isset($_POST['years'])) {
						$_POST['years'] = $dob->format('Y');
					}
					if (!isset($_POST['months'])) {
						$_POST['months'] = $dob->format('n');
					}
					if (!isset($_POST['days'])) {
						$_POST['days'] = $dob->format('j');
					}
				}
				
				$guestInformations['firstname_invoice'] = $context->getBillingAddress()->getFirstName();
				$guestInformations['lastname_invoice'] = $context->getBillingAddress()->getLastName();
				$guestInformations['address1_invoice'] = $context->getBillingAddress()->getStreet();
				$guestInformations['address2_invoice'] = '';
				$guestInformations['postcode_invoice'] = $context->getBillingAddress()->getPostCode();
				$guestInformations['city_invoice'] = $context->getBillingAddress()->getCity();
				
				$guestInformations['phone_invoice'] = $context->getBillingAddress()->getPhoneNumber();
				$guestInformations['phone_mobile_invoice'] = $context->getBillingAddress()->getMobilePhoneNumber();
				$guestInformations['id_country_invoice'] = 0;
				if ($context->getBillingAddress()->getCountryIsoCode() !== null) {
					$guestInformations['id_country_invoice'] = Country::getByIso($context->getBillingAddress()->getCountryIsoCode());
				}
				$guestInformations['id_state_invoice'] = 0;
				if ($context->getBillingAddress()->getState() !== null) {
					$guestInformations['id_state_invoice'] = State::getIdByIso($context->getBillingAddress()->getState());
				}
				
				if (!isset($_POST['customer_firstname'])) {
					$_POST['customer_firstname'] = $_POST['firstname'];
				}
				if (!isset($_POST['customer_lastname'])) {
					$_POST['customer_lastname'] = $_POST['lastname'];
				}
				
				// PrestaShop 1.5.x requires a different handling:
				foreach ($guestInformations as $invoiceKey => $invoiceValue) {
					if (!isset($_POST[$invoiceKey])) {
						$_POST[$invoiceKey] = $invoiceValue;
					}
				}
				
				$this->context->smarty->assign(array(
					'guestInformations' => $guestInformations 
				));
			}
		}
		
		
	}
	
	
	/**
	 *
	 * @return PostFinanceCw_Entity_ExternalCheckoutContext
	 * @throws Exception
	 */
	private function loadContext($contextId){
		if (!empty($contextId)) {
			return PostFinanceCw_Util::getEntityManager()->fetch('PostFinanceCw_Entity_ExternalCheckoutContext', $contextId);
		}
		else {
			throw new Exception("Invalid context id.");
		}
	}

	private function checkToken(PostFinanceCw_Entity_ExternalCheckoutContext $context){
		if (!empty($_GET['postfinancecw-context-id'])) {
			$token = Tools::getValue('token');
			if (!empty($token)) {
				if ($token !== null && $context->getSecurityToken() === $token) {
					$expiryDate = $context->getSecurityTokenExpiryDate();
					if ($expiryDate instanceof DateTime) {
						$expiryDate = new Customweb_Core_DateTime($expiryDate);
						if ($expiryDate->getTimestamp() > time()) {
							return true;
						}
					}
				}
			}
			throw new Exception("Invalid token");
		}
	}
	
	public function sortCheckouts($a, $b){
		if (isset($a['sortOrder']) && isset($b['sortOrder'])) {
			if ($a['sortOrder'] < $b['sortOrder']) {
				return -1;
			}
			else if ($a['sortOrder'] > $b['sortOrder']) {
				return 1;
			}
			else {
				return 0;
			}
		}
		else {
			return 0;
		}
	}

	public function hookCwExternalCheckoutCollection(){
		$widgets = array();
		
		
		$context = PostFinanceCw_Entity_ExternalCheckoutContext::getReusableContextByCartId($this->context->cart->id);
		if ($context === null) {
			$context = new PostFinanceCw_Entity_ExternalCheckoutContext();
			PostFinanceCw_Util::getEntityManager()->persist($context);
		}
		$providerService = PostFinanceCw_Util::createContainer()->getBean('Customweb_Payment_ExternalCheckout_IProviderService');
		if (!($providerService instanceof Customweb_Payment_ExternalCheckout_IProviderService)) {
			throw new Customweb_Core_Exception_CastException('Customweb_Payment_ExternalCheckout_IProviderService');
		}
		
		$context->updateFromCart($this->context->cart);
		$context->setState(Customweb_Payment_ExternalCheckout_IContext::STATE_PENDING);
		$cookie = $this->context->cookie;
		if ($cookie->logged == 1 && !empty($cookie->id_customer)) {
			$customer = new Customer($cookie->id_customer);
			$context->setCustomerEmailAddress($customer->email);
		}
		
		$checkouts = $providerService->getCheckouts($context);
		
		foreach ($checkouts as $checkout) {
			$this->smarty->assign(
					array(
						'widget' => $providerService->getWidgetHtml($checkout, $context),
						'checkoutName' => $checkout->getMachineName() 
					));
			$widgets['postfinancecw_' . $checkout->getMachineName()] = array(
				'sortOrder' => $checkout->getSortOrder(),
				'widget' => $this->evaluateTemplate('shopping-cart-checkout.tpl') 
			);
		}
		PostFinanceCw_Util::getEntityManager()->persist($context);
		
		

		return $widgets;
	}

	public function hookBackOfficeHeader(){
		$id_order = Tools::getValue('id_order');
		
		// Check if we need to ask the customer to refund the amount          			 		  	    
		if ((isset($_POST['partialRefund']) || isset($_POST['cancelProduct'])) && !isset($_GET['confirmed'])) {
			$transaction = current(PostFinanceCw_Entity_Transaction::getTransactionsByOrderId($id_order));
			if (is_object($transaction) && $transaction->getTransactionObject() !== null &&
					 $transaction->getTransactionObject()->isPartialRefundPossible()) {
				$order = new Order($id_order);
				if ($order->module == ('postfinancecw_' . $transaction->getPaymentMachineName())) {
					$url = '?controller=AdminPostFinanceCwRefund&token=' . Tools::getAdminTokenLite('AdminPostFinanceCwRefund');
					$url .= '&' . Customweb_Core_Url::parseArrayToString($_POST);
					header('Location: ' . $url);
					die();
				}
			}
		}
		
		if (isset($_POST['submitPostFinanceCwRefundAuto'])) {
			try {
				$transaction = current(PostFinanceCw_Entity_Transaction::getTransactionsByOrderId($id_order));
				$this->refundTransaction($transaction->getTransactionId(), self::getRefundAmount($_POST));
			}
			catch (Exception $e) {
				$this->context->controller->errors[] = PostFinanceCw::translate("Could not refund the transaction: ") . $e->getMessage();
				unset($_POST['partialRefund']);
				unset($_POST['cancelProduct']);
			}
		}
		
		

		
		if (isset($_GET['controller']) && $_GET['controller'] == 'AdminOrders' && isset($_POST['submitAddOrder']) && !isset($_GET['confirmed'])) {
			$paymentMethodName = $_POST['payment_module_name'];
			if (substr($paymentMethodName, 0, strlen('postfinancecw')) == 'postfinancecw') {
				$url = '?controller=AdminPostFinanceCwMoto&token=' . Tools::getAdminTokenLite('AdminPostFinanceCwMoto');
				$url .= '&' . Customweb_Core_Url::parseArrayToString($_POST);
				header('Location: ' . $url);
				die();
			}
		}
		
	}

	public function hookActionMailSend($data){
		if ($this->isCreationOfPendingOrderActive()) {
			if (!isset($data['event'])) {
				throw new Exception("No item 'event' provided in the mail action function.");
			}
			$event = $data['event'];
			if (!($event instanceof MailMessageEvent)) {
				throw new Exception("Invalid type provided by the mail send action.");
			}
			
			if (self::isRecordingMailMessages()) {
				foreach ($event->getMessages() as $message) {
					self::$recordedMailMessages[] = $message;
				}
				$event->setMessages(array());
			}
		}
	}

	public static function isRecordingMailMessages(){
		return self::$recordMailMessages;
	}

	public static function startRecordingMailMessages(){
		self::$recordMailMessages = true;
		self::$recordedMailMessages = array();
	}

	/**
	 *
	 * @return MailMessage[]
	 */
	public static function stopRecordingMailMessages(){
		self::$recordMailMessages = false;
		
		return self::$recordedMailMessages;
	}

	public function isCreationOfPendingOrderActive(){
		$createPendingOrder = $this->getConfigApi()->getConfigurationValue(self::CREATE_PENDING_ORDER_KEY);
		
		if ($createPendingOrder == 'active') {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * This method extracts the refund amount from the POST data.
	 *
	 * @param array $data
	 * @return float amount
	 */
	public static function getRefundAmount($data){
		$amount = 0;
		$order_detail_list = array();
		if (isset($data['partialRefundProduct'])) {
			foreach ($data['partialRefundProduct'] as $id_order_detail => $amount_detail) {
				$order_detail_list[$id_order_detail]['quantity'] = (int) $data['partialRefundProductQuantity'][$id_order_detail];
				
				if (empty($amount_detail)) {
					$order_detail = new OrderDetail((int) $id_order_detail);
					$order_detail_list[$id_order_detail]['amount'] = $order_detail->unit_price_tax_incl *
							 $order_detail_list[$id_order_detail]['quantity'];
				}
				else
					$order_detail_list[$id_order_detail]['amount'] = (float) str_replace(',', '.', $amount_detail);
				$amount += $order_detail_list[$id_order_detail]['amount'];
			}
			
			$shipping_cost_amount = (float) str_replace(',', '.', $data['partialRefundShippingCost']);
			if ($shipping_cost_amount > 0) {
				$amount += $shipping_cost_amount;
			}
		}
		
		// Either the partialRefundProduct or the cancelQuantity should be considered but never both.
		else if (isset($data['cancelQuantity'])) {
			foreach ($data['cancelQuantity'] as $id_order_detail => $quantity) {
				$q = (int) $quantity;
				if ($q > 0) {
					$order_detail = new OrderDetail((int) $id_order_detail);
					$line_amount = $order_detail->unit_price_tax_incl * $q;
					$amount += $line_amount;
				}
			}
		}
		
		return $amount;
	}

	/**
	 * This method is used to add a special info field in the order
	 * Tab.
	 *
	 * @param array $params Hook parameters
	 * @return string the html output
	 */
	public function hookAdminOrder($params){
		$html = '';
		
		$order = new Order((int) $params['id_order']);
		if (!strstr($order->module, 'postfinancecw')) {
			return '';
		}
		
		$errorMessage = '';
		try {
			$this->processAdminAction();
		}
		catch (Exception $e) {
			$errorMessage = $e->getMessage();
		}
		
		$transactions = PostFinanceCw_Entity_Transaction::getTransactionsByCartOrOrder($order->id_cart, $order->id);
		
		if (is_array($transactions) && count($transactions) > 0) {
			
			$activeTransactionId = false;
			if (isset($_POST['id_transaction'])) {
				$activeTransactionId = $_POST['id_transaction'];
			}
			
			$this->context->smarty->assign(
					array(
						'order_id' => $params['id_order'],
						'base_url' => _PS_BASE_URL_SSL_ . __PS_BASE_URI__,
						'transactions' => $transactions,
						'date_format' => $this->context->language->date_format_full,
						'errorMessage' => $errorMessage,
						'activeTransactionId' => $activeTransactionId 
					));
			
			$this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/css/admin.css');
			$this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/js/admin.js');
			$html .= $this->evaluateTemplate('/views/templates/back/admin_order.tpl');
		}
		
		return $html;
	}

	public function getConfiguraionValue($key, $langId = null){
		return $this->getConfigApi()->getConfigurationValue($key, $langId);
	}

	public function hasConfiguraionValue($key, $langId = null){
		return $this->getConfigApi()->hasConfigurationKey($key, $langId);
	}

	private function processAdminAction(){
		if (isset($_POST['id_transaction'])) {
			
			
			if (isset($_POST['submitPostFinanceCwRefund'])) {
				$amount = null;
				if (isset($_POST['refund_amount'])) {
					$amount = $_POST['refund_amount'];
				}
				
				$close = false;
				if (isset($_POST['close']) && $_POST['close'] == '1') {
					$close = true;
				}
				$this->refundTransaction($_POST['id_transaction'], $amount, $close);
			}
			
			

			
			if (isset($_POST['submitPostFinanceCwCancel'])) {
				$this->cancelTransaction($_POST['id_transaction']);
			}
			
			

			
			if (isset($_POST['submitPostFinanceCwCapture'])) {
				$amount = null;
				if (isset($_POST['capture_amount'])) {
					$amount = $_POST['capture_amount'];
				}
				
				$close = false;
				if (isset($_POST['close']) && $_POST['close'] == '1') {
					$close = true;
				}
				$this->captureTransaction($_POST['id_transaction'], $amount, $close);
			}
			
		}
	}
	
	
	public function refundTransaction($transactionId, $amount = null, $close = false){
		$dbTransaction = PostFinanceCw_Entity_Transaction::loadById($transactionId);
		$adapter = PostFinanceCw_Util::createContainer()->getBean('Customweb_Payment_BackendOperation_Adapter_Service_IRefund');
		if ($dbTransaction->getTransactionObject() != null && $dbTransaction->getTransactionObject()->isRefundPossible()) {
			if ($amount !== null) {
				$items = Customweb_Util_Invoice::getItemsByReductionAmount(
						$dbTransaction->getTransactionObject()->getTransactionContext()->getOrderContext()->getInvoiceItems(), $amount, 
						$dbTransaction->getTransactionObject()->getCurrencyCode());
				$adapter->partialRefund($dbTransaction->getTransactionObject(), $items, $close);
			}
			else {
				$adapter->refund($dbTransaction->getTransactionObject());
			}
			PostFinanceCw_Util::getEntityManager()->persist($dbTransaction);
		}
		else {
			throw new Exception("The given transaction is not refundable.");
		}
	}
	
	

	
	public function captureTransaction($transactionId, $amount = null, $close = false){
		$dbTransaction = PostFinanceCw_Entity_Transaction::loadById($transactionId);
		$adapter = PostFinanceCw_Util::createContainer()->getBean('Customweb_Payment_BackendOperation_Adapter_Service_ICapture');
		if ($dbTransaction->getTransactionObject() != null && $dbTransaction->getTransactionObject()->isCapturePossible()) {
			if ($amount !== null) {
				$items = Customweb_Util_Invoice::getItemsByReductionAmount(
						$dbTransaction->getTransactionObject()->getTransactionContext()->getOrderContext()->getInvoiceItems(), $amount, 
						$dbTransaction->getTransactionObject()->getCurrencyCode());
				$adapter->partialCapture($dbTransaction->getTransactionObject(), $items, $close);
			}
			else {
				$adapter->capture($dbTransaction->getTransactionObject());
			}
			PostFinanceCw_Util::getEntityManager()->persist($dbTransaction);
		}
		else {
			throw new Exception("The given transaction is not capturable.");
		}
	}
	
	

	
	public function cancelTransaction($transactionId){
		$dbTransaction = PostFinanceCw_Entity_Transaction::loadById($transactionId);
		$adapter = self::createContainer()->getBean('Customweb_Payment_BackendOperation_Adapter_Service_ICancel');
		if ($dbTransaction->getTransactionObject() != null && $dbTransaction->getTransactionObject()->isCancelPossible()) {
			$adapter->cancel($dbTransaction->getTransactionObject());
			PostFinanceCw_Util::getEntityManager()->persist($dbTransaction);
		}
		else {
			throw new Exception("The given transaction cannot be cancelled.");
		}
	}
	
	private function evaluateTemplate($file){
		return $this->display(__FILE__, $file);
	}

	public function l($string, $specific = false, $id_lang = null){
		return self::translate($string, $specific);
	}

	public static function translate($string, $sprintf = false, $module = 'postfinancecw'){
		$stringOriginal = $string;
		$string = str_replace("\n", " ", $string);
		$string = preg_replace("/\t++/", " ", $string);
		$string = preg_replace("/( +)/", " ", $string);
		$string = preg_replace("/[^a-zA-Z0-9]*/", "", $string);

		$rs = Translate::getModuleTranslation($module, $string, $module, $sprintf);
		if ($string == $rs) {
			$rs = $stringOriginal;
		}
		
		if ($sprintf !== false && is_array($sprintf)) {
			$rs = Customweb_Core_String::_($rs)->format($sprintf);
		}

		if (version_compare(_PS_VERSION_, '1.6') > 0) {
			return htmlspecialchars_decode($rs);
		}
		else {
			return $rs;
		}
	}

	public static function getAdminUrl($controller, array $params, $token = true){
		if ($token) {
			$params['token'] = Tools::getAdminTokenLite($controller);
		}
		$id_lang = Context::getContext()->language->id;
		$path = Dispatcher::getInstance()->createUrl($controller, $id_lang, $params, false);
		$protocol = 'http://';
		if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
			$protocol = 'https://';
		}
		
		return $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER["SCRIPT_NAME"]) . '/' . ltrim($path, '/');
	}

	private static function getShopIds(){
		$shops = array();
		$rs = Db::getInstance()->query('
				SELECT
					id_shop
				FROM
					`' . _DB_PREFIX_ . 'shop`');
		foreach ($rs as $data) {
			$shops[] = $data['id_shop'];
		}
		return $shops;
	}
}

// Register own translation function in smarty          			 		  	    
if (!function_exists('cwSmartyTranslate')) {
	global $smarty;

	function cwSmartyTranslate($params, &$smarty){
		$sprintf = isset($params['sprintf']) ? $params['sprintf'] : false;
		if (empty($params['mod'])) {
			throw new Exception(sprintf("Could not translate string '%s' because no module was provided.", $params['s']));
		}
		
		return PostFinanceCw::translate($params['s'], $sprintf, $params['mod']);
	}
	smartyRegisterFunction($smarty, 'function', 'lcw', 'cwSmartyTranslate', false);
}




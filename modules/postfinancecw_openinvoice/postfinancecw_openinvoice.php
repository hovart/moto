<?php
/**
 * You are allowed to use this API in your web application.
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
require_once $modulePath.'/postfinancecw/postfinancecw.php';

/**
 * PostFinanceCw_OpenInvoice 
 * 
 * This class defines is the module class for the 
 * payment method "OpenInvoice".
 * 
 * @author customweb GmbH
 */
class PostFinanceCw_OpenInvoice extends PostFinanceCw_PaymentMethod
{
	public $name = 'postfinancecw_openinvoice';
	public $paymentMethodName = 'openinvoice';
	public $paymentMethodDisplayName = 'Open Invoice';
	
	/**
	 * This method init the module.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function getFormFields() {
		$fields = array(
			0 => array(
				'name' => 'PROCESSOR',
 				'label' => $this->l("Processor"),
 				'desc' => $this->l("Select the processor for open invoice."),
 				'default' => 'billpay',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'afterpay',
 							'name' => $this->l("AfterPay"),
 						),
 						1 => array(
							'id' => 'billpay',
 							'name' => $this->l("Billpay"),
 						),
 						2 => array(
							'id' => 'PostFinanceFIS',
 							'name' => $this->l("PostFinance FIS"),
 						),
 						3 => array(
							'id' => 'klarna',
 							'name' => $this->l("Klarna"),
 						),
 						4 => array(
							'id' => 'ratepay',
 							'name' => $this->l("RatePay"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			1 => array(
				'name' => 'BRAND_COUNTRY',
 				'label' => $this->l("Brand Country"),
 				'desc' => $this->l("Select the country code defined in the backend of
						 for this payment method.
					"),
 				'default' => 'de',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'at',
 							'name' => $this->l("Austria (AT)"),
 						),
 						1 => array(
							'id' => 'ch',
 							'name' => $this->l("Switzerland (CH)"),
 						),
 						2 => array(
							'id' => 'de',
 							'name' => $this->l("Germany (DE)"),
 						),
 						3 => array(
							'id' => 'dk',
 							'name' => $this->l("Denmark (DK)"),
 						),
 						4 => array(
							'id' => 'fi',
 							'name' => $this->l("Finland (FI)"),
 						),
 						5 => array(
							'id' => 'nl',
 							'name' => $this->l("Netherlands (NL)"),
 						),
 						6 => array(
							'id' => 'no',
 							'name' => $this->l("Norway (NO)"),
 						),
 						7 => array(
							'id' => 'se',
 							'name' => $this->l("Sweden (SE)"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			2 => array(
				'name' => 'CAPTURING',
 				'label' => $this->l("Capturing"),
 				'desc' => $this->l("Should the amount be captured automatically after the
						order (direct) or should the amount only be reserved (deferred)?
					"),
 				'default' => 'direct',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'direct',
 							'name' => $this->l("Directly after order"),
 						),
 						1 => array(
							'id' => 'deferred',
 							'name' => $this->l("Deferred"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			3 => array(
				'name' => 'STATUS_AUTHORIZED',
 				'label' => $this->l("Authorized Status"),
 				'desc' => $this->l("This status is set, when the payment was successfull
						and it is authorized.
					"),
 				'default' => 'authorized',
 				'order_status' => array(
				),
 				'type' => 'orderstatus',
 			),
 			4 => array(
				'name' => 'STATUS_UNCERTAIN',
 				'label' => $this->l("Uncertain Status"),
 				'desc' => $this->l("You can specify the order status for new orders that
						have an uncertain authorisation status.
					"),
 				'default' => 'uncertain',
 				'order_status' => array(
				),
 				'type' => 'orderstatus',
 			),
 			5 => array(
				'name' => 'STATUS_CANCELLED',
 				'label' => $this->l("Cancelled Status"),
 				'desc' => $this->l("You can specify the order status when an order is
						cancelled.
					"),
 				'default' => 'cancelled',
 				'order_status' => array(
					0 => array(
						'id' => 'no_status_change',
 						'name' => $this->l("Don't change order status"),
 					),
 				),
 				'type' => 'orderstatus',
 			),
 			6 => array(
				'name' => 'STATUS_CAPTURED',
 				'label' => $this->l("Captured Status"),
 				'desc' => $this->l("You can specify the order status for orders that are
						captured either directly after the order or manually in the
						backend.
					"),
 				'default' => 'no_status_change',
 				'order_status' => array(
					0 => array(
						'id' => 'no_status_change',
 						'name' => $this->l("Don't change order status"),
 					),
 				),
 				'type' => 'orderstatus',
 			),
 			7 => array(
				'name' => 'STATUS_SUCCESS_AFTER_UNCERTAIN',
 				'label' => $this->l("HTTP Status for Successful Payments"),
 				'desc' => $this->l("You can specify the order status for orders that are
						successful after being in a uncertain state. In order to use this
						setting, you will need to activate the http-request for status
						changes as outlined in the manual.
					"),
 				'default' => 'no_status_change',
 				'order_status' => array(
					0 => array(
						'id' => 'no_status_change',
 						'name' => $this->l("Don't change order status"),
 					),
 				),
 				'type' => 'orderstatus',
 			),
 			8 => array(
				'name' => 'STATUS_REFUSED_AFTER_UNCERTAIN',
 				'label' => $this->l("HTTP Status for Refused Payments"),
 				'desc' => $this->l("You can specify the order status for orders that are
						refused after being in a uncertain state. In order to use this
						feature you will have to set up the http request for status
						changes as outlined in the manual.
					"),
 				'default' => 'no_status_change',
 				'order_status' => array(
					0 => array(
						'id' => 'no_status_change',
 						'name' => $this->l("Don't change order status"),
 					),
 				),
 				'type' => 'orderstatus',
 			),
 			9 => array(
				'name' => 'AUTHORIZATIONMETHOD',
 				'label' => $this->l("Authorization Method"),
 				'desc' => $this->l("Select the authorization method to use for processing this payment method."),
 				'default' => 'PaymentPage',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'PaymentPage',
 							'name' => $this->l("Payment Page"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 		);
		$fields = array_merge(parent::getFormFields(), $fields);
		return $fields;
	}
		
	/**
	 * This method installs the module.
	 *
	 * @return boolean if it was successful
	 */
	public function install() {
		return parent::install() && $this->installMethodSpecificConfigurations();
	}
	
	public function uninstall() {
		return parent::uninstall() && $this->uninstallMethodSpecificConfigurations();
	}
	
	public function installMethodSpecificConfigurations() {
		$this->getConfigApi()->updateConfigurationValue('PROCESSOR', 'billpay');
		$this->getConfigApi()->updateConfigurationValue('BRAND_COUNTRY', 'de');
		$this->getConfigApi()->updateConfigurationValue('CAPTURING', 'direct');
		$this->getConfigApi()->updateConfigurationValue('STATUS_AUTHORIZED', Configuration::get('PS_OS_PAYMENT'));
		$this->getConfigApi()->updateConfigurationValue('STATUS_UNCERTAIN', Configuration::get('PS_OS_PREPARATION'));
		$this->getConfigApi()->updateConfigurationValue('STATUS_CANCELLED', Configuration::get('PS_OS_CANCELED'));
		$this->getConfigApi()->updateConfigurationValue('STATUS_CAPTURED', 'no_status_change');
		$this->getConfigApi()->updateConfigurationValue('STATUS_SUCCESS_AFTER_UNCERTAIN', 'no_status_change');
		$this->getConfigApi()->updateConfigurationValue('STATUS_REFUSED_AFTER_UNCERTAIN', 'no_status_change');
		$this->getConfigApi()->updateConfigurationValue('AUTHORIZATIONMETHOD', 'PaymentPage');
		
		return true;
	}
	
	public function uninstallMethodSpecificConfigurations() {
		$this->getConfigApi()->removeConfigurationValue('PROCESSOR');
		$this->getConfigApi()->removeConfigurationValue('BRAND_COUNTRY');
		$this->getConfigApi()->removeConfigurationValue('CAPTURING');
		$this->getConfigApi()->removeConfigurationValue('STATUS_AUTHORIZED');
		$this->getConfigApi()->removeConfigurationValue('STATUS_UNCERTAIN');
		$this->getConfigApi()->removeConfigurationValue('STATUS_CANCELLED');
		$this->getConfigApi()->removeConfigurationValue('STATUS_CAPTURED');
		$this->getConfigApi()->removeConfigurationValue('STATUS_SUCCESS_AFTER_UNCERTAIN');
		$this->getConfigApi()->removeConfigurationValue('STATUS_REFUSED_AFTER_UNCERTAIN');
		$this->getConfigApi()->removeConfigurationValue('AUTHORIZATIONMETHOD');
		;
		return true;
	}
	
	public function getPaymentMethodConfigurationValue($key, $languageCode = null) {
		$multiSelectKeys = array(
		);
		$rs = parent::getPaymentMethodConfigurationValue($key, $languageCode);
		if (isset($multiSelectKeys[$key])) {
			if (empty($rs)) {
				return array();
			}
			else {
				return explode(',', $rs);
			}
		}
		else {
			return $rs;
		}
	}
	
}


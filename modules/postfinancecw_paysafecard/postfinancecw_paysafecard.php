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
 * PostFinanceCw_Paysafecard 
 * 
 * This class defines is the module class for the 
 * payment method "Paysafecard".
 * 
 * @author customweb GmbH
 */
class PostFinanceCw_Paysafecard extends PostFinanceCw_PaymentMethod
{
	public $name = 'postfinancecw_paysafecard';
	public $paymentMethodName = 'paysafecard';
	public $paymentMethodDisplayName = 'paysafecard';
	
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
 			1 => array(
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
 			2 => array(
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
 			3 => array(
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
 			4 => array(
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
 			5 => array(
				'name' => 'REFUSING_THRESHOLD',
 				'label' => $this->l("Refused Transaction Threshold"),
 				'desc' => $this->l("A typical pattern of a fraud transaction is a series
						of refused transaction before one of them is accepted. This
						setting defines the threshold after any following transaction is
						marked as uncertain. E.g. a threshold of three will mark any
						successful transaction after three refused transaction as
						uncertain.
					"),
 				'default' => '3',
 				'type' => 'text',
 			),
 			6 => array(
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
		$this->getConfigApi()->updateConfigurationValue('CAPTURING', 'direct');
		$this->getConfigApi()->updateConfigurationValue('STATUS_AUTHORIZED', Configuration::get('PS_OS_PAYMENT'));
		$this->getConfigApi()->updateConfigurationValue('STATUS_UNCERTAIN', Configuration::get('PS_OS_PREPARATION'));
		$this->getConfigApi()->updateConfigurationValue('STATUS_CANCELLED', Configuration::get('PS_OS_CANCELED'));
		$this->getConfigApi()->updateConfigurationValue('STATUS_CAPTURED', 'no_status_change');
		$this->getConfigApi()->updateConfigurationValue('REFUSING_THRESHOLD', '3');
		$this->getConfigApi()->updateConfigurationValue('AUTHORIZATIONMETHOD', 'PaymentPage');
		
		return true;
	}
	
	public function uninstallMethodSpecificConfigurations() {
		$this->getConfigApi()->removeConfigurationValue('CAPTURING');
		$this->getConfigApi()->removeConfigurationValue('STATUS_AUTHORIZED');
		$this->getConfigApi()->removeConfigurationValue('STATUS_UNCERTAIN');
		$this->getConfigApi()->removeConfigurationValue('STATUS_CANCELLED');
		$this->getConfigApi()->removeConfigurationValue('STATUS_CAPTURED');
		$this->getConfigApi()->removeConfigurationValue('REFUSING_THRESHOLD');
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


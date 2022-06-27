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

require_once 'Customweb/Payment/Authorization/Server/IAdapter.php';
require_once 'Customweb/Core/Exception/CastException.php';
require_once 'Customweb/Core/Http/Response.php';

require_once 'PostFinanceCw/Util.php';
require_once 'PostFinanceCw/PaymentMethod.php';
require_once 'PostFinanceCw/Entity/Transaction.php';


class PostFinanceCwServerModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		
		/* @var $module PostFinanceCw_PaymentMethod */
		$module = PostFinanceCw_PaymentMethod::getInstanceById(Tools::getValue('id_module', null));

		$transactionId = Tools::getValue('cw_transaction_id', null);
		if (empty($transactionId)) {
			$transaction = $module->createTransaction($module->getOrderContext());
		}
		else {
			$transaction = PostFinanceCw_Entity_Transaction::loadById($transactionId);
		}
		
		$adapter = PostFinanceCw_Util::getAuthorizationAdapter($transaction->getAuthorizationType());
		
		if (!($adapter instanceof Customweb_Payment_Authorization_Server_IAdapter)) {
			throw new Customweb_Core_Exception_CastException('Customweb_Payment_Authorization_Server_IAdapter');
		}
		$transactionObject =$transaction->getTransactionObject();
		$response = $adapter->processAuthorization($transactionObject, $_REQUEST);
		PostFinanceCw_Util::getTransactionHandler()->persistTransactionObject($transactionObject);
		
		$response = new Customweb_Core_Http_Response($response);
		$response->send();
		die();
	}
	
	
	protected function displayMaintenancePage() {
		// We want never to see here the maintenance page.
	}
	
	protected function displayRestrictedCountryPage() {
		// We do not want to restrict the content by any country.
	}
	
	protected function canonicalRedirection($canonical_url = '') {
		// We do not need any canonical redirect
	}

}

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


require_once 'PostFinanceCw/Util.php';
require_once 'PostFinanceCw/PaymentMethod.php';


class PostFinanceCwAjaxModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();
		$cart = $this->context->cart;
		
		/* @var $module PostFinanceCw_PaymentMethod */
		$module = PostFinanceCw_PaymentMethod::getInstanceById(Tools::getValue('id_module', null));
		
		$orderContext = $module->getOrderContext();
		$adapter = PostFinanceCw_Util::getShopAdapterByPaymentAdapter(PostFinanceCw_Util::getAuthorizationAdapterByContext($orderContext));
		
		$adapter->prepareCheckout($module, $orderContext, null, false);
		$rs = $adapter->processTransactionCreationAjaxCall();
		
		echo json_encode($rs);
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

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

library_load_class_by_name('Customweb_Util_System');

class PostFinanceCwSuccessModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	/**
	 * @see FrontController::initContent()          			 		  	    
	 */
	public function initContent()
	{
		$transactionId = Tools::getValue('cw_transaction_id', null);
		$dbTransaction = PostFinanceCw_Entity_Transaction::loadById(intval($transactionId));
		
		if ($dbTransaction->getTransactionId() === null) {
			die("No transaction found for the given id.");
		}
		
		$id_cart = $dbTransaction->getCartId();
		$cart = new Cart($id_cart);
		$customer = new Customer($cart->id_customer);
		$key = $customer->secure_key;

		$link = new Link();
		$successUrl = $link->getPageLink('order-confirmation', true, null, array(
			'id_cart' => $id_cart,
			'id_module' => $dbTransaction->getModuleId(),
			'key' => $key,
		));
		
		$timeoutUrl = $link->getModuleLink('postfinancecw', 'timeout', array('cw_transaction_id' => $dbTransaction->getTransactionId()), true);
		
		$failedUrl = $link->getModuleLink('postfinancecw', 'error', array(
			'cw_transaction_id' => $dbTransaction->getTransactionId(),
			'id_cart' => $id_cart,
			'key' => $key,
		), true);
		
		
		$cookieKey = 'alias_postfinancecw_' . $dbTransaction->getModuleId();
		$this->context->cookie->__set($cookieKey, NULL);
		
		
		// We have to close the session here otherwise the transaction may not be updated by the notification
		// callback.
		$this->context->cookie->write();
		
		$start = time();
		$maxExecutionTime = Customweb_Util_System::getMaxExecutionTime() - 5;
		
		if ($maxExecutionTime > 60) {
			$maxExecutionTime = 60;
		}
		
		while (true) {
		
			$dbTransaction = PostFinanceCw_Entity_Transaction::loadById(intval($transactionId), false);
			$id_order = Order::getOrderByCartId((int)$id_cart);
			$transactionObject = $dbTransaction->getTransactionObject();
			
			if ($transactionObject->isAuthorizationFailed()) {
				header('Location: ' . $failedUrl);
				die();
			}
			else if ($transactionObject->isAuthorized()) {
				// Make sure we delete the cart.
				unset($this->context->cookie->id_cart);
				header('Location: ' . $successUrl);
				die();
			}
			
			if (time() - $start > $maxExecutionTime) {
				header('Location: ' . $timeoutUrl);
				die();
			}
			else {
				// Wait 2 seconds for the next try.
				sleep(2);
			}
		}
	}
}

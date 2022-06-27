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

require_once 'Customweb/Payment/Authorization/Widget/ITransactionContext.php';
require_once 'Customweb/Payment/Authorization/Hidden/ITransactionContext.php';
require_once 'Customweb/Payment/Authorization/Iframe/ITransactionContext.php';
require_once 'Customweb/Payment/Authorization/PaymentPage/ITransactionContext.php';
require_once 'Customweb/Payment/Authorization/Ajax/ITransactionContext.php';
require_once 'Customweb/Payment/Authorization/Server/ITransactionContext.php';
require_once 'Customweb/Payment/Authorization/Moto/ITransactionContext.php';

require_once 'PostFinanceCw/Util.php';
require_once 'PostFinanceCw/Entity/Transaction.php';


class PostFinanceCw_TransactionContext implements Customweb_Payment_Authorization_PaymentPage_ITransactionContext,
Customweb_Payment_Authorization_Hidden_ITransactionContext, Customweb_Payment_Authorization_Server_ITransactionContext,
Customweb_Payment_Authorization_Iframe_ITransactionContext, Customweb_Payment_Authorization_Moto_ITransactionContext,
Customweb_Payment_Authorization_Widget_ITransactionContext, Customweb_Payment_Authorization_Ajax_ITransactionContext
{
	private $transactionId;
	private $externalTransactionId = null;
	private $aliasTransactionId = NULL;
	private $orderContext;
	private $adminUrl = NULL;
	private $orderId = NULL;
	
	private $failedUrl = NULL;
	private $successUrl = NULL;
	private $breakoutUrl = NULL;

	public function __construct(PostFinanceCw_Entity_Transaction $transaction, PostFinanceCw_OrderContext $orderContext, $aliasTransactionId = NULL) {
		$this->transactionId = $transaction->getTransactionId();
		$this->orderContext = $orderContext;
		$this->orderId = $transaction->getOrderId();

		if (PostFinanceCw_Util::isAliasManagerActive($this->orderContext)) {
			$this->aliasTransactionId = $aliasTransactionId;
		}
		
		if (isset(Context::getContext()->employee->id)) {
			$this->adminUrl = PostFinanceCw::getAdminUrl('AdminPostFinanceCwMoto', array());
		}
		
		
		$link = new Link();
		$this->failedUrl = $link->getModuleLink('postfinancecw', 'error', array(), true);
		$this->successUrl = $link->getModuleLink('postfinancecw', 'success', array(), true);
		$this->breakoutUrl = $link->getModuleLink('postfinancecw', 'breakout', array(), true);
		
	}

	public function getOrderContext() {
		return $this->orderContext;
	}

	public function getTransactionId() {
		return $this->transactionId;
	}
	
	/**
	 * @deprecated
	 */
	public function getInternalTransactionId() {
		return $this->transactionId;
	}

	public function getOrderId() {
		return $this->orderId;
	}
	
	public function isOrderIdUnique() {
		return false;
	}
	
	public function getInternalTransaction() {
		return PostFinanceCw_Entity_Transaction::loadById($this->getInternalTransactionId());
	}

	public function getCapturingMode() {
		return null;
	}

	public function createRecurringAlias() {
		return false;
	}

	public function getAlias() {
		if ($this->aliasTransactionId === 'new') {
			return 'new';
		}

		if ($this->aliasTransactionId !== null) {
			$transcation = PostFinanceCw_Entity_Transaction::loadById((int)$this->aliasTransactionId);
			if ($transcation !== null && $transcation->getTransactionObject() !== null) {
				return $transcation->getTransactionObject();
			}
		}

		return null;
	}

	public function getCustomParameters() {
		return array(
			'cw_transaction_id' => $this->transactionId,
		);
	}

	public function getSuccessUrl() {
		return $this->successUrl;
	}

	public function getFailedUrl() {
		return $this->failedUrl;		
	}

	public function getPaymentCustomerContext() {
		return PostFinanceCw_Util::getPaymentCustomerContext($this->orderContext->getCustomerId());
	}

	public function getNotificationUrl() {
		$link = new Link();
		return $link->getModuleLink('postfinancecw', 'notification', array(), true);
	}

	public function getIframeBreakOutUrl() {
		return $this->breakoutUrl;
	}

	public function getBackendSuccessUrl() {
		return $this->adminUrl;
	}

	public function getBackendFailedUrl() {
		return $this->adminUrl;
	}
	

	public function getJavaScriptSuccessCallbackFunction() {
		return '
		function (redirectUrl) {
			window.location = redirectUrl
		}';
	}

	public function getJavaScriptFailedCallbackFunction() {
		return '
		function (redirectUrl) {
			window.location = redirectUrl
		}';
	}

}